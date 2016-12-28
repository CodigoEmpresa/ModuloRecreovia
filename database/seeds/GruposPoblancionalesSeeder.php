<?php

use Illuminate\Database\Seeder;

class GruposPoblancionalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('GruposPoblacionales')->delete();
		DB::statement('ALTER TABLE GruposPoblacionales AUTO_INCREMENT = 1');

		DB::table('GruposPoblacionales')->insert([
			[
				'Grupo' => 'Preescolar',
				'Edad_Inicio' => 0,
				'Edad_Fin' => 5,
			],[
				'Grupo' => 'Primaria',
				'Edad_Inicio' => 6,
				'Edad_Fin' => 12,
			],[
				'Grupo' => 'Bachillerato',
				'Edad_Inicio' => 13,
				'Edad_Fin' => 17,
			],[
				'Grupo' => 'Adultos Jóvenes',
				'Edad_Inicio' => 18,
				'Edad_Fin' => 26,
			],[
				'Grupo' => 'Adultos',
				'Edad_Inicio' => 27,
				'Edad_Fin' => 59,
			],[
				'Grupo' => 'Personas Mayores',
				'Edad_Inicio' => 60,
				'Edad_Fin' => -1,
			],
		]);
    }
}

