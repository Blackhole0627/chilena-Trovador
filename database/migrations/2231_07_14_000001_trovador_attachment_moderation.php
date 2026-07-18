<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Trovador — T8: adds AWS Rekognition moderation state to attachments.
 *
 *  moderation_status:
 *    - processing      : queued / awaiting Rekognition result
 *    - approved        : auto-approved (below review threshold)
 *    - pending_review  : flagged for human review (between thresholds)
 *    - rejected        : auto-rejected (above reject threshold), hidden
 *    - failed          : moderation could not complete after retries
 *    - not_applicable  : type never moderated (audio / regular files)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->string('moderation_status', 20)->default('not_applicable')->after('length');
            $table->float('moderation_score')->nullable()->after('moderation_status');
            $table->json('moderation_labels')->nullable()->after('moderation_score');
            $table->unsignedTinyInteger('moderation_attempts')->default(0)->after('moderation_labels');
            $table->timestamp('moderated_at')->nullable()->after('moderation_attempts');

            $table->index('moderation_status');
        });
    }

    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropIndex(['moderation_status']);
            $table->dropColumn([
                'moderation_status',
                'moderation_score',
                'moderation_labels',
                'moderation_attempts',
                'moderated_at',
            ]);
        });
    }
};
