<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaZonasPersonal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ZonasPersonasRecreovia', function($table){
            $table->integer('Id_Zona')->unsigned();
            $table->integer('Id_Persona')->unsigned();
            $table->enum('tipo', ['gestor', 'profesor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ZonasPersonasRecreovia');
    }
}
