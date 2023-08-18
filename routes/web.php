<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

//Home page
Route::get('/', [JobController::class, 'index']);

//Show Create job from
Route::get('/jobs/create', [JobController::class, 'create'])->middleware('auth');

//Create and store job
Route::post('/jobs', [JobController::class, 'store'])->middleware('auth');

//Edit job
Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->middleware('auth');

//Submit to update job
Route::put('/jobs/{job}', [JobController::class, 'update']);

//Delete job
Route::delete('/jobs/{job}', [JobController::class, 'delete'])->middleware('auth');

//Manage jobs
Route::get('/jobs/manage', [JobController::class, 'manage'])->middleware('auth');

//Show single job
Route::get('/jobs/{job}', [JobController::class, 'show']);

//Show Register form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

//Create and store user
Route::post('/users', [UserController::class, 'store']);

//Logout user
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

//Show Login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

//Authenticate user
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

//Show profile form
Route::get('/users/profile', [UserController::class, 'profile'])->middleware('auth');

//Submit to update user profile
Route::put('/users/profile', [UserController::class, 'update']);
