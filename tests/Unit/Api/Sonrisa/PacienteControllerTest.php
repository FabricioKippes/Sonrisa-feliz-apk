<?php

namespace Tests\Unit\Api\Sonrisa;

use App\HistoriaClinica;
use App\Paciente;
use App\Turno;

class PacienteControllerTest extends SonrisaTestBase
{
    const PACIENTE_URL = 'api/v1/pacientes';

    const PACKAGE_JSON_PACIENTE_STRUCTURE = [
        'paciente' => [
            'dni',
            'nombre',
            'apellido',
            'telefono',
            'fecha_nacimiento',
            'obra_social_id',
            'usuario_id'
        ]
    ];


    public function testList()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $pacientes = factory(Paciente::class, 5)->create();

        $body = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($body);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::PACIENTE_URL);

        $response->assertOk();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);
    }

    public function testGetTurnos()
    {
        $structure = $this->structure;
        $user = $structure['user'];
        $paciente = $structure['paciente'];
        $turnos = factory(Turno::class, 5)->create(['paciente_dni' => $paciente->dni]);

        $authenticateBody = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::PACIENTE_URL . '/turnos');

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }

    public function testGetHistorias()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $paciente = $structure['paciente'];
        factory(HistoriaClinica::class, 5)->create(['paciente_dni' => $paciente->dni]);

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::PACIENTE_URL . '/' . $paciente->dni . '/historias');

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }
}
