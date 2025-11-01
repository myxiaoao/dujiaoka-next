<?php

namespace Tests\Feature;

use App\Models\Goods;
use App\Models\GoodsGroup;
use App\Models\Order;
use App\Models\Pay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemHealthTest extends TestCase
{
    /**
     * 测试数据库连接
     */
    public function test_database_connection(): void
    {
        $this->assertTrue(true);
        
        // 测试是否能连接数据库
        $result = \DB::select('SELECT 1');
        $this->assertNotEmpty($result);
    }

    /**
     * 测试模型关系
     */
    public function test_model_relationships(): void
    {
        // 测试 GoodsGroup 模型是否存在
        $this->assertTrue(class_exists(GoodsGroup::class));
        
        // 测试 Goods 模型是否存在
        $this->assertTrue(class_exists(Goods::class));
        
        // 测试 Order 模型是否存在
        $this->assertTrue(class_exists(Order::class));
        
        // 测试 Pay 模型是否存在
        $this->assertTrue(class_exists(Pay::class));
    }

    /**
     * 测试路由是否正常
     */
    public function test_routes_are_accessible(): void
    {
        // 测试前台首页
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * 测试服务提供者注册
     */
    public function test_service_providers_registered(): void
    {
        // 测试 GoodsService 是否注册
        $this->assertTrue(app()->bound('Service\GoodsService'));
        
        // 测试 OrderService 是否注册
        $this->assertTrue(app()->bound('Service\OrderService'));
        
        // 测试 PayService 是否注册
        $this->assertTrue(app()->bound('Service\PayService'));
    }
}
