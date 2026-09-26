<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Barang yang Dipesan';


    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('product.name')
                    ->label('Produk')
                    ->disabled(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->disabled(),

                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->prefix('Rp ')
                    ->disabled(),

                Forms\Components\TextInput::make('weight')
                    ->label('Berat')
                    ->suffix(' gram')
                    ->disabled(),

            ]);
    }


    public function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('weight')
                    ->label('Berat')
                    ->suffix(' gram'),

            ])

            ->headerActions([])

            ->actions([])

            ->bulkActions([]);
    }
}