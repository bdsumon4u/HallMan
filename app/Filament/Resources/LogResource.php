<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogResource\Pages;
use App\Filament\Resources\LogResource\RelationManagers;
use App\Models\Hall;
use App\Models\Log;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogResource extends Resource
{
    protected static ?string $model = Log::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('hall_id')
                    ->placeholder('Select the log hall')
                    ->label('Hall')
                    ->relationship('hall', 'name')
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(true),
                Forms\Components\Select::make('student_id')
                    ->placeholder('Select the log student')
                    ->label('Student')
                    ->relationship('student', 'name')
                    ->getSearchResultsUsing(function (string $search): array {
                        return Student::query()
                            ->where('name', 'like', "%$search%")
                            ->orWhere('sid', 'like', "%$search%")
                            ->limit(10)->get()->mapWithKeys(fn (Student $student): array => [
                                $student->getKey() => $student->name . ' [' . $student->sid . ']',
                            ])->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        if (!$student = Student::find($value)) {
                            return null;
                        }

                        return $student->name . ' [' . $student->sid . ']';
                    })
                    ->columnSpanFull()
                    ->searchable()
                    ->live(true)
                    ->afterStateUpdated(function ($get, $set) {
                        $log = Log::query()
                            ->where('student_id', $get('student_id'))
                            ->latest('id')
                            ->first();

                        $set('action', $log?->action == 'in' ? 'out' : 'in');
                    }),
                Forms\Components\Radio::make('action')
                    ->options([
                        'in' => 'Enter',
                        'out' => 'Exit',
                    ])
                    ->columns(2)
                    ->label('Action')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with(['hall', 'student.hall']);
            })
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('student.image')
                    ->label('Photo'),
                Tables\Columns\TextColumn::make('student.name')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record->student) {
                            return $state;
                        }

                        return '<strong>'.$record->student?->name.'</strong> - ' . $record->student?->sid;
                    })
                    ->html()
                    ->default('Unknown')
                    ->label('Student')
                    ->searchable()
                    ->sortable()
                    ->description(function ($record) {
                        return $record->student?->hall?->name;
                    }),
                // Tables\Columns\TextColumn::make('hall.name')
                //     ->label('Hall')
                //     ->searchable()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('hall_id')
                    ->label('Hall')
                    ->options(fn () => Hall::pluck('name', 'id')->toArray())
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalWidth('md'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->persistFiltersInSession();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogs::route('/'),
            // 'create' => Pages\CreateLog::route('/create'),
            // 'edit' => Pages\EditLog::route('/{record}/edit'),
        ];
    }
}
