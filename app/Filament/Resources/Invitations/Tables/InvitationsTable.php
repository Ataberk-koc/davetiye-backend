<?php

namespace App\Filament\Resources\Invitations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class InvitationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('groom_name')
                    ->label('Damat Adı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bride_name')
                    ->label('Gelin Adı')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('event_type')
                    ->label('Etkinlik Türü')
                    ->colors([
                        'danger' => 'düğün',
                        'warning' => 'nişan',
                        'info' => 'kına',
                        'success' => 'söz',
                        'gray' => 'diğer',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('location')
                    ->label('Mekan')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        return $column->getState();
                    }),
                TextColumn::make('wedding_date')
                    ->label('Düğün Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                ImageColumn::make('image')
                    ->label('Görsel'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
