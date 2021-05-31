<?php

namespace Tests\Unit\Api\Sonrisa;

use App\ObraSocial;
use App\Paciente;
use App\Http\Controllers\Api\Resources\BaseApi;
use Faker\Factory;

class ObraSocialControllerTest extends SonrisaTestBase
{
    const OBRA_SOCIAL_URL = 'api/v1/obras-sociales';

    const PACKAGE_JSON_OBRA_SOCIAL_STRUCTURE = [
        'obra_social' => [
            'nombre',
            'id',
            'telefono'
        ]
    ];

    /**
     * Obtener lista de obras sociales
     */

    public function testGetObrasSociales()
    {
        $obras_sociales = factory(ObraSocial::class, 5)->create();

        $response = $this->withHeaders($this->getHeaders())
            ->getJson(self::OBRA_SOCIAL_URL);

        $response->assertOk();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Crear una obra social
     */

    public function testCreateObraSocial()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $faker = Factory::create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'nombre' => $faker->unique()->company,
            'telefono' => $faker->isbn10
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->postJson(
            self::OBRA_SOCIAL_URL,
            $body,
            $this->getAuthHeader()
        );

        $response->assertCreated();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_OBRA_SOCIAL_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Test Delete Obra Social.
     */
    public function testDeleteObraSocial()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $obra_social = factory(ObraSocial::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);
        $response = $this->deleteJson(
            self::OBRA_SOCIAL_URL . '/' . $obra_social->id,
            [],
            $this->getAuthHeader()
        );


        $response->assertOk();
    }

    /**
     * Test Delete Obra Social asignada a un paciente.
     */
    public function testDeleteObraSocialAssignedToPaciente()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $paciente = factory(Paciente::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);
        $response = $this->deleteJson(
            self::OBRA_SOCIAL_URL . '/' . $paciente->obra_social_id,
            [],
            $this->getAuthHeader()
        );

        $response->assertOk();
    }

    /**
     * Editar una obra social
     */

    public function testUpdateObraSocial()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $obra_social = Factory(ObraSocial::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'nombre' => "Test Edit Obra",
            'telefono' => $obra_social->telefono
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->putJson(
            self::OBRA_SOCIAL_URL . '/' . $obra_social->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertOk();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_OBRA_SOCIAL_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);

        $package = json_decode($response->getContent());
        $nuevo_nombre = $package->data->obra_social->nombre;

        $this->assertTrue($nuevo_nombre == $body['nombre']);
    }

    /**
     * Edit Obra Social fail.
     * */
    public function testEditObraSocialFail()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $obra_social = factory(ObraSocial::class)->create();
        $obra_social2 = factory(ObraSocial::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'nombre' => $obra_social2->nombre,
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->putJson(
            self::OBRA_SOCIAL_URL . '/' . $obra_social->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertStatus(BaseApi::HTTP_INVALID_REQUEST);
    }
}
