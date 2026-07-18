<?php

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . '/vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Model\Clinic;
use App\Model\Vet;

try {

    $quary = Clinic::with('vet.appointment.pet.owner')->getModels();
    //$quary = Vet::with('appointment.pet.owner')->getModels();

    dd($quary);

    // Подключаем Router для WEB
    require_once BASE_DIR . '/Route/web/RouteListWeb.php';
    // Подключаем Router для API
    //require_once BASE_DIR . '/Route/api/RouteListApi.php';


} catch (\Throwable $th) {

    throw $th;
}
