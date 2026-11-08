<?php

namespace App\Filament\Paciente\Resources\Lesions\RelationManagers;

use App\Filament\Paciente\Resources\Seguimientos\SeguimientoResource;
use App\Filament\Support\SeguimientoFotos;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SeguimientosRelationManager extends RelationManager
{
    protected static string $relationship = 'seguimientos';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
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
                CreateAction::make()
                    ->label(__('actions.nuevo_seguimiento'))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['subido_por'] = 'paciente';

                        return $data;
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn ($record): string => SeguimientoResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->label(__('actions.agregar_fotos')),
            ]);
    }
}
