<?php

namespace App\Filament\Profesional\Resources\Seguimientos;

use App\Filament\Profesional\Resources\Seguimientos\Pages;
use App\Models\Lesion;
use App\Models\Paciente;
use App\Models\Seguimiento;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SeguimientoResource extends Resource
{
    protected static ?string $model = Seguimiento::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocument;

    public static function getNavigationLabel(): string
    {
        return __('nav.seguimientos');
    }

    public static function getModelLabel(): string
    {
        return __('nav.seguimientos');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.seguimientos');
    }

    // Solo muestra seguimientos de pacientes asignados al profesional
    public static function getEloquentQuery(): Builder
    {
        $profesional = Auth::user()->profesional;
        $pacienteIds = Paciente::where('profesional_id', $profesional?->id)->pluck('id');
        $lesionIds = Lesion::whereIn('paciente_id', $pacienteIds)->pluck('id');
        return parent::getEloquentQuery()->whereIn('lesion_id', $lesionIds)->orderBy('fecha', 'desc');
    }

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\Select::make('lesion_id')
                ->label('Lesión del paciente')
                ->required()
                ->options(function () {
                    $profesional = Auth::user()->profesional;
                    $pacienteIds = Paciente::where('profesional_id', $profesional?->id)->pluck('id');
                    return Lesion::whereIn('paciente_id', $pacienteIds)
                        ->with('paciente.user')
                        ->get()
                        ->mapWithKeys(fn($l) => [
                            $l->id => $l->paciente->user->name . ' — ' . $l->nombre
                        ]);
                })
                ->searchable()
                ->columnSpanFull(),

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
                ->label(__('fields.nota_clinica'))
                ->rows(3)
                ->columnSpanFull(),

            Forms\Components\SpatieMediaLibraryFileUpload::make('fotos_clinicas')
                ->label(__('fields.fotos_lesion'))
                ->collection('fotos_clinicas')
                ->image()
                ->multiple()
                ->maxSize(5120)
                ->panelLayout('grid')
                ->columnSpanFull()
                ->reorderable()
                ->appendFiles(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lesion.paciente.user.name')
                    ->label(__('tables.paciente'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('lesion.nombre')
                    ->label(__('tables.lesion'))
                    ->searchable(),

                SpatieMediaLibraryImageColumn::make('fotos_clinicas')
                    ->label(__('fields.fotos_lesion'))
                    ->collection('fotos_clinicas')
                    ->conversion('thumb')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),

                TextColumn::make('fecha')
                    ->label(__('fields.fecha'))
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('subido_por')
                    ->label(__('tables.subido_por'))
                    ->badge(),
            ])
            ->defaultSort('fecha', 'desc')
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('buscar_similares')
                    ->label('Buscar similares')
                    ->icon('heroicon-o-magnifying-glass')
                    ->modalHeading('Imágenes similares')
                    ->modalContent(fn ($record) => view('filament.profesional.seguimientos.similares', ['seguimiento' => $record])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSeguimientos::route('/'),
            'create' => Pages\CreateSeguimiento::route('/create'),
            'view'   => Pages\ViewSeguimiento::route('/{record}'),
            'edit'   => Pages\EditSeguimiento::route('/{record}/edit'),
        ];
    }
}
