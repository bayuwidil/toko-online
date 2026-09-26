<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockProducts extends BaseWidget
{
    protected static ?string $heading = 'Stok Hampir Habis';

    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table

            ->query(
                Product::query()
                    ->where('stock', '<=', 10)
                    ->orderBy('stock')
                    ->limit(10)
            )

            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->alignEnd()
                    ->color(
                        fn ($state) => match (true) {

                            $state <= 3 => 'danger',

                            $state <= 10 => 'warning',

                            default => 'success',

                        }
                    ),

            ])

            ->paginated(false);
    }
}