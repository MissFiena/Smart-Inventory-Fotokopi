<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_batches', function (Blueprint $table) {
            $table->id();
            
            // 🔗 Link to your products table with cascade deletion
            $table->foreignId('product_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // 📊 Stock Tracking Parameters
            $table->integer('initial_quantity');
            $table->integer('remaining_quantity')->default(0);
            
            // ⏳ Expiry and Control Fields
            $table->date('expiry_date');
            $table->text('notes')->nullable();
            
            $table->timestamps();

            // ⚡ Structural Performance Optimization Indexes
            $table->index(['product_id', 'expiry_date']);
            $table->index('remaining_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_batches');
    }
};