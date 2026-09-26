<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan';

    protected static ?string $description =
        'Penjualan 12 bulan terakhir';

    protected static ?string $maxHeight = '350px';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
{
    $start = now()
        ->subMonths(11)
        ->startOfMonth();

    $sales = Order::query()
        ->selectRaw(
            'DATE_FORMAT(created_at, "%Y-%m") as period'
        )
        ->selectRaw(
            'SUM(grand_total) as total'
        )
        ->where('payment_status', 'paid')
        ->where('created_at', '>=', $start)
        ->groupBy('period')
        ->orderBy('period')
        ->pluck('total', 'period');

    $labels = [];
    $data = [];

    for ($i = 11; $i >= 0; $i--) {

        $date = now()->subMonths($i);

        $period = $date->format('Y-m');

        $labels[] = $date->translatedFormat('M Y');

        $data[] = (float) ($sales[$period] ?? 0);
    }

    return [
        'datasets' => [
            [
                'label' => 'Penjualan',
                'data' => $data,
                'fill' => true,
                'tension' => 0.4,
            ],
        ],
        'labels' => $labels,
    ];
}

    protected function getType(): string
    {
        return 'line';
    }
}