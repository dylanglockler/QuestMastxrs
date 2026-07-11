<?php

namespace App\Filament\Resources\Hunts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class HuntForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('tagline')
                    ->helperText('A short, snappy line for the hunt card.'),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->rows(4),
                TextInput::make('city')
                    ->required(),
                TextInput::make('neighborhood'),
                FileUpload::make('cover_image')
                    ->image()
                    ->directory('hunts'),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'archived' => 'Archived',
                    ])
                    ->required()
                    ->default('draft'),
                Textarea::make('starting_hint')
                    ->columnSpanFull()
                    ->rows(3)
                    ->helperText('The general area where questers should start looking — keep it cryptic.'),
                DateTimePicker::make('published_at'),
            ]);
    }
}
