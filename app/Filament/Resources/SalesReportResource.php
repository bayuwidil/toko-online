<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesReportResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class SalesReportResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Penjualan';

    protected static ?string $modelLabel = 'Laporan Penjualan';

    protected static ?string $pluralModelLabel = 'Laporan Penjualan';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('payment_status', 'paid')
                    ->with(['user']);
            })

            ->defaultSort('created_at', 'desc')

            ->columns([

                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_phone')
                    ->label('WhatsApp')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_cost')
                    ->label('Ongkir')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total Penjualan')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Pembayaran')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Menunggu',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state ?? '-'),
                    })
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])

            ->filters([

                Tables\Filters\Filter::make('tanggal')
                    ->label('Periode Penjualan')
                    ->form([

                        Forms\Components\DatePicker::make('dari')
                            ->label('Dari tanggal'),

                        Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai tanggal'),

                    ])
                    ->query(function (
                        Builder $query,
                        array $data
                    ) {

                        return $query
                            ->when(
                                $data['dari'] ?? null,
                                fn (Builder $query, $date) =>
                                    $query->whereDate(
                                        'created_at',
                                        '>=',
                                        $date
                                    )
                            )
                            ->when(
                                $data['sampai'] ?? null,
                                fn (Builder $query, $date) =>
                                    $query->whereDate(
                                        'created_at',
                                        '<=',
                                        $date
                                    )
                            );
                    }),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending' => 'Menunggu',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('payment_type')
                    ->label('Metode Pembayaran')
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'cash' => 'Cash',
                        'cod' => 'COD',
                        'ewallet' => 'E-Wallet',
                    ]),
            ])

            ->headerActions([

                ExportAction::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->exports([
                        ExcelExport::make('penjualan')
                            ->fromTable()
                            ->withFilename(
                                fn () =>
                                    'laporan-penjualan-' .
                                    now()->format('Y-m-d-His')
                            ),
                    ]),

                Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('danger')
                    ->url(
                        fn () =>
                            route(
                                'filament.admin.sales-report.pdf'
                            )
                    )
                    ->openUrlInNewTab(),
            ])

            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
            ])

            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesReports::route('/'),
        ];
    }
}