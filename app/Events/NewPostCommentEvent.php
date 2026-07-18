<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Trovador — T6. Broadcasts a new post comment TikTok-Live style.
 * Cloned from NewStreamChatMessage. Access to the channel is gated by
 * PostsController::authorizePostChannel() (T10).
 */
class NewPostCommentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message, $channelName, $userId;

    public function __construct($postId, $message, $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
        $this->channelName = 'post-comment-channel-'.$postId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel($this->channelName);
    }

    public function broadcastAs()
    {
        return 'new-comment';
    }
}
