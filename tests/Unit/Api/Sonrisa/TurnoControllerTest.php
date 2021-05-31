<?php

namespace Tests\Unit\Api\Sonrisa;

use App\Turno;
use App\Http\Controllers\Api\Resources\BaseApi;
use App\Paciente;
use App\Prestacion;
use Carbon\Carbon;
use Faker\Factory;

class TurnoControllerTest extends SonrisaTestBase
{
    const TURNO_URL = 'api/v1/turnos';

    const PACKAGE_JSON_TURNO_STRUCTURE = [
        'turno' => [
            'fecha',
            'horario',
            'paciente_dni',
            'prestacion_id',
            'estado'
        ]
    ];

    const PACKAGE_JSON_TURNOS_STRUCTURE = [
        'turnos' => [
            'ocupados',
            'libres'
        ]
    ];

    const PACKAGE_JSON_TURNOS_DISPONIBLES_STRUCTURE = [
        'turnos'
    ];

    /**
     * Obtener lista de turnos
     */

    public function testGetTurnos()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $turnos = factory(Turno::class, 5)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::TURNO_URL);

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_TURNOS_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Obtener lista de turnos
     */

    public function testGetTurnosDisponibles()
    {
        $structure = $this->structure;
        $usuario = $structure['user'];
        $turnos = factory(Turno::class, 5)->create();

        $authenticateBody = [
            'email' => $usuario->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::TURNO_URL . '/disponibles');

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_TURNOS_DISPONIBLES_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }


    /**
     * Crear un Turno
     */
    public function testCreateTurno()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $faker = Factory::create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $date = $faker->unique()->dateTimeThisMonth('2020-11-20 12:00:00');

        $body = [
            'fecha' => Carbon::parse($date)->format('Y-m-d'),
            'horario' => Carbon::parse($date)->format('H:i'),
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->postJson(
            self::TURNO_URL,
            $body,
            $this->getAuthHeader()
        );

        $response->assertCreated();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_TURNO_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Test Delete Turno.
     */
    public function testDeleteTurno()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $turno = factory(Turno::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);
        $response = $this->deleteJson(
            self::TURNO_URL . '/' . $turno->id,
            [],
            $this->getAuthHeader()
        );


        $response->assertOk();
    }

    /**
     * Editar una Turno
     */

    public function testUpdateTurnoAdmin()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $turno = Factory(Turno::class)->create();
        $paciente = Factory(Paciente::class)->create();
        $prestacion = Factory(Prestacion::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'fecha' => $turno->fecha,
            'horario' => $turno->horario,
            'paciente_dni' => $paciente->dni,
            'prestacion_id' => $prestacion->id
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->postJson(
            self::TURNO_URL,
            $body,
            $this->getAuthHeader()
        );

        $response->assertCreated();
        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_TURNO_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);

        $package = json_decode($response->getContent());
        $updated = $package->data->turno->id;

        $this->assertTrue($updated == $turno->id);
    }


    /**
     * Editar una Turno
     */

    public function testUpdateTurnoPaciente()
    {
        $structure = $this->structure;
        $user = $structure['user'];
        $faker = Factory::create();
        $turno = Factory(Turno::class)->create();

        $authenticateBody = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $body = [
            'descripcion' => $faker->text(),
            'images' => [
                UtilitiesForTest::getFileTest(),
                UtilitiesForTest::getFileTest(),
                UtilitiesForTest::getFileTest(),
            ]
        ];

        $response = $this->putJson(
            self::TURNO_URL . '/' . $turno->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_TURNO_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);

    }

    /**
     * Editar turno asignado fail
     */

    public function testUpdateTurnoPacienteFail()
    {
        $structure = $this->structure;
        $user = $structure['user'];
        $paciente = Factory(Paciente::class)->create();
        $faker = Factory::create();
        $turno = Factory(Turno::class)->create(['paciente_dni' => $paciente->dni]);

        $authenticateBody = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $body = [
            'descripcion' => $faker->text(),
            'images' => [
                UtilitiesForTest::getFileTest(),
                UtilitiesForTest::getFileTest(),
                UtilitiesForTest::getFileTest(),
            ]
        ];

        $response = $this->putJson(
            self::TURNO_URL . '/' . $turno->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertStatus(BaseApi::HTTP_CONFLICT);
    }

    /**
     * Editar una Turno
     */

    public function testLiberarTurnoPaciente()
    {
        $structure = $this->structure;
        $user = $structure['user'];

        $turno = Factory(Turno::class)->create(
            [ 'paciente_dni' => $user->paciente->dni ]
        );

        $authenticateBody = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->putJson(
            self::TURNO_URL . '/' . $turno->id . '/liberar',
            [],
            $this->getAuthHeader()
        );

        $response->assertOk();
    }

    /**
     * Editar una Turno fail - usuario no paciente
     */

    public function testAssignTurnoNoPacienteFail()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $faker = Factory::create();
        $turno = Factory(Turno::class)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $body = [
            'descripcion' => $faker->text(),
            'images' => [
                UtilitiesForTest::getFileTest(),
                UtilitiesForTest::getFileTest(),
                UtilitiesForTest::getFileTest(),
            ]
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->putJson(
            self::TURNO_URL . '/' . $turno->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertStatus(BaseApi::HTTP_INVALID_REQUEST);
    }


    /**
     * Test Delete turno fail.
     */
    public function testDeleteTurnoFail()
    {
        $structure = $this->structure;
        $user = $structure['user'];
        $turno = factory(Turno::class)->create();

        $authenticateBody = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);
        $response = $this->deleteJson(
            self::TURNO_URL . '/' . $turno->id,
            [],
            $this->getAuthHeader()
        );

        $response->assertUnauthorized();
    }
}
