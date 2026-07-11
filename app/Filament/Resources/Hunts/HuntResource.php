<?php

namespace App\Filament\Resources\Hunts;

use App\Filament\Resources\Hunts\Pages\CreateHunt;
use App\Filament\Resources\Hunts\Pages\EditHunt;
use App\Filament\Resources\Hunts\Pages\ListHunts;
use App\Filament\Resources\Hunts\RelationManagers\CluesRelationManager;
use App\Filament\Resources\Hunts\Schemas\HuntForm;
use App\Filament\Resources\Hunts\Tables\HuntsTable;
use App\Models\Hunt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HuntResource extends Resource
{
    protected static ?string $model = Hunt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return HuntForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HuntsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CluesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHunts::route('/'),
            'create' => CreateHunt::route('/create'),
            'edit' => EditHunt::route('/{record}/edit'),
        ];
    }
}
