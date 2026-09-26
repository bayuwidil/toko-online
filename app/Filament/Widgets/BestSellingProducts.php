<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\OrderItem;

class BestSellingProducts extends BaseWidget
{
    protected static ?string $heading = 'Produk Terlaris';

    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->withSum([
                        'orderItems as total_sold' => function ($query) {
                            $query->whereHas('order', function ($order) {
                                $order->where('payment_status', 'paid');
                            });
                        }
                    ], 'quantity')
                    ->orderByDesc('total_sold')
                    ->limit(5)
            )
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('total_sold')
                    ->label('Terjual')
                    ->formatStateUsing(
                        fn ($state) => number_format(
                            (int) $state,
                            0,
                            ',',
                            '.'
                        ) . ' pcs'
                    )
                    ->badge()
                    ->color('success')
                    ->alignEnd(),

            ])
            ->paginated(false);
    }
}