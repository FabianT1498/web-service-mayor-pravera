<?php

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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => ['auth']], function () {

	/********           VISIT         **********/
	Route::resource('visitas', 'VisitController')->except(['destroy', 'index']);
	Route::get('mis-visitas/{status?}', 'VisitController@myVisits')->name('mis_visitas');
	Route::put('visita/{id}/anular', 'VisitController@denyVisit')->name('visitas.denyVisit');
	Route::put('visita/{id}/confirmar', 'VisitController@confirmVisit')->name('visitas.confirmVisit');
	Route::post('/visitas-por-confirmar', 'VisitController@getVisitsByConfirm');
});

require __DIR__.'/auth.php';
