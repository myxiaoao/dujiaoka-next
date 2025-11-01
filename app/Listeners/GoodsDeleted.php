<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Listeners;

use App\Events\GoodsDeleted as GoodsDeletedEvent;
use App\Models\Carmis;

class GoodsDeleted
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(GoodsDeletedEvent $event)
    {
        Carmis::query()->where('goods_id', $event->goods->id)->delete();
    }
}
