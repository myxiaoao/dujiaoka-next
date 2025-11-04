<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Seeders;

use App\Models\Pay;
use Illuminate\Database\Seeder;

class PaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = [
            // 支付宝
            [
                'id' => 1,
                'pay_name' => '支付宝当面付',
                'pay_check' => 'zfbf2f',
                'pay_method' => 2,
                'pay_client' => 3,
                'merchant_id' => '商户号',
                'merchant_key' => '支付宝公钥',
                'merchant_pem' => '商户私钥',
                'pay_handleroute' => '/pay/alipay',
                'is_open' => 0,
            ],
            [
                'id' => 2,
                'pay_name' => '支付宝 PC',
                'pay_check' => 'aliweb',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/alipay',
                'is_open' => 1,
            ],

            // 码支付
            [
                'id' => 3,
                'pay_name' => '码支付 QQ',
                'pay_check' => 'mqq',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/mapay',
                'is_open' => 0,
            ],
            [
                'id' => 4,
                'pay_name' => '码支付支付宝',
                'pay_check' => 'mzfb',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/mapay',
                'is_open' => 0,
            ],
            [
                'id' => 5,
                'pay_name' => '码支付微信',
                'pay_check' => 'mwx',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/mapay',
                'is_open' => 0,
            ],

            // Paysapi
            [
                'id' => 6,
                'pay_name' => 'Paysapi 支付宝',
                'pay_check' => 'pszfb',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/paysapi',
                'is_open' => 0,
            ],
            [
                'id' => 7,
                'pay_name' => 'Paysapi 微信',
                'pay_check' => 'pswx',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/paysapi',
                'is_open' => 0,
            ],

            // 微信
            [
                'id' => 8,
                'pay_name' => '微信扫码',
                'pay_check' => 'wescan',
                'pay_method' => 2,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/wepay',
                'is_open' => 1,
            ],

            // Payjs
            [
                'id' => 11,
                'pay_name' => 'Payjs 微信扫码',
                'pay_check' => 'payjswescan',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/payjs',
                'is_open' => 0,
            ],

            // 易支付
            [
                'id' => 14,
                'pay_name' => '易支付-支付宝',
                'pay_check' => 'alipay',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => '',
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/yipay',
                'is_open' => 0,
            ],
            [
                'id' => 15,
                'pay_name' => '易支付-微信',
                'pay_check' => 'wxpay',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => null,
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/yipay',
                'is_open' => 0,
            ],
            [
                'id' => 16,
                'pay_name' => '易支付-QQ 钱包',
                'pay_check' => 'qqpay',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => null,
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/yipay',
                'is_open' => 0,
            ],

            // PayPal
            [
                'id' => 17,
                'pay_name' => 'PayPal',
                'pay_check' => 'paypal',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => '商户号',
                'merchant_key' => null,
                'merchant_pem' => '密钥',
                'pay_handleroute' => '/pay/paypal',
                'is_open' => 1,
            ],

            // V免签
            [
                'id' => 19,
                'pay_name' => 'V 免签支付宝',
                'pay_check' => 'vzfb',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => 'V 免签通讯密钥',
                'merchant_key' => null,
                'merchant_pem' => 'V 免签地址 例如 https://vpay.qq.com/    结尾必须有/',
                'pay_handleroute' => 'pay/vpay',
                'is_open' => 0,
            ],
            [
                'id' => 20,
                'pay_name' => 'V 免签微信',
                'pay_check' => 'vwx',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => 'V 免签通讯密钥',
                'merchant_key' => null,
                'merchant_pem' => 'V 免签地址 例如 https://vpay.qq.com/    结尾必须有/',
                'pay_handleroute' => 'pay/vpay',
                'is_open' => 0,
            ],

            // Stripe
            [
                'id' => 21,
                'pay_name' => 'Stripe[微信支付宝]',
                'pay_check' => 'stripe',
                'pay_method' => 1,
                'pay_client' => 1,
                'merchant_id' => 'pk开头的可发布密钥',
                'merchant_key' => null,
                'merchant_pem' => 'sk开头的密钥',
                'pay_handleroute' => 'pay/stripe',
                'is_open' => 0,
            ],

            // Coinbase
            [
                'id' => 22,
                'pay_name' => 'Coinbase[加密货币]',
                'pay_check' => 'coinbase',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => '费率',
                'merchant_key' => 'API密钥',
                'merchant_pem' => '共享密钥',
                'pay_handleroute' => 'pay/coinbase',
                'is_open' => 0,
            ],

            // Epusdt
            [
                'id' => 23,
                'pay_name' => 'Epusdt[trc20]',
                'pay_check' => 'epusdt',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'API密钥',
                'merchant_key' => '不填即可',
                'merchant_pem' => 'api请求地址',
                'pay_handleroute' => 'pay/epusdt',
                'is_open' => 0,
            ],

            // TokenPay - 各种加密货币
            [
                'id' => 24,
                'pay_name' => 'TRX',
                'pay_check' => 'tokenpay-trx',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'TRX',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 25,
                'pay_name' => 'USDT-TRC20',
                'pay_check' => 'tokenpay-usdt-trc',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'USDT_TRC20',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 26,
                'pay_name' => 'ETH',
                'pay_check' => 'tokenpay-eth',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_ETH_ETH',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 27,
                'pay_name' => 'USDT-ERC20',
                'pay_check' => 'tokenpay-usdt-eth',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_ETH_USDT_ERC20',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 28,
                'pay_name' => 'USDC-ERC20',
                'pay_check' => 'tokenpay-usdc-eth',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_ETH_USDC_ERC20',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 29,
                'pay_name' => 'BNB',
                'pay_check' => 'tokenpay-bnb',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_BSC_BNB',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 30,
                'pay_name' => 'USDT-BSC',
                'pay_check' => 'tokenpay-usdt-bsc',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_BSC_USDT_BEP20',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 31,
                'pay_name' => 'USDC-BSC',
                'pay_check' => 'tokenpay-usdc-bsc',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_BSC_USDC_BEP20',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 32,
                'pay_name' => 'MATIC',
                'pay_check' => 'tokenpay-matic',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_Polygon_MATIC',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 33,
                'pay_name' => 'USDT-Polygon',
                'pay_check' => 'tokenpay-usdt-polygon',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_Polygon_USDT_ERC20',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
            [
                'id' => 34,
                'pay_name' => 'USDC-Polygon',
                'pay_check' => 'tokenpay-usdc-polygon',
                'pay_method' => 1,
                'pay_client' => 3,
                'merchant_id' => 'EVM_Polygon_USDC_ERC20',
                'merchant_key' => '你的API密钥',
                'merchant_pem' => 'https://token-pay.xxx.com',
                'pay_handleroute' => 'pay/tokenpay',
                'is_open' => 0,
            ],
        ];

        foreach ($payments as $payment) {
            Pay::updateOrCreate(
                ['pay_check' => $payment['pay_check']],
                $payment
            );
        }
    }
}
