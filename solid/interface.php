<?php

//Відрефакторити приклад по принципу Interface segregation:


interface BirdFly
{
    public function fly();
}

interface BirdEat
{
    public function eat();
}

interface BirdSleep
{
    public function sleep();
}

class Swallow implements BirdFly, BirdEat, BirdSleep
{

    public function fly()
    {
    }
    public function eat()
    {
    }
    public function sleep()
    {
    }

}

class Ostrich implements BirdEat, BirdSleep
{
    public function eat()
    {
    }
    public function sleep()
    {
    }

}
