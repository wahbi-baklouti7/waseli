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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrier_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('departed_country_id')->constrained('countries')->cascadeOnDelete();
            $table->foreignId('arrival_city_id')->constrained('regions')->cascadeOnDelete();
            $table->timestamp('arrival_date');
            $table->string('status')->default('open')->index(); // open, in_progress, completed
            $table->timestamps();

            // Indexes for faster filtering
            $table->index('category_id');
            $table->index('arrival_city_id');
            $table->index('arrival_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
