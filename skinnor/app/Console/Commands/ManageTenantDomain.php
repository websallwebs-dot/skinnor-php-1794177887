<?php

namespace App\Console\Commands;

use App\Models\Clinica;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Console\Command;

class ManageTenantDomain extends Command
{
    protected $signature = 'tenant:domain {action : create|list|delete} {--id= : Tenant ID} {--domain=}';
    protected $description = 'Manage tenant domains';

    public function handle()
    {
        $action = $this->argument('action');

        match($action) {
            'create' => $this->createDomain(),
            'list' => $this->listDomains(),
            'delete' => $this->deleteDomain(),
            default => $this->error("Acción desconocida: $action"),
        };
    }

    private function createDomain()
    {
        $tenantId = $this->option('id') ?? $this->ask('Tenant ID (ej: clinica-test):');
        $domain = $this->option('domain') ?? $this->ask('Domain (ej: clinica-test.skinnor.local):');

        // Verificar que el tenant existe
        $tenant = Clinica::find($tenantId);
        if (!$tenant) {
            $this->error("❌ Tenant '$tenantId' no existe");
            return;
        }

        // Verificar que el dominio no existe
        if (Domain::where('domain', $domain)->exists()) {
            $this->error("❌ El dominio '$domain' ya está registrado");
            return;
        }

        try {
            Domain::create([
                'tenant_id' => $tenantId,
                'domain' => $domain,
            ]);

            $this->info("✅ Dominio creado exitosamente");
            $this->table(['Dominio', 'Tenant ID'], [
                [$domain, $tenantId],
            ]);
        } catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
        }
    }

    private function listDomains()
    {
        $domains = Domain::with('tenant')->get();

        if ($domains->isEmpty()) {
            $this->info("No hay dominios configurados");
            return;
        }

        $this->table(
            ['Dominio', 'Tenant ID', 'Clínica'],
            $domains->map(fn($d) => [
                $d->domain,
                $d->tenant_id,
                $d->tenant?->nombre ?? 'N/A'
            ])->toArray()
        );
    }

    private function deleteDomain()
    {
        $domain = $this->option('domain') ?? $this->ask('Domain:');
        
        $domainModel = Domain::where('domain', $domain)->first();
        if (!$domainModel) {
            $this->error("❌ El dominio '$domain' no existe");
            return;
        }

        // Verificar que no sea el único dominio de la clínica
        $clinicaDomains = Domain::where('tenant_id', $domainModel->tenant_id)->count();
        if ($clinicaDomains <= 1) {
            $this->error("❌ No puedes eliminar el único dominio de una clínica");
            return;
        }

        if ($this->confirm("¿Estás seguro de que quieres eliminar '$domain'?")) {
            $domainModel->delete();
            $this->info("✅ Dominio eliminado exitosamente");
        } else {
            $this->info("Operación cancelada");
        }
    }
}
