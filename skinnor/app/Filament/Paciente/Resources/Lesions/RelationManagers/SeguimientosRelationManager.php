<?php

namespace App\Filament\Paciente\Resources\Lesions\RelationManagers;

use App\Filament\Paciente\Resources\Seguimientos\SeguimientoResource;
use App\Filament\Support\SeguimientoFotos;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SeguimientosRelationManager extends RelationManager
{
    protected static string $relationship = 'seguimientos';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('tables.historial_seguimientos');
    }

    public function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\DatePicker::make('fecha')
                ->label(__('fields.fecha'))
                ->required()
                ->default(now()),

            Forms\Components\TimePicker::make('hora')
                ->label(__('fields.hora'))
                ->required()
                ->default(now())
                ->seconds(false),

            Forms\Components\Textarea::make('nota')
                ->label(__('fields.nota_opcional'))
                ->rows(3),

            SeguimientoFotos::uploadField(),
        ]);
    }

    public function table(Table $table): Table
    {
        return SeguimientoFotos::configureLesionHistoryTable($table)
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label(__('actions.nuevo_seguimiento'))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['subido_por'] = 'paciente';

                        return $data;
                    }),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make()
                    ->url(fn ($record): string => SeguimientoResource::getUrl('view', ['record' => $record])),
                \Filament\Actions\EditAction::make()
                    ->label(__('actions.agregar_fotos')),
            ]);
    }
}
