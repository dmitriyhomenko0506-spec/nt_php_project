<?php

class RGB 
{

    public int $red;
    public int $green;
    public int $blue;

    public function __construct (int $red, int $green, int $blue)
    {
      $this->red = $red;
      $this->green = $green;
      $this->blue = $blue;
    }

    public static function Rand (): self
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

class ValueObject 
{

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

    // get  ---- отдаем значение (set)
    public function getRed(): int
     {
        return $this->red;
     }
    
    public function getGreen(): int
      {
       return $this->green;
      }

    public function getBlue(): int
      {
         return $this->blue;
      }


   //validate(int $color)
    private function validate(int $color): bool
    {
       return $color >= 0 AND $color <= 255;
    }

   
    //set 
    public function setRed(int $red): void 
    {
       $this->validate($red) ? $this->red = $red : throw new Exception("Error red");
    }

    public function setGreen(int $green): void
    {
       $this->validate($green) ? $this->green = $green : throw new Exception("Error green");
    }

    public function setBlue(int $blue): void
    {
       $this->validate($blue) ? $this->blue = $blue : throw new Exception("Error blue");
    }
    

   /*
   public function mix (int $newRed, int $newGreen, int $newBlue){
     $mixRed = (int) ($this->red + $newRed) / 2;
     $mixGreen = (int) ($this->green + $newGreen) / 2;
     $mixBlue = (int) ($this->blue + $newBlue) / 2;

     return new self ($mixRed, $mixGreen, $mixBlue);
   }
  */


   public function comparison (self $OtherObject): bool
   {
       return  $this->red == $OtherObject->getRed() AND
               $this->green == $OtherObject->getGreen() AND
               $this->blue == $OtherObject->getBlue();
   }

   public function mix (self $OtherColor): self
   {

     $mixRed = (int) (($this->red + $OtherColor->getRed() ) / 2);
     $mixGreen = (int) (($this->green + $OtherColor-> getGreen()) / 2);
     $mixBlue = (int) (($this->blue + $OtherColor-> getBlue()) / 2);

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
