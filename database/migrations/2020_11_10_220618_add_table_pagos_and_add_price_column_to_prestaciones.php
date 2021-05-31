<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTablePagosAndAddPriceColumnToPrestaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pagos')) {
            Schema::create('pagos', function (Blueprint $table) {
                $table->increments('id')
                    ->nullable(false);

                $table->decimal('monto', 10, 2)
                    ->nullable(false);
                $table->unsignedInteger('dni_titular')
                    ->nullable(false);
                $table->string('nombre_titular')
                    ->nullable(false);
                $table->string('concepto')
                    ->nullable(false);

                $table->unsignedInteger('paciente_dni')
                    ->nullable();
                $table->foreign('paciente_dni')
                    ->references('dni')
                    ->on('pacientes')
                    ->onDelete('set null');

                $table->unsignedInteger('turno_id')
                    ->nullable();
                $table->foreign('turno_id')
                    ->references('id')
                    ->on('turnos')
                    ->onDelete('set null');

                $table->timestamps();
            });
        }

        if (Schema::hasTable('turnos') && !Schema::hasColumn('turnos', 'precio')) {
            Schema::table('turnos', function (Blueprint $table) {
                $table->decimal('precio', 10, 2)
                    ->nullable()
                    ->after('prestacion_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');

        if (Schema::hasTable('turnos') && Schema::hasColumn('turnos', 'precio')) {
            Schema::table('turnos', function (Blueprint $table) {
                $table->dropColumn('precio');
            });
        }
    }
}
