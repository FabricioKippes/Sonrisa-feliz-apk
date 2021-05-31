<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Resources\BaseApi;
use Illuminate\Http\Request;
use App\Http\Requests\LoginUser;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use App\Http\Requests\RegistarUsuario;
use App\Http\Requests\UpdateUsuarioPaciente;
use App\Paciente;
use Illuminate\Support\Facades\Auth;
use App\Usuario;

/**
 * **
 * @OA\Schema(
 *   schema="GetUsuarioResponse",
 *   @OA\Property(
 *     property="message",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="error",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="integer",
 *     format="int32"
 *   ),
 *   @OA\Property(
 *     property="data",
 *     type="object",
 *     @OA\Property(
 *       property="usuario",
 *       type="object",
 *       description="Un Usuario",
 *       ref="#/components/schemas/Usuario-schema"
 *     ),
 *   ),
 *   @OA\Property(
 *     property="headers",
 *     type="object",
 *   ),
 * )
 *
 */
/**
 * @OA\Schema(
 *   schema="Usuario-schema",
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *   ),
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="boolean",
 *   ),
 *   @OA\Property(
 *     property="tipo",
 *     type="string",
 *   )
 * )
 */
/**
 * @OA\Schema(
 *   schema="Usuario-paciente-schema",
 *   required={"email","password","usuario","dni","nombre","apellido","fecha_nacimiento"},
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *   ),
 *   @OA\Property(
 *     property="usuario",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="password",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="dni",
 *     type="integer",
 *     example="33000111",
 *   ),
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *     example="Sandro",
 *   ),
 *   @OA\Property(
 *     property="apellido",
 *     type="string",
 *     example="Lacunza",
 *   ),
 *   @OA\Property(
 *     property="fecha_nacimiento",
 *     type="string",
 *     format="date",
 *     example="1985-10-31",
 *   ),
 *   @OA\Property(
 *     property="telefono",
 *     type="string",
 *     example="362 4114567",
 *   ),
 *   @OA\Property(
 *     property="obra_social_id",
 *     type="integer",
 *     example=1,
 *   ),
 * )
 */

/**
 * @OA\Schema(
 *   schema="Usuario-paciente-put-schema",
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *   ),
 *   @OA\Property(
 *     property="password",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="telefono",
 *     type="string",
 *     example="362 4114567",
 *   ),
 *   @OA\Property(
 *     property="obra_social_id",
 *     type="integer",
 *     example=1,
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="Login-schema",
 *   required={"email","password"},
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *     example="admin@sonrisa.com",
 *   ),
 *   @OA\Property(
 *     property="password",
 *     type="string",
 *     example="unoDos34!",
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="PackageSuccessUserLogin",
 *   @OA\Property(
 *     property="message",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="error",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="integer",
 *     format="int32"
 *   ),
 *   @OA\Property(
 *     property="data",
 *     type="object",
 *     @OA\Property(
 *       property="token",
 *       type="string",
 *       description="Token para la autentificacion",
 *     ),
 *     @OA\Property(
 *       property="usuario",
 *       type="object",
 *       description="Usuario Logueado",
 *       ref="#/components/schemas/Usuario-schema"
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Schema(
 *   schema="RefreshResponse",
 *   @OA\Property(
 *     property="message",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="error",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="integer",
 *     format="int32"
 *   ),
 *   @OA\Property(
 *     property="data",
 *     type="object",
 *     @OA\Property(
 *       property="token",
 *       type="string",
 *       description="Token para la autentificacion",
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Schema(
 *   schema="RegistroResponse",
 *   @OA\Property(
 *     property="message",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="error",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="integer",
 *     format="int32"
 *   ),
 *   @OA\Property(
 *     property="data",
 *     type="object",
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="PackageSuccessUpdate",
 *   @OA\Property(
 *     property="message",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="error",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="integer",
 *     format="int32"
 *   ),
 *   @OA\Property(
 *     property="data",
 *     type="object",
 *     @OA\Property(
 *       property="usuario",
 *       type="object",
 *       description="Usuario",
 *       ref="#/components/schemas/Usuario-paciente-response",
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Schema(
 *   schema="Usuario-paciente-response",
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *   ),
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="boolean",
 *   ),
 *   @OA\Property(
 *     property="tipo",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="paciente",
 *     type="object",
 *     ref="#/components/schemas/PacienteSchema",
 *   ),
 * )
 */


