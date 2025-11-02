<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Models;

use App\Events\OrderUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'order_sn',
        'goods_id',
        'coupon_id',
        'pay_id',
        'title',
        'email',
        'info',
        'buy_amount',
        'goods_price',
        'coupon_discount_price',
        'wholesale_discount_price',
        'total_price',
        'actual_price',
        'search_pwd',
        'buy_ip',
        'status',
        'trade_no',
        'type',
    ];

    /**
     * 待支付
     */
    const STATUS_WAIT_PAY = 1;

    /**
     * 待处理
     */
    const STATUS_PENDING = 2;

    /**
     * 处理中
     */
    const STATUS_PROCESSING = 3;

    /**
     * 已完成
     */
    const STATUS_COMPLETED = 4;

    /**
     * 失败
     */
    const STATUS_FAILURE = 5;

    /**
     * 过期
     */
    const STATUS_EXPIRED = -1;

    /**
     * 异常
     */
    const STATUS_ABNORMAL = 6;

    /**
     * 优惠券未回退
     */
    const COUPON_BACK_WAIT = 0;

    /**
     * 优惠券已回退
     */
    const COUPON_BACK_OK = 1;

    protected $dispatchesEvents = [
        'updated' => OrderUpdated::class,
    ];

    /**
     * 状态映射
     *
     * @return array
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public static function getStatusMap()
    {
        return [
            self::STATUS_WAIT_PAY => admin_trans('order.fields.status_wait_pay'),
            self::STATUS_PENDING => admin_trans('order.fields.status_pending'),
            self::STATUS_PROCESSING => admin_trans('order.fields.status_processing'),
            self::STATUS_COMPLETED => admin_trans('order.fields.status_completed'),
            self::STATUS_FAILURE => admin_trans('order.fields.status_failure'),
            self::STATUS_ABNORMAL => admin_trans('order.fields.status_abnormal'),
            self::STATUS_EXPIRED => admin_trans('order.fields.status_expired'),
        ];
    }

    /**
     * 类型映射
     *
     * @return array
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public static function getTypeMap()
    {
        return [
            self::AUTOMATIC_DELIVERY => admin_trans('goods.fields.automatic_delivery'),
            self::MANUAL_PROCESSING => admin_trans('goods.fields.manual_processing'),
        ];
    }

    /**
     * 关联商品
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    /**
     * 关联优惠券
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * 关联支付
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author    assimon<ashang@utf8.hk>
     * @copyright assimon<ashang@utf8.hk>
     *
     * @link      http://utf8.hk/
     */
    public function pay()
    {
        return $this->belongsTo(Pay::class, 'pay_id');
    }
}
