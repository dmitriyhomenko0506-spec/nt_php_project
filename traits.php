<?php

trait Onew {

public function test (){
    return '1';
   }

} 

trait Two {

public function test (){
    return '2';
}
}

trait Three {

public function test (){
        return '3';
    }
}

class AllTraint {

use Onew, Two, Three {
   
    //Делаем Onew основным.
    Onew::test insteadof Two, Three;
    
    Onew::test as testOne;  // 1
    Two::test as testTwo;  //2
    Three::test as testThree; //3 
}


public function QuantitySumm (): float
{
    $result = $this->testOne() + $this->testTwo() +  $this->testThree();
    return $result;
}

}

$result = new AllTraint;
var_dump($result->QuantitySumm());

