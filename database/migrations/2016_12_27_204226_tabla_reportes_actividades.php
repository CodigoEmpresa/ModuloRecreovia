<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaReportesActividades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Reportes', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Punto')->unsigned();
            $table->integer('Id_Cronograma')->unsigned();
            $table->date('Dia');
            $table->enum('Condiciones_Climaticas', ['soleado', 'opaco', 'frio', 'lluvia']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Punto')->references('Id_Punto')->on('Puntos')->onDelete('cascade');
            $table->foreign('Id_Cronograma')->references('Id')->on('Cronogramas')->onDelete('cascade');
        });

        Schema::create('ReportesProfesores', function(Blueprint $table)
        {   
            $table->increments('Id');
            $table->integer('Id_Reporte')->unsigned();
            $table->integer('Id_Profesor')->unsigned();
            $table->time('Hora_Llegada');
            $table->time('Hora_Salida');
            $table->integer('Sesiones_Realizadas');
            $table->boolean('Planificacion');
            $table->boolean('Sistema_De_Datos');
            $table->text('Novedades');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Reporte')->references('Id')->on('Reportes')->onDelete('cascade');
            $table->foreign('Id_Profesor')->references('Id_Recreopersona')->on('Recreopersonas')->onDelete('cascade');
        });

        Schema::create('ReportesNovedades', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Reporte')->unsigned();
            $table->time('Cod_514_523');
            $table->time('Cod_514_541');
            $table->time('Cod_514_542');
            $table->text('Novedades');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Reporte')->references('Id')->on('Reportes')->onDelete('cascade');
        });

        Schema::create('ReportesServicios', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Reporte')->unsigned();
            $table->time('Cod_514_523');
            $table->time('Cod_514_541');
            $table->time('Cod_514_542');
            $table->enum('tipo', ['Sonido', 'Tarima']);
            $table->string('Empresa');
            $table->string('Placa_Camion');
            $table->text('Operarios');
            $table->text('Observaciones_Generales');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Reporte')->references('Id')->on('Reportes')->onDelete('cascade');
        });

        Schema::create('GruposPoblacionales', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->string('Grupo');
            $table->integer('Edad_Inicio');
            $table->integer('Edad_Fin');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Participaciones', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Sesion')->unsigned();
            $table->integer('Id_Grupo')->unsigned();
            $table->enum('Genero', ['M', 'F']);
            $table->enum('Grupo_Asistencia', ['Participantes', 'Asistentes']);
            $table->integer('Cantidad');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Sesion')->references('Id')->on('Sesiones')->onDelete('cascade');
            $table->foreign('Id_Grupo')->references('Id')->on('GruposPoblacionales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Participaciones', function(Blueprint $table) 
        {
            $table->dropForeign(['Id_Grupo']);
            $table->dropForeign(['Id_Sesion']);
        });

        Schema::table('ReportesServicios', function(Blueprint $table) 
        {
            $table->dropForeign(['Id_Reporte']);
        });

        Schema::table('ReportesNovedades', function(Blueprint $table) 
        {
            $table->dropForeign(['Id_Reporte']);
        });

        Schema::table('ReportesProfesores', function(Blueprint $table) 
        {
            $table->dropForeign(['Id_Profesor']);
            $table->dropForeign(['Id_Reporte']);
        });

        Schema::table('Reportes', function(Blueprint $table) 
        {
            $table->dropForeign(['Id_Cronograma']);
            $table->dropForeign(['Id_Punto']);
        });
        
        Schema::drop('Participaciones');
        Schema::drop('GruposPoblacionales');
        Schema::drop('ReportesServicios');
        Schema::drop('ReportesNovedades');
        Schema::drop('ReportesProfesores');
        Schema::drop('Reportes');
    }
}
