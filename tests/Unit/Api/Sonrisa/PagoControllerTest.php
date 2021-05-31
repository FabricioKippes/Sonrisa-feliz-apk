<?php

namespace Tests\Unit\Api\Sonrisa;

use App\Pago;
use App\Prestacion;
use App\Turno;
use Carbon\Carbon;
use Faker\Factory;

class PagoControllerTest extends SonrisaTestBase
{
    const PAGO_URL = 'api/v1/pagos';

    const PACKAGE_JSON_PAGO_STRUCTURE = [
        'pago' => [
            'turno_id',
            'paciente_dni',
            'dni_titular',
            'nombre_titular',
            'nro_factura',
            'fecha',
            'concepto'
        ]
    ];

    /**
     * Obtener lista de historias clinicas
     */

    public function testGetList()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        factory(Pago::class, 5)->create();

        $authenticateBody = [
            'email' => $admin->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::PAGO_URL);

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }

    public function testCreate()
    {
        $structure = $this->structure;
        $user = $structure['user'];
        $dni = $user->paciente->dni;

        $turno = factory(Turno::class)->create([
            'paciente_dni' => $dni,
            'precio' => 500,
            'prestacion_id' => function () {
                return factory(Prestacion::class)->create()->id;
            },
        ]);

        $faker = Factory::create();

        $authenticateBody = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $body = [
            'paciente_dni' => $dni,
            'turno_id' => $turno->id,
            'dni_titular' => 99999999,
            'nombre_titular' => $faker->name,
            'nro_tarjeta' => '1111222233334444',
            'cod_seguridad' => '111',
            'fecha_expiracion' => Carbon::tomorrow()
        ];

        $this->authenticateUser($authenticateBody);

        $response = $this->postJson(
            self::PAGO_URL,
            $body,
            $this->getAuthHeader()
        );

        $response->assertOk();
    }
}
