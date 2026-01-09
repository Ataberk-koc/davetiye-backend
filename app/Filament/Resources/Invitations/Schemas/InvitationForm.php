<?php

namespace App\Filament\Resources\Invitations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Border;

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
                    ->disk('public')
                    ->directory('invitations')
                    ->visibility('public'),
                    Select::make('border_id')
                    ->label('Kenarlık Seçimi')
                    ->relationship('border', 'name')
                    ->searchable()
                    ->preload()
                    // HTML izni veriyoruz ki <img> etiketi çalışsın
                    ->allowHtml() 
                    // Listede nasıl görüneceğini ayarlıyoruz (Resim + İsim)
                    ->getOptionLabelFromRecordUsing(fn (Border $record) => 
                        '<div style="display:flex; align-items:center; gap:10px;">
                            <img src="' . asset('storage/' . $record->image_path) . '" style="height: 40px; width: 40px; object-fit: contain; border-radius: 4px; border:1px solid #ddd;" />
                            <span>' . $record->name . '</span>
                        </div>'
                    )
                    // Dropdown içinde "Yeni Ekle" (+) butonu çıkarır
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Kenarlık Adı')
                            ->required(),
                        FileUpload::make('image_path')
                            ->label('Kenarlık Görseli')
                            ->image()
                            ->directory('borders') // Storage/app/public/borders içine kaydeder
                            ->disk('public') // Önemli: Public diski
                            ->required(),
                    ])
                    ->editOptionForm([
                         TextInput::make('name')->required(),
                         FileUpload::make('image_path')->image()->disk('public')->directory('borders'),
                    ]),
            ]);
    }
}
