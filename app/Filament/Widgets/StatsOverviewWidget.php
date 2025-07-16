<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Produk;
use App\Models\Daerah;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total User', User::count()),
            Stat::make('Total Produk', Produk::count()),
            Stat::make('Total Daerah', Daerah::count()),
        ];
    }
}
