<?php

/* 
   Liskov Substitution Principle
   Наследники должны дополнять, а не изменять поведение!
   Если классы по своей сути разные, но должны выполнять похожие действия, разделение через интерфейсы

 */



interface GetPrice
{
    public function getPrice();

}

class PhysicalProduct implements GetPrice
{
    public function getPrice()
    {
    }
    public function shipToAddress(string $address)
    {
    }
}

class DigitalProduct implements GetPrice
{
    public function getPrice()
    {
    }

    public function shipToEmail(string $email)
    {
    }
}