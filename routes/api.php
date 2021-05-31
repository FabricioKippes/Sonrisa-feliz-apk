<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/v1'], function () {

    // USER
    Route::post(
        'usuarios/login',
        [
            'as' => 'usuario.login',
            'uses' => 'UsuarioController@login',
        ]
    );

    Route::post(
        'usuarios',
        [
            'as' => 'usuario.register',
            'uses' => 'UsuarioController@register',
        ]
    );

    Route::post(
        '/usuarios/password',
        [
            'as' => 'usuario.password.create',
            'uses' => 'PasswordResetController@create',
        ]
    );

    Route::put(
        '/usuarios/password',
        [
            'as' => 'usuario.password.put',
            'uses' => 'PasswordResetController@put',
        ]
    );

    Route::post(
        'usuarios/refresh',
        [
            'as' => 'usuario.refresh',
            'uses' => 'UsuarioController@refresh',
        ]
    );

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::put(
            'usuarios/{usuario}',
            [
                'as' => 'usuario.update',
                'uses' => 'UsuarioController@update',
            ]
        );

        Route::middleware('admin')->group(function () {
            Route::get(
                'usuarios',
                [
                    'as' => 'get.list',
                    'uses' => 'UsuarioController@getList',
                ]
            );
        });
    });

    // Obras Sociales

    Route::get(
        '/obras-sociales',
        [
            'as' => 'obrasSociales.list',
            'uses' => 'ObraSocialController@getList',
        ]
    );

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::middleware('admin')->group(function () {
            Route::post(
                '/obras-sociales',
                [
                    'as' => 'obrasSociales.create',
                    'uses' => 'ObraSocialController@create',
                ]
            );
            Route::delete(
                '/obras-sociales/{obrasocial}',
                [
                    'as' => 'obrasSociales.delete',
                    'uses' => 'ObraSocialController@delete',
                ]
            );
            Route::put(
                '/obras-sociales/{obrasocial}',
                [
                    'as' => 'obrasSociales.put',
                    'uses' => 'ObraSocialController@put',
                ]
            );
        });
    });

    // Prestaciones
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::middleware('admin')->group(function () {
            Route::get(
                '/prestaciones',
                [
                    'as' => 'prestaciones.list',
                    'uses' => 'PrestacionController@getList',
                ]
            );
            Route::post(
                '/prestaciones',
                [
                    'as' => 'prestaciones.create',
                    'uses' => 'PrestacionController@create',
                ]
            );
            Route::delete(
                '/prestaciones/{prestacion}',
                [
                    'as' => 'prestaciones.delete',
                    'uses' => 'PrestacionController@delete',
                ]
            );
            Route::put(
                '/prestaciones/{prestacion}',
                [
                    'as' => 'prestaciones.put',
                    'uses' => 'PrestacionController@put',
                ]
            );
        });
    });


    // Turnos
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::put(
            '/turnos/{turno}',
            [
                'as' => 'turnos.put',
                'uses' => 'TurnoController@put',
            ]
        );
        Route::put(
            '/turnos/{turno}/liberar',
            [
                'as' => 'turnos.liberar',
                'uses' => 'TurnoController@liberar',
            ]
        );
        Route::get(
            '/turnos/disponibles',
            [
                'as' => 'turnos.getDisponibles',
                'uses' => 'TurnoController@getDisponibles',
            ]
        );
        Route::middleware('admin')->group(function () {
            Route::get(
                '/turnos',
                [
                    'as' => 'turnos.list',
                    'uses' => 'TurnoController@getList',
                ]
            );
            Route::post(
                '/turnos',
                [
                    'as' => 'turnos.create',
                    'uses' => 'TurnoController@create',
                ]
            );
            Route::delete(
                '/turnos/{turno}',
                [
                    'as' => 'turnos.delete',
                    'uses' => 'TurnoController@delete',
                ]
            );
        });
    });

     // Pacientes

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get(
            '/pacientes/turnos',
            [
                'as' => 'pacientes.turnos',
                'uses' => 'PacienteController@getTurnos',
            ]
        );
        Route::middleware('admin')->group(function () {
            Route::get(
                '/pacientes',
                [
                    'as' => 'pacientes.list',
                    'uses' => 'PacienteController@getList',
                ]
            );
            Route::get(
                '/pacientes/{paciente}/historias',
                [
                    'as' => 'pacientes.listHistorias',
                    'uses' => 'PacienteController@getHistorias',
                ]
            );
            Route::get(
                '/pacientes/{paciente}/historias/download',
                [
                    'as' => 'pacientes.download',
                    'uses' => 'PacienteController@download',
                ]
            );
        });
    });

    // Prestaciones
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::middleware('admin')->group(function () {
            Route::get(
                '/historias-clinicas',
                [
                    'as' => 'historiasClinicas.list',
                    'uses' => 'HistoriaClinicaController@getList',
                ]
            );
            Route::post(
                '/historias-clinicas',
                [
                    'as' => 'historiasClinicas.create',
                    'uses' => 'HistoriaClinicaController@create',
                ]
            );
            Route::delete(
                '/historias-clinicas/{historiaclinica}',
                [
                    'as' => 'historiasClinicas.delete',
                    'uses' => 'HistoriaClinicaController@delete',
                ]
            );
            Route::put(
                '/historias-clinicas/{historiaclinica}',
                [
                    'as' => 'historiasClinicas.put',
                    'uses' => 'HistoriaClinicaController@put',
                ]
            );
        });
    });

    // Prestaciones
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::middleware('admin')->group(function () {
            Route::get(
                '/pagos',
                [
                    'as' => 'pagos.list',
                    'uses' => 'PagoController@getList',
                ]
            );
        });
        Route::post(
            '/pagos',
            [
                'as' => 'pagos.registrar',
                'uses' => 'PagoController@registrar',
            ]
        );
    });
});
