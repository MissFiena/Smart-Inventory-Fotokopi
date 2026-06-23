<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'category', 'unit',
        'current_stock', 'min_stock',
        'description', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'is_active'   => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function wasteLogs()
    {
        return $this->hasMany(WasteLog::class);
    }

    public function batches()
    {
        return $this->hasMany(StockBatch::class);
    }

    // ── Dynamic Batch System Accessors ─────────

    /**
     * Accessor attribute ($product->stock)
     * Calculates total physical stock across all unexpired active batches.
     */
    public function getStockAttribute()
    {
        return $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->where(function($q) {
                $q->where('expiry_date', '>=', now()->toDateString())
                  ->orWhereNull('expiry_date'); // Include packaging
            })
            ->sum('remaining_quantity');
    }

    /**
     * Accessor attribute ($product->status)
     * Provides a clean fallback status string driven completely by batch tracking logs.
     */
    public function getStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Out of Stock';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        if ($this->isExpiringSoon(30)) {
            return 'Near Expiry';
        }

        if ($this->isLowStock()) {
            return 'Low Stock';
        }

        return 'Good';
    }

    // ── Upgraded Business Logic Helpers ─────────────────

    /**
     * Checks if stock volume is below warning threshold.
     * Uses dynamic batch totals instead of stagnant table columns.
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Checks if the earliest expiring active batch falls within a specific window.
     * Bumped default alert window to 30 days to clear up frontend tracking calculations.
     */
    public function isExpiringSoon(int $days = 30): bool
    {
        $earliestExpiry = $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date') // Only consider items that actually expire
            ->where('expiry_date', '>=', now()->toDateString())
            ->min('expiry_date');

        if (!$earliestExpiry) {
            return false;
        }

        return Carbon::parse($earliestExpiry)->diffInDays(now()) <= $days;
    }

    /**
     * Flags true if there are remaining quantities left in past-due batches.
     */
    public function isExpired(): bool
    {
        return $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date') // Ignore packaging
            ->where('expiry_date', '<', now()->toDateString())
            ->exists();
    }

    /**
     * Quality score: 0–100 based completely on active batch metrics
     */
    public function qualityScore(): int
    {
        $score = 100;
        $currentStockTotal = $this->stock;

        if ($currentStockTotal === 0) {
            $score -= 40;
        } elseif ($this->isLowStock()) {
            $score -= 20;
        }

        if ($this->isExpired()) {
            $score -= 40;
        } elseif ($this->isExpiringSoon(3)) {
            $score -= 25;
        } elseif ($this->isExpiringSoon(7)) {
            $score -= 10;
        }

        return max(0, $score);
    }

    /**
     * Predict days until stockout using dynamic batch totals
     */
    public function daysUntilStockout(): ?int
    {
        $totalOut = $this->transactions()
            ->where('type', 'check_out')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('quantity');

        if ($totalOut == 0) {
            return null;
        }

        $dailyAverage = $totalOut / 30;
        return (int) floor($this->stock / $dailyAverage);
    }
}