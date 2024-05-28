<?php

namespace App\Events;

use App\Enum\FriendStatus;
use App\Models\Friend;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusFriendship implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        private Friend $friend,
        private FriendStatus $status
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("response_friendship.{$this->friend->from_user_id}");
    }

    public function broadcastAs()
    {
        return 'ResponseFriendship';
    }

    public function broadcastWith()
    {
        return ['statusFriendship' => $this->friend];
    }
}
