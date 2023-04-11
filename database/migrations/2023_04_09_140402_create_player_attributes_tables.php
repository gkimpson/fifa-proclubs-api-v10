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
            $table->foreign('player_id')->references('id')->on('players');
            $table->unsignedInteger('player_id');
            $table->unsignedInteger('club_id');
            $table->unsignedInteger('ea_player_id')->nullable(); // TODO - need to find out if this changes with new game
            $table->enum('platform', \App\Enums\Platforms::all());
            $table->string('player_name', 255); // psn/xbox/pc unique handle

            $table->unsignedInteger('pace');
            $table->unsignedInteger('shooting');

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
