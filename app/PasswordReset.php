<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class PasswordReset  extends Model
{
    use Notifiable;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'password_resets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token'
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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Create validation rules.
     *
     * @var array
     */
    public static $createRules = [
        'email' => 'required|string|email|exists:App\Usuario,email',
    ];

    /**
     * Reset password validation rules.
     *
     * @var array
     */
    public static $resetRules = [
        'password' => 'required|string',
        'token' => 'required|string'
    ];
}
