<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
use App\Models\Goods;
use App\Models\GoodsGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page renders successfully', function (): void {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSeeLivewire('pages.home');
});

test('search order page renders successfully', function (): void {
    $response = $this->get('/search-order');

    $response->assertSuccessful();
    $response->assertSeeLivewire('pages.search-order');
});

test('error page renders successfully', function (): void {
    $response = $this->get('/error');

    $response->assertSuccessful();
    $response->assertSeeLivewire('pages.error');
});

test('buy page renders with valid product', function (): void {
    $group = GoodsGroup::factory()->create();
    $goods = Goods::factory()->create([
        'group_id' => $group->id,
        'in_stock' => 10,
        'is_open' => Goods::STATUS_OPEN,
    ]);

    $response = $this->get("/buy/{$goods->id}");

    $response->assertSuccessful();
    $response->assertSeeLivewire('pages.buy');
    $response->assertSee($goods->gd_name);
});
