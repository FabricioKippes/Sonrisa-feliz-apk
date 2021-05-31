<?php

use App\ObraSocial;
use Illuminate\Database\Seeder;

class ObraSocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ObraSocial::class)->create([
            'nombre' => 'OSDE'
        ]);

        factory(ObraSocial::class)->create([
            'nombre' => 'Swiss Medical'
        ]);

        factory(ObraSocial::class)->create([
            'nombre' => 'InSSSeP'
        ]);

        factory(ObraSocial::class)->create([
            'nombre' => 'Sancor Salud'
        ]);

        factory(ObraSocial::class)->create([
            'nombre' => 'Unión Personal'
        ]);

        factory(ObraSocial::class)->create([
            'nombre' => 'OSECAC'
        ]);
    }
}
