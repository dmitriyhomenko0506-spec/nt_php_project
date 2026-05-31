<?php

interface TypeCar
{
    public function get_classe(): string;
    public function get_price(): int;
}



// Mysql 

abstract class AutoCar
{

    //select * from AutoCar where class = 'ecomon';
    protected function econom(): array
    {
        return [
            "auto" => "Renault: Logan",
            "driver" => "Иванов Иван Иванович",
            "phone" => "050-000-00-00",
            "number_car" => "AA12342"
        ];

    }

    //select * from AutoCar where class = 'standart';
    protected function standart(): array
    {
        return [
            "auto" => "Volkswagen: Polo",
            "driver" => "Петров Петр Петрович",
            "phone" => "050-000-00-00",
            "number_car" => "AA98745"
        ];

    }

    //select * from AutoCar where class = 'business';
    protected function business(): array
    {
        return [
            "auto" => "Toyota Camry",
            "driver" => "Федоров Федор Федорович",
            "phone" => "050-000-00-00",
            "number_car" => "AA32564"
        ];

    }

}



class EconomCar extends AutoCar implements TypeCar
{

    private string $classe = "econom";
    private array $autoexecutor;
    private int $price = 100; // Цена за км
    public int $km;

    public function __construct(int $km)
    {
        $this->price = $this->price * $km;
        $this->km = $km;
        $this->autoexecutor = parent::econom();
    }

    public function get_classe(): string
    {
        return ($this->classe);
    }

    public function get_price(): int
    {
        return ($this->price);
    }
}

class StandartCar extends AutoCar implements TypeCar
{

    private string $classe = "standart";
    private array $autoexecutor;
    private int $price = 200; // Цена за км
    public int $km;

    public function __construct(int $km)
    {
        $this->price = $this->price * $km;
        $this->km = $km;
        $this->autoexecutor = parent::standart();
    }

    public function get_classe(): string
    {
        return ($this->classe);
    }

    public function get_price(): int
    {
        return ($this->price);
    }
}

class BusinessCar extends AutoCar implements TypeCar
{

    private string $classe = "business";
    private array $autoexecutor;
    private int $price = 300; // Цена за км
    public int $km;

    public function __construct(int $km)
    {
        $this->price = $this->price * $km;
        $this->km = $km;
        $this->autoexecutor = parent::business();
    }

    public function get_classe(): string
    {
        return ($this->classe);
    }

    public function get_price(): int
    {
        return ($this->price);
    }
}

trait getCarType
{
    public function getCar(int $km): TypeCar
    {
        return match ($this->classe) {
            'econom' => new EconomCar($km),
            'standart' => new StandartCar($km),
            'business' => new BusinessCar($km),
            default => throw new Exception("Неизвестный тип такси: " . $this->classe)
        };
    }
}


final class Taxi
{
    use getCarType;

    public string $classe;

    public function __construct(string $class)
    {
        $this->classe = strtolower(trim($class));
    }
}


try {

    $type = $_GET['classe'] ?? 'business';

    $taxi = new Taxi($type);
    $car = $taxi->getCar(15); // Едем 15 км
    print_r($car);

} catch (Exception $error) {
    echo "Ошибка заказа: " . $error->getMessage();
}