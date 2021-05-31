<?php

namespace Tests\Unit\Api\Sonrisa;

use App\Prestacion;
use App\Http\Controllers\Api\Resources\BaseApi;
use Faker\Factory;

class PrestacionControllerTest extends SonrisaTestBase
{
    const PRESTACION_URL = 'api/v1/prestaciones';

    const PACKAGE_JSON_PRESTACION_STRUCTURE = [
        'prestacion' => [
            'nombre',
            'id',
            'descripcion'
        ]
    ];

    /**
     * Obtener lista de prestaciones
     */

    public function testGetPrestaciones()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $prestaciones = factory(Prestacion::class, 5)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::PRESTACION_URL);

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Crear una prestación
     */

    public function testCreatePrestacion()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $faker = Factory::create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'nombre' => $faker->unique()->sentence(3),
            'descripcion' => $faker->text(),
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->postJson(
            self::PRESTACION_URL,
            $body,
            $this->getAuthHeader()
        );

        $response->assertCreated();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_PRESTACION_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Test Delete Prestación.
     */
    public function testDeletePrestacion()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $prestacion = factory(Prestacion::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);
        $response = $this->deleteJson(
            self::PRESTACION_URL . '/' . $prestacion->id,
            [],
            $this->getAuthHeader()
        );


        $response->assertOk();
    }

    /**
     * Editar una prestación
     */

    public function testUpdatePrestacion()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $faker = Factory::create();
        $prestacion = Factory(Prestacion::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'nombre' => "Test Edit Prestacion",
            'descripcion' => $faker->text(),
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->putJson(
            self::PRESTACION_URL . '/' . $prestacion->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertOk();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_PRESTACION_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);

        $package = json_decode($response->getContent());
        $nuevo_nombre = $package->data->prestacion->nombre;

        $this->assertTrue($nuevo_nombre == $body['nombre']);
    }

    /**
     * Edit Prestacion fail.
     * */
    public function testEditPrestacionFail()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $prestacion = factory(Prestacion::class)->create();
        $prestacion2 = factory(Prestacion::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'nombre' => $prestacion2->nombre,
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->putJson(
            self::PRESTACION_URL . '/' . $prestacion->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertStatus(BaseApi::HTTP_INVALID_REQUEST);
    }
}
