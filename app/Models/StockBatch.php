<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBatch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'initial_quantity',
        'remaining_quantity',
        'expiry_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expiry_date' => 'date',
        'initial_quantity' => 'integer',
        'remaining_quantity' => 'integer',
    ];

    /**
     * Get the master product record that owns this specific batch instance.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}