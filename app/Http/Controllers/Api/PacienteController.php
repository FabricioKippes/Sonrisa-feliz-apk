<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Resources\BaseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Resources\ResponsePackage;
use App\Paciente;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * **
 * @OA\Schema(
 *   schema="GetPacienteResponse",
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
 *       property="paciente",
 *       type="string",
 *       description="Un paciente.",
 *       ref="#/components/schemas/PacienteSchema"
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
 *   schema="GetPacienteListResponse",
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
 *     format="int32",
 *     example=200,
 *   ),
 *   @OA\Property(
 *     property="data",
 *     type="object",
 *     @OA\Property(
 *       property="pacientes",
 *       type="array",
 *       description="Listado de Pacientes",
 *       @OA\Items(
 *             oneOf={@OA\Schema(ref="#/components/schemas/PacienteSchema")}
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
 *   schema="PacienteSchema",
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
 *   @OA\Property(
 *     property="usuario_id",
 *     type="integer",
 *     example=2,
 *   ),
 * )
 */
class PacienteController extends BaseApi
{
    /**
     * Devuelve una lista de pacientes.
     *
     * @OA\Get(
     *   path="/pacientes",
     *   tags={
     *     "Paciente",
     *   },
     *   summary="Lista de pacientes",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   operationId="getPacientes",
     *   security={{"Bearer":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Pacientes",
     *     @OA\JsonContent(ref="#/components/schemas/GetPacienteListResponse"),
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


        $pacientes= Paciente::all();

        return $package->setData('pacientes', $pacientes)
            ->toResponse();
    }

    /**
     * Devuelve la lista de turnos del usuario loggeado.
     *
     * @OA\Get(
     *   path="/pacientes/turnos",
     *   tags={
     *     "Paciente",
     *   },
     *   summary="Lista de turnos del paciente",
     *   description="***Importante:*** El usuario debe estar registrado como paciente.",
     *   operationId="getTurnosPaciente",
     *   security={{"Bearer":{}}},
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTurnos(Request $Request)
    {
        $package = new ResponsePackage();
        $paciente = $this->usuario()->paciente;

        if (is_null($paciente)) {
            return $package
                ->setError(BaseApi::NO_RESULT, BaseApi::HTTP_NOT_FOUND)
                ->setData('errors', BaseApi::USER_NOT_PACIENTE)
                ->toResponse();
        }

        $turnos = $paciente->turnos;

        return $package->setData('turnos', $turnos)
            ->toResponse();
    }

    /**
     * Devuelve la lista de historias clinicas de un paciente.
     *
     * @OA\Get(
     *   path="/pacientes/{paciente}/historias",
     *   tags={
     *     "Paciente",
     *   },
     *   summary="Lista de historias clinicas de un paciente",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   operationId="getHistoriasPaciente",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="paciente",
     *     in="path",
     *     required=true,
     *     description="DNI del paciente.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
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
     * @param Paciente $paciente
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistorias(Paciente $paciente)
    {
        $package = new ResponsePackage();

        $historias_clinicas = $paciente->historias_clinicas;

        return $package->setData('historias_clinicas', $historias_clinicas)
            ->toResponse();
    }

    /**
     * Devuelve la lista de historias clinicas de un paciente.
     *
     * @OA\Get(
     *   path="/pacientes/{paciente}/historias/download",
     *   tags={
     *     "Paciente",
     *   },
     *   summary="Lista de historias clinicas de un paciente",
     *   description="***Importante:*** El usuario debe ser admin.",
     *   operationId="getHistoriasPaciente",
     *   security={{"Bearer":{}}},
     *   @OA\Parameter(
     *     name="paciente",
     *     in="path",
     *     required=true,
     *     description="DNI del paciente.",
     *     example="1",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="archivo docx",
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
     * @param Paciente $paciente
     * @return \Illuminate\Http\JsonResponse
     */
    public function download(Paciente $paciente)
    {
        $template = new TemplateProcessor(storage_path('historia_clinica.docx'));
        $historias_clinicas = $paciente->historias_clinicas;

        $template->setValue('nombre', $paciente->nombre);
        $template->setValue('apellido', $paciente->apellido);
        $template->setValue('dni', $paciente->dni);

        $obrasocial = is_null($paciente->obra_social_id)
            ? '-' : $paciente->obra_social->nombre;
        $template->setValue('obrasocial', $obrasocial);

        $template->setValue('telefono', $paciente->telefono);
        $template->setValue('fechanacimiento', $paciente->fecha_nacimiento);
        $template->setValue('edad', $paciente->edad);

        $registros = [];

        foreach ($historias_clinicas as $historia) {
            $registro = [
                'fecha' => $historia->fecha,
                'prestacion' => $historia->prestacion->nombre,
                'observaciones' => $historia->observaciones
            ];

            $registros[] = $registro;
        }


        $template->cloneRowAndSetValues('fecha', $registros);

        $export_file = public_path($paciente->dni . '.docx');
        $template->saveAs($export_file);
        return response()->download($export_file)->deleteFileAfterSend(true);
    }
}
