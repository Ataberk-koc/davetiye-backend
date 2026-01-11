<?php

namespace App\Filament\Resources\Invitations\Pages;

use App\Filament\Resources\Invitations\InvitationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInvitation extends EditRecord
{
    protected static string $resource = InvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Eğer "other" seçildiyse, custom değeri ana alana kopyala
        if ($data['title_font_family'] === 'other' && !empty($data['custom_title_font_family'])) {
            $data['title_font_family'] = $data['custom_title_font_family'];
        }
        if ($data['signature_font_family'] === 'other' && !empty($data['custom_signature_font_family'])) {
            $data['signature_font_family'] = $data['custom_signature_font_family'];
        }
        if ($data['body_font_family'] === 'other' && !empty($data['custom_body_font_family'])) {
            $data['body_font_family'] = $data['custom_body_font_family'];
        }

        // Custom alanları database'ye kaydetme (fillable olmadığından)
        unset($data['custom_title_font_family']);
        unset($data['custom_signature_font_family']);
        unset($data['custom_body_font_family']);

        return $data;
    }
}
