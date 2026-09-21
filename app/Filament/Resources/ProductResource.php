<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Informasi Utama')->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                            
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi Produk')
                            ->columnSpanFull(),
                    ])->columns(2),

                    Forms\Components\Section::make('Harga & Stok')->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Jual')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                            
                        Forms\Components\TextInput::make('stock')
                            ->label('Stok Awal')
                            ->required()
                            ->numeric()
                            ->default(0),
                            
                        Forms\Components\TextInput::make('weight')
                            ->label('Berat')
                            ->required()
                            ->numeric()
                            ->suffix('gram')
                            ->helperText('Diperlukan untuk hitung ongkir otomatis'),
                    ])->columns(3),
                ])->columnSpan(['lg' => 2]),

                Forms\Components\Section::make('Varian & Ukuran')
                ->description('Tambahkan varian ukuran atau warna (Opsional)')
                ->schema([
                    Forms\Components\Repeater::make('variants')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Varian (Contoh: XL / Merah)')
                                ->required(),
                            Forms\Components\TextInput::make('stock')
                                ->label('Stok Varian')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('price_adjustment')
                                ->label('Tambahan Harga (Rp)')
                                ->numeric()
                                ->default(0)
                                ->helperText('Isi 0 jika harga sama dengan produk utama.'),
                        ])
                        ->columns(3)
                        ->defaultItems(0) // Tidak wajib diisi jika produk tidak punya varian
                ]),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Gambar & Status')->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Produk')
                            ->image()
                            ->directory('products')
                            ->required(),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Tampilkan produk ini di website')
                            ->default(true),
                    ]),
                ])->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
