<?php

//Відрефакторити приклад по принципу Dependency inversion:


interface ConnectToBd
{
    public function getData();
}

class Mysql implements ConnectToBd
{

    private string $host;
    private string $user;
    private string $password;
    private string $dbname;

    public function __construct(string $host, string $user, string $password, string $dbname)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    public function getData()
    {
    }
}


class Controller
{
    private $adapter;

    public function __construct(ConnectToBd $database)
    {
        $this->adapter = $database;
    }

    public function getData()
    {

        return $this->adapter->getData();
    }

}