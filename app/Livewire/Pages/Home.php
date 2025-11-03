<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Pages;

use App\Service\GoodsService;
use App\Service\SeoService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('首页')]
class Home extends Component
{
    public string $search = '';

    public function mount(GoodsService $goodsService): void
    {
        // 可以在这里预加载数据或执行其他初始化逻辑
    }

    public function render(GoodsService $goodsService, SeoService $seoService)
    {
        // 获取商品分组及商品
        $goodsGroups = $goodsService->withGroup();

        // 如果有搜索关键词，过滤商品
        if ($this->search) {
            $goodsGroups = $goodsGroups->map(function ($group) {
                $group->setRelation('goods', $group->goods->filter(function ($goods) {
                    return str_contains(strtolower($goods->gd_name), strtolower($this->search))
                        || str_contains(strtolower($goods->gd_description ?? ''), strtolower($this->search));
                }));

                return $group;
            })->filter(function ($group) {
                return $group->goods->count() > 0;
            });
        }

        // 获取 SEO 数据
        $seoData = $seoService->getHomeSeoData();

        return view('livewire.pages.home', [
            'goodsGroups' => $goodsGroups,
        ])->with($seoData);
    }
}
