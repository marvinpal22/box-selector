<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoxController;
Route::get('/', function () {
    return view('welcome');
});

Route::post('/pack-items',[BoxController::class,'packItem']);
