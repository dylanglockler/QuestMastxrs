<?php

namespace App\Filament\Resources\Hunts\Pages;

use App\Filament\Resources\Hunts\HuntResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHunt extends EditRecord
{
    protected static string $resource = HuntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
