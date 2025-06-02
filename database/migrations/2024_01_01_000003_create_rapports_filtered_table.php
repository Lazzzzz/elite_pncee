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
        Schema::connection('elite')->create('rapports_filtered', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rapport_id')->unique();

            // Index for faster lookups
            $table->index('rapport_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('elite')->dropIfExists('rapports_filtered');
    }
};
