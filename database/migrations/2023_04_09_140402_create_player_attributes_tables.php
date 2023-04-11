<?php

use App\Enums\Platforms;
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
        Schema::create('player_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('player_id');
            $table->unsignedInteger('club_id');
            $table->unsignedInteger('ea_player_id')->nullable(); // TODO - need to find out if this changes with new game
            $table->enum('platform', Platforms::all());
            $table->string('player_name', 255); // psn/xbox/pc unique handle

            // TODO - need to figure out which method I prefer
            //$table->json('attributes')->nullable();
            // OR
            //individual attributes??

            $table->foreign('player_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_attributes');
    }
};
