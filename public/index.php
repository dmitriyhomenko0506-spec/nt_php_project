<?php

require_once __DIR__ . '/../vendor/autoload.php';



try {

    // Подключаем Router для WEB
    require_once __DIR__ . '/../Route/web/RouteListWeb.php';
    // Подключаем Router для API
    //require_once __DIR__ . '/../Route/web/RouteListApi.php';


} catch (\Throwable $th) {

    throw $th;

}





