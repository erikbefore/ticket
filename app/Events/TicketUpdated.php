<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Model\Ticket;

class TicketUpdated
{
    use InteractsWithSockets, SerializesModels;
	public $original;
	public $modified;
	
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ticket $original, Ticket $modified)
    {
        $this->original = $original;
		$this->modified = $modified;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
