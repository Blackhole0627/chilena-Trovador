<?php

namespace App\Helpers;

use App\Model\Attachment;
use App\Model\FeaturedUser;
use App\Model\Post;
use App\Model\Reel;
use App\Model\User;
use App\Providers\AttachmentServiceProvider;
use Illuminate\Support\Collection;

/**
 * Trovador — F4. Supplies content for the "Destacados" feed widget tabs:
 * Reels, Fotos, Audio, Perfiles nuevos. Every method is defensive: on any
 * error it returns an empty collection so the widget degrades gracefully
 * rather than breaking the feed.
 */
class FeaturedContentHelper
{
    public const LIMIT = 12;

    public static function reels(): Collection
    {
        try {
            $q = Reel::query()->with('user')->latest();
            // Respect a status column if the schema has one.
            if (\Illuminate\Support\Facades\Schema::hasColumn('reels', 'status')) {
                $q->where('status', 'active');
            }
            return $q->limit(self::LIMIT)->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    public static function photos(): Collection
    {
        return self::postsByAttachmentType('image');
    }

    public static function audio(): Collection
    {
        return self::postsByAttachmentType('audio');
    }

    public static function newProfiles(): Collection
    {
        try {
            // Curated featured users first, then fall back to newest public creators.
            $featured = FeaturedUser::query()->with('user')->latest()->limit(self::LIMIT)->get()
                ->map(fn ($f) => $f->user)->filter();

            if ($featured->count() >= self::LIMIT) {
                return $featured->values();
            }

            $extra = User::query()
                ->whereNotNull('username')
                ->latest()
                ->limit(self::LIMIT - $featured->count())
                ->get();

            return $featured->merge($extra)->unique('id')->values();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    /**
     * Recent posts whose attachments include the given media type.
     */
    private static function postsByAttachmentType(string $type): Collection
    {
        try {
            $postIds = Attachment::query()
                ->whereNotNull('post_id')
                ->latest()
                ->limit(self::LIMIT * 5) // over-fetch, filter by type in PHP
                ->get(['post_id', 'type'])
                ->filter(fn ($a) => AttachmentServiceProvider::getAttachmentType($a->type) === $type)
                ->pluck('post_id')
                ->unique()
                ->take(self::LIMIT);

            if ($postIds->isEmpty()) {
                return collect();
            }

            return Post::query()
                ->with('user')
                ->whereIn('id', $postIds)
                ->latest()
                ->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    /**
     * Whether the widget should render at all.
     */
    public static function enabled(): bool
    {
        return (bool) getSetting('feed.featured_highlights_enabled');
    }
}
