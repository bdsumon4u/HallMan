<?php

namespace App\Filament\Resources\HallResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->integer()
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn ($rule) => $rule->where('hall_id', $this->ownerRecord->getKey())),
                Forms\Components\TextInput::make('capacity')
                    ->required()
                    ->integer(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->withCount(['students' => function (Builder $query) {
                    $query->where('session', '>=', now()->subYears(6)->format('Y') . '-' . now()->subYears(5)->format('y'));
                }]);
            })
            ->recordTitleAttribute('number')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('students_count')
                    ->label('Students')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->color(fn ($record) => $record->status === 'Full' ? Color::Red : Color::Green)
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->slideOver()
                    ->modalWidth('md'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalWidth('md'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
