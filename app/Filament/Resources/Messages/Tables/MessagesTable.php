<?php

namespace App\Filament\Resources\Messages\Tables;

use App\Models\Hunt;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('clue.hunt.title')
                    ->label('Hunt')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clue.title')
                    ->label('Clue')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('nickname')
                    ->searchable(),
                TextColumn::make('body')
                    ->limit(60)
                    ->wrap(),
                IconColumn::make('hidden_at')
                    ->label('Hidden')
                    ->boolean()
                    ->getStateUsing(fn ($record) => ! is_null($record->hidden_at)),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('hunt')
                    ->options(fn () => Hunt::query()->pluck('title', 'id'))
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, $huntId) => $query->whereHas('clue', fn (Builder $q) => $q->where('hunt_id', $huntId))
                    )),
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
