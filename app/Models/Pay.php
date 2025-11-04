<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Pay extends BaseModel
{
    use SoftDeletes;

    protected $table = 'pays';

    protected $fillable = [
        'pay_name',
        'pay_check',
        'pay_handleroute',
        'pay_method',
        'pay_client',
        'merchant_id',
        'merchant_key',
        'merchant_pem',
        'is_open',
    ];

    /**
     * 跳转
     */
    const METHOD_JUMP = 1;

    /**
     * 扫码
     */
    const METHOD_SCAN = 2;

    /**
     * 电脑
     */
    const PAY_CLIENT_PC = 1;

    /**
     * 手机
     */
    const PAY_CLIENT_MOBILE = 2;

    /**
     * 通用
     */
    const PAY_CLIENT_ALL = 3;

    public static function getMethodMap()
    {
        return [
            self::METHOD_JUMP => admin_trans('pay.fields.method_jump'),
            self::METHOD_SCAN => admin_trans('pay.fields.method_scan'),
        ];
    }

    public static function getClientMap()
    {
        return [
            self::PAY_CLIENT_PC => admin_trans('pay.fields.pay_client_pc'),
            self::PAY_CLIENT_MOBILE => admin_trans('pay.fields.pay_client_mobile'),
            self::PAY_CLIENT_ALL => admin_trans('pay.fields.pay_client_all'),
        ];
    }
}
