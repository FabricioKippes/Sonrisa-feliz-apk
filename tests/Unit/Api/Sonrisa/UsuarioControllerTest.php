<?php

namespace Tests\Unit\Api\Sonrisa;

use App\Usuario;
use App\Http\Controllers\Api\Resources\BaseApi;
use Faker\Factory;

class UsuarioControllerTest extends SonrisaTestBase
{
    const USUARIOS_URL = 'api/v1/usuarios';

    const PACKAGE_JSON_LOGIN_STRUCTURE = [
      'usuario',
      'token',
    ];

    const PACKAGE_JSON_USUARIO_STRUCTURE = [
        'usuario' => [
            'email',
            'usuario',
            'tipo'
        ]
    ];

    const PACKAGE_JSON_USUARIO_PACIENTE_STRUCTURE = [
        'usuario' => [
            'email',
            'usuario',
            'tipo',
            'paciente' => [
                'dni',
                'nombre',
                'apellido',
                'telefono',
                'fecha_nacimiento',
                'obra_social_id',
                'usuario_id'
            ]
        ]
    ];

    /**
     * Login user.
     */
    public function testLogin()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];

        $body = [
            'email' => $admin->email,
            'password' => 'secret'
        ];

        $response = $this->postJson(
            self::USUARIOS_URL . '/login',
            $body,
            $this->getHeaders()
        );

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_LOGIN_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Obtener lista de usuarios.
     */

    public function testGetUsuarios()
    {
        $structure = $this->structure;
        $admin = $structure['admin'];
        $users = factory(Usuario::class, 5)->create();

        $body = [
            'email' => $admin->email,
            'password' => 'secret'
        ];

        $this->authenticateUser($body);

        $response = $this->withHeaders($this->getAuthHeader())
            ->getJson(self::USUARIOS_URL);

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;

        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Test registro de usuario de tipo paciente
     */
    public function testRegistrarUsuario()
    {
        $structure = $this->structure;
        $paciente = $structure['paciente'];
        $faker = Factory::create();

        $body = [
            'email' => $faker->unique()->email,
            'usuario' => $faker->unique()->lastName(),
            'password' => 'unoDos34!',
            'dni' => $faker->unique()->numberBetween($min = 100000, $max = 999999),
            'nombre' => $paciente->nombre,
            'apellido' => $paciente->apellido,
            'fecha_nacimiento' => $paciente->fecha_nacimiento,
            'telefono' => $paciente->telefono,
            'obra_social_id' => $paciente->obra_social_id,
        ];

        $response = $this->postJson(
            self::USUARIOS_URL,
            $body,
            $this->getHeaders()
        );

        $response->assertCreated();
    }

    /**
     * Test registro de usuario de tipo paciente
     */
    public function testRegistrarUsuarioFailDuplicated()
    {
        $structure = $this->structure;
        $paciente = $structure['paciente'];
        $user = $structure['user'];
        $faker = Factory::create();

        $body = [
            'email' => $user->email,
            'usuario' => $user->usuario,
            'password' => 'unoDos34!',
            'dni' => $faker->unique()->numberBetween($min = 100000, $max = 999999),
            'nombre' => $paciente->nombre,
            'apellido' => $paciente->apellido,
            'fecha_nacimiento' => $paciente->fecha_nacimiento,
            'telefono' => $paciente->telefono,
            'obra_social_id' => $paciente->obra_social_id,
        ];

        $response = $this->postJson(
            self::USUARIOS_URL,
            $body,
            $this->getHeaders()
        );

        $response->assertStatus(BaseApi::HTTP_INVALID_REQUEST);
    }

    /**
     * Test registro de usuario de tipo paciente
     */
    public function testRegistrarUsuarioFailMissingFields()
    {
        $structure = $this->structure;
        $paciente = $structure['paciente'];
        $faker = Factory::create();

        $body = [
            'email' => $faker->unique()->email,
            'password' => 'unoDos34!',
            'fecha_nacimiento' => $paciente->fecha_nacimiento,
            'telefono' => $paciente->telefono,
            'obra_social_id' => $paciente->obra_social_id,
        ];

        $response = $this->postJson(
            self::USUARIOS_URL,
            $body,
            $this->getHeaders()
        );

        $response->assertStatus(BaseApi::HTTP_INVALID_REQUEST);
    }

    /**
     * Test registro de usuario de tipo paciente
     */
    public function testUpdateUsuario()
    {
        $structure = $this->structure;
        $user = $structure['user'];
        $obra_social = $structure['obra_social'];
        $faker = Factory::create();

        $body = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($body);

        $body = [
            'email' => $faker->unique()->email,
            'password' => 'unoDos34!',
            'telefono' => $faker->isbn10,
            'obra_social_id' => $obra_social->id,
        ];

        $response = $this->putJson(
            self::USUARIOS_URL . '/' . $user->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertOk();

        $jsonStructure = self::PACKAGE_JSON_STRUCTURE;
        $jsonStructure['data'] = self::PACKAGE_JSON_USUARIO_PACIENTE_STRUCTURE;
        $response->assertJsonStructure($jsonStructure);
    }

    /**
     * Test registro de usuario de tipo paciente
     */
    public function testUpdateUsuarioFailEmailUsed()
    {
        $structure = $this->structure;
        $paciente = $structure['paciente'];
        $admin = $structure['admin'];

        $body = [
            'email' => $paciente->usuario->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($body);

        $body = [
            'email' => $admin->email,
            'password' => 'unoDos34!',
            'telefono' => $paciente->telefono,
            'obra_social_id' => $paciente->obra_social_id,
        ];

        $response = $this->putJson(
            self::USUARIOS_URL . '/' . $paciente->usuario->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertStatus(BaseApi::HTTP_INVALID_REQUEST);
    }

    /**
     * Test registro de usuario de tipo paciente
     */
    public function testUpdateUsuarioFailInvalidOSid()
    {
        $structure = $this->structure;
        $paciente = $structure['paciente'];
        $usuario = $paciente->usuario;

        $body = [
            'email' => $usuario->email,
            'password' => 'secret',
        ];

        $this->authenticateUser($body);

        $body = [
            'email' => $usuario->email,
            'password' => 'unoDos34!',
            'telefono' => $paciente->telefono,
            'obra_social_id' => PHP_INT_MAX,
        ];

        $response = $this->putJson(
            self::USUARIOS_URL . '/' . $usuario->id,
            $body,
            $this->getAuthHeader()
        );

        $response->assertStatus(BaseApi::HTTP_INVALID_REQUEST);
    }
}
