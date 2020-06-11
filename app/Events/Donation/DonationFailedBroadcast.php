<?php

namespace App\Events\Donation;

use App\Model\Donation\Entities\Donation\Donation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationFailedBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function broadcastOn()
    {
        return new Channel('donation-' . $this->donation->getId());
    }

    public function broadcastAs()
    {
        return 'failed';
    }

    public function broadcastWith(): array
    {
        return $this->donation->toArray();
    }
}
