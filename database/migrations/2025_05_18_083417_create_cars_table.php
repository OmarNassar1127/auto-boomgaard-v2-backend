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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            
            // Basic Information (fixed columns)
            $table->string('brand');
            $table->string('model');
            $table->string('price');
            $table->string('tax_info')->default('incl. BTW');
            $table->string('mileage');
            $table->string('year');
            $table->string('color');
            $table->string('transmission');
            $table->string('fuel');
            $table->string('power');
            
            // JSON Columns
            $table->json('specifications')->nullable();
            $table->json('highlights')->nullable();
            $table->json('options_accessories')->nullable();
            
            // Status Management
            $table->enum('vehicle_status', ['sold', 'listed', 'reserved', 'upcoming'])->default('upcoming');
            $table->enum('post_status', ['draft', 'published'])->default('draft');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
