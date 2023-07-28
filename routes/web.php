<?php

use App\Http\Controllers\SetTimeController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('user', 'UserController');
Route::resource('attendance', 'AttendanceController')->only(['index', 'show']);


Route::get('/set-time', [SetTimeController::class, 'index'])
    ->name('set-time')
    ->middleware('auth', 'is_admin');

Route::post('/save-time', [SetTimeController::class, 'saveTime'])
    ->name('save-time')
    ->middleware('auth', 'is_admin');
// Route::post('/save-time', [SetTimeController::class, 'saveTime'])->name('save-time');

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::post('/filter_data', [AttendanceController::class, 'filter'])->name('filter');





Route::get('/generate-pdf', [AttendanceController::class, 'generatePDF'])->name('generate.pdf');
