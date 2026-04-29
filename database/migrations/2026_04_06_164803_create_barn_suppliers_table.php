<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barn_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barn_id')->constrained('barns')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('supplier_name');
            $table->string('contact_number')->nullable();
            $table->string('supplier_status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barn_suppliers');
    }
};