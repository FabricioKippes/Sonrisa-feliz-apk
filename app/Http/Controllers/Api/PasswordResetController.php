<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Resources\BaseApi;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use App\Http\Requests\PasswordResetCreate;
use App\Http\Requests\PasswordResetChange;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *   schema="PasswordResetBodyPostSchema",
 *   required={"email"},
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *     example="paciente@sonrisa.com"
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="PasswordResetBodyPutSchema",
 *     required={"password","token"},
 *   @OA\Property(
 *     property="password",
 *     type="string",
 *     example="unoDos34!"
 *   ),
 *   @OA\Property(
 *     property="token",
 *     type="string",
 *   ),
 * )
 */
class PasswordResetController extends BaseApi
{
    /**
     * Generate a token and then send it to the user's email.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/usuarios/password",
     *   tags={
     *     "PasswordReset",
     *   },
     *   summary="Generar un token para restablecer contraseña y enviarlo por mail",
     *   description="***Importante:*** La dirección de correo debe estar registrada",
     *   operationId="CreatePasswordReset",
     *   @OA\RequestBody(
     *     description="Campos para solicitar un password reset",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/PasswordResetBodyPostSchema"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send reset link Successful.",
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Validation error.",
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not found user for email provided.",
     *   )
     * )
     *
     * @param  PasswordResetCreate $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(PasswordResetCreate $request)
    {
        $package = new ResponsePackage();

        $user = Usuario::where('email', $request->email)->first();

        $token = '';

        while (true) {
            $token = Str::random(60);
            if (!PasswordReset::where('token', $token)->first()) {
                break;
            }
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['token' => $token]
        );

        if ($user && $passwordReset) {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        }

        return $package->setStatus(BaseApi::HTTP_OK)
            ->toResponse();
    }

    /**
     * Reset password user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *   path="/usuarios/password",
     *   tags={
     *     "PasswordReset",
     *   },
     *   summary="Restablecer password de usuario",
     *   description="***Note:*** The token must be valid.",
     *   operationId="ResetPassword",
     *   @OA\RequestBody(
     *     description="Campos para restablecer la contraseña",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/PasswordResetBodyPutSchema"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Change password successful."
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Validation error",
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found.",
     *   )
     * )
     *
     * @param  PasswordResetChange $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function put(PasswordResetChange $request)
    {
        $package = new ResponsePackage();

        $passwordReset = PasswordReset::where('token', $request->token)->firstOrFail();

        $user = Usuario::where('email', $passwordReset->email)->firstOrFail();

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(20)->isPast()) {
            return $package
                ->setError(BaseApi::PASSWORD_RESET_TOKEN_EXPIRED, BaseApi::HTTP_INVALID_REQUEST)
                ->setData('errors', BaseApi::PASSWORD_RESET_TOKEN_EXPIRED)
                ->toResponse();
        }

        if ($user) {
            $user->password = $request->password;
            $user->save();
            $passwordReset->delete();
            $user->notify(new PasswordResetSuccess());

            return $package->setData('usuario', $user)
                ->setStatus(BaseApi::HTTP_OK)
                ->toResponse();
        }
        return $package
            ->setError(BaseApi::INVALID_DATA, BaseApi::HTTP_CONFLICT)
            ->setData('errors', BaseApi::PASSWORD_RESET_INVALID_TOKEN)
            ->toResponse();
    }
}
