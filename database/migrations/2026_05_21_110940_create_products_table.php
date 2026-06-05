<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->nullable();       // barcode / code
            $table->string('category');                          // Food, Beverage, Supply…
            $table->string('unit');                               // pcs, kg, bottle, roll…
            $table->integer('current_stock')->default(0);
            $table->integer('min_stock')->default(5);            // trigger low-stock alert
            $table->date('expiry_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};