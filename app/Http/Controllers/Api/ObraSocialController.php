<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Resources\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use App\ObraSocial;
use App\Http\Requests\CreateObraSocial;
use App\Http\Requests\UpdateObraSocial;

/**
 * **
 * @OA\Schema(
 *   schema="ObraSocialResponse",
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
 *       property="obra_social",
 *       type="string",
 *       description="Una Obra Social.",
 *       ref="#/components/schemas/ObraSocialResponseSchema"
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
 *   schema="ObraSocialListResponse",
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
 *       property="obras_sociales",
 *       type="array",
 *       description="Listado de Obras Sociales",
 *       @OA\Items(
 *             oneOf={@OA\Schema(ref="#/components/schemas/ObraSocialResponseSchema")}
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
 *   schema="ObraSocialResponseSchema",
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="telefono",
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
 *   schema="ObraSocialPostBodySchema",
 *   required={"nombre","telefono"},
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="telefono",
 *     type="string",
 *   ),
 * )
 */
/**
 * @OA\Schema(
 *   schema="ObraSocialPutBodySchema",
 *   @OA\Property(
 *     property="nombre",
 *     type="string",
 *   ),
 *   @OA\Property(
 *     property="telefono",
 *     type="string",
 *   ),
 * )
 */
class ObraSocialController extends BaseApi
{
    /**
     * Devuelve una lista de obras sociales.
     *
     * @OA\Get(
     *   path="/obras-sociales",
     *   tags={
     *     "ObraSocial",
     *   },
     *   summary="Lista de obras sociales",
     *   operationId="getList",
     *   @OA\Response(
     *     response=200,
     *     description="Obras Sociales",
     *     @OA\JsonContent(ref="#/components/schemas/ObraSocialListResponse"),
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
        $obras_sociales = ObraSocial::all();

        return $package->setData('obras_sociales', $obras_sociales)
            ->toResponse();
    }

    /**
     * Create Obra Social.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/obras-sociales",
     *   tags={
     *     "ObraSocial",
     *   },
     *   summary="Crear una obra social.",
     *   operationId="CreateObraSocial",
     *   description="Crear una nueva obra social. </br> ***Importante:*** El usuario debe ser admin.",
     *   security={{"Bearer":{}}},
     *   @OA\RequestBody(
     *     description="Campos para crear una obra social.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/ObraSocialPostBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Creación exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/ObraSocialResponse"),
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
     * @param  CreateObraSocial $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateObraSocial $request)
    {
        $package = new ResponsePackage();

        try {
            $obra_social = ObraSocial::create([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono
            ]);

            return $package->setData('obra_social', $obra_social)
                ->setStatus(BaseApi::HTTP_CREATED)
                ->toResponse();

        } catch (\Exception $e) {
            return $package->setError(BaseApi::CREATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }


    /**
     * Delete Obra Social.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *    path="/obras-sociales/{obrasocial}",
     *   tags={
     *     "ObraSocial",
     *   },
     *   summary="Eliminar Obra social",
     *   description="Eliminar una obra social. </br> ***Importante:*** El usuario debe ser admin.",
     *   operationId="deleteObraSocial",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="obrasocial",
     *     in="path",
     *     required=true,
     *     description="Id de la obra social.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Obra social Eliminada.",
     *     @OA\JsonContent(ref="#/components/schemas/PackageDeleteResponse"),
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="No encontrada",
     *   ),
     * )
     *
     * @param ObraSocial $obrasocial
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(ObraSocial $obrasocial)
    {
        $package = new ResponsePackage();

        try {
            $obrasocial->delete();
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
     * Update Obra Social.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *   path="/obras-sociales/{obrasocial}",
     *   tags={
     *     "ObraSocial",
     *   },
     *   summary="Editar Obra social",
     *   description="Ediatr una obra social. </br> ***Importante:*** El usuario debe ser admin.",
     *   operationId="editarObraSocial",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="obrasocial",
     *     in="path",
     *     required=true,
     *     description="Id de la obra social.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *     description="Campos para editar una obra social.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/ObraSocialPutBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Actualización exitosa",
     *     @OA\JsonContent(ref="#/components/schemas/ObraSocialResponse"),
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
     * @param UpdateObraSocial $request
     * @param ObraSocial $obrasocial
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function put(UpdateObraSocial $request, ObraSocial $obrasocial)
    {
        $package = new ResponsePackage();

        try {
            $obrasocial->update($request->all());
            return $package->setData('obra_social', $obrasocial)
                ->toResponse();
        } catch (\Exception $e) {
            return $package
                ->setError(BaseApi::UPDATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }
}
