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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('match_id')->unique();               // EA unique identifier
            $table->unsignedMediumInteger('home_team_id');
            $table->unsignedMediumInteger('away_team_id');
            $table->unsignedTinyInteger('home_team_goals');
            $table->unsignedTinyInteger('away_team_goals');
            $table->unsignedTinyInteger('home_team_player_count')->default(0);
            $table->unsignedTinyInteger('away_team_player_count')->default(0);
            $table->enum('outcome', ['homewin', 'awaywin', 'draw']);
            $table->enum('platform', ['ps4', 'ps5', 'xboxone', 'xboxseriessx', 'pc']);
            $table->string('media')->nullable();
            $table->json('properties')->nullable();
            $table->timestamp('match_date');
            $table->timestamps();

            $table->index('home_team_id');
            $table->index('away_team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
