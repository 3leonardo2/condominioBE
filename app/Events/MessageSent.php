<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class MessageSent implements ShouldBroadcastNow  // <-- Cambiado a ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public $message;
    public $username;
    public $userId;

    public function __construct($message, $username, $userId)
    {
        $this->message = $message;
        $this->username = $username;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'username' => $this->username,
            'userId' => $this->userId,
        ];
    }
}