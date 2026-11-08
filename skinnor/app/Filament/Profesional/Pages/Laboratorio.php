<?php

declare(strict_types=1);

namespace App\Filament\Profesional\Pages;

use App\Models\Lesion;
use App\Models\Paciente;
use App\Models\Seguimiento;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class Laboratorio extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use WithFileUploads;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.profesional.pages.laboratorio';

    public function getTitle(): string|Htmlable
    {
        return 'Laboratorio Dermatológico';
    }

    public static function getNavigationLabel(): string
    {
        return 'Laboratorio';
    }

    public ?int $pacienteSeleccionado = null;

    public string $tabActiva = 'seguimientos';

    // Datos del paciente seleccionado
    public ?string $pacienteFotoUrl = null;

    public ?string $pacienteNombre = null;

    public ?string $pacienteEmail = null;

    public ?string $pacienteTelefono = null;

    public ?string $pacienteRut = null;

    public ?string $pacienteFechaNacimiento = null;

    public ?string $pacienteEdad = null;

    public ?string $pacienteGenero = null;

    public ?string $pacienteHistorialMedico = null;

    public ?string $pacienteAlergias = null;

    public ?string $pacienteMedicamentos = null;

    public ?string $pacienteContactoEmergencia = null;

    public ?string $pacienteTelefonoEmergencia = null;

    // Métricas del paciente
    public int $totalSeguimientos = 0;

    public int $totalLesiones = 0;

    public int $diasMonitoreo = 0;

    public int $consultasPendientes = 0;

    // Lesiones y consultas
    public array $pacienteLesiones = [];

    public array $pacienteConsultas = [];

    // Subida de nueva foto
    public $nuevaFotoPaciente = null;

    public ?string $notaNuevaFoto = null;

    public ?int $lesionNuevaFotoId = null;

    // Métricas ABCDE
    public ?int $lesionSeleccionadaId = null;

    public ?int $abcde_asimetria = 1;

    public ?int $abcde_bordes = 1;

    public ?int $abcde_color = 1;

    public ?int $abcde_diametro = 1;

    public ?int $abcde_evolucion = 1;

    public ?string $abcde_notas = null;

    public ?string $abcde_resultado = null;

    public ?string $abcde_riesgo = null;

    public function mount(): void
    {
        if (request()->has('paciente')) {
            $this->pacienteSeleccionado = (int) request('paciente');
            $this->cargarPacienteCompleto();
        }
    }

    public function form(Schema $form): Schema
    {
        return $form->components([
            Select::make('pacienteSeleccionado')
                ->label('Expediente del Paciente')
                ->placeholder('-- Selecciona un paciente para iniciar el análisis --')
                ->options($this->getPacientesOptions())
                ->live()
                ->searchable()
                ->afterStateUpdated(fn ($state) => $this->updatedPacienteSeleccionado($state))
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                if (! $this->pacienteSeleccionado) {
                    return Seguimiento::query()->whereRaw('1 = 0');
                }

                return Seguimiento::query()
                    ->with(['media', 'lesion'])
                    ->whereHas('lesion', fn ($q) => $q->where('paciente_id', $this->pacienteSeleccionado));
            })
            ->columns([
                SpatieMediaLibraryImageColumn::make('fotos_clinicas')
                    ->label('Fotografías')
                    ->collection('fotos_clinicas')
                    ->circular()
                    ->stacked()
                    ->limit(4)
                    ->limitedRemainingText(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('hora')
                    ->label('Hora')
                    ->time('H:i')
                    ->placeholder('—'),

                TextColumn::make('lesion.nombre')
                    ->label('Lesión')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('lesion.parte_del_cuerpo')
                    ->label('Ubicación')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                TextColumn::make('nota')
                    ->label('Observaciones Clínicas')
                    ->limit(55)
                    ->placeholder('Sin observaciones')
                    ->wrap(),

                TextColumn::make('subido_por')
                    ->label('Origen')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'profesional' => 'Médico',
                        'paciente' => 'Paciente',
                        default => $state ?? '—',
                    })
                    ->color(fn ($state) => match ($state) {
                        'profesional' => 'success',
                        'paciente' => 'info',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('fecha', 'desc')
            ->emptyStateHeading('Sin seguimientos registrados')
            ->emptyStateDescription('Este paciente aún no tiene fotografías clínicas registradas.')
            ->emptyStateIcon('heroicon-o-camera')
            ->actions([
                Action::make('evaluarAbcde')
                    ->label('Evaluar ABCDE')
                    ->icon('heroicon-o-chart-bar')
                    ->color('primary')
                    ->button()
                    ->size('xs')
                    ->action(function (Seguimiento $record) {
                        $this->tabActiva = 'metricas';
                        $this->lesionSeleccionadaId = $record->lesion_id;
                        Notification::make()
                            ->title("Evaluando lesión: {$record->lesion?->nombre}")
                            ->info()
                            ->send();
                    }),

                Action::make('buscar_similares')
                    ->label('Similares HAM10000')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('gray')
                    ->button()
                    ->size('xs')
                    ->modalHeading('Búsqueda de Casos Similares en HAM10000')
                    ->modalContent(fn ($record) => view('filament.profesional.seguimientos.similares', ['seguimiento' => $record])),
            ]);
    }

    public function updatedPacienteSeleccionado($value): void
    {
        $this->pacienteSeleccionado = $value ? (int) $value : null;
        $this->cargarPacienteCompleto();
        $this->resetTable();
    }

    public function seleccionarPacienteDirecto(int $pacienteId): void
    {
        $this->pacienteSeleccionado = $pacienteId;
        $this->cargarPacienteCompleto();
        $this->resetTable();
    }

    public function limpiarPaciente(): void
    {
        $this->pacienteSeleccionado = null;
        $this->resetearDatosPaciente();
        $this->resetTable();
    }

    public function activarHerramienta(string $tab): void
    {
        $this->tabActiva = $tab;
    }

    public function cargarPacienteCompleto(): void
    {
        if (! $this->pacienteSeleccionado) {
            $this->resetearDatosPaciente();

            return;
        }

        $paciente = Paciente::with(['user', 'media', 'lesiones.seguimientos.media', 'consultas'])
            ->find($this->pacienteSeleccionado);

        if (! $paciente) {
            $this->resetearDatosPaciente();

            return;
        }

        // Datos del usuario
        $this->pacienteNombre = $paciente->user?->name ?? 'Paciente #' . $paciente->id;
        $this->pacienteEmail = $paciente->user?->email ?? '—';
        $this->pacienteTelefono = $paciente->telefono ?: ($paciente->user?->telefono ?? '—');

        // Datos del paciente
        $this->pacienteRut = $paciente->rut ?? '—';
        if ($paciente->fecha_nacimiento) {
            $this->pacienteFechaNacimiento = Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y');
            $this->pacienteEdad = (string) Carbon::parse($paciente->fecha_nacimiento)->age . ' años';
        } else {
            $this->pacienteFechaNacimiento = '—';
            $this->pacienteEdad = '—';
        }

        $this->pacienteGenero = match ($paciente->genero) {
            'masculino' => 'Masculino',
            'femenino' => 'Femenino',
            'otro' => 'Otro',
            default => $paciente->genero ?? '—',
        };

        $this->pacienteHistorialMedico = $paciente->historial_medico ?? 'Sin antecedentes registrados.';
        $this->pacienteAlergias = $paciente->alergias ?? 'Sin alergias registradas.';
        $this->pacienteMedicamentos = $paciente->medicamentos ?? 'Sin medicamentos actuales.';
        $this->pacienteContactoEmergencia = $paciente->nombre_contacto_emergencia ?? '—';
        $this->pacienteTelefonoEmergencia = $paciente->telefono_emergencia ?? '—';

        // Foto del paciente (o foto del usuario)
        $fotoMedia = $paciente->getMedia('foto')->first() ?? $paciente->user?->getMedia('foto')->first();
        $this->pacienteFotoUrl = $fotoMedia ? $fotoMedia->getUrl() : null;

        // Métricas
        $this->totalLesiones = $paciente->lesiones->count();
        $this->totalSeguimientos = $paciente->lesiones->sum(fn ($l) => $l->seguimientos->count());

        $primeraFecha = $paciente->lesiones->min('fecha_inicio') ?? $paciente->created_at ?? now();
        $this->diasMonitoreo = max(1, (int) Carbon::parse($primeraFecha)->diffInDays(now()));

        $this->consultasPendientes = $paciente->consultas->where('leida', false)->count();

        // Lesiones
        $this->pacienteLesiones = $paciente->lesiones->map(function ($lesion) {
            return [
                'id' => $lesion->id,
                'nombre' => $lesion->nombre,
                'parte_del_cuerpo' => $lesion->parte_del_cuerpo ?? '—',
                'fecha_inicio' => $lesion->fecha_inicio ? Carbon::parse($lesion->fecha_inicio)->format('d/m/Y') : '—',
                'estado' => $lesion->estado,
                'seguimientos_count' => $lesion->seguimientos->count(),
            ];
        })->toArray();

        // Consultas
        $this->pacienteConsultas = $paciente->consultas->map(function ($consulta) {
            return [
                'id' => $consulta->id,
                'mensaje' => $consulta->mensaje,
                'respuesta' => $consulta->respuesta,
                'leida' => (bool) $consulta->leida,
                'respondida_at' => $consulta->respondida_at ? Carbon::parse($consulta->respondida_at)->format('d/m/Y H:i') : null,
                'created_at' => $consulta->created_at ? $consulta->created_at->format('d/m/Y H:i') : '—',
            ];
        })->toArray();
    }

    public function guardarNuevaFoto(): void
    {
        if (! $this->pacienteSeleccionado) {
            Notification::make()
                ->title('Selecciona un paciente')
                ->warning()
                ->send();

            return;
        }

        if (! $this->nuevaFotoPaciente) {
            Notification::make()
                ->title('Adjunta una fotografía')
                ->warning()
                ->send();

            return;
        }

        $this->validate([
            'nuevaFotoPaciente' => 'image|max:10240',
        ]);

        $paciente = Paciente::with('lesiones')->find($this->pacienteSeleccionado);
        if (! $paciente) {
            return;
        }

        // Asociar a lesión seleccionada o a la primera
        $lesionId = $this->lesionNuevaFotoId ?: $paciente->lesiones->first()?->id;

        if (! $lesionId) {
            $lesion = Lesion::create([
                'paciente_id' => $paciente->id,
                'nombre' => 'Lesión clínica',
                'fecha_inicio' => today(),
                'estado' => 'activa',
            ]);
            $lesionId = $lesion->id;
        }

        $seguimiento = Seguimiento::create([
            'lesion_id' => $lesionId,
            'profesional_id' => Auth::user()->profesional?->id,
            'fecha' => today(),
            'hora' => now()->format('H:i:s'),
            'subido_por' => 'profesional',
            'nota' => $this->notaNuevaFoto ?: 'Fotografía clínica capturada en consulta.',
        ]);

        $seguimiento->addMedia($this->nuevaFotoPaciente->getRealPath())
            ->usingFileName($this->nuevaFotoPaciente->getClientOriginalName())
            ->toMediaCollection('fotos_clinicas', env('MEDIA_DISK', 'public'));

        $this->nuevaFotoPaciente = null;
        $this->notaNuevaFoto = null;
        $this->cargarPacienteCompleto();
        $this->resetTable();

        Notification::make()
            ->title('Fotografía clínica guardada con éxito')
            ->body('La imagen se ha indexado en la tabla de seguimientos.')
            ->success()
            ->send();
    }

    public function guardarAbcde(): void
    {
        $this->validate([
            'abcde_asimetria' => 'required|integer|min:1|max:5',
            'abcde_bordes' => 'required|integer|min:1|max:5',
            'abcde_color' => 'required|integer|min:1|max:5',
            'abcde_diametro' => 'required|integer|min:1|max:5',
            'abcde_evolucion' => 'required|integer|min:1|max:5',
        ]);

        $this->calcularAbcde();

        Notification::make()
            ->title('Evaluación ABCDE Guardada')
            ->body("{$this->abcde_resultado} — Nivel de Riesgo: {$this->abcde_riesgo}")
            ->success()
            ->send();
    }

    protected function calcularAbcde(): void
    {
        $puntos = [
            'asimetria' => $this->abcde_asimetria ?? 1,
            'bordes' => $this->abcde_bordes ?? 1,
            'color' => $this->abcde_color ?? 1,
            'diametro' => $this->abcde_diametro ?? 1,
            'evolucion' => $this->abcde_evolucion ?? 1,
        ];

        $total = array_sum($puntos);

        if ($total >= 20) {
            $riesgo = 'Muy Alto (Atención prioritaria y biopsia sugerida)';
        } elseif ($total >= 15) {
            $riesgo = 'Alto (Seguimiento dermatoscópico estrecho)';
        } elseif ($total >= 10) {
            $riesgo = 'Moderado';
        } else {
            $riesgo = 'Bajo (Parámetros benignos)';
        }

        $this->abcde_resultado = "Puntuación Total: {$total} / 25";
        $this->abcde_riesgo = $riesgo;
    }

    protected function resetearDatosPaciente(): void
    {
        $this->pacienteFotoUrl = null;
        $this->pacienteNombre = null;
        $this->pacienteEmail = null;
        $this->pacienteTelefono = null;
        $this->pacienteRut = null;
        $this->pacienteFechaNacimiento = null;
        $this->pacienteEdad = null;
        $this->pacienteGenero = null;
        $this->pacienteHistorialMedico = null;
        $this->pacienteAlergias = null;
        $this->pacienteMedicamentos = null;
        $this->pacienteContactoEmergencia = null;
        $this->pacienteTelefonoEmergencia = null;
        $this->totalSeguimientos = 0;
        $this->totalLesiones = 0;
        $this->diasMonitoreo = 0;
        $this->consultasPendientes = 0;
        $this->pacienteLesiones = [];
        $this->pacienteConsultas = [];
        $this->abcde_resultado = null;
        $this->abcde_riesgo = null;
    }

    public function getPacientesOptions(): array
    {
        $query = Paciente::query()->with('user');

        return $query->get()->mapWithKeys(function ($paciente) {
            $nombre = $paciente->user?->name ?? 'Paciente #' . $paciente->id;
            $rut = $paciente->rut ? " ({$paciente->rut})" : '';

            return [$paciente->id => "{$nombre}{$rut}"];
        })->toArray();
    }

    public function getPacientesList(): array
    {
        return Paciente::query()
            ->with(['user', 'media'])
            ->get()
            ->map(function ($paciente) {
                return [
                    'id' => $paciente->id,
                    'nombre' => $paciente->user?->name ?? 'Paciente #' . $paciente->id,
                    'rut' => $paciente->rut ?? 'Sin RUT',
                    'telefono' => $paciente->telefono ?: ($paciente->user?->telefono ?? '—'),
                    'email' => $paciente->user?->email ?? '—',
                ];
            })
            ->toArray();
    }
}
