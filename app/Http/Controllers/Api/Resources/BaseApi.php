<?php

namespace App\Http\Controllers\Api\Resources;

use App\Usuario;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class BaseApi extends Controller
{
    //API Http codes
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_INVALID_REQUEST = 400;
    const HTTP_AUTH_ERROR = 401;
    const HTTP_FORBIDDEN_ERROR = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_CONFLICT = 409;
    const HTTP_VALIDATION_ERROR = 422;
    const HTTP_TOO_MANY_ATTEMPTS = 429;
    const HTTP_SERVER_ERROR = 500;

    //API Pagination results limit
    const PAGINATION_LIMIT = 50;

    //API generic errors strings
    const DEFAULT_VALIDATION_ERROR = 'Error de validación de datos';
    const DEFAULT_AUTHORIZATION_ERROR = 'No está autorizado para realizar esta acción';
    const DEFAULT_MODEL_QUERY_RESULT_ERROR = 'Objetivo no encontrado';
    const TOO_MANY_ATTEMPTS = 'Ha alcanzado el límite de solicitudes, por favor intente de nuevo en unos minutos';

    const RESET_LINK_SENT = 'Token enviado';
    const PASSWORD_RESET_INVALID_USER = 'El mail no es válido';
    const PASSWORD_RESET_INVALID_TOKEN = 'El token no es válido';
    const PASSWORD_RESET_TOKEN_EXPIRED = 'El token ya ha sido usado o ha expirado';

    const API_GENERAL_ERROR = 'Ha ocurrido un error';
    const API_INVALID_REQUEST = 'Solicitud inválida';
    const API_TOKEN_NOT_PROVIDED = 'Token no proveído';
    const USER_UNAUTHORIZED = 'Usuario no autorizado';
    const TOKEN_UNABLE_TO_REFRESH = 'No se ha podido refrescar el token';

    const NOT_FOUND = "No encontrado";
    const SUCCESS = "Operación realizada exitosamente";
    const CHANGES_FAIL = "La actualización ha fallado";
    const CREATE_FAIL = "La creación ha fallado";
    const UPDATE_FAIL = "La actualización ha fallado";
    const DELETE_FAIL = 'La eliminación ha fallado';
    const USER_NOT_PACIENTE = 'El usuario no está registrado como paciente';
    const DATA_EXIST_DATABASE = "Los datos ya se encuentran registrados en la base de datos";
    const NO_RESULT = 'No se encontraron resultados';

    //Auth
    const AUTH_FAIL = 'Authentication Fail.';
    const AUTH_SUCCESS = 'Authentication Successful.';

    //Account
    const REGISTRATION_SUCCESS = 'Se ha registrado exitosamente';
    const REGISTRATION_FAIL = 'El registro ha fallado';
    const USER_NOT_FOUND = 'Usuario no encontrado';
    const INVALID_DATA = 'Los datos preveídos no son válidos';
    const CHANGES_SUCCESS = 'Los cambios se han realizado exitosamente';
    const MAIL_RESET_SEND = 'Le hemos enviado un correo para restablecer su contraseña';
    const INVALID_EMAIL_PASSWORD = 'Correo o contraseña incorrectos';
    const MISSING_USER_PASSWORD = 'La password es requerida';
    const PASSWORD_INVALID = 'La password no es válida';

    const TURNO_OCUPADO = 'El turno ya se encuentra asignado a un paciente';
    const TURNO_NO_OCUPADO = 'El turno no se encuentra asignado a ningún paciente';
    const TURNO_AJENO = 'No puede eliminar el turno de otro paciente';
    const TURNO_AJENO_PAGO = 'El turno seleccionado no está asignado a ese paciente';
    const TURNO_NO_CONFIRMADO = 'Su turno aún no ha sido confirmado';

    public static function file($fileP, $disk)
    {
        $date = Carbon::now();
        $name = Str::random(10);
        $file = base64_decode($fileP['data']);
        $fileType = $fileP['extension'];
        $filePath = $date->monthName . $date->year . '/' . $name . "." . $fileType;
        $fileOk = Storage::disk($disk)->put($filePath, $file);

        if ($fileOk) {
            return $filePath;
        }

        throw new \Exception('File not saved.',BaseApi::HTTP_SERVER_ERROR);
    }

    /**
     * generate codes random.
     */
    public static function RandomStr($length, $keyspace = '')
    {
        $pieces = [];
        if (empty($keyspace)) {
            $keyspace = config('ABCDEFGHIJKLMNPQRSTUVWXYZ');
        }
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    /**
     * Returns authenticated account.
     *
     * @return mixed
     */
    public function usuario()
    {
        return auth('api')->user();
    }

    /**
     * Gets the user id from the token.
     *
     * @return mixed
     */
    public function getUsuarioId()
    {
        return auth('api')->getPayload()->get('usuario_id');
    }

    /**
     * Refresh existing token.
     *
     * @return mixed
     *
     * @throws \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function refreshToken()
    {
        return auth('api')->refresh();
    }
}
