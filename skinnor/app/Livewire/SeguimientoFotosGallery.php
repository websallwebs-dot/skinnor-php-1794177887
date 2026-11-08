<?php

namespace App\Livewire;

use App\Models\Seguimiento;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class SeguimientoFotosGallery extends Component
{
    use WithFileUploads;

    public int $seguimientoId;

    public $nuevaFoto = null;

    public function mount(int $seguimientoId): void
    {
        $this->seguimientoId = $seguimientoId;
    }

    protected function getSeguimiento(): Seguimiento
    {
        return Seguimiento::findOrFail($this->seguimientoId);
    }

    public function subirFoto(): void
    {
        $this->validate([
            'nuevaFoto' => 'required|image|max:10240',
        ]);

        $seguimiento = $this->getSeguimiento();

        $seguimiento
            ->addMedia($this->nuevaFoto->getRealPath())
            ->usingFileName($this->nuevaFoto->getClientOriginalName())
            ->toMediaCollection('fotos_clinicas', env('MEDIA_DISK', 'public'));

        $this->nuevaFoto = null;

        Notification::make()
            ->title(__('messages.fotos_guardadas'))
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.seguimiento-fotos-gallery', [
            'seguimiento' => $this->getSeguimiento(),
        ]);
    }
}
