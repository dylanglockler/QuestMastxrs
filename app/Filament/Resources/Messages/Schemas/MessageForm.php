<?php

namespace App\Filament\Resources\Messages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clue_id')
                    ->relationship('clue', 'riddle_text')
                    ->searchable()
                    ->required(),
                TextInput::make('nickname')
                    ->required(),
                Textarea::make('body')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
