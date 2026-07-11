<?php

namespace App\Filament\Resources\Hunts\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CluesRelationManager extends RelationManager
{
    protected static string $relationship = 'clues';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order')
                    ->numeric()
                    ->required(),
                TextInput::make('title')
                    ->maxLength(255)
                    ->helperText('Optional short label, shown to hosts only.'),
                Textarea::make('riddle_text')
                    ->label('Riddle')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('location_note')
                    ->label('General area (non-spoiler)')
                    ->rows(2)
                    ->columnSpanFull(),
                Repeater::make('hints')
                    ->relationship('hints')
                    ->columnSpanFull()
                    ->orderColumn('order')
                    ->minItems(3)
                    ->maxItems(3)
                    ->defaultItems(3)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->schema([
                        Textarea::make('text')
                            ->label('Hint')
                            ->required()
                            ->rows(2),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('riddle_text')
            ->defaultSort('order')
            ->columns([
                TextColumn::make('order')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('riddle_text')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('hints_count')
                    ->counts('hints')
                    ->label('Hints'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
