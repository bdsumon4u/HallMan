<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->placeholder('Upload the student image')
                    ->hiddenLabel()
                    ->required()
                    ->image()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sid')
                    ->placeholder('Enter the student sid')
                    ->label('SID')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->placeholder('Enter the student name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->placeholder('Enter the student email')
                    ->label('Email'),
                Forms\Components\TextInput::make('phone')
                    ->placeholder('Enter the student phone')
                    ->label('Phone')
                    ->required(),
                Forms\Components\Select::make('hall_id')
                    ->placeholder('Select the student hall')
                    ->label('Hall')
                    ->relationship('hall', 'name')
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->live(true),
                Forms\Components\TextInput::make('address')
                    ->placeholder('Enter the student address')
                    ->label('Address')
                    ->required()
                    ->columnSpanFull()
                    ->hidden(fn ($get) => $get('hall_id')),
                Forms\Components\TextInput::make('block_no')
                    ->placeholder('Enter the student block number')
                    ->label('Block No')
                    ->required()
                    ->hidden(fn ($get) => !$get('hall_id')),
                Forms\Components\TextInput::make('room_no')
                    ->placeholder('Enter the student room number')
                    ->label('Room No')
                    ->required()
                    ->hidden(fn ($get) => !$get('hall_id')),
                Forms\Components\TextInput::make('department')
                    ->placeholder('Enter the student department')
                    ->label('Department')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('session')
                    ->placeholder('Enter the student session')
                    ->label('Session')
                    ->required(),
                Forms\Components\TextInput::make('year')
                    ->placeholder('Enter the student year')
                    ->label('Year')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sid')
                    ->label('SID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hall.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('block_no')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('room_no')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('department')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('session')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
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
            'index' => Pages\ListStudents::route('/'),
            // 'create' => Pages\CreateStudent::route('/create'),
            // 'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
