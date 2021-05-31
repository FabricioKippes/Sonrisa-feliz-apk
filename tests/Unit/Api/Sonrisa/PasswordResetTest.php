<?php

namespace Tests\Unit\Api\Tcm;

use App\Http\Controllers\Api\Resources\BaseApi;
use App\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\Unit\Api\Sonrisa\SonrisaTestBase;

class PasswordResetTest extends SonrisaTestBase
{
    const PASSWORD_RESET_URL = 'api/v1/usuarios/password';

    /**
     * Test Create PasswordReset Success.
     */
    public function testCreatePasswordResetSuccess()
    {
        $structure = $this->structure;
        $user = $structure['user'];

        $body = [
            'email' => $user->email,
        ];

        $response = $this->postJson(
            self::PASSWORD_RESET_URL,
            $body,
            $this->getHeaders()
        );

        $response->assertOk();
    }

    /**
     * Test Create PasswordReset fail.
     */
    public function testPasswordResetFail()
    {
        $body = [
            'email' => "test@email.com",
        ];

        $response = $this->postJson(
            self::PASSWORD_RESET_URL,
            $body,
            $this->getHeaders()
        );

        $response->assertStatus(BaseApi::HTTP_INVALID_REQUEST);

    }

    /**
     * Test put PasswordReset Success.
     */
    public function testPutPasswordResetSuccess()
    {
        $structure = $this->structure;
        $user = $structure['user'];
        $passwordReset = factory(PasswordReset::class)->create(['email' => $user->email]);

        $body = [
            'password' => 'secret',
            'token' => $passwordReset->token
        ];

        $response = $this->putJson(
            self::PASSWORD_RESET_URL,
            $body,
            $this->getHeaders()
        );

        $response->assertOk();

        $this->assertIsBool(Hash::check('secret', $user->password));

        $this->assertDeleted($passwordReset);
    }

    /**
     * Test Put PasswordReset fail.
     */
    public function testPutResetFailTokenInvalid()
    {
        $structure = $this->structure;
        $user = $structure['user'];
        factory(PasswordReset::class)->create(['email' => $user->email]);

        $body = [
            'password' => 'secret',
            'token' => Str::random(60)
        ];

        $response = $this->putJson(
            self::PASSWORD_RESET_URL,
            $body,
            $this->getHeaders()
        );

        $response->assertNotFound();
    }
}
