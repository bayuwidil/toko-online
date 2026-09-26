<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ThankYou extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Terima Kasih';

    protected static ?string $title = 'Terima Kasih & Promo';

    protected static ?string $slug = 'terima-kasih';

    protected static ?string $navigationGroup = 'Bantuan & Kontak';

    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.thank-you';
}