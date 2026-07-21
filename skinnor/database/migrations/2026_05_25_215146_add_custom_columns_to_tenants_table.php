<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'nombre')) {
                $table->string('nombre')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'plan')) {
                $table->string('plan')->default('free');
            }
            if (!Schema::hasColumn('tenants', 'activa')) {
                $table->boolean('activa')->default(true);
            }
            if (!Schema::hasColumn('tenants', 'pais')) {
                $table->string('pais')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'ciudad')) {
                $table->string('ciudad')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'plan', 'activa', 'pais', 'ciudad']);
        });
    }
};
