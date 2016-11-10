<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaRecreopersonas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Recreopersonas', function(Blueprint $table)
        {
            $table->increments('Id_Recreopersona');
            $table->integer('Id_Persona')->unsigned();
            $table->enum('tipo', ['Gestor', 'Profesor']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Recreopersonas');
    }
}
