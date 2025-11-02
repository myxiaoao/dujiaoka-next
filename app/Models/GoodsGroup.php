<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Models;

use App\Events\GoodsGroupDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsGroup extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'goods_group';

    protected $fillable = [
        'gp_name',
        'is_open',
        'ord',
    ];

    protected $dispatchesEvents = [
        'deleted' => GoodsGroupDeleted::class,
    ];

    /**
     * 关联商品
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function goods()
    {
        return $this->hasMany(Goods::class, 'group_id');
    }
}
