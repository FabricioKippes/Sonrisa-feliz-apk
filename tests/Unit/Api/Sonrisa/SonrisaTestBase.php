<?php

namespace Tests\Unit\Api\Sonrisa;

use App\ObraSocial;
use App\Paciente;
use App\Usuario;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SonrisaTestBase extends TestCase
{
    use DatabaseTransactions;

    /**
     * Basic Json Structure for ResponsePackage. Extend 'data' with your expected response structure.
     */
    const PACKAGE_JSON_STRUCTURE = [
        'message',
        'error',
        'status',
        'data',
        'headers',
    ];

    const PACKAGE_JSON_MODEL_NOT_FOUND_STRUCTURE = [
        'errors' => [
            'model',
        ],
    ];

    public $structure;

    /**
     * Use it to store data between tests.
     */
    protected $store = [];

    /**
     * Setup structure
     */
    public function setUp(): void
    {
        parent::setUp();


        $user = factory(Usuario::class)->create(['usuario' => 'usuario1110']);
        $paciente = factory(Paciente::class)->create(['usuario_id' => $user->id]);
        $admin = factory(Usuario::class)->create(['usuario' => 'usuario11101', 'tipo' => 'admin']);
        $obra_social = factory(ObraSocial::class)->create();


        $this->structure = [
            'user' => $user,
            'admin' => $admin,
            'paciente' => $paciente,
            'obra_social' => $obra_social
        ];
    }

    /**
     * Gets the standard Headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        return $headers;
    }

    /**
     * Gets the current Headers for make JWT auth request.
     *
     * @return array
     */
    public function getAuthHeader()
    {
        $headers = $this->getHeaders();
        $headers['Authorization'] = 'Bearer ' . $this->store['auth']['token'];
        return $headers;
    }

    /**
     * Authenticates the a User by email.
     *
     * @param string $userEmail
     * @param string $userPassword
     *
     * @return void
     */
    public function authenticateUser($credentials)
    {
        $response = $this->postJson(
            UsuarioControllerTest::USUARIOS_URL . '/login',
            $credentials,
            $this->getHeaders()
        );

        $package = json_decode($response->getContent());

        $this->store['auth']['token'] = isset($package->data->token) ? $package->data->token : '';

    }

    /**
     * Fail Auth Header.
     */
    public function getAuthHeaderFail()
    {
        $headers = $this->getHeaders();
        $headers['Authorization'] = 'Bearer ' . ' ';
        return $headers;
    }

}
