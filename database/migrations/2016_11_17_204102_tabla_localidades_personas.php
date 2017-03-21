<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaLocalidadesPersonas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PuntosPersonas', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Recreopersona')->unsigned();
            $table->integer('Id_Punto')->unsigned();
            $table->enum('tipo', ['Profesor', 'Gestor']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Punto')->references('Id_Punto')->on('Puntos');
            $table->foreign('Id_Recreopersona')->references('Id_Recreopersona')->on('Recreopersonas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('PuntosPersonas', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Recreopersona']);
            $table->dropForeign(['Id_Punto']);
        });

        Schema::drop('PuntosPersonas');
    }
}
