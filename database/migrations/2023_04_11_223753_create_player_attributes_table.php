<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->enum('favourite_position', ['G', 'D', 'M', 'F', 'A'])->default('A');
            $attributes = [
                'ACCELERATION',
                'AGGRESSION',
                'AGILITY',
                'ATTACK_POSITION',
                'BALANCE',
                'BALL_CONTROL',
                'CROSSING',
                'CURVE',
                'DRIBBLING',
                'FINISHING',
                'FREE_KICK_ACCURACY',
                'GK_DIVING',
                'GK_HANDLING',
                'GK_KICKING',
                'GK_POSITIONING',
                'GK_REFLEXES',
                'HEADING_ACCURACY',
                'INTERCEPTIONS',
                'JUMPING',
                'LONG_PASS',
                'LONG_SHOTS',
                'MARKING',
                'PENALTIES',
                'REACTIONS',
                'SHORT_PASS',
                'SHOT_POWER',
                'SLIDE_TACKLE',
                'SPRINT_SPEED',
                'STAMINA',
                'STAND_TACKLE',
                'STRENGTH',
                'UNSURE_ATTRIBUTE', // have no idea what the single attribute is yet...
                'VISION',
                'VOLLEYS',
            ];

            $attributes = array_map('strtolower', $attributes);

            foreach ($attributes as $attribute) {
                $table->unsignedTinyInteger($attribute)->default(0);
            }

            $table->timestamps();

            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_attributes');
    }
};
