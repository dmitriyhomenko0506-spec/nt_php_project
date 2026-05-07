<?php

// Функция checkEven
function checkEven (int $number){
    if ($number % 2 === 0){
        $result = 1;} else {
            $result = 0;
        }
   return $result; 
}

//Функция gradeLetter
function gradeLetter (int $number){

    if ($number > 100 ){
        $result = "A";
    } elseif ($number <= 100 and $number >= 90){
        $result = "A";
    } elseif ($number <= 89 and $number >= 80) {
        $result = "B";
    } elseif ($number <= 79 and $number >= 70) {
        $result = "C";
    } elseif ($number <= 69 and $number >= 60) {
        $result = "D";
    } else {
        $result = "F";
    }

    return $result; 
}

// Функция passwordStrength
function passwordStrength ($str){

    $count = mb_strlen($str);

    if($count >= 10) {$result = "strong";}
    elseif ($count >= 7) {$result = "medium";}
    elseif ($count <= 6) {$result = "weak";}

    return $result;
}

// Функция sumPositive
function sumPositive (array $data){
  
    if(isset($data))
    {
        $dataNumber = array_filter($data, function($n) {
            return $n > 0;
        });
         
        if(empty($dataNumber)){
            $result = 0;
        } else {
            $result = array_sum($dataNumber);
        }
    }

    return $result;
}