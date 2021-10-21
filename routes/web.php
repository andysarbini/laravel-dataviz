<?php

use App\Http\Controllers\BasicChartController;
use App\Http\Controllers\OccupancyRateController;
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

Route::get('/basic-chart', [BasicChartController::class, 'index']);
Route::get('/basic-chart/bar', [BasicChartController::class, 'barChart']);
Route::get('/basic-chart/donut', [BasicChartController::class, 'donutChart']);
Route::get('/basic-chart/radial', [BasicChartController::class, 'radialChart']);
Route::get('/basic-chart/polar', [BasicChartController::class, 'polarAreaChart']);
Route::get('/basic-chart/line', [BasicChartController::class, 'lineChart']);
Route::get('/basic-chart/area', [BasicChartController::class, 'areaChart']);
Route::get('/basic-chart/heat-map', [BasicChartController::class, 'heatMapChart']);
Route::get('/basic-chart/radar', [BasicChartController::class, 'radarChart']);

Route::get('/occupancy-rate', [OccupancyRateController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/occupancy-rate/room', [OccupancyRateController::class, 'room']);

Route::get('/occupancy-rate/bed', [OccupancyRateController::class, 'bed']);