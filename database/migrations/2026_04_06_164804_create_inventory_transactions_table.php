<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barn_id')->constrained('barns')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supply_id')->constrained('barn_supplies')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('barn_suppliers')->onDelete('cascade');
            $table->string('transaction_type');   // 'stock_in' or 'stock_out'
            $table->integer('quantity');
            $table->decimal('unit_cost', 8, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
