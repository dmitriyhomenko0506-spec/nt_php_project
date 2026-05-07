<?php

//Подключение функции
require_once __DIR__ . '/functions.php'; 


// Функция checkEven
$number = 7; // Устанавливаем число 
$numberResult = checkEven($number);
//print_r ($numberResult); //Выводим результат проверки на экран.


//Функция gradeLetter
$number = 105; // Устанавливаем число 
$numberResult = gradeLetter($number);
print_r ($numberResult); //Выводим результат проверки на экран.


// Функция passwordStrength
$password = "abcdefghi"; // Пишим пароль 
$passwordResult = passwordStrength($password);
//print_r ($passwordResult); //Выводим результат проверки на экран.


// Функция sumPositive
$ar = [25, 30, 50, 100]; //Пишим массив
$arResult = sumPositive($ar);
//print_r ($arResult); //Выводим результат проверки на экран.
