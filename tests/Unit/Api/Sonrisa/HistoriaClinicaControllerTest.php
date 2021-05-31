<?php

namespace Tests\Unit\Api\Sonrisa;

use App\HistoriaClinica;
use App\Prestacion;
use App\Http\Controllers\Api\Resources\BaseApi;
use App\Paciente;
use Faker\Factory;

class HistoriaClinicaControllerTest extends SonrisaTestBase
{
    const HISTORIA_CLINICA_URL = 'api/v1/historias-clinicas';

    const PACKAGE_JSON_HISTORIA_CLINICA_STRUCTURE = [
        'historia_clinica' => [
            'observaciones',
            'prestacion_id',
            'paciente_dni',
            'prestacion'
        ]
    ];

    /**
     * Obtener lista de historias clinicas
     */

    public function testGetList()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        factory(HistoriaClinica::class, 5)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::HISTORIA_CLINICA_URL);

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }

    public function testCreate()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $paciente = $structure['paciente'];
        $faker = Factory::create();
        $prestacion = factory(Prestacion::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'observaciones' => $faker->text(),
            'paciente_dni' => $paciente->dni,
            'prestacion_id' => $prestacion->id
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->postJson(
            self::HISTORIA_CLINICA_URL,
            $body,
            $this->getAuthHeader()
        );

        $response->assertCreated();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_HISTORIA_CLINICA_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);
    }

    public function testDelete()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $historia = factory(HistoriaClinica::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);
        $response = $this->deleteJson(
            self::HISTORIA_CLINICA_URL . '/' . $historia->id,
            [],
            $this->getAuthHeader()
        );


        $response->assertOk();
    }

    public function testUpdate()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $faker = Factory::create();
        $historia = Factory(HistoriaClinica::class)->create();
        $paciente = Factory(Paciente::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'descripcion' => $faker->text(),
            'paciente_dni' => $paciente->dni
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->putJson(
            self::HISTORIA_CLINICA_URL . '/' . $historia->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertOk();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_HISTORIA_CLINICA_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);
    }
}
