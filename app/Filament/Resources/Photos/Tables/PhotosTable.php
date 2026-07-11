<?php

namespace App\Filament\Resources\Photos\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PhotosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('path')
                    ->label('Photo')
                    ->disk('public'),
                TextColumn::make('hunt.title')
                    ->label('Hunt')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nickname')
                    ->searchable(),
                TextColumn::make('caption')
                    ->limit(50)
                    ->placeholder('—'),
                TextColumn::make('hidden_at')
                    ->label('Hidden')
                    ->dateTime()
                    ->placeholder('Live')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('hunt_id')
                    ->relationship('hunt', 'title')
                    ->label('Hunt'),
            ])
            ->recordActions([
                Action::make('hide')
                    ->icon(Heroicon::OutlinedEyeSlash)
                    ->color('danger')
                    ->visible(fn ($record) => is_null($record->hidden_at))
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'hidden_at' => now(),
                        'hidden_by' => Auth::id(),
                    ])),
                Action::make('unhide')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('success')
                    ->visible(fn ($record) => ! is_null($record->hidden_at))
                    ->action(fn ($record) => $record->update([
                        'hidden_at' => null,
                        'hidden_by' => null,
                    ])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
