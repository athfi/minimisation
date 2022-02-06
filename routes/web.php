<?php

use App\Http\Controllers\MyProjectController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestProjectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use IU\PHPCap\RedCapProject;
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
    return redirect('/dashboard');
//    return view('welcome');
});

Route::post('/redcap/login',
    [TestProjectController::class, 'redcapLogin'] )->name('redcapLogin');

//Route::get('/testMini', [TestProjectController::class, 'randomise'] )->name('randomise');

Route::get('/redcap/login', function (Request $request) {
    return redirect('/dashboard');
});

Route::get('/test/randoms/{rollsNumber?}',  [TestProjectController::class, 'test_randoms'] )
    ->name('test-randoms');





Route::middleware(['auth:sanctum', 'verified'])->get('/redcap/logged',
    [TestProjectController::class, 'redcapLogged'] )->name('redcapLogged');


Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', function (){ return view('dashboard'); })
    ->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/old_project',
    [TestProjectController::class, 'index'] )->name('old_project');

Route::middleware(['auth:sanctum', 'verified'])->get('/metadata',
    [TestProjectController::class, 'metadata'] )->name('metadata');

Route::middleware(['auth:sanctum', 'verified'])->get('/record',
    [TestProjectController::class, 'records'] )->name('records');

Route::middleware(['auth:sanctum', 'verified'])->get('/record/{record_id?}',
    [TestProjectController::class, 'record'] )->name('record');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    //TODO change to post
    Route::get('/randomise/{record_id?}', [TestProjectController::class, 'randomise'] )
        ->name('randomise');

    Route::get('/minimisation', [TestProjectController::class, 'minimisation'] )
        ->name('minimisation');
});


//Projects
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    //Project
    Route::get('/project', [ProjectController::class, 'index'] )
        ->name('projects');
    Route::get('/project/create', [ProjectController::class, 'create'] )
        ->name('project-create');

    Route::get('/project/{project}', [ProjectController::class, 'show'] )
        ->name('project-show');

    Route::get('/project/{project}/config/minimisation', [MyProjectController::class, 'minimisationSetting'] )
        ->name('setting-minimisation');

    Route::get('/project/{project}/records', [MyProjectController::class, 'records'] )
        ->name('project-records');

    Route::get('/project/{project}/record/{id}', [MyProjectController::class, 'record'] )
        ->name('project-record');
});


