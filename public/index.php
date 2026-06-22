<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Говорим PHP, какие именно классы мы будем использовать ниже
use App\Route\Route;
use App\Controller\IndexController;
use App\Controller\AboutController;
use App\Controller\UserController;
use App\Controller\ContactController;

// Регистрируем страницу "О нас" через контроллер
// Выражение AboutController::class просто возвращает строку 'AboutController'
Route::get('/clinic/', [IndexController::class, 'onePage']);
Route::get('/clinic/about', [AboutController::class, 'aboutPage']);
Route::get('/clinic/user', [UserController::class, 'all']);
Route::get('/clinic/user/{id}', [UserController::class, 'show']);
Route::get('/clinic/contact', [ContactController::class, 'contactPage']);
Route::post('/clinic/contact', [ContactController::class, 'submitForm']);

// Запускаем 
Route::dispatch();