<?php

class RGB {

    public int $red;
    public int $green;
    public int $blue;

    public function __construct (int $red, int $green, int $blue)
    {
      $this->red = $red;
      $this->green = $green;
      $this->blue = $blue;
    }

    public static function Rand ()
    {
       $red = random_int(0, 255);
       $green = random_int(0, 255);
       $blue = random_int(0, 255);

       return new self($red, $green, $blue);
    }

}

$color = RGB::Rand();

var_dump($color);

/*
$color = new RGB(100, 100, 100);
$colorRand = $color->Rand();
var_dump($colorRand);
*/

class ValueObject {


     //Значение = свойства
     private int $red;   
     private int $green;
     private int $blue;

     public function __construct(int $red, int $green, int $blue)
     {
         $this->setRed($red);
         $this->setGreen($green);
         $this->setBlue($blue);
     }

     /// get  ---- отдаем значение (set)

      public function getRed(){
        return $this->red;
     }
    
      public function getGreen(){
       return $this->green;
     }

      public function getBlue(){
         return $this->blue;
     }

   
     ///set 
     
    public function setRed($red){
      if ($red < 0  OR $red > 255 ) {
        throw new Exception ("Error red");
      } else {
        $this->red = $red;
      }
    }

    public function setGreen($green){
      if ($green < 0  OR $green > 255 ) {
        throw new Exception ("Error green");
      } else {
        $this->green = $green;
      }
   }

    public function setBlue($blue){
      if ($blue < 0  OR $blue > 255 ) {
        throw new Exception ("Error blue");
      } else {
        $this->blue = $blue;
      }
   }
    

   /*
   public function mix (int $newRed, int $newGreen, int $newBlue){
     $mixRed = (int) ($this->red + $newRed) / 2;
     $mixGreen = (int) ($this->green + $newGreen) / 2;
     $mixBlue = (int) ($this->blue + $newBlue) / 2;

     return new self ($mixRed, $mixGreen, $mixBlue);
   }
  */


   public function comparison (self $OtherObject )
   {
       if (
            $this->red == $OtherObject->getRed() AND
            $this->green == $OtherObject->getGreen() AND
            $this->blue == $OtherObject->getBlue() 
         ) 
         { 
          return true; } else {
          return false;
         }
   }

   public function mix (self $OtherColor)
   {
     
     $mixRed = (int) ($this->red + $OtherColor->getRed() ) / 2;
     $mixGreen = (int) ($this->green + $OtherColor-> getGreen()) / 2;
     $mixBlue = (int) ($this->blue + $OtherColor-> getBlue()) / 2;

     return new self ($mixRed, $mixGreen, $mixBlue);
   }
}



$color = new ValueObject(250,250,250);
$OtherObject = new ValueObject(200,150,250);
$OtherColor = new ValueObject(100,100,100);

var_dump($color->comparison($OtherObject)); 

$mixColor = $color->mix($OtherColor);
//$mixColor ->getRed();
//$mixColor ->getGreen();
//$mixColor ->getBlue();

var_dump($mixColor);

//$mixRed = $color->mix(100, 100, 100);
//var_dump ($mixRed);
