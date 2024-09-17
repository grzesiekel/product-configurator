<?php

namespace App\Filament\Resources\AttributeItemResource\Pages;

use App\Filament\Resources\AttributeItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttributeItem extends EditRecord
{
    protected static string $resource = AttributeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
