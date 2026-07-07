<?php

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . '/vendor/autoload.php';

use App\Model\Clinic;


try {

    $clinics = Clinic::select()->where('id = 2')->get();
    //dump($clinics);

    $singleClinic = Clinic::find(1);
    //dump($singleClinic);

    $findBy = Clinic::findBy('city', 'Одесса');
    dump($findBy);


    // Подключаем Router для WEB
    //require_once BASE_DIR . '/Route/web/RouteListWeb.php';
    // Подключаем Router для API
    //require_once BASE_DIR . '/Route/api/RouteListApi.php';


} catch (\Throwable $th) {

    throw $th;
}
