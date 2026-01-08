<?php

namespace App\Filament\Resources\Invitations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InvitationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('groom_name')
                    ->label('Damat Adı')
                    ->required(),
                TextInput::make('groom_surname')
                    ->label('Damat Soyadı')
                    ->required(),
                TextInput::make('bride_name')
                    ->label('Gelin Adı')
                    ->required(),
                TextInput::make('bride_surname')
                    ->label('Gelin Soyadı')
                    ->required(),
                DateTimePicker::make('wedding_date')
                    ->label('Düğün Tarihi')
                    ->required(),
                Select::make('event_type')
                    ->label('Etkinlik Türü')
                    ->options([
                        'düğün' => 'Düğün',
                        'nişan' => 'Nişan',
                        'kına' => 'Kına',
                        'söz' => 'Söz',
                        'diğer' => 'Diğer',
                    ])
                    ->default('düğün')
                    ->required(),
                TextInput::make('location')
                    ->label('Mekan / Adres')
                    ->maxLength(500),
                Textarea::make('description')
                    ->label('Davetiye Açıklaması')
                    ->rows(4)
                    ->maxLength(2000),
                FileUpload::make('image')
                    ->label('Davetiye Görseli')
                    ->image()
                    ->directory('invitations')
                    ->visibility('public'),
            ]);
    }
}
