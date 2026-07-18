<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Trovador — T7: per-post / per-reel comment toggle.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('comments_enabled')->default(true)->after('is_pinned');
        });

        if (Schema::hasTable('reels')) {
            Schema::table('reels', function (Blueprint $table) {
                $table->boolean('comments_enabled')->default(true);
            });
        }
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('comments_enabled');
        });

        if (Schema::hasTable('reels')) {
            Schema::table('reels', function (Blueprint $table) {
                $table->dropColumn('comments_enabled');
            });
        }
    }
};
