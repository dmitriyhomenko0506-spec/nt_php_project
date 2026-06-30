<?php

namespace App\Class;

use PDO;

class DB
{
    static protected $PDO = null;

    static public function connect()
    {
        if (static::$PDO === null) {

            require_once BASE_DIR . '/Config/ConfigConnectDB.php';

            $DB_HOST = DB_HOST;
            $DB_NAME = DB_NAME;
            $DB_USER = DB_USER;
            $DB_PASS = DB_PASS;

            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Выбрасывать исключения при ошибках
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Возвращать данные в виде ассоциативных массивов
            );

            $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;";

            static::$PDO = new PDO($dsn, $DB_USER, $DB_PASS, $options);
        }
        return static::$PDO;
    }

}