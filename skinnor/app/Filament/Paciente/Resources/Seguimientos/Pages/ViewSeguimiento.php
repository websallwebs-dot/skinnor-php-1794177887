<?php

namespace App\Filament\Paciente\Resources\Seguimientos\Pages;

use App\Filament\Paciente\Resources\Seguimientos\SeguimientoResource;
use App\Filament\Support\SeguimientoFotos;
use Carbon\Carbon;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewSeguimiento extends ViewRecord
{
    protected static string $resource = SeguimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist->schema([

            Section::make(__('sections.informacion_seguimiento'))
                ->schema([
                    TextEntry::make('lesion.nombre')->label(__('tables.lesion')),
                    TextEntry::make('lesion.parte_del_cuerpo')->label('Ubicación')->badge(),
                    TextEntry::make('fecha')->label(__('fields.fecha'))->date('d/m/Y'),
                    TextEntry::make('hora')->label(__('fields.hora'))->time('H:i'),
                    TextEntry::make('nota')->label(__('tables.nota'))
                        ->placeholder(__('messages.sin_nota'))->columnSpanFull(),
                    TextEntry::make('subido_por')->label(__('tables.subido_por'))
                        ->formatStateUsing(fn (?string $state) => SeguimientoFotos::formatSubidoPor($state))
                        ->badge(),
                    TextEntry::make('created_at')->label(__('tables.registro_sistema'))
                        ->dateTime('d/m/Y H:i'),
                ])
                ->columns(2),

            Section::make('Fotos clínicas')
                ->schema([
                    ViewEntry::make('fotos_grid')
                        ->label('')
                        ->view('filament.seguimientos.fotos-grid')
                        ->viewData(fn () => ['seguimientoId' => $this->record->getKey()]),
                ])
                ->columnSpanFull(),

            Section::make('Historial cronológico')
                ->schema([
                    ViewEntry::make('historial_fotos')
                        ->label('')
                        ->view('filament.paciente.lesion-historial')
                        ->viewData(fn () => [
                            'seguimientos' => $this->record
                                ->lesion
                                ->seguimientos()
                                ->with('media')
                                ->orderByDesc('fecha')
                                ->orderByDesc('hora')
                                ->get()
                                ->map(function ($seg) {
                                    return [
                                        'id' => $seg->id,
                                        'fecha' => optional($seg->fecha)->format('d/m/Y') ?? '—',
                                        'hora' => $seg->hora ? Carbon::parse($seg->hora)->format('H:i') : '—',
                                        'nota' => $seg->nota,
                                        'subido_por' => $seg->subido_por,
                                        'total_fotos' => $seg->getMedia('fotos_clinicas')->count(),
                                        'registrado' => $seg->created_at?->format('d/m/Y H:i'),
                                        'view_url' => SeguimientoResource::getUrl('view', ['record' => $seg->id]),
                                    ];
                                })->toArray(),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
