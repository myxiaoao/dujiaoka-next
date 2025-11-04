<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
use App\Livewire\Pages\Buy;
use App\Models\Goods;
use App\Models\GoodsGroup;
use App\Models\Pay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('redirects to homepage when accessing offline goods', function () {
    // 创建支付方式（必需）
    Pay::create([
        'pay_name' => 'Test Pay',
        'pay_check' => 'test',
        'pay_method' => 1,
        'pay_client' => 1,
        'merchant_id' => 'test_id',
        'merchant_pem' => 'test_pem',
        'pay_handleroute' => '/pay/test',
        'is_open' => 1,
    ]);

    // 创建分类
    $group = GoodsGroup::factory()->create(['is_open' => 1]);

    // 创建未上架商品
    $goods = Goods::factory()->create([
        'group_id' => $group->id,
        'is_open' => 0, // 未上架
    ]);

    // 测试 Livewire 组件
    Livewire::test(Buy::class, ['id' => $goods->id])
        ->assertRedirect('/');

    // 检查 session 消息
    expect(session('error'))->toBe('该商品暂未上架，敬请期待！');
});

test('redirects to homepage when accessing non-existent goods', function () {
    // 创建支付方式（必需）
    Pay::create([
        'pay_name' => 'Test Pay',
        'pay_check' => 'test2',
        'pay_method' => 1,
        'pay_client' => 1,
        'merchant_id' => 'test_id',
        'merchant_pem' => 'test_pem',
        'pay_handleroute' => '/pay/test2',
        'is_open' => 1,
    ]);

    // 测试 Livewire 组件
    Livewire::test(Buy::class, ['id' => 99999])
        ->assertRedirect('/');

    // 检查 session 消息
    expect(session('error'))->toBe('该商品不存在或已下架');
});

test('can mount component with online goods', function () {
    // 创建支付方式（必需）
    Pay::create([
        'pay_name' => 'Test Pay',
        'pay_check' => 'test3',
        'pay_method' => 1,
        'pay_client' => 1,
        'merchant_id' => 'test_id',
        'merchant_pem' => 'test_pem',
        'pay_handleroute' => '/pay/test3',
        'is_open' => 1,
    ]);

    // 创建分类
    $group = GoodsGroup::factory()->create(['is_open' => 1]);

    // 创建已上架商品
    $goods = Goods::factory()->create([
        'group_id' => $group->id,
        'is_open' => 1, // 已上架
    ]);

    // 测试 Livewire 组件能成功挂载
    Livewire::test(Buy::class, ['id' => $goods->id])
        ->assertSet('product.id', $goods->id)
        ->assertSet('product.gd_name', $goods->gd_name)
        ->assertSee($goods->gd_name);
});
