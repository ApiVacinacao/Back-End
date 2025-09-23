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
            $table->boolean('status')->default(true);

            ##PARA Mysql
            //relacionamento de tabelas
            $table->foreignId('medico_id');
            $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('cascade');

            $table->foreignId('local_atendimento_id');
            $table->foreign('local_atendimento_id')->references('id')->on('local_atendimentos')->onDelete('cascade');

            $table->foreignId('tipo_consulta_id');
            $table->foreign('tipo_consulta_id')->references('id')->on('tipo_consultas')->onDelete('cascade');


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
