<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeeResource\Pages;
use App\Filament\Resources\FeeResource\RelationManagers;
use App\Models\Fee;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeeResource extends Resource
{
    protected static ?string $model = Fee::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-bangladeshi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->placeholder('Select the fee student')
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
                    ->live(true),
                Forms\Components\DatePicker::make('from')
                    ->placeholder('Select the fee from date')
                    ->label('From Date')
                    ->required()
                    ->native(false),
                Forms\Components\DatePicker::make('till')
                    ->placeholder('Select the fee till date')
                    ->label('Till Date')
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('amount')
                    ->placeholder('Enter the fee amount')
                    ->label('Amount')
                    ->required(),
                Forms\Components\Textarea::make('remarks')
                    ->placeholder('Enter the fee remarks')
                    ->label('Remarks'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('from')
                    ->label('From Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('till')
                    ->label('Till Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->label('Remarks')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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
            ]);
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
            'index' => Pages\ListFees::route('/'),
            // 'create' => Pages\CreateFee::route('/create'),
            // 'edit' => Pages\EditFee::route('/{record}/edit'),
        ];
    }
}
