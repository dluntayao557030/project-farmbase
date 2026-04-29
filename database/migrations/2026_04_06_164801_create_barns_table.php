<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barn_owner_id')->constrained('users')->onDelete('cascade');
            $table->string('barn_name');
            $table->string('country');
            $table->string('city');
            $table->string('region');
            $table->string('farm_type');
            $table->string('permit_number');
            $table->string('permit_doc_path'); //for document upload
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barns');
    }
};