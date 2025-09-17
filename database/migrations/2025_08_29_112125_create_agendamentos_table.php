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
            $table->integer('medico_id')->unsigned();
            $table->foreign('medico_id')->references('id')->on('medicos');

            // Local de atendimento (opcional)
            $table->integer('local_atendimento_id')->unsigned();
            $table->foreign('local_atendimento_id')->references('id')->on('local_atendimentos');

            // Tipo de consulta (opcional)
            $table->integer('tipo_consulta_id')->unsigned();
            $table->foreign('tipo_consulta_id')->references('id')->on('tipo_consultas');

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
