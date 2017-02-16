<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NuevosCamposTablaJornadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Jornadas', function(Blueprint $table)
        {
            $table->string('Contacto_Nombre', 100)->after('Fecha_Evento_Fin')->nullable();
            $table->string('Contacto_Telefono', 20)->after('Contacto_Nombre')->nullable();
            $table->string('Contacto_Correo', 100)->after('Contacto_Telefono')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Jornadas', function(Blueprint $table)
        {
            $table->dropColumn('Contacto_Correo');
            $table->dropColumn('Contacto_Telefono');
            $table->dropColumn('Contacto_Nombre');
        });
    }
}
