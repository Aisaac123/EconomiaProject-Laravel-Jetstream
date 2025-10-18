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
        // Evita recrear la tabla si ya existe
        if (Schema::hasTable('credits')) {
            return;
        }

        Schema::create('credits', function (Blueprint $table) {
            $table->id();

            $table->string('debtor_names', 255)
                ->after('user_id')
                ->comment('Nombres del deudor');

            $table->string('debtor_last_names', 255)
                ->after('user_id')
                ->comment('Apellidos del deudor');

            $table->string('debtor_id_number', 50)
                ->after('debtor_name')
                ->comment('Cédula del deudor');

            // Usuario que creó el crédito (opcional)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->comment('Usuario que generó el cálculo o crédito');

            // Tipo de cálculo o crédito (simple, compuesto, etc.)
            $table->enum('type', array_map(fn ($e) => $e->value, \App\Enums\CalculationType::cases()))
                ->index()
                ->comment('Tipo de cálculo financiero');

            // Parámetros de entrada (inputs dinámicos)
            $table->json('inputs')
                ->comment('Datos de entrada variables según el tipo de cálculo');

            // Resultados (JSON flexible, puede incluir resumen o tabla de amortización)
            $table->json('results')
                ->nullable()
                ->comment('Resultados calculados o simulados del crédito');

            // Estado del registro: calculated, pending, paid, etc.
            $table->string('status', 30)
                ->default('calculated')
                ->index()
                ->comment('Estado del cálculo o crédito');

            // Campos opcionales útiles para auditoría o versiones futuras
            $table->string('reference_code', 100)
                ->nullable()
                ->unique()
                ->comment('Código interno o de referencia del crédito');

            $table->timestamp('calculated_at')
                ->nullable()
                ->comment('Fecha en la que se realizó el cálculo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Elimina la tabla si existe
        if (Schema::hasTable('credits')) {
            Schema::drop('credits');
        }
    }
};
