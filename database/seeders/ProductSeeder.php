<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name'=>'Mineral Water', 'category'=>'Beverage', 'unit'=>'bottle',
             'current_stock'=>4,  'min_stock'=>10, 'expiry_date'=>null],

            ['name'=>'Coffee Powder', 'category'=>'Food',     'unit'=>'pack',
             'current_stock'=>1,  'min_stock'=>5,  'expiry_date'=>null],

            ['name'=>'Backdrop Paper','category'=>'Supply',   'unit'=>'roll',
             'current_stock'=>2,  'min_stock'=>3,  'expiry_date'=>null],

            ['name'=>'Fresh Milk',    'category'=>'Beverage', 'unit'=>'carton',
             'current_stock'=>3,  'min_stock'=>5,
             'expiry_date'=>now()->addDays(2)->toDateString()],

            ['name'=>'Yogurt Drink',  'category'=>'Beverage', 'unit'=>'bottle',
             'current_stock'=>8,  'min_stock'=>5,
             'expiry_date'=>now()->addDays(5)->toDateString()],

            ['name'=>'Croissant',     'category'=>'Food',     'unit'=>'pcs',
             'current_stock'=>5,  'min_stock'=>10,
             'expiry_date'=>now()->addDay()->toDateString()],

            ['name'=>'A4 Paper',      'category'=>'Supply',   'unit'=>'ream',
             'current_stock'=>20, 'min_stock'=>5,  'expiry_date'=>null],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}