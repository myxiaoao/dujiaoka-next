<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Listeners;

use App\Events\GoodsGroupDeleted as GoodsGroupDeletedEvent;
use App\Models\Goods;

class GoodsGroupDeleted
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
     * @return void
     */
    public function handle(GoodsGroupDeletedEvent $event)
    {
        Goods::query()->where('group_id', $event->goodsGroup->id)->delete();
    }
}
