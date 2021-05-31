<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Turno extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'turnos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paciente_dni',
        'prestacion_id',
        'fecha',
        'horario',
        'precio'
    ];

    /**
     * Date attributes.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'fecha',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'paciente_dni' => 'integer',
        'prestacion_id' => 'integer',
        'fecha' => 'date',
        'precio' => 'float'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Register validation rules.
     *
     * @var array
     */
    public static $createRules = [
        'paciente_dni' => 'nullable|integer|exists:App\Paciente,dni',
        'prestacion_id' => 'nullable|integer|exists:App\Prestacion,id',
        'fecha' => 'required|date',
        'horario' => 'required',
        'precio' => 'nullable|numeric|min:0'
    ];

    /**
     * Register validation rules.
     *
     * @var array
     */
    public static $assignRules = [
        'descripcion' => 'required|string|min:1|max:255',
        'images.*.filename' => 'required|string|min:1',
        'images.*.image' => 'required|string|min:1'
    ];

    /**
     * Appends attributes.
     */
    protected $appends = [
        'estado',
    ];

    public function getEstadoAttribute()
    {
        $pago = $this->pago()->first();

        if (!is_null($pago)) {
            return 'Pagado';
        }
        if (!is_null($this->attributes['prestacion_id']) && !is_null($this->attributes['precio'])) {
            return 'Confirmado';
        }
        return 'Pendiente';
    }

    public function getFechaAttribute()
    {
        return date('Y-m-d', strtotime($this->attributes['fecha']));
    }

    public function getHorarioAttribute()
    {
        return Carbon::parse($this->attributes['horario'])->format('H:i');
    }

    public function setHorarioAttribute($value)
    {
        $this->attributes['horario'] = Carbon::parse($value)->format('H:i');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function prestacion()
    {
        return $this->belongsTo(Prestacion::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pago()
    {
        return $this->hasOne(Pago::class);
    }
}
