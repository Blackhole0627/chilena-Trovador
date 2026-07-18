<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Trovador — F2: scheduled lives.
 * `name` already stores the stream title. We add the schedule time and two
 * "notification already sent" guards so each reminder fires exactly once.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->timestamp('scheduled_at')->nullable()->after('status');
            $table->boolean('scheduled_notified_24h')->default(false)->after('scheduled_at');
            $table->boolean('scheduled_notified_15m')->default(false)->after('scheduled_notified_24h');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->dropIndex(['scheduled_at']);
            $table->dropColumn(['scheduled_at', 'scheduled_notified_24h', 'scheduled_notified_15m']);
        });
    }
};
