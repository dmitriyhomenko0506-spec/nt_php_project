<?php

use App\Route\Route;
use App\Middleware\UserIdMiddleware;
use App\Controller\IndexController;
use App\Controller\AboutController;
use App\Controller\UserController;
use App\Controller\ContactController;

// API
Route::get('/api/clinic/about', [AboutController::class, 'aboutPage']);
Route::post('/api/clinic/contact', [ContactController::class, 'submitForm']);
Route::put('/api/clinic/user', [UserController::class, 'all']);


Route::dispatch();
