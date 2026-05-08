<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChat implements ShouldBroadcastNow  // ← Esta interfaz es OBLIGATORIA
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $user;

    public function __construct($chat, $user)
    {
        $this->chat = $chat;
        $this->user = $user;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('chat');  // ← Canal público, no PrivateChannel
    }

    public function broadcastAs(): string
    {
        return 'new-chat';
    }

    public function broadcastWith(): array
    {
        return [
            'mensaje' => $this->chat,
            'usuario' => $this->user->name ?? $this->user ?? 'Anónimo',
            'timestamp' => now()->toISOString(),
        ];
    }
}