<?php

use App\Usuario;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Usuario::class)->create([
            'usuario' => 'admin',
            'email' => 'admin@sonrisa.com',
            'tipo' => 'admin',
            'password' => 'unoDos34!'
        ]);
    }
}

