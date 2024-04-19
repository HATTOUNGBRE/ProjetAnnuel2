<?php

use App\Http\Controllers\InscriptionController;
use Illuminate\Support\Facades\Route;

use App\Models\Inscription;


Route::get('/', function () {
    return view('welcome');
});


Route::resource('inscriptions', InscriptionController::class);