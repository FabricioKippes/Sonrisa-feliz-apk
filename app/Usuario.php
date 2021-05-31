<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Paciente;

class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario',
        'tipo',
        'email',
        'password',
        'status'
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
        'email' => 'string',
        'usuario' => 'string',
        'tipo' => 'string',
        'status' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];

    public static $tipos = [
        'admin',
        'paciente',
    ];

    /**
     * Register validation rules.
     *
     * @var array
     */
    public static $createRules = [
        'email' => 'required|email|unique:usuarios',
        'password' => 'required|string|min:8',
        'usuario' => 'string|min:2|max:30',
        'tipo' => 'required|string|in:admin,paciente',
        'status' => 'required|boolean',
    ];

    /**
     * Login validation rules.
     *
     * @var array
     */
    public static $loginRules = [
        'email' => 'email|required',
        'password' => 'required',
    ];

    /**
     * Register validation rules.
     *
     * @var array
     */
    public static $registerRules = [
        'email' => 'required|email|unique:usuarios',
        'password' => 'required|string|min:8',
        'usuario' => 'string|min:2|max:30',
        'dni' => 'required|integer|unique:pacientes',
        'nombre' => 'required|string|min:1|max:50',
        'apellido' => 'required|string|min:1|max:50',
        'fecha_nacimiento' => 'required|date',
        'telefono' => 'nullable|string|min:1|max:11',
        'obra_social_id' => 'nullable|integer|exists:App\ObraSocial,id',
    ];

    /**
     * Register validation rules.
     *
     * @var array
     */
    public static $updateRules = [
        'email' => 'email|unique:usuarios',
        'password' => 'string|min:8',
        'telefono' => 'nullable|string|min:1|max:11',
        'obra_social_id' => 'nullable|integer|exists:App\ObraSocial,id',
    ];

    /**
     * Custom messages for rules validator.
     *
     * @var array
     */
    public static $createMessage = [
        'email.unique' => 'El correo ya se encuentra registrado',
    ];

    /**
     * Appends attributes.
     */
    protected $appends = [
        'paciente',
    ];

    public function getPacienteAttribute()
    {
        return $this->paciente()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    /**
     * Set attribute Password
     **/
    public function setPasswordAttribute($value)
    {
        $pass = Hash::make($value);
        $this->attributes['password'] = $pass;
    }

    /**
     * Check if the user is type admin
     **/
    public function isAdmin()
    {
        return $this->tipo == self::$tipos[0];
    }

    /**
     * Check if the user is type paciente
     **/
    public function isPaciente()
    {
        return $this->tipo == self::$tipos[1];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
