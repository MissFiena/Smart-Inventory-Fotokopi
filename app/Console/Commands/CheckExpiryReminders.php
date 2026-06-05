<?php
namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CheckExpiryReminders extends Command
{
    protected $signature   = 'inventory:check-expiry';
    protected $description = 'Show items expiring within 7 days';

    public function handle()
    {
        $items = Product::whereBetween('expiry_date', [
            today(), today()->addDays(7)
        ])->get();

        if ($items->isEmpty()) {
            $this->info('✅ No items expiring soon.');
            return;
        }

        $this->table(
            ['Product', 'Expiry Date', 'Days Left', 'Stock'],
            $items->map(fn($p) => [
                $p->name,
                $p->expiry_date->format('d M Y'),
                today()->diffInDays($p->expiry_date),
                $p->current_stock . ' ' . $p->unit,
            ])
        );
    }
}