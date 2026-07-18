<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Trovador — F3: presence streak badges.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('streak_days')->default(0)->after('welcome_audio');
            $table->string('streak_badge')->nullable()->after('streak_days');
            $table->date('streak_last_day')->nullable()->after('streak_badge');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['streak_days', 'streak_badge', 'streak_last_day']);
        });
    }
};
