<?php


interface SmartDevice
{
    public function turnOn();
    public function turnOff();

}

interface Lamp
{
    public function setBrightness(int $level);
}

interface Cond
{
    public function setTemperature(int $degrees);
}

class SmartBulb implements SmartDevice, Lamp
{
    public function turnOn()
    {
    }
    public function turnOff()
    {
    }
    public function setBrightness(int $level)
    {
    }

}

class Climat implements SmartDevice, Cond
{
    public function turnOn()
    {
    }
    public function turnOff()
    {
    }
    public function setTemperature(int $degrees)
    {
    }
}