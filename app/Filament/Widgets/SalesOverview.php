<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSales = Order::query()
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $totalOrders = Order::query()
            ->where('payment_status', 'paid')
            ->count();

        $totalProducts = OrderItem::query()
            ->whereHas(
                'order',
                fn ($query) =>
                    $query->where(
                        'payment_status',
                        'paid'
                    )
            )
            ->sum('quantity');

        return [

            Stat::make(
                'Total Penjualan',
                'Rp ' .
                number_format(
                    $totalSales,
                    0,
                    ',',
                    '.'
                )
            )
                ->description('Total transaksi berhasil')
                ->descriptionIcon(
                    'heroicon-m-banknotes'
                )
                ->color('success'),

            Stat::make(
                'Total Pesanan',
                number_format(
                    $totalOrders,
                    0,
                    ',',
                    '.'
                )
            )
                ->description('Pesanan berhasil dibayar')
                ->descriptionIcon(
                    'heroicon-m-shopping-bag'
                )
                ->color('primary'),

            Stat::make(
                'Produk Terjual',
                number_format(
                    $totalProducts,
                    0,
                    ',',
                    '.'
                ) . ' pcs'
            )
                ->description('Total unit terjual')
                ->descriptionIcon(
                    'heroicon-m-cube'
                )
                ->color('warning'),

        ];
    }
}