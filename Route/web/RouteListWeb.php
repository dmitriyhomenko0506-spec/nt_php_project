<?php

use App\Route\Route;
//use App\Route\RouteApi;
use App\Middleware\UserIdMiddleware;
use App\Controller\IndexController;
use App\Controller\AboutController;
use App\Controller\UserController;
use App\Controller\ContactController;

// WEB
Route::get('/clinic/', [IndexController::class, 'onePage']);
Route::get('/clinic/about', [AboutController::class, 'aboutPage']);
Route::get('/clinic/user', [UserController::class, 'all']);
Route::get('/clinic/user/{id}', [UserController::class, 'show'], UserIdMiddleware::class);
Route::get('/clinic/contact', [ContactController::class, 'contactPage']);
Route::post('/clinic/contact', [ContactController::class, 'submitForm']);


Route::dispatch();
