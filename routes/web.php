<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController ;

Route::get('/',             [ProyectoController::class, 'index'])->name('Proyecto.index');

Route::post('/inscripcion', [ProyectoController::class,'inscribirse'])->name('Proyecto.inscribir');

Route::get( '/login',       [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',       [\App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::post('/logout',      [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/register',     [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('registro');
Route::post('/register',    [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('registrar');

