<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Models\Calculation;
use Illuminate\Support\Facades\Route;

Route::get('report/{calculation}', fn (Calculation $calculation) => view('report', compact('calculation')));
