<?php

namespace App\Filament\Paciente\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Mi Perfil';
    protected static ?string $title = 'Mi Perfil';
    protected string $view = 'filament.paciente.pages.profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $paciente = $user->paciente;

        $this->form->fill([
            'name'             => $user->name,
            'email'            => $user->email,
            'foto'             => $user->foto,
            'telefono'         => $user->telefono,
            'rut'              => $paciente?->rut,
            'fecha_nacimiento' => $paciente?->fecha_nacimiento,
            'genero'           => $paciente?->genero,
            'alergias'         => $paciente?->alergias,
            'medicamentos'     => $paciente?->medicamentos,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Información personal')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('foto')
                            ->label('Foto de perfil')
                            ->image()
                            ->directory('perfiles')
                            ->disk('public')
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('Nombre completo')
                            ->required(),

                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required(),

                        TextInput::make('telefono')
                            ->label('Teléfono'),

                        TextInput::make('rut')
                            ->label('RUT'),

                        DatePicker::make('fecha_nacimiento')
                            ->label('Fecha de nacimiento'),

                        Select::make('genero')
                            ->label('Género')
                            ->options([
                                'masculino' => 'Masculino',
                                'femenino'  => 'Femenino',
                                'otro'      => 'Otro',
                            ]),
                    ]),

                Section::make('Información médica')
                    ->schema([
                        Textarea::make('alergias')
                            ->label('Alergias'),

                        Textarea::make('medicamentos')
                            ->label('Medicamentos'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        $user->update([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'foto'     => $data['foto'],
            'telefono' => $data['telefono'],
        ]);

        $user->paciente->update([
            'rut'              => $data['rut'],
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'genero'           => $data['genero'],
            'alergias'         => $data['alergias'],
            'medicamentos'     => $data['medicamentos'],
        ]);

        Notification::make()
            ->title('Perfil actualizado correctamente')
            ->success()
            ->send();
    }
}
