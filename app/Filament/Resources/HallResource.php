<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HallResource\Pages;
use App\Filament\Resources\HallResource\RelationManagers;
use App\Models\Hall;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HallResource extends Resource
{
    protected static ?string $model = Hall::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('Enter the hall name')
                    ->label('Name')
                    ->unique()
                    ->required(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->modifyQueryUsing(function ($query) {
                $query->withCount([
                    'rooms' => fn ($query) => $query,
                    'students' => function ($query) {
                        $query->where('session', '>=', now()->subYears(6)->format('Y') . '-' . now()->subYears(5)->format('y'));
                    },
                ])
                ->withSum('rooms', 'capacity');
            })
            ->recordUrl(fn ($record) => Pages\EditHall::getUrl(['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rooms_count')
                    ->label('Rooms')
                    ->sortable()
                    ->summarize(Sum::make()),
                Tables\Columns\TextColumn::make('rooms_sum_capacity')
                    ->label('Seats')
                    ->default(0)
                    ->sortable()
                    ->summarize(Sum::make()),
                Tables\Columns\TextColumn::make('students_count')
                    ->label('Students')
                    ->sortable()
                    ->summarize(Sum::make()),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->default(function ($record) {
                        if ($record->rooms_sum_capacity > $record->students_count) {
                            return ($record->rooms_sum_capacity - $record->students_count) . ' Left';
                        }
                        
                        return 'Full';
                    })
                    ->color(fn ($record) => $record->rooms_sum_capacity <= $record->students_count ? Color::Red : Color::Green)
                    ->badge()
                    ->summarize(
                        Summarizer::make()
                            ->using(function ($query) {
                                return $query->sum('rooms_sum_capacity') - $query->sum('students_count');
                            })
                            ->label('Sum')
                    ),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHalls::route('/'),
            // 'create' => Pages\CreateHall::route('/create'),
            'edit' => Pages\EditHall::route('/{record}/edit'),
        ];
    }
}
