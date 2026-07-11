<?php

namespace App\Filament\Resources\Hunts\Pages;

use App\Filament\Resources\Hunts\HuntResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHunts extends ListRecords
{
    protected static string $resource = HuntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
