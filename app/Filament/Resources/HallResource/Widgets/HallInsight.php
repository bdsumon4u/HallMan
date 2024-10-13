<?php

namespace App\Filament\Resources\HallResource\Widgets;

use App\Filament\Resources\HallResource;
use App\Filament\Resources\HallResource\Pages\CreateHall;
use App\Filament\Resources\HallResource\Pages\EditHall;
use App\Models\Hall;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class HallInsight extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return HallResource::table($table)
            ->query(
                Hall::query()
                    ->withCount([
                        'rooms' => fn ($query) => $query,
                        'students' => function ($query) {
                            $query->where('session', '>=', now()->subYears(5)->format('Y'));
                        },
                    ])
                    ->withSum('rooms', 'capacity')
            )
            ->headerActions([
                Tables\Actions\Action::make('Add Hall')
                    ->form(fn ($form) => HallResource::form($form))
                    ->slideOver()
                    ->modalWidth('md')
                    ->icon('heroicon-o-plus'),
            ]);
    }
}
