<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'pagos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paciente_dni',
        'turno_id',
        'monto',
        'dni_titular',
        'nombre_titular',
        'concepto'
    ];

    /**
     * Date attributes.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'paciente_dni' => 'integer',
        'turno_id' => 'integer',
        'monto' => 'float',
        'dni_titular' => 'integer',
        'nombre_titular' => 'string',
        'concepto' => 'string'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Register validation rules.
     *
     * @var array
     */
    public static $registrarRules = [
        'paciente_dni' => 'required|integer|exists:App\Paciente,dni',
        'turno_id' => 'required|integer|exists:App\Turno,id',
        'nro_tarjeta' => 'required|string|min:16|max:16',
        'cod_seguridad' => 'required|string|min:3|max:4',
        'nombre_titular' => 'required|string|min:1',
        'fecha_expiracion' => 'required|date|after:today',
        'dni_titular' => 'required|integer'
    ];

        /**
     * Appends attributes.
     */
    protected $appends = [
        'fecha',
        'nro_factura'
    ];

    public function getFechaAttribute()
    {
        return date('d/m/Y - h:m', strtotime($this->attributes['created_at']));
    }

    public function getNroFacturaAttribute()
    {
        return sprintf('%08d', $this->attributes['id']);
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
    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
}
