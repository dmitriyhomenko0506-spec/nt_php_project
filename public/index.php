<?php

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . '/vendor/autoload.php';


try {

    // Подключаем Router для WEB
    require_once BASE_DIR . '/Route/web/RouteListWeb.php';
    // Подключаем Router для API
    //require_once BASE_DIR . '/Route/api/RouteListApi.php';


} catch (\Throwable $th) {

    throw $th;

}







