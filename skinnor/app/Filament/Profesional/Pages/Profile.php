<?php

namespace App\Filament\Profesional\Pages;

use App\Models\Profesional;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Mi Perfil';
    protected static ?string $title = 'Mi Perfil';
    protected string $view = 'filament.profesional.pages.profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $profesional = $user->profesional;

        $this->form->fill([
            'name'             => $user->name,
            'email'            => $user->email,
            'foto'             => $user->foto,
            'especialidad'     => $profesional?->especialidad,
            'numero_licencia'  => $profesional?->numero_licencia,
            'rut'              => $profesional?->rut,
            'universidad'      => $profesional?->universidad,
            'anos_experiencia' => $profesional?->anos_experiencia,
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
                            ->visibility('public')
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('Nombre completo')
                            ->required(),

                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required(),

                        TextInput::make('rut')
                            ->label('RUT'),
                    ]),

                Section::make('Información profesional')
                    ->columns(2)
                    ->schema([
                        TextInput::make('especialidad')
                            ->label('Especialidad'),

                        TextInput::make('numero_licencia')
                            ->label('Número de licencia'),

                        TextInput::make('universidad')
                            ->label('Universidad'),

                        TextInput::make('anos_experiencia')
                            ->label('Años de experiencia')
                            ->numeric(),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'foto'  => $data['foto'],
        ]);

        $user->profesional->update([
            'especialidad'     => $data['especialidad'],
            'numero_licencia'  => $data['numero_licencia'],
            'rut'              => $data['rut'],
            'universidad'      => $data['universidad'],
            'anos_experiencia' => $data['anos_experiencia'],
        ]);

        Notification::make()
            ->title('Perfil actualizado correctamente')
            ->success()
            ->send();
    }
}
