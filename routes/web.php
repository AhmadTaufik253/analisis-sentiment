<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluasiModelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportDataController;
use App\Http\Controllers\LabellingController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\SplitDataController;
use App\Http\Controllers\ModellingController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\VisualisasiController;

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

Route::redirect('/','/login');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard-chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

Route::get('/import-data', [ImportDataController::class, 'index'])->name('import-data');
Route::post('/import-data', [ImportDataController::class, 'store'])->name('import-data.store');
Route::delete('import-data', [ImportDataController::class, 'destroy'])->name('import-data.destroy');

Route::get('/preprocessing', [PreprocessingController::class, 'index'])->name('preprocessing');
Route::post('/preprocessing', [PreprocessingController::class, 'process'])->name('preprocessing.process');
Route::delete('preprocessing', [PreprocessingController::class, 'destroy'])->name('preprocessing.destroy');

Route::get('/labelling', [LabellingController::class, 'index'])->name('labelling');
Route::post('/labelling', [LabellingController::class, 'process'])->name('labelling.process');
Route::delete('labelling', [LabellingController::class, 'destroy'])->name('labelling.destroy');

Route::get('/split-data', [SplitDataController::class, 'index'])->name('split-data');
Route::post('/split-data', [SplitDataController::class, 'process'])->name('split-data.process');
Route::delete('split-data', [SplitDataController::class, 'destroy'])->name('split-data.destroy');

Route::get('/modelling', [ModellingController::class, 'index'])->name('modelling');
Route::post('/modelling', [ModellingController::class, 'process'])->name('modelling.process');
Route::delete('/modelling/{id}', [ModellingController::class, 'destroy'])->name('modelling.destroy');

Route::get('/testing', [TestingController::class, 'index'])->name('testing');
Route::post('/testing', [TestingController::class, 'process'])->name('testing.process');

Route::get('/visualisasi-data', [VisualisasiController::class, 'index'])->name('visualisasi-data');

Route::get('/evaluasi-model', [EvaluasiModelController::class, 'index'])->name('evaluasi-model');
