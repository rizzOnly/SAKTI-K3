<?php

namespace App\Filament\AdminK3\Widgets;

use Filament\Widgets\Widget;

class CustomWelcomeWidget extends Widget
{
    // Ubah string ini agar pas dengan nama folder Anda (widget tanpa 's')
    protected static string $view = 'filament.widget.custom-welcome-widget';
    protected int | string | array $columnSpan = 'full';
}
