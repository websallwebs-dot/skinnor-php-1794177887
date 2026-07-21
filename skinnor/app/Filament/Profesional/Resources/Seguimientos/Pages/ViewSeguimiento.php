<?php

namespace App\Filament\Profesional\Resources\Seguimientos\Pages;

use App\Filament\Profesional\Resources\Seguimientos\SeguimientoResource;
use App\Filament\Support\SeguimientoFotos;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewSeguimiento extends ViewRecord
{
    protected static string $resource = SeguimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Section::make(__('sections.informacion_seguimiento'))
                    ->schema([
                        TextEntry::make('lesion.paciente.user.name')
                            ->label(__('tables.paciente')),
                        TextEntry::make('lesion.nombre')
                            ->label(__('tables.lesion')),
                        TextEntry::make('fecha')
                            ->label(__('fields.fecha'))
                            ->date('d/m/Y'),
                        TextEntry::make('hora')
                            ->label(__('fields.hora'))
                            ->time('H:i'),
                        TextEntry::make('nota')
                            ->label(__('tables.nota'))
                            ->placeholder(__('messages.sin_nota'))
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->label(__('tables.registro_sistema'))
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('subido_por')
                            ->label(__('tables.subido_por'))
                            ->formatStateUsing(fn (?string $state): string => SeguimientoFotos::formatSubidoPor($state))
                            ->badge(),
                    ])
                    ->columns(2),

                SeguimientoFotos::photosSection(),
            ]);
    }
}