class UsuarioController extends BaseApi
{
    /**
     * Devuelve una lista de usuarios.
     *
     * @OA\Get(
     *   path="/usuarios",
     *   tags={
     *     "Usuario",
     *   },
     *   summary="Lista de usuarios registrados",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   operationId="getList",
     *   security={{"Bearer":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="User.",
     *     @OA\JsonContent(ref="#/components/schemas/GetUsuarioResponse"),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden.",
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found.",
     *   ),
     *   @OA\Response(
     *     response=409,
     *     description="Refresh Errors.",
     *   ),
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $package = new ResponsePackage();


        $usuarios = Usuario::all();

        return $package->setData('usuarios', $usuarios)
            ->toResponse();
    }


    /**
     * User authentication + Login associated user.
     * This will return a JWT token and url to be embedded into users site.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/usuarios/login",
     *   tags={
     *     "Usuario",
     *   },
     *   summary="Logear un usuario.",
     *   operationId="loginUsuario",
     *   @OA\RequestBody(
     *     description="Datos para loguear un usuario.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Login-schema"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Authentication Successful.",
     *     @OA\JsonContent(ref="#/components/schemas/PackageSuccessUserLogin"),
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Validation Errors.",
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found.",
     *   ),
     * )
     *
     * @param  LoginUser $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUser $request)
    {
        $package = new ResponsePackage();

        $user = Auth::attempt($request->all());

        if (empty($user)) {
            return $package->setError(
                BaseApi::INVALID_EMAIL_PASSWORD,
                BaseApi::HTTP_CONFLICT
            )
                ->setData('errors', BaseApi::INVALID_EMAIL_PASSWORD)
                ->toResponse();
        }

        $account = Auth::user();

        if (!$account->status) {
            return $package->setError(
                BaseApi::USER_NOT_FOUND,
                BaseApi::HTTP_CONFLICT
            )
                ->setData('errors', BaseApi::AUTH_FAIL)
                ->toResponse();
        }

        $claims = [
            'usuario_id' => $account->id,
            'email' => $account->email,
        ];

        $token = auth('api')->claims($claims)->fromUser($account);

        return $package->setData('usuario', $account)
            ->setData('token', $token)
            ->toResponse();
    }

    /**
     * @OA\Post(
     *   path="/usuarios/refresh",
     *   tags={
     *     "Usuario",
     *   },
     *   summary="Refrescar el token de autentificación",
     *   operationId="refreshToken",
     *   security={{"Bearer":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Token.",
     *     @OA\JsonContent(ref="#/components/schemas/RefreshResponse"),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden.",
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found.",
     *   ),
     *   @OA\Response(
     *     response=409,
     *     description="Refresh Errors.",
     *   ),
     * )
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $package = new ResponsePackage();

        $token = $request->bearerToken();

        if (empty($token)) {
            return $package
                ->setError(BaseApi::API_INVALID_REQUEST, BaseApi::HTTP_CONFLICT)
                ->setData('errors', BaseApi::API_TOKEN_NOT_PROVIDED)
                ->toResponse();
        }

        try {
            $token = $this->refreshToken();
            return $package
                ->setData('token', $token)
                ->toResponse();
        } catch (TokenInvalidException $e) {
            return $package->setError($e->getMessage(), BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        } catch (JWTException $e) {
            return $package->setError($e->getMessage(), BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        } catch (\Exception $e) {
            return $package->setError($e->getMessage(), BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }

    /**
     * @OA\Post(
     *   path="/usuarios",
     *   tags={
     *     "Usuario",
     *   },
     *   summary="Registrar paciente",
     *   operationId="CrearUsuarioPaciente",
     *   @OA\RequestBody(
     *     description="Campos para crear un usuario paciente",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Usuario-paciente-schema"),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Registration Successful.",
     *     @OA\JsonContent(ref="#/components/schemas/RegistroResponse"),
     *   ),
     *   @OA\Response(
     *     response=409,
     *     description="Creation Fail.",
     *   ),
     *  @OA\Response(
     *     response=422,
     *     description="Validation Error.",
     *   ),
     * )
     *
     * @param  RegistarUsuario $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistarUsuario $request)
    {
        $package = new ResponsePackage();
        try {
            $usuario = Usuario::create(
                [
                    'email' => $request->email,
                    'usuario' => $request->usuario,
                    'tipo' => 'paciente',
                    'password' => $request->password,
                ]
            );

            Paciente::create(
                [
                    'dni' => $request->dni,
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'telefono' => $request->telefono,
                    'obra_social_id' => $request->obra_social_id,
                    'usuario_id' => $usuario->id,
                ]
            );

            $usuario->paciente;

            return $package->setStatus(BaseApi::HTTP_CREATED)
                ->toResponse();
        } catch (\Exception $e) {
            return $package
                ->setError(BaseApi::CREATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }


    /**
     * @OA\Put(
     *   path="/usuarios/{usuario}",
     *   tags={
     *     "Usuario",
     *   },
     *   summary="Modificar usuario paciente",
     *   operationId="UpdateUsuarioPaciente",
     *   security={{"Bearer":{}}},
     *   @OA\RequestBody(
     *     description="Campos para modificar un usuario paciente",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Usuario-paciente-put-schema"),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Registration Successful.",
     *     @OA\JsonContent(ref="#/components/schemas/PackageSuccessUpdate"),
     *   ),
     *   @OA\Response(
     *     response=409,
     *     description="Creation Fail.",
     *   ),
     *  @OA\Response(
     *     response=422,
     *     description="Validation Error.",
     *   ),
     * )
     *
     * @param  UpdateUsuarioPaciente $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUsuarioPaciente $request, Usuario $usuario)
    {
        $package = new ResponsePackage();
        try {
            $usuario->update($request->only(['email', 'password']));
            if (!is_null($usuario->paciente))
                $usuario->paciente->update($request->only(['telefono', 'obra_social_id']));

            $usuario->paciente;

            return $package->setData('usuario', $usuario)
                ->toResponse();
        } catch (\Exception $e) {
            return $package
                ->setError(BaseApi::UPDATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }
}
