<?php

namespace App\Filament\Resources\AttributeItemResource\Pages;

use App\Filament\Resources\AttributeItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttributeItems extends ListRecords
{
    protected static string $resource = AttributeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
