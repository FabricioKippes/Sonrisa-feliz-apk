<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Resources\BaseApi;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use App\Http\Requests\RegistrarPago;
use App\Notifications\FacturaDePago;
use App\Paciente;
use App\Pago;
use App\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * **
 * @OA\Schema(
 *   schema="PagoResponse",
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
 *       property="pago",
 *       type="string",
 *       description="Un Pago.",
 *       ref="#/components/schemas/PagoResponseSchema"
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
 *   schema="PagoListResponse",
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
 *       property="pagos",
 *       type="array",
 *       description="Listado de pagos",
 *       @OA\Items(
 *             oneOf={@OA\Schema(ref="#/components/schemas/PagoResponseSchema")}
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
 *   schema="PagoResponseSchema",
 *   @OA\Property(
 *     property="fecha",
 *     type="string",
 *     format="date",
 *     example="2020-10-25 - 12:30"
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
 *     property="turno_id",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="turno",
 *     type="Object",
 *     ref="#/components/schemas/TurnoResponseSchema"
 *   ),
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="monto",
 *     type="number",
 *     format="double",
 *   ),
 *   @OA\Property(
 *     property="dni_titular",
 *     type="integer",
 *     example="11222333",
 *   ),
 *   @OA\Property(
 *     property="nombre_titular",
 *     type="string",
 *     example="Sandro Lacunza",
 *   ),
*   @OA\Property(
 *     property="concepto",
 *     type="string",
 *     example="Blanqueamiento bucal",
 *   )
 * )
 */
/**
 * @OA\Schema(
 *   schema="PagoPostBodySchema",
 *   required={"paciente_dni", "turno_id", "dni_titular", "nombre_titular", "nro_tarjeta", "cod_seguridad", "fecha_expiracion"},
 *   @OA\Property(
 *     property="paciente_dni",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="turno_id",
 *     type="integer",
 *   ),
 *   @OA\Property(
 *     property="dni_titular",
 *     type="integer",
 *     example="11222333",
 *   ),
 *   @OA\Property(
 *     property="nombre_titular",
 *     type="string",
 *     example="Sandro Lacunza",
 *   ),
 *   @OA\Property(
 *     property="nro_tarjeta",
 *     type="string",
 *     example="1111222233334444",
 *   ),
 *   @OA\Property(
 *     property="cod_seguridad",
 *     type="string",
 *     example="444",
 *   ),
*   @OA\Property(
 *     property="fecha_expiracion",
 *     type="string",
 *     format="date"
 *   ),
 * )
 */
class PagoController extends BaseApi
{
    /**
     * Devuelve una lista de pagos.
     *
     * @OA\Get(
     *   path="/pagos",
     *   tags={
     *     "Pago",
     *   },
     *   summary="Lista de pagos",
     *   security={{"Bearer":{}}},
     *   description="***Importante:*** El usuario debe ser admin.",
     *   operationId="getList",
     *   @OA\Response(
     *     response=200,
     *     description="turnos",
     *     @OA\JsonContent(ref="#/components/schemas/PagoListResponse"),
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
        $pagos = Pago::with(['paciente', 'turno'])->get();

        return $package->setData('pagos', $pagos)
            ->toResponse();
    }


    /**
     * Update or Create Turno.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/pagos",
     *   tags={
     *     "Pago",
     *   },
     *   summary="Registrar pago.",
     *   operationId="registrarPago",
     *   description="requiere un token",
     *   security={{"Bearer":{}}},
     *   @OA\RequestBody(
     *     description="Campos para registrar un pago.",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/PagoPostBodySchema"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Factura de pago en pdf",
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
     * @param  RegistrarPago $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrar(RegistrarPago $request)
    {
        $package = new ResponsePackage();

        try {

            $turno = Turno::with('prestacion')->findOrFail($request->turno_id);
            $paciente = Paciente::with('usuario')->findOrFail($request->paciente_dni);

            if ($turno->paciente_dni != $request->paciente_dni) {
                return $package
                    ->setError(BaseApi::TURNO_AJENO_PAGO, BaseApi::HTTP_CONFLICT)
                    ->setData('errors', BaseApi::TURNO_AJENO_PAGO)
                    ->toResponse();
            }

            if (!is_null($turno->prestacion) && !is_null($turno->precio)) {

                $pago = Pago::create(
                    [
                        'paciente_dni' => $request->paciente_dni,
                        'turno_id' => $turno->id,
                        'monto' => $turno->precio,
                        'dni_titular' => $request->dni_titular,
                        'nombre_titular' => $request->nombre_titular,
                        'concepto' => $turno->prestacion->nombre
                    ]
                );

                if (!is_null($pago)) {
                    $data = [
                        'fecha' => $pago->fecha,
                        'monto' => $pago->monto,
                        'fecha_turno' =>  Carbon::parse($turno->fecha)->format('d/m/Y'),
                        'concepto' => $pago->concepto,
                        'id' => $turno->prestacion->id,
                        'nro_factura' => $pago->nro_factura
                    ];

                    $file = $pago->nro_factura . '.pdf';
                    $export_pdf = public_path($file);
                    \PDF::loadView('factura', compact('data'))->save($export_pdf);

                    $paciente->usuario->notify(new FacturaDePago($file, $export_pdf));


                    return response()->download($export_pdf)->deleteFileAfterSend(true);
                }

                return $package->setError(BaseApi::CREATE_FAIL, BaseApi::HTTP_CONFLICT)
                    ->setData('errors', BaseApi::CREATE_FAIL)
                    ->toResponse();
            }

            return $package
                ->setError(BaseApi::TURNO_NO_CONFIRMADO, BaseApi::HTTP_CONFLICT)
                ->setData('errors', BaseApi::TURNO_NO_CONFIRMADO)
                ->toResponse();

        } catch (\Exception $e) {
            return $package->setError(BaseApi::CREATE_FAIL, BaseApi::HTTP_CONFLICT)
                ->setData('errors', $e->getMessage())
                ->toResponse();
        }
    }
}
