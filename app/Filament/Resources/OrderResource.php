<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Pesanan';

    protected static ?string $navigationGroup = 'Penjualan';

    protected static ?int $navigationSort = 1;


    /*
    |--------------------------------------------------------------------------
    | FORM / VIEW
    |--------------------------------------------------------------------------
    */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                /*
                |--------------------------------------------------------------------------
                | INFORMASI PESANAN
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make('Informasi Pesanan')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([

                        Forms\Components\TextInput::make('order_number')
                            ->label('Nomor Pesanan')
                            ->disabled(),

                        Forms\Components\TextInput::make('created_at')
                            ->label('Tanggal Pesanan')
                            ->formatStateUsing(
                                fn ($state) =>
                                    $state
                                        ? \Carbon\Carbon::parse($state)
                                            ->translatedFormat('d F Y H:i')
                                        : '-'
                            )
                            ->disabled(),

                    ])
                    ->columns(2),


                /*
                |--------------------------------------------------------------------------
                | DATA PEMBELI
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make('Data Pembeli')
                    ->icon('heroicon-o-user')
                    ->schema([

                        Forms\Components\TextInput::make('shipping_name')
                            ->label('Nama Pembeli')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_phone')
                            ->label('No. WhatsApp')
                            ->disabled(),

                        Forms\Components\TextInput::make('user.email')
                            ->label('Email')
                            ->disabled(),

                    ])
                    ->columns(2),


                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make('Status Pesanan')
                    ->icon('heroicon-o-information-circle')
                    ->schema([

                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'unpaid' => 'Belum Dibayar',
                                'pending' => 'Menunggu Pembayaran',
                                'paid' => 'Sudah Dibayar',
                                'failed' => 'Gagal',
                            ])
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending' => 'Menunggu',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required(),

                    ])
                    ->columns(2),


                /*
                |--------------------------------------------------------------------------
                | ALAMAT PENGIRIMAN
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make('Alamat Pengiriman')
                    ->icon('heroicon-o-map-pin')
                    ->schema([

                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Alamat Lengkap')
                            ->disabled()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('shipping_subdistrict')
                            ->label('Desa / Kelurahan')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_district')
                            ->label('Kecamatan')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_city')
                            ->label('Kabupaten / Kota')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_province')
                            ->label('Provinsi')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_postal_code')
                            ->label('Kode Pos')
                            ->disabled(),

                    ])
                    ->columns(2),


                /*
                |--------------------------------------------------------------------------
                | PENGIRIMAN
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make('Pengiriman')
                    ->icon('heroicon-o-truck')
                    ->schema([

                        Forms\Components\TextInput::make('shipping_courier')
                            ->label('Kurir')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_service')
                            ->label('Layanan')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_weight')
                            ->label('Berat')
                            ->suffix(' gram')
                            ->disabled(),

                    ])
                    ->columns(3),


                /*
                |--------------------------------------------------------------------------
                | PEMBAYARAN
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make('Pembayaran')
                    ->icon('heroicon-o-banknotes')
                    ->schema([

                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp ')
                            ->disabled()
                            ->formatStateUsing(
                                fn ($state) =>
                                    number_format(
                                        (int) $state,
                                        0,
                                        ',',
                                        '.'
                                    )
                            ),

                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Ongkos Kirim')
                            ->prefix('Rp ')
                            ->disabled()
                            ->formatStateUsing(
                                fn ($state) =>
                                    number_format(
                                        (int) $state,
                                        0,
                                        ',',
                                        '.'
                                    )
                            ),

                        Forms\Components\TextInput::make('grand_total')
                            ->label('Total Pembayaran')
                            ->prefix('Rp ')
                            ->disabled()
                            ->formatStateUsing(
                                fn ($state) =>
                                    number_format(
                                        (int) $state,
                                        0,
                                        ',',
                                        '.'
                                    )
                            ),

                        Forms\Components\TextInput::make('payment_type')
                            ->label('Metode Pembayaran')
                            ->disabled(),

                    ])
                    ->columns(2),


                /*
                |--------------------------------------------------------------------------
                | STOK
                |--------------------------------------------------------------------------
                */

                Forms\Components\Section::make('Status Stok')
                    ->icon('heroicon-o-cube')
                    ->schema([

                        Forms\Components\Toggle::make('stock_deducted')
                            ->label('Stok Sudah Dikurangi')
                            ->disabled(),

                    ]),

            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return $table

            ->modifyQueryUsing(
                fn (Builder $query) =>
                    $query->with(['user'])
            )

            ->defaultSort('created_at', 'desc')

            ->columns([

                /*
                |--------------------------------------------------------------------------
                | ORDER
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),


                /*
                |--------------------------------------------------------------------------
                | PEMBELI
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('shipping_name')
                    ->label('Pembeli')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_phone')
                    ->label('WhatsApp')
                    ->searchable(),


                /*
                |--------------------------------------------------------------------------
                | TOTAL
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),


                /*
                |--------------------------------------------------------------------------
                | PEMBAYARAN
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()

                    ->formatStateUsing(
                        fn ($state) => match ($state) {

                            'unpaid' =>
                                'Belum Dibayar',

                            'pending' =>
                                'Menunggu',

                            'paid' =>
                                'Dibayar',

                            'failed' =>
                                'Gagal',

                            default =>
                                ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $state ?? '-'
                                    )
                                ),
                        }
                    )

                    ->color(
                        fn ($state) => match ($state) {

                            'unpaid' =>
                                'gray',

                            'pending' =>
                                'warning',

                            'paid' =>
                                'success',

                            'failed' =>
                                'danger',

                            default =>
                                'gray',
                        }
                    ),


                /*
                |--------------------------------------------------------------------------
                | STATUS PESANAN
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('status')
                    ->label('Pesanan')
                    ->badge()

                    ->formatStateUsing(
                        fn ($state) => match ($state) {

                            'pending' =>
                                'Menunggu',

                            'processing' =>
                                'Diproses',

                            'shipped' =>
                                'Dikirim',

                            'completed' =>
                                'Selesai',

                            'cancelled' =>
                                'Dibatalkan',

                            default =>
                                ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $state ?? '-'
                                    )
                                ),
                        }
                    )

                    ->color(
                        fn ($state) => match ($state) {

                            'pending' =>
                                'warning',

                            'processing' =>
                                'info',

                            'shipped' =>
                                'primary',

                            'completed' =>
                                'success',

                            'cancelled' =>
                                'danger',

                            default =>
                                'gray',
                        }
                    ),


                /*
                |--------------------------------------------------------------------------
                | STOK
                |--------------------------------------------------------------------------
                */

                Tables\Columns\IconColumn::make('stock_deducted')
                    ->label('Stok')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),


                /*
                |--------------------------------------------------------------------------
                | TANGGAL
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])


            /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */

            ->filters([

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'failed' => 'Gagal',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending' => 'Menunggu',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\TernaryFilter::make('stock_deducted')
                    ->label('Stok Sudah Dikurangi'),

                Tables\Filters\Filter::make('created_at')
                    ->label('Tanggal Pesanan')
                    ->form([

                        Forms\Components\DatePicker::make('from')
                            ->label('Dari'),

                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai'),

                    ])

                    ->query(
                        function (
                            Builder $query,
                            array $data
                        ): Builder {

                            return $query

                                ->when(
                                    $data['from'] ?? null,
                                    fn (
                                        Builder $query,
                                        $date
                                    ) =>
                                        $query->whereDate(
                                            'created_at',
                                            '>=',
                                            $date
                                        )
                                )

                                ->when(
                                    $data['until'] ?? null,
                                    fn (
                                        Builder $query,
                                        $date
                                    ) =>
                                        $query->whereDate(
                                            'created_at',
                                            '<=',
                                            $date
                                        )
                                );
                        }
                    ),

            ])


            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

            ->actions([

                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),

            ])


            /*
            |--------------------------------------------------------------------------
            | BULK
            |--------------------------------------------------------------------------
            */

            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [

            'index' =>
                Pages\ListOrders::route('/'),

            'view' =>
                Pages\ViewOrder::route('/{record}'),

            'edit' =>
                Pages\EditOrder::route('/{record}/edit'),

        ];
    }
}