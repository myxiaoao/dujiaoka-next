<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Events;

use App\Models\GoodsGroup;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GoodsGroupDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $goodsGroup;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(GoodsGroup $goodsGroup)
    {
        $this->goodsGroup = $goodsGroup;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
