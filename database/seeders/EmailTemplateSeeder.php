<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace Database\Seeders;

use App\Models\Emailtpl;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'id' => 2,
                'tpl_token' => 'card_send_user_email',
                'tpl_name' => '发货邮件模板',
                'tpl_content' => $this->getCardSendUserEmailTemplate(),
            ],
            [
                'id' => 3,
                'tpl_token' => 'manual_send_manage_mail',
                'tpl_name' => '人工处理订单通知',
                'tpl_content' => $this->getManualSendManageMailTemplate(),
            ],
            [
                'id' => 4,
                'tpl_token' => 'failed_order',
                'tpl_name' => '订单异常通知',
                'tpl_content' => $this->getFailedOrderTemplate(),
            ],
            [
                'id' => 5,
                'tpl_token' => 'completed_order',
                'tpl_name' => '订单完成通知',
                'tpl_content' => $this->getCompletedOrderTemplate(),
            ],
            [
                'id' => 6,
                'tpl_token' => 'pending_order',
                'tpl_name' => '待处理订单通知',
                'tpl_content' => $this->getPendingOrderTemplate(),
            ],
        ];

        foreach ($templates as $template) {
            Emailtpl::updateOrCreate(
                ['tpl_token' => $template['tpl_token']],
                $template
            );
        }
    }

    private function getCardSendUserEmailTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .order-info {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card-info {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{webname}</h1>
        <p>订单已完成</p>
    </div>
    <div class="content">
        <p>您好，</p>
        <p>您的订单已成功处理并完成！</p>

        <div class="order-info">
            <h3>订单信息</h3>
            <p><strong>订单号：</strong>{order_id}</p>
            <p><strong>商品名称：</strong>{order_title}</p>
            <p><strong>订单金额：</strong>{order_price}</p>
        </div>

        <div class="card-info">
            <h3>卡密信息</h3>
            <div>{ord_info}</div>
        </div>

        <p style="color: #dc3545; font-weight: bold;">请妥善保管您的卡密信息！</p>
        <p>如有任何问题，请联系我们。</p>
    </div>
    <div class="footer">
        <p>本邮件由系统自动发送，请勿直接回复</p>
        <p>&copy; {webname}</p>
    </div>
</body>
</html>
HTML;
    }

    private function getManualSendManageMailTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
        }
        .order-details {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{webname}</h1>
        <p>人工处理订单通知</p>
    </div>
    <div class="content">
        <div class="alert-box">
            <h3>⚠️ 需要人工处理</h3>
            <p>有新的订单需要您手动处理。</p>
        </div>

        <div class="order-details">
            <h3>订单详情</h3>
            <p><strong>订单号：</strong>{order_id}</p>
            <p><strong>商品名称：</strong>{order_title}</p>
            <p><strong>订单金额：</strong>{order_price}</p>
            <p><strong>购买数量：</strong>{buy_amount}</p>
            <p><strong>订单信息：</strong></p>
            <div>{ord_info}</div>
        </div>

        <p>请尽快登录后台处理该订单。</p>
    </div>
    <div class="footer">
        <p>本邮件由系统自动发送，请勿直接回复</p>
        <p>&copy; {webname}</p>
    </div>
</body>
</html>
HTML;
    }

    private function getFailedOrderTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .error-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 15px 0;
            color: #721c24;
        }
        .order-info {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{webname}</h1>
        <p>订单异常通知</p>
    </div>
    <div class="content">
        <div class="error-box">
            <h3>❌ 订单处理失败</h3>
            <p>很抱歉，您的订单处理出现异常。</p>
        </div>

        <div class="order-info">
            <h3>订单信息</h3>
            <p><strong>订单号：</strong>{order_id}</p>
            <p><strong>商品名称：</strong>{order_title}</p>
            <p><strong>订单金额：</strong>{order_price}</p>
        </div>

        <p>如有疑问，请联系客服处理。</p>
    </div>
    <div class="footer">
        <p>本邮件由系统自动发送，请勿直接回复</p>
        <p>&copy; {webname}</p>
    </div>
</body>
</html>
HTML;
    }

    private function getCompletedOrderTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            color: #155724;
        }
        .order-info {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{webname}</h1>
        <p>订单完成通知</p>
    </div>
    <div class="content">
        <div class="success-box">
            <h3>✅ 订单已完成</h3>
            <p>恭喜！您的订单已成功完成。</p>
        </div>

        <div class="order-info">
            <h3>订单信息</h3>
            <p><strong>订单号：</strong>{order_id}</p>
            <p><strong>商品名称：</strong>{order_title}</p>
            <p><strong>订单金额：</strong>{order_price}</p>
            <p><strong>完成时间：</strong>{completed_at}</p>
        </div>

        <p>感谢您的购买！</p>
    </div>
    <div class="footer">
        <p>本邮件由系统自动发送，请勿直接回复</p>
        <p>&copy; {webname}</p>
    </div>
</body>
</html>
HTML;
    }

    private function getPendingOrderTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .pending-box {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 15px 0;
            color: #0c5460;
        }
        .order-info {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{webname}</h1>
        <p>待处理订单通知</p>
    </div>
    <div class="content">
        <div class="pending-box">
            <h3>⏳ 订单待处理</h3>
            <p>您的订单正在处理中，请耐心等待。</p>
        </div>

        <div class="order-info">
            <h3>订单信息</h3>
            <p><strong>订单号：</strong>{order_id}</p>
            <p><strong>商品名称：</strong>{order_title}</p>
            <p><strong>订单金额：</strong>{order_price}</p>
            <p><strong>下单时间：</strong>{created_at}</p>
        </div>

        <p>我们会尽快处理您的订单，请留意后续通知。</p>
    </div>
    <div class="footer">
        <p>本邮件由系统自动发送，请勿直接回复</p>
        <p>&copy; {webname}</p>
    </div>
</body>
</html>
HTML;
    }
}
