<?php

use Illuminate\Database\Seeder;

class ZonasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Zonas')->delete();
        DB::statement('ALTER TABLE Zonas AUTO_INCREMENT = 1');

        DB::table('Zonas')->insert([
			[
				'Nombre' => 'Zona principal'
			]
		]);
    }
}
