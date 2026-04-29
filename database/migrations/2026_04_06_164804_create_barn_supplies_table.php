<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barn_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barn_id')->constrained('barns')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('supply_img_path')->nullable();   // for image upload
            $table->string('supply_name');
            $table->integer('stock')->default(0);
            $table->decimal('current_unit_cost', 8, 2)->default(0.00);
            $table->integer('reorder_level')->default(10);
            $table->string('supply_status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barn_supplies');
    }
};
