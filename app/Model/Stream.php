<?php

namespace App\Model;

use App\Providers\GenericHelperServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stream extends Model
{
    public const PUSHR_DRIVER = 1;
    public const LIVEKIT_DRIVER = 2;

    /**
     * Streaming is currently playing.
     */
    public const SCHEDULED_STATUS = 'scheduled'; // Trovador F2

    public const IN_PROGRESS_STATUS = 'in-progress';

    /**
     * Streaming ended.
     */
    public const ENDED_STATUS = 'ended';

    /**
     * Stream deleted.
     */
    public const DELETED_STATUS = 'deleted';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'driver', 'user_id', 'status', 'name', 'slug', 'poster', 'pushr_id', 'hls_link', 'vod_link', 'rtmp_server', 'rtmp_key', 'price', 'requires_subscription', 'sent_expiring_reminder', 'is_public', 'settings',
        'scheduled_at', 'scheduled_notified_24h', 'scheduled_notified_15m', // Trovador F2
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var list<string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ended_at' => 'datetime',
        'scheduled_at' => 'datetime', // Trovador F2
        'settings' => 'array',
    ];

    public function getPosterAttribute($value)
    {
        if($value){
            return GenericHelperServiceProvider::getFilePathByActiveStorageDriver($value);
        }else{
            return asset('/img/live-stream-cover.svg');
        }

    }

    /**
     * Relationships.
     */
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany<StreamMessage, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(StreamMessage::class);
    }

    /**
     * @return HasMany<Transaction, $this>
     */
    public function streamPurchases(): HasMany
    {
        return $this->hasMany(Transaction::class, 'stream_id', 'id')->where('status', 'approved')->where('type', 'stream-access');
    }

    /**
     * @return HasMany<Transaction, $this>
     */
    public function streamTips(): HasMany
    {
        return $this->hasMany(Transaction::class, 'stream_id', 'id')->where('status', 'approved')->where('type', 'tip');
    }

    public function isLivekitDriver() {
        return $this->driver === self::LIVEKIT_DRIVER;
    }

    public function getDriverSlug() {
        return $this->driver === self::LIVEKIT_DRIVER ? 'livekit' : 'pushr';
    }
}
