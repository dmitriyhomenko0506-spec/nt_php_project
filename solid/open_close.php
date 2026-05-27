<?php

/*
Open/Closed Principle (Принцип открытости/закрытости) 
Расширение метода оплаты
*/

interface MethodPay
{
    public function Method();
}

class MethodPayPrivat implements MethodPay
{
    public function Method(): string
    {
        return 'Privat';
    }
}

class MethodPayMono implements MethodPay
{

    public function Method(): string
    {
        return 'Mono';
    }
}

final class OrderPayCheck
{
    private string $order_name;
    private float $price;
    private int $quantity;
    private MethodPay $type;

    public function __construct(string $order_name, float $price, int $quantity, MethodPay $type)
    {
        $this->order_name = $order_name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->type = $type;

    }

    public function Buy(): array
    {
        return [
            'Название товара' => $this->order_name,
            'Цена' => $this->price,
            'Количество' => $this->quantity,
            'Итого к оплате' => $this->price * $this->quantity,
            'Способ оплаты' => $this->type->Method()
        ];
    }

}



$monoPayment = new MethodPayMono();
$order = new OrderPayCheck('Детская коляска', 150, 2, $monoPayment);
$check = $order->Buy();

var_dump($check);
