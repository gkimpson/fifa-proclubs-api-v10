<?php

use App\Enums\Platforms;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('email', function (Blueprint $table) {
                $table->enum('platform', Platforms::all());
                $table->unsignedMediumInteger('club_id');
            });
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('platform');
            $table->dropColumn('club_id');
        });
    }
};
