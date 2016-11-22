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
        Schema::create('LocalidadesPersonas', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Recreopersona')->unsigned();
            $table->integer('Id_Localidad')->unsigned();
            $table->enum('tipo', ['Profesor', 'Gestor']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Recreopersona')->references('Id_Recreopersona')->on('Recreopersonas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('LocalidadesPersonas', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Recreopersona']);
        });

        Schema::drop('LocalidadesPersonas');
    }
}
