<?php

namespace App\Filament\Profesional\Pages;

use App\Services\Similares\DatasetSearchService;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;

class BuscarDataset extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';
    protected string $view = 'filament.profesional.pages.buscar-dataset';

    public static function getNavigationLabel(): string
    {
        return 'Buscar en Dataset';
    }

    // Form data
    public ?string $diagnostico = null;
    public ?string $tipoLesion = null;
    public ?string $edadMin = null;
    public ?string $edadMax = null;
    public ?string $localizacion = null;
    public ?string $dataset = 'HAM10000';
    public int $limit = 20;

    // Results
    public array $resultados = [];
    public bool $isSearching = false;
    public string $errorMessage = '';

    public function form(Schema $form): Schema
    {
        return $form->schema([
            Select::make('diagnostico')
                ->label('Diagnóstico')
                ->options([
                    'akiec' => 'Queratosis actínica / Carcinoma intraepitelial',
                    'bcc' => 'Carcinoma basocelular',
                    'bkl' => 'Lesiones benignas tipo queratosis',
                    'df' => 'Dermatofibroma',
                    'mel' => 'Melanoma',
                    'nv' => 'Nevos melanocíticos',
                    'vasc' => 'Lesiones vasculares',
                ])
                ->placeholder('Todos')
                ->searchable(),
            Select::make('tipoLesion')
                ->label('Tipo de lesión')
                ->options([
                    'histo' => 'Histológico',
                    'consensus' => 'Consenso',
                    'confocal' => 'Confocal',
                ])
                ->placeholder('Todos')
                ->searchable(),
            TextInput::make('dataset')
                ->label('Dataset')
                ->default('HAM10000')
                ->disabled(),
            TextInput::make('edadMin')
                ->label('Edad mínima')
                ->type('number')
                ->placeholder('Ej: 30')
                ->default(30),
            TextInput::make('edadMax')
                ->label('Edad máxima')
                ->type('number')
                ->placeholder('Ej: 60')
                ->default(60),
            Select::make('localizacion')
                ->label('Localización')
                ->options([
                    'face' => 'Cara',
                    'scalp' => 'Cuero cabelludo',
                    'ear' => 'Oído',
                    'neck' => 'Cuello',
                    'trunk' => 'Tronco',
                    'upper extremity' => 'Extremidad superior',
                    'lower extremity' => 'Extremidad inferior',
                    'genital' => 'Genital',
                    'acral' => 'Acral',
                    'back' => 'Espalda',
                    'chest' => 'Pecho',
                    'abdomen' => 'Abdomen',
                    'hand' => 'Mano',
                    'foot' => 'Pie',
                ])
                ->placeholder('Todas')
                ->searchable(),
            TextInput::make('limit')
                ->label('Resultados máximos')
                ->type('number')
                ->default(20),
        ]);
    }

    public function buscar(): void
    {
        $this->validate([
            'limit' => 'required|integer|min:1|max:100',
        ]);

        $this->isSearching = true;
        $this->errorMessage = '';
        $this->resultados = [];

        try {
            $service = new DatasetSearchService();
            
            $filtros = [];
            if ($this->diagnostico) {
                $filtros['dx'] = $this->diagnostico;
            }
            if ($this->tipoLesion) {
                $filtros['dx_type'] = $this->tipoLesion;
            }
            
            \Log::info('Payload enviado a DatasetSearchService:', ['filtros' => $filtros]);

            if ($this->edadMin) {
                $filtros['age'] = ['$gte' => (int) $this->edadMin];
            }
            if ($this->edadMax) {
                if (isset($filtros['age'])) {
                    $filtros['age']['$lte'] = (int) $this->edadMax;
                } else {
                    $filtros['age'] = ['$lte' => (int) $this->edadMax];
                }
            }
            if ($this->localizacion) {
                $filtros['localization'] = $this->localizacion;
            }
            // Solo añadir filtro de dataset si el usuario lo cambió explícitamente
            if ($this->dataset && $this->dataset !== 'HAM10000') {
                $filtros['dataset'] = $this->dataset;
            }

            // Si no hay filtros, no hacer búsqueda
            if (empty($filtros)) {
                $this->isSearching = false;
                Notification::make()
                    ->title('Sin filtros')
                    ->body('Por favor, selecciona al menos un filtro para buscar.')
                    ->warning()
                    ->send();
                return;
            }

            $resultados = $service->buscarPorFiltros($filtros, $this->limit);
            
            $this->resultados = $resultados;
            $this->isSearching = false;

            Notification::make()
                ->title('Búsqueda completada')
                ->body(count($resultados) . ' casos encontrados')
                ->success()
                ->send();

        } catch (\Exception $e) {
            $this->isSearching = false;
            $this->errorMessage = $e->getMessage();
            
            Notification::make()
                ->title('Error en búsqueda')
                ->body($this->errorMessage)
                ->danger()
                ->send();
        }
    }

    public function limpiar(): void
    {
        $this->diagnostico = null;
        $this->tipoLesion = null;
        $this->edadMin = null;
        $this->edadMax = null;
        $this->localizacion = null;
        $this->dataset = 'HAM10000';
        $this->limit = 20;
        $this->resultados = [];
        $this->errorMessage = '';
    }

    public static function canAccess(): bool
    {
        return true;
    }
}
