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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Data e hora do agendamento
            $table->date('data');
            $table->time('hora');

            // Relacionamento com médicos
            $table->foreignId('medico_id')->constrained('medicos')->onDelete('cascade');

            // Local de atendimento (opcional)
            $table->foreignId('local_atendimento_id')->nullable()->constrained('local_atendimentos')->onDelete('set null');

            // Tipo de consulta (opcional)
            $table->foreignId('tipo_consulta_id')->nullable()->constrained('tipo_consultas')->onDelete('set null');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
