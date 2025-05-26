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
        Schema::table('cars', function (Blueprint $table) {
            // Change price to decimal for proper formatting
            $table->decimal('price', 10, 2)->change();
            
            // Change mileage, year, power to integers
            $table->unsignedInteger('mileage')->change();
            $table->unsignedSmallInteger('year')->change();
            $table->unsignedSmallInteger('power')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Revert back to strings
            $table->string('price')->change();
            $table->string('mileage')->change();
            $table->string('year')->change();
            $table->string('power')->change();
        });
    }
};
