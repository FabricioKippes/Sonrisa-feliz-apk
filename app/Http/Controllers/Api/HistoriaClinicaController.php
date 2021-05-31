<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Resources\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use App\HistoriaClinica;
use App\Http\Requests\CreateHistoriaClinica;
use App\Http\Requests\UpdateHistoriaClinica;

/**
 * **
 * @OA\Schema(
 *   schema="HistoriaClinicaResponse",
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
 *       property="historia_clinica",
 *       type="string",
 *       description="Una Historia Clínica.",
 *       ref="#/components/schemas/HistoriaClinicaResponseSchema"
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
 *   schema="HistoriaClinicaListResponse",
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
 *       property="historias_clinicas",
 *       type="array",
 *       description="Listado de historias_clinicas",
 *       @OA\Items(
 *             oneOf={@OA\Schema(ref="#/components/schemas/HistoriaClinicaResponseSchema")}
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
 *   schema="HistoriaClinicaResponseSchema",
 *   @OA\Property(
 *     property="paciente_dni",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="paciente",
 *     type="Object",
 *     ref="#/components/schemas/PacienteSchema",
 *   ),
 *   @OA\Property(
 *     property="prestacion_id",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="prestacion",
 *     type="Object",
 *     ref="#/components/schemas/PrestacionResponseSchema",
 *   ),
 *   @OA\Property(
 *     property="observaciones",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="HistoriaClinicaPostBodySchema",
 *   required={"observaciones","paciente_dni", "prestacion_id"},
 *   @OA\Property(
 *     property="observaciones",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="paciente_dni",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="prestacion_id",
 *     type="integer",
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="HistoriaClinicaPutBodySchema",
 *   @OA\Property(
 *     property="observaciones",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="paciente_dni",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="prestacion_id",
 *     type="integer",
 *   ),
 * )
 */
class HistoriaClinicaController extends BaseApi
{
    /**
     * Devuelve una lista de historias clínicas.
     *
     * @OA\Get(
     *   path="/historias-clinicas",
     *   tags={
     *     "HistoriaClinica",
     *   },
     *   summary="Lista de historias clínicas",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   security={{"Bearer":{}}},
     *   operationId="getList",
     *   @OA\Response(
     *     response=200,
     *     description="historias_clinicas",
     *     @OA\JsonContent(ref="#/components/schemas/HistoriaClinicaListResponse"),
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
        $historias_clinicas = HistoriaClinica::with('paciente')->get();

        return $package->setData('historias_clinicas', $historias_clinicas)
            ->toResponse();
    }

    /**
     * Create Historia Clínica.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/historias-clinicas",
     *   tags={
     *     "HistoriaClinica",
     *   },
     *   summary="Crear una historia clínica.",
     *   operationId="CreateHistoriaClinica",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   security={{"Bearer":{}}},
     *   @OA\RequestBody(
     *     description="Campos para crear una historia clínica.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/HistoriaClinicaPostBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Creación exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/HistoriaClinicaResponse"),
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
     * @param  CreateHistoriaClinica $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateHistoriaClinica $request)
    {
        $package = new ResponsePackage();

        try {
            $historia_clinica = HistoriaClinica::create([
                'observaciones' => $request->observaciones,
                'paciente_dni' => $request->paciente_dni,
                'prestacion_id' => $request->prestacion_id
            ]);

            return $package->setData('historia_clinica', $historia_clinica)
                ->setStatus(BaseApi::HTTP_CREATED)
                ->toResponse();
        } catch (\Exception $e) {
            return $package->setError(BaseApi::CREATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }


    /**
     * Delete Historia Clínica.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *    path="/historias-clinicas/{historiaclinica}",
     *   tags={
     *     "HistoriaClinica",
     *   },
     *   summary="Eliminar historia clínica",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   operationId="deleteHistoriaClinica",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="historiaclinica",
     *     in="path",
     *     required=true,
     *     description="Id de la historia clínica.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Historia Clínica Eliminada.",
     *     @OA\JsonContent(ref="#/components/schemas/PackageDeleteResponse"),
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="No encontrada",
     *   ),
     * )
     *
     * @param HistoriaClinica $historiaclinica
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(HistoriaClinica $historiaclinica)
    {
        $package = new ResponsePackage();

        try {
            $historiaclinica->delete();
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
     * Update Historia Clínica.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *   path="/historias-clinicas/{historiaclinica}",
     *   tags={
     *     "HistoriaClinica",
     *   },
     *   summary="Editar Historia Clínica",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   operationId="editarHistoriaClinica",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="historiaclinica",
     *     in="path",
     *     required=true,
     *     description="Id de la historia clínica.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *     description="Campos para editar una historia clínica.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/HistoriaClinicaPutBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Actualización exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/HistoriaClinicaResponse"),
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
     * @param UpdateHistoriaClinica $request
     * @param HistoriaClinica $historiaclinica
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function put(UpdateHistoriaClinica $request, HistoriaClinica $historiaclinica)
    {
        $package = new ResponsePackage();

        try {
            $historiaclinica->update($request->all());
            return $package->setData('historia_clinica', $historiaclinica)
                ->toResponse();
        } catch (\Exception $e) {
            return $package
                ->setError(BaseApi::UPDATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }
}
