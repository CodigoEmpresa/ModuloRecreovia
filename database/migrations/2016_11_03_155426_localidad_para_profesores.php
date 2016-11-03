<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LocalidadParaProfesores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ZonasPersonasRecreovia', function($table){
            $table->integer('Id_Localidad')->unsigned()->after('Id_Persona');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('ZonasPersonasRecreovia', function($table){
            $table->dropColumn(['Id_Localidad']);
        });
    }
}
