<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Member;
use App\Models\Product;
use App\Models\Store;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Store Count', Store::count()),
            Stat::make('Member Count', Member::count()),
            Stat::make('Product Count', Product::count()),
        ];
    }
}
