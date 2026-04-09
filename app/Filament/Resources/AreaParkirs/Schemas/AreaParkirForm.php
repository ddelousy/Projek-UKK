<?php

namespace App\Filament\Resources\AreaParkirs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AreaParkirForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama_area')
                ->required(),

            TextInput::make('kapasitas')
                ->numeric()
                ->required(),
        ]);
    }
}
