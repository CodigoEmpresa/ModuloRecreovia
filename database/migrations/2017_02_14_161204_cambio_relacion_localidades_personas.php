<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambioRelacionLocalidadesPersonas extends Migration
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

        $puntos_asignados = DB::select('SELECT PuntosPersonas.*, Puntos.Id_Localidad FROM PuntosPersonas, Puntos WHERE PuntosPersonas.`Id_Punto` = Puntos.`Id_Punto`');
        
        if ($puntos_asignados)
        {
            foreach ($puntos_asignados as $punto) 
            {
                DB::table('LocalidadesPersonas')->insert([
                    'Id_Recreopersona' => $punto->Id_Recreopersona,
                    'Id_Localidad' => $punto->Id_Localidad,
                    'tipo' => $punto->tipo
                ]);
            }
        }

        Schema::table('PuntosPersonas', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Recreopersona']);
            $table->dropForeign(['Id_Punto']);
        });

        Schema::drop('PuntosPersonas');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('PuntosPersonas', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Recreopersona')->unsigned();
            $table->integer('Id_Punto')->unsigned();
            $table->enum('tipo', ['Profesor', 'Gestor']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Punto')->references('Id_Punto')->on('Puntos')->onDelete('cascade');
            $table->foreign('Id_Recreopersona')->references('Id_Recreopersona')->on('Recreopersonas')->onDelete('cascade');
        });
        
        Schema::table('LocalidadesPersonas', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Recreopersona']);
        });

        Schema::drop('LocalidadesPersonas');
    }
}
