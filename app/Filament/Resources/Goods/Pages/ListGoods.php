<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Goods\Pages;

use App\Filament\Resources\Goods\GoodsResource;
use App\Models\Goods;
use App\Models\GoodsGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGoods extends ListRecords
{
    protected static string $resource = GoodsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('全部')
                ->badge(Goods::count()),
        ];

        // 获取所有商品分类
        $groups = GoodsGroup::query()
            ->orderBy('ord', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($groups as $group) {
            $count = Goods::where('group_id', $group->id)->count();

            $tabs['group_'.$group->id] = Tab::make($group->gp_name)
                ->badge($count)
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('group_id', $group->id));
        }

        // 添加未分类商品 Tab
        $uncategorizedCount = Goods::whereNull('group_id')->count();
        if ($uncategorizedCount > 0) {
            $tabs['uncategorized'] = Tab::make('未分类')
                ->badge($uncategorizedCount)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('group_id'));
        }

        return $tabs;
    }
}
