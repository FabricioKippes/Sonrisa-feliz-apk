<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ObraSocial;
use App\Turno;
use Carbon\Carbon;

class Paciente extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'pacientes';

    protected $primaryKey = 'dni';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'telefono',
        'obra_social_id',
        'usuario_id'
    ];

    /**
     * Date attributes.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'fecha_nacimiento'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dni' => 'integer',
        'nombre' => 'string',
        'apellido' => 'string',
        'fecha_nacimiento' => 'date',
        'telefono' => 'string',
        'obra_social_id' => 'integer',
        'usuario_id' => 'integer'
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
        'dni' => 'required|integer|unique:pacientes',
        'nombre' => 'required|string|min:1|max:50',
        'apellido' => 'required|string|min:1|max:50',
        'fecha_nacimiento' => 'required|date',
        'telefono' => 'nullable|string|min:1|max:11',
        'obra_social_id' => 'nullable|integer|exists:App\ObraSocial,id',
        'usuario_id' => 'required|integer|exists:App\Usuario,id'
    ];

    /**
     * Appends attributes.
     */
    protected $appends = [
        'obra_social',
        'edad'
    ];

    public function getObraSocialAttribute()
    {
        return $this->obraSocial()->first();
    }

    public function getFechaNacimientoAttribute()
    {
        return date('Y-m-d', strtotime($this->attributes['fecha_nacimiento']));
    }

    /**
     * Accessor for Age.
     */
    public function getEdadAttribute()
    {
        return Carbon::parse($this->attributes['fecha_nacimiento'])->age;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obraSocial()
    {
        return $this->belongsTo(ObraSocial::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function turnos()
    {
        return $this->hasMany(Turno::class)->where('fecha', '>=', date('Y-m-d'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function historias_clinicas()
    {
        return $this->hasMany(HistoriaClinica::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
