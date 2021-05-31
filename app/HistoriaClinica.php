<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'historias_clinicas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paciente_dni',
        'prestacion_id',
        'observaciones'
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
        'prestacion_id' => 'integer',
        'observaciones' => 'string'
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
        'paciente_dni' => 'required|integer|exists:App\Paciente,dni',
        'prestacion_id' => 'required|integer|exists:App\Prestacion,id',
        'observaciones' => 'required|string|min:1'
    ];

    /**
     * Register validation rules.
     *
     * @var array
     */
    public static $updateRules = [
        'paciente_dni' => 'integer|exists:App\Paciente,dni',
        'prestacion_id' => 'integer|exists:App\Prestacion,id',
        'observaciones' => 'string|min:1'
    ];

    /**
     * Appends attributes.
     */
    protected $appends = [
        'prestacion',
        'fecha',
    ];

    public function getPrestacionAttribute()
    {
        return $this->prestacion()->first();
    }

    public function getfechaAttribute()
    {
        $fecha = is_null('updated_at')
        ? $this->attributes['created_at'] : $this->attributes['updated_at'];
        return date('Y-m-d - h:m', strtotime($fecha));
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
}
