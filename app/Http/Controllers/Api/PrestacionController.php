<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Resources\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use App\Prestacion;
use App\Http\Requests\CreatePrestacion;
use App\Http\Requests\UpdatePrestacion;

/**
 * **
 * @OA\Schema(
 *   schema="PrestacionResponse",
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
 *       property="prestacion",
 *       type="string",
 *       description="Una Prestación.",
 *       ref="#/components/schemas/PrestacionResponseSchema"
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
 *   schema="PrestacionListResponse",
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
 *       property="prestaciones",
 *       type="array",
 *       description="Listado de prestaciones",
 *       @OA\Items(
 *             oneOf={@OA\Schema(ref="#/components/schemas/PrestacionResponseSchema")}
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
 *   schema="PrestacionResponseSchema",
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="descripcion",
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
 *   schema="PrestacionPostBodySchema",
 *   required={"nombre","descripcion"},
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="descripcion",
 *     type="string",
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="PrestacionPutBodySchema",
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="descripcion",
 *     type="string",
 *   ),
 * )
 */
class PrestacionController extends BaseApi
{
    /**
     * Devuelve una lista de prestaciones.
     *
     * @OA\Get(
     *   path="/prestaciones",
     *   tags={
     *     "Prestacion",
     *   },
     *   summary="Lista de prestaciones",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   security={{"Bearer":{}}},
     *   operationId="getList",
     *   @OA\Response(
     *     response=200,
     *     description="prestaciones",
     *     @OA\JsonContent(ref="#/components/schemas/PrestacionListResponse"),
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
    public function getList (Request $request)
    {
        $package = new ResponsePackage();
        $prestaciones = Prestacion::all();

        return $package->setData('prestaciones', $prestaciones)
            ->toResponse();
    }

    /**
     * Create Prestación.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/prestaciones",
     *   tags={
     *     "Prestacion",
     *   },
     *   summary="Crear una prestación.",
     *   operationId="CreatePrestacion",
     *   description="Crear una nueva prestación. </br> ***Importante:*** El usuario debe ser admin.",
     *   security={{"Bearer":{}}},
     *   @OA\RequestBody(
     *     description="Campos para crear una prestación.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/PrestacionPostBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Creación exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/PrestacionResponse"),
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
     * @param  CreatePrestacion $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreatePrestacion $request)
    {
        $package = new ResponsePackage();

        try {
            $prestacion = Prestacion::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion
            ]);

            return $package->setData('prestacion', $prestacion)
                ->setStatus(BaseApi::HTTP_CREATED)
                ->toResponse();

        } catch (\Exception $e) {
            return $package->setError(BaseApi::CREATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }


    /**
     * Delete Prestación.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *    path="/prestaciones/{prestacion}",
     *   tags={
     *     "Prestacion",
     *   },
     *   summary="Eliminar Prestacion",
     *   description="Eliminar una prestación. </br> ***Importante:*** El usuario debe ser admin.",
     *   operationId="deletePrestacion",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="prestacion",
     *     in="path",
     *     required=true,
     *     description="Id de la prestación.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Prestación Eliminada.",
     *     @OA\JsonContent(ref="#/components/schemas/PackageDeleteResponse"),
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="No encontrada",
     *   ),
     * )
     *
     * @param Prestacion $prestacion
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Prestacion $prestacion)
    {
        $package = new ResponsePackage();

        try {
            $prestacion->delete();
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
     * Update Prestación.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *   path="/prestaciones/{prestacion}",
     *   tags={
     *     "Prestacion",
     *   },
     *   summary="Editar Prestación",
     *   description="Editar una prestación. </br> ***Importante:*** El usuario debe ser admin.",
     *   operationId="editarPrestacion",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="prestacion",
     *     in="path",
     *     required=true,
     *     description="Id de la prestación.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *     description="Campos para editar una prestación.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/PrestacionPutBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Actualización exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/PrestacionResponse"),
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
     * @param UpdatePrestacion $request
     * @param Prestacion $prestacion
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function put(UpdatePrestacion $request, Prestacion $prestacion)
    {
        $package = new ResponsePackage();

        try {
            $prestacion->update($request->all());
            return $package->setData('prestacion', $prestacion)
                ->toResponse();
        } catch (\Exception $e) {
            return $package
                ->setError(BaseApi::UPDATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }
}
