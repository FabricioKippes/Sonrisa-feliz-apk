<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Resources\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use App\Http\Requests\AssignTurnoPaciente;
use App\Turno;
use App\Http\Requests\CreateTurno;
use App\Notifications\SolicitudTurno;
use App\Usuario;
use stdClass;

/**
 * **
 * @OA\Schema(
 *   schema="TurnoResponse",
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
 *       property="turno",
 *       type="string",
 *       description="Un Turno.",
 *       ref="#/components/schemas/TurnoResponseSchema"
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
 * **
 * @OA\Schema(
 *   schema="TurnoListResponse",
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
 *       property="turnos",
 *       type="array",
 *       description="Listado de turnos",
 *       @OA\Items(
 *             oneOf={@OA\Schema(ref="#/components/schemas/TurnoResponseSchema")}
 *       ),
 *     ),
 *   ),
 *   @OA\Property(
 *     property="headers",
 *     type="object",
 *   ),
 * ),
 *
 */
/**
 * @OA\Schema(
 *   schema="TurnoResponseSchema",
 *   @OA\Property(
 *     property="fecha",
 *     type="string",
 *     format="date",
 *     example="2020/10/25"
 *   ),
 *   @OA\Property(
 *     property="horario",
 *     type="string",
 *     example="10:30"
 *   ),
 *   @OA\Property(
 *     property="paciente_dni",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="paciente",
 *     type="Object",
 *     ref="#/components/schemas/PacienteSchema"
 *   ),
 *   @OA\Property(
 *     property="prestacion_id",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="prestacion",
 *     type="Object",
 *     ref="#/components/schemas/PrestacionResponseSchema"
 *   ),
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="estado",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="precio",
 *     type="number",
 *     format="double",
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="TurnoPostBodySchema",
 *   required={"fecha","horario"},
 *   @OA\Property(
 *     property="fecha",
 *     type="string",
 *     format="date"
 *   ),
 *   @OA\Property(
 *     property="horario",
 *     type="string",
 *     example="10:30:00"
 *   ),
 *   @OA\Property(
 *     property="paciente_dni",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="prestacion_id",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="precio",
 *     type="number",
 *     format="double",
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="TurnoPutBodySchema",
 *   required={"descripcion", "images"},
 *   @OA\Property(
 *     property="descripcion",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="images",
 *     type="array",
 *     @OA\Items(
 *       oneOf={@OA\Schema(ref="#/components/schemas/Images")}
 *     ),
 *   ),
 * )
 */

/**
 * @OA\Schema(
 *   schema="Images",
 *   @OA\Property(
 *     property="filename",
 *     type="string",
 *    ),
 *   @OA\Property(
 *     property="image",
 *     type="string",
 *   ),
 * )
 */
