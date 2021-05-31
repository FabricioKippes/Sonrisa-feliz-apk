<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestacion extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'prestaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
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
        'nombre' => 'string',
        'descripcion' => 'string'
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
        'descripcion' => 'required|string|min:1|max:255',
        'nombre' => 'required|string|min:1|max:50|unique:prestaciones'
    ];

    /**
     * Register validation rules.
     *
     * @var array
     */
    public static $updateRules = [
        'descripcion' => 'string|min:1|max:255',
        'nombre' => 'string|min:1|max:50|unique:prestaciones'
    ];
}
