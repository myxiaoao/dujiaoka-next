<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends BaseModel
{
    use SoftDeletes;

    protected $table = 'coupons';

    protected $fillable = [
        'coupon',
        'discount',
        'is_use',
        'ret',
        'is_open',
    ];

    /**
     * 未使用
     */
    const STATUS_UNUSED = 1;

    /**
     * 已使用
     */
    const STATUS_USE = 2;

    /**
     * 关联商品
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function goods()
    {
        return $this->belongsToMany(Goods::class, 'coupons_goods', 'coupons_id', 'goods_id');
    }

    public static function getStatusUseMap()
    {
        return [
            self::STATUS_USE => admin_trans('coupon.fields.status_use'),
            self::STATUS_UNUSED => admin_trans('coupon.fields.status_unused'),
        ];
    }
}
