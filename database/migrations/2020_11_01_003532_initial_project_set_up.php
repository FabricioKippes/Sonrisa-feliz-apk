<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialProjectSetUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id')
                ->nullable(false);
            $table->string('usuario')
                ->unique()
                ->nullable(false);
            $table->string('email')
                ->unique()
                ->nullable(false);
            $table->string('password')
                ->nullable(false);
            $table->enum('tipo', ['admin', 'paciente'])
                ->nullable(false);
            $table->boolean('status')
                ->default(true);
            $table->timestamps();
        });

        Schema::create('obras_sociales', function (Blueprint $table) {
            $table->increments('id')
                ->nullable(false);
            $table->string('nombre')
                ->unique()
                ->nullable(false);
            $table->string('telefono')
                ->nullable(false);
            $table->timestamps();
        });

        Schema::create('prestaciones', function (Blueprint $table) {
            $table->increments('id')
                ->nullable(false);
            $table->string('nombre')
                ->nullable(false);
            $table->longText('descripcion')
                ->nullable(false);
            $table->timestamps();
        });

        Schema::create('pacientes', function (Blueprint $table) {
            $table->unsignedInteger('dni')
                ->index()
                ->nullable(false);
            $table->string('nombre')
                ->nullable(false);
            $table->string('apellido')
                ->nullable(false);
            $table->date('fecha_nacimiento')
                ->nullable(false);
            $table->string('telefono')
                ->nullable(true);

            $table->unsignedInteger('obra_social_id')
                ->nullable();
            $table->foreign('obra_social_id')
                ->references('id')
                ->on('obras_sociales')
                ->onDelete('set null');

            $table->unsignedInteger('usuario_id')
                ->nullable(false);

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuarios')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('turnos', function (Blueprint $table) {
            $table->increments('id')
                ->nullable(false);
            $table->date('fecha')
                ->nullable(false);
            $table->time('horario')
                ->nullable(false);

            $table->unsignedInteger('paciente_dni')
                ->nullable();
            $table->foreign('paciente_dni')
                ->references('dni')
                ->on('pacientes')
                ->onDelete('set null');

            $table->unsignedInteger('prestacion_id')
                ->nullable();
            $table->foreign('prestacion_id')
                ->references('id')
                ->on('prestaciones')
                ->onDelete('set null');

            $table->index(['fecha', 'horario']);
            $table->timestamps();
        });


        Schema::create('historias_clinicas', function (Blueprint $table) {
            $table->increments('id')
                ->nullable(false);

            $table->longText('observaciones')
                ->nullable(false);

            $table->unsignedInteger('prestacion_id')
                ->nullable(false);
            $table->foreign('prestacion_id')
                ->references('id')
                ->on('prestaciones')
                ->onDelete('cascade');

            $table->unsignedInteger('paciente_dni')
                ->nullable(false);
            $table->foreign('paciente_dni')
                ->references('dni')
                ->on('pacientes')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')
                ->unique();
            $table->string('token');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historias_clinicas');
        Schema::dropIfExists('turnos');
        Schema::dropIfExists('pacientes');
        Schema::dropIfExists('prestaciones');
        Schema::dropIfExists('obras_sociales');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('usuarios');
    }
}
