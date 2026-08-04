<?php

use App\Route\Route;

//* Middleware  */
use App\Middleware\UserIdMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CheckAuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\ClinicMiddleware;
use App\Middleware\DoctorMiddleware;


//* Controller */
use App\Controller\IndexController;
use App\Controller\AboutController;
use App\Controller\UserController;
use App\Controller\ContactController;
use App\Controller\AuthController;
use App\Controller\AdminController;
use App\Controller\VetClinicController;
use App\Controller\VetController;


// Web /// front 
Route::get('/clinic/', [IndexController::class, 'onePage']);
Route::get('/clinic/about', [AboutController::class, 'aboutPage']);
Route::get('/clinic/user', [UserController::class, 'all']);
Route::get('/clinic/user/{id}', [UserController::class, 'show'], UserIdMiddleware::class);
Route::get('/clinic/contact', [ContactController::class, 'contactPage']);


//Web // login admin
Route::get('/clinic/login', [AuthController::class, 'index'], AuthMiddleware::class);
Route::post('/clinic/login', [AuthController::class, 'submit'], AuthMiddleware::class);
Route::get('/clinic/logout', [AuthController::class, 'logout'], AuthMiddleware::class);

//web // admin / clinic
Route::get('/clinic/admin/vet-clinic/{id}', [VetClinicController::class, 'vetClinic'], ClinicMiddleware::class);
Route::get('/clinic/admin/doctor/edit/{id}', [VetClinicController::class, 'editeDoctor'], ClinicMiddleware::class);
Route::post('/clinic/admin/doctor/update', [VetClinicController::class, 'updateDoctor']);
Route::get('/clinic/admin/doctor/delete/{id}', [VetClinicController::class, 'deleteDoctor'], ClinicMiddleware::class);
Route::get('/clinic/admin/doctor/create/clinic/{id}', [VetClinicController::class, 'createDoctorPage'], ClinicMiddleware::class);
Route::post('/clinic/admin/doctor/create/clinic/{id}', [VetClinicController::class, 'createDoctor']);
///Route::get('/clinic/admin/appointment/create/clinic/{id}');


//web // admin / doctor
Route::get('/clinic/admin/vet/{id}', [VetController::class, 'vet'], DoctorMiddleware::class);
///Route::get('/clinic/admin/appointment/create/vet/{id}');


//web //admin/admin
Route::get('/clinic/admin/admin', [AdminController::class, 'admin'], AdminMiddleware::class);
Route::get('/clinic/admin/admin/create_clinic', [AdminController::class, 'createClinicPage'], AdminMiddleware::class);
Route::post('/clinic/admin/admin/create_clinic', [AdminController::class, 'createClinic']);
Route::get('/clinic/admin/admin/doctor/create', [AdminController::class, 'createDoctorPage'], AdminMiddleware::class);
Route::post('/clinic/admin/admin/doctor/create', [AdminController::class, 'createDoctor']);
Route::get('/clinic/admin/admin/doctor/delete/{id}', [AdminController::class, 'deleteDoctor'], AdminMiddleware::class);
Route::get('/clinic/admin/admin/doctor/edit/{id}', [AdminController::class, 'editeDoctor'], AdminMiddleware::class);
Route::post('/clinic/admin/admin/doctor/update', [AdminController::class, 'updateDoctor']);
Route::get('/clinic/admin/view/{id}', [AdminController::class, 'showClinicInfo'], AdminMiddleware::class);


Route::dispatch();
