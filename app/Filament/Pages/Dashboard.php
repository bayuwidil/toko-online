<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BestSellingProducts;
use App\Filament\Widgets\LowStockProducts;
use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\SalesOverview;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getWidgets(): array
    {
        return [
            SalesOverview::class,
            BestSellingProducts::class,
            LowStockProducts::class,
            SalesChart::class,
        ];
    }
}
