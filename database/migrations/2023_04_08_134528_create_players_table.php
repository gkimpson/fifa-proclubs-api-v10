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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('club_id');
            $table->unsignedInteger('ea_player_id')->nullable(); // TODO - need to find out if this changes with new game
            $table->enum('platform', Platforms::all());
            $table->string('player_name', 255); // psn/xbox/pc unique handle
            $table->string('attributes', 255);
            $table->timestamps();

            $table->unique(['club_id', 'platform', 'player_name'], 'idx_unique_player_by_club_and_platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