class TurnoController extends BaseApi
{
    /**
     * Devuelve una lista de turnos.
     *
     * @OA\Get(
     *   path="/turnos",
     *   tags={
     *     "Turno",
     *   },
     *   summary="Lista de turnos",
     *   security={{"Bearer":{}}},
     *   description="***Importante:*** El usuario debe ser admin.",
     *   operationId="getList",
     *   @OA\Response(
     *     response=200,
     *     description="turnos",
     *     @OA\JsonContent(ref="#/components/schemas/TurnoListResponse"),
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
        $turnos = new stdClass;
        $turnos->ocupados = Turno::with(['paciente', 'prestacion'])
            ->whereNotNull('paciente_dni')
            ->get();
        $turnos->libres = Turno::with(['paciente', 'prestacion'])
            ->whereNull('paciente_dni')
            ->get();

        return $package->setData('turnos', $turnos)
            ->toResponse();
    }

    /**
     * Update or Create Turno.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/turnos",
     *   tags={
     *     "Turno",
     *   },
     *   summary="Crear o actualiza un turno.",
     *   operationId="updateOrCreateTurno",
     *   description="Si la fecha y horario coinciden con algun registro se
     *   actualiza ese registro, sino se creara un nuevo turno
     *   </br> ***Importante:*** El usuario debe ser admin.",
     *   security={{"Bearer":{}}},
     *   @OA\RequestBody(
     *     description="Campos para crear o actualizar un turno.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/TurnoPostBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Creación exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/TurnoResponse"),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Usuario no autorizado.",
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="No encontrado",
     *   ),
     *   @OA\Response(
     *     response=409,
     *     description="Creación fallida.",
     *   ),
     *  @OA\Response(
     *     response=422,
     *     description="Error de validación",
     *   ),
     * )
     *
     * @param  CreateTurno $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateTurno $request)
    {
        $package = new ResponsePackage();

        try {
            $prestacion = is_null($request->paciente_dni)
                ? null : $request->prestacion_id;
            $precio = is_null($request->paciente_dni)
                ? null : $request->precio;

            $turno = Turno::updateOrCreate(
                [
                    'fecha' => $request->fecha,
                    'horario' => $request->horario,
                ],
                [
                    'paciente_dni' => $request->paciente_dni,
                    'prestacion_id' => $prestacion,
                    'precio' => $precio
                ]
            );

            $turno->paciente;
            $turno->prestacion;

            return $package->setData('turno', $turno)
                ->setStatus(BaseApi::HTTP_CREATED)
                ->toResponse();
        } catch (\Exception $e) {
            return $package->setError(BaseApi::CREATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }


    /**
     * Delete Turno.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *    path="/turnos/{turno}",
     *   tags={
     *     "Turno",
     *   },
     *   summary="Eliminar Turno",
     *   description="Eliminar un turno. </br> ***Importante:*** El usuario debe ser admin.",
     *   operationId="deleteTurno",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="turno",
     *     in="path",
     *     required=true,
     *     description="Id del turno.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Turno Eliminado.",
     *     @OA\JsonContent(ref="#/components/schemas/PackageDeleteResponse"),
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="No encontrado",
     *   ),
     * )
     *
     * @param Turno $turno
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Turno $turno)
    {
        $package = new ResponsePackage();

        try {
            $turno->delete();
            return $package->setStatus(BaseApi::HTTP_OK)
                ->toResponse();
        } catch (\Exception $e) {
            return $package
                ->setError(BaseApi::DELETE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }

    /**
     * Asignar turno a un paciente.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *   path="/turnos/{turno}",
     *   tags={
     *     "Turno",
     *   },
     *   summary="Asignar Turno",
     *   description="***Importante:*** El usuario debe estar registrado como paciente.",
     *   operationId="asignarTurno",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="turno",
     *     in="path",
     *     required=true,
     *     description="Id del turno.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *     description="Campos para solicitar un turno.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/TurnoPutBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Actualización exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/TurnoResponse"),
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
     * @param AssignTurnoPaciente $request
     * @param Turno $turno
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function put(AssignTurnoPaciente $request, Turno $turno)
    {
        $package = new ResponsePackage();
        $paciente = $this->usuario()->paciente;

        if (!isset($paciente->dni)) {
            return $package
                ->setError(BaseApi::UPDATE_FAIL, BaseApi::HTTP_INVALID_REQUEST)
                ->setData('errors', BaseApi::USER_NOT_PACIENTE)
                ->toResponse();
        }

        try {
            if (is_null($turno->paciente_dni)) {
                $turno->update([ 'paciente_dni' => $paciente->dni ]);
                $admins = Usuario::where('tipo', 'admin')->get();

                foreach ($admins as $admin) {
                    $admin->notify(new SolicitudTurno($request->descripcion, $request->images, $paciente, $turno));
                }

                $turno->paciente;
                $turno->prestacion;

                return $package->setData('turno', $turno)
                    ->toResponse();
            } else {
                return $package
                    ->setError(BaseApi::TURNO_OCUPADO, BaseApi::HTTP_CONFLICT)
                    ->setData('errors', BaseApi::TURNO_OCUPADO)
                    ->toResponse();
            }
        } catch (\Exception $e) {
            return $package
                ->setError(BaseApi::UPDATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }

    /**
     * Liberar un turno.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *   path="/turnos/{turno}/liberar",
     *   tags={
     *     "Turno",
     *   },
     *   summary="Liberar un Turno",
     *   description="***Importante:*** El usuario debe estar registrado como paciente.",
     *   operationId="liberarTurno",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="turno",
     *     in="path",
     *     required=true,
     *     description="Id del turno.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Actualización exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/TurnoResponse"),
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
     * @param Turno $turno
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function liberar(Turno $turno)
    {
        $package = new ResponsePackage();
        $paciente = $this->usuario()->paciente;

        if (!isset($paciente->dni)) {
            return $package
                ->setError(BaseApi::UPDATE_FAIL, BaseApi::HTTP_INVALID_REQUEST)
                ->setData('errors', BaseApi::USER_NOT_PACIENTE)
                ->toResponse();
        }

        try {
            if ($turno->paciente_dni == $paciente->dni) {
                $turno->update(
                    [
                        'paciente_dni' => null,
                        'precio' => null,
                        'prestacion_id' => null
                    ]
                );

                return $package->setStatus(BaseApi::HTTP_OK)
                    ->toResponse();
            } else if (is_null($turno->paciente_dni)) {
                return $package
                    ->setError(BaseApi::TURNO_NO_OCUPADO, BaseApi::HTTP_INVALID_REQUEST)
                    ->setData('errors', BaseApi::TURNO_NO_OCUPADO)
                    ->toResponse();
            } else {
                return $package
                    ->setError(BaseApi::TURNO_AJENO, BaseApi::HTTP_FORBIDDEN_ERROR)
                    ->setData('errors', BaseApi::TURNO_AJENO)
                    ->toResponse();
            }
        } catch (\Exception $e) {
            return $package
                ->setError(BaseApi::UPDATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }

    /**
     * Devuelve una lista de turnos disponibles.
     *
     * @OA\Get(
     *   path="/turnos/disponibles",
     *   tags={
     *     "Turno",
     *   },
     *   summary="Lista de turnos disponibles",
     *   security={{"Bearer":{}}},
     *   description="***Importante:*** El usuario debe estar registrado.",
     *   operationId="getDisponibles",
     *   @OA\Response(
     *     response=200,
     *     description="turnos",
     *     @OA\JsonContent(ref="#/components/schemas/TurnoListResponse"),
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
    public function getDisponibles(Request $request)
    {
        $package = new ResponsePackage();
        $turnos = Turno::whereNull('paciente_dni')
            ->where('fecha', '>=', date('Y-m-d'))
            ->get()
            ->groupBy('fecha');

        return $package->setData('turnos', $turnos)
            ->toResponse();
    }
}
