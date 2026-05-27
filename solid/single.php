<?php

/*
За принципом Single Responsibility проведіть рефакторинг класу так щоб у вас був клас 
для роботи з продуктом,
 для обробки продукту 
 та для виведення продукту.
Без реалізації коду методів
*/

class Product
{
    public function get($name)
    {
    }
    public function set($name, $value)
    {
    }
}

class ProductEditer
{
    public function save()
    {
    }
    public function update()
    {
    }
    public function delete()
    {
    }
}

class ProductView
{
    public function show()
    {
    }
    public function print()
    {
    }
}