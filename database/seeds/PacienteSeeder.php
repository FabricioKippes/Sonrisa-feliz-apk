<?php

use Illuminate\Database\Seeder;
use App\Usuario;
use App\Paciente;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(Usuario::class)->create([
            'usuario' => 'paciente',
            'email' => 'paciente@sonrisa.com',
            'password' => 'unoDos34!',
        ]);

        factory(Paciente::class)->create([
            'nombre' => 'Paciente',
            'apellido' => 'Seeded',
            'usuario_id' => $user->id
        ]);
    }
}
