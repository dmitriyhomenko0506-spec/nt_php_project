<?php

use App\Route\Route;
use App\Middleware\UserIdMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CheckAuthMiddleware;
use App\Controller\IndexController;
use App\Controller\AboutController;
use App\Controller\UserController;
use App\Controller\ContactController;
use App\Controller\AuthController;
use App\Controller\AdminController;
use App\Controller\VetClinicController;
use App\Controller\VetController;


// WEB
Route::get('/clinic/', [IndexController::class, 'onePage']);
Route::get('/clinic/about', [AboutController::class, 'aboutPage']);
Route::get('/clinic/user', [UserController::class, 'all']);
Route::get('/clinic/user/{id}', [UserController::class, 'show'], UserIdMiddleware::class);
Route::get('/clinic/contact', [ContactController::class, 'contactPage']);
Route::get('/clinic/login', [AuthController::class, 'index']);
Route::post('/clinic/login', [AuthController::class, 'submit'], AuthMiddleware::class);
Route::get('/clinic/logout', [AuthController::class, 'logout']);
Route::get('/clinic/admin', [AdminController::class, 'admin'], CheckAuthMiddleware::class);
Route::get('/clinic/vet-clinic', [VetClinicController::class, 'vetClinic'], CheckAuthMiddleware::class);
Route::get('/clinic/vet', [VetController::class, 'vet'], CheckAuthMiddleware::class);

Route::dispatch();
