<?php

namespace App\Filament\Support;

use App\Models\Seguimiento;
use Carbon\Carbon;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SeguimientoFotos
{
    public const THUMB_SIZE = 100;

    public const TABLE_THUMB_SIZE = 52;

    public static function uploadField(): SpatieMediaLibraryFileUpload
    {
        $disk = env('MEDIA_DISK', 'public');

        return SpatieMediaLibraryFileUpload::make('fotos_clinicas')
            ->label(__('fields.fotos_lesion'))
            ->collection('fotos_clinicas')
            ->disk($disk)
            ->image()
            ->multiple()
            ->maxSize(5120)
            ->panelLayout('grid')
            ->imagePreviewHeight((string) self::THUMB_SIZE)
            ->columnSpanFull()
            ->reorderable()
            ->appendFiles()
            ->mediaName(fn ($file) => preg_replace('/[^a-zA-Z0-9_\-\s]/', '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'foto');
    }

    public static function photosSection(): Section
    {
        return Section::make(__('sections.fotos_clinicas'))
            ->schema([
                ViewEntry::make('fotos_clinicas_grid')
                    ->label('')
                    ->view('filament.seguimientos.fotos-grid')
                    ->viewData(fn (Seguimiento $record): array => [
                        'seguimientoId' => $record->getKey(),
                    ]),
            ])
            ->columnSpanFull();
    }

    public static function configureHistoryTable(Table $table, bool $showLesion = false): Table
    {
        $columns = [];

        if ($showLesion) {
            $columns[] = Tables\Columns\TextColumn::make('lesion.nombre')
                ->label(__('tables.lesion'))
                ->searchable()
                ->wrap();
        }

        $columns = array_merge($columns, self::historyTableColumns());

        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->with($showLesion ? ['media', 'lesion'] : ['media'])
                ->orderByDesc('fecha')
                ->orderByDesc('hora')
                ->orderByDesc('created_at'))
            ->defaultSort('fecha', 'desc')
            ->columns($columns);
    }

    /** @return array<int, Tables\Columns\Column> */
    public static function historyTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('fecha')
                ->label(__('tables.registro'))
                ->formatStateUsing(fn ($state, Seguimiento $record): string => self::formatRegistro($record))
                ->description(fn (Seguimiento $record): ?string => self::formatRegistradoEl($record))
                ->sortable(['fecha', 'hora'])
                ->wrap(),

            Tables\Columns\TextColumn::make('nota')
                ->label(__('tables.nota'))
                ->limit(80)
                ->placeholder(__('messages.sin_nota'))
                ->wrap(),

            Tables\Columns\TextColumn::make('fotos_clinicas')
                ->label(__('fields.fotos_lesion'))
                ->formatStateUsing(fn ($state, Seguimiento $record): string => self::tableThumbnailsHtml($record))
                ->html(),

            Tables\Columns\TextColumn::make('subido_por')
                ->label(__('tables.subido_por'))
                ->badge()
                ->formatStateUsing(fn (?string $state): string => self::formatSubidoPor($state))
                ->color(fn (?string $state): string => match ($state) {
                    'profesional' => 'success',
                    'paciente' => 'info',
                    default => 'gray',
                }),
        ];
    }

    public static function configureLesionHistoryTable(Table $table): Table
    {
        return self::configureHistoryTable($table)
            ->paginated(false)
            ->heading(__('tables.historial_seguimientos'))
            ->description(__('messages.historial_seguimientos_desc'));
    }

    public static function formatRegistro(Seguimiento $record): string
    {
        $fecha = $record->fecha?->format('d/m/Y') ?? '—';
        $hora = $record->hora
            ? Carbon::parse($record->hora)->format('H:i')
            : '—';

        return "{$fecha} · {$hora}";
    }

    public static function formatRegistradoEl(Seguimiento $record): ?string
    {
        if (! $record->created_at) {
            return null;
        }

        return __('messages.registrado_el', [
            'fecha' => $record->created_at->format('d/m/Y H:i'),
        ]);
    }

    public static function formatSubidoPor(?string $state): string
    {
        return match ($state) {
            'paciente' => __('fields.subido_por_paciente'),
            'profesional' => __('fields.subido_por_profesional'),
            default => $state ?? '—',
        };
    }

    public static function tableThumbnailsHtml(Seguimiento $record, int $size = self::TABLE_THUMB_SIZE, int $max = 4): string
    {
        $media = $record->getMedia('fotos_clinicas');

        if ($media->isEmpty()) {
            return '<span style="font-size:12px;color:#9ca3af;">'.e(__('messages.sin_fotos')).'</span>';
        }

        $html = '<div style="display:flex;flex-wrap:wrap;gap:5px;align-items:center;">';

        foreach ($media->take($max) as $item) {
            $disk = env('MEDIA_DISK', 'public');
            $url = ($disk === 's3')
                ? $item->getTemporaryUrl(now()->addMinutes(60))
                : $item->getUrl();

            $html .= sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" style="display:block;line-height:0;">'
                .'<img src="%s" alt="" style="width:%dpx;height:%dpx;border-radius:7px;object-fit:cover;border:1px solid #e5e7eb;display:block;" />'
                .'</a>',
                e($url),
                e($url),
                $size,
                $size,
            );
        }

        if ($media->count() > $max) {
            $html .= '<span style="font-size:11px;color:#6b7280;font-weight:500;">+'.($media->count() - $max).'</span>';
        }

        $html .= '</div>';

        return $html;
    }

    public static function attachUploadedPhotos(Seguimiento $seguimiento, array|string|null $paths): void
    {
        foreach (Arr::wrap($paths) as $path) {
            if (blank($path)) {
                continue;
            }

            if ($path instanceof TemporaryUploadedFile) {
                self::addMediaFromTemporaryFile($seguimiento, $path);

                continue;
            }

            if (! is_string($path)) {
                continue;
            }

            if (TemporaryUploadedFile::canUnserialize($path)) {
                $files = TemporaryUploadedFile::unserializeFromLivewireRequest($path);

                foreach (Arr::wrap($files) as $file) {
                    if ($file instanceof TemporaryUploadedFile) {
                        self::addMediaFromTemporaryFile($seguimiento, $file);
                    }
                }

                continue;
            }

            try {
                self::addMediaFromTemporaryFile(
                    $seguimiento,
                    TemporaryUploadedFile::createFromLivewire($path),
                );
            } catch (\Throwable) {
                continue;
            }
        }

        $seguimiento->load('media');
    }

    private static function addMediaFromTemporaryFile(Seguimiento $seguimiento, TemporaryUploadedFile $file): void
    {
        if (! $file->exists()) {
            return;
        }

        $content = $file->get();

        if (is_null($content)) {
            return;
        }

        $seguimiento
            ->addMediaFromString($content)
            ->usingFileName($file->getClientOriginalName())
            ->toMediaCollection('fotos_clinicas', env('MEDIA_DISK', 'public'));
    }
}
