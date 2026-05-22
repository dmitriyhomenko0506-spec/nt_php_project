<?php

class User {

 // Приватные свойства
  private string $name;
  private int $age;
  private string $email ='';


//Set - private
  private function setName (string $name)
  {
      $this->name = $name;
  }
  
 private function setAge (int $age)
  {
      $this->age = $age;
  }

  //get - private
  private function getName()
  {
    return $this->name;
  }

  private function getAge()
  {
    return $this->age;
  }

  //массив пользователя
  private function getInfoUser(): array
  {

    $array = [
        'name' => $this->name ?? null,
        'age' => $this->age ?? null,
        'email' => $this->email
    ];

    return $array;
  }


 //call проверяет если такой метод, нет -> ошибка, да -> обходит private и возвращает результат работы метода.
  public function __call(string $method, array $args)
  {

    if (!method_exists($this, $method)){
        throw new Exception ('Method -> '.$method.' not found');  
    } 

      return call_user_func_array([$this, $method], $args);
  }

}

try {
   
$user = new User;
$userName = $user->setName('Dmitriy');
$userGetName = $user->getName();
//var_dump($userGetName);

$userAge = $user->setAge(35);
$userGetAge = $user->getAge();
//var_dump($userGetAge);

$allInfoUser = $user->getInfoUser();
var_dump($allInfoUser);

//$userEmail = $user->setEmail('test@test.com');  // Method -> setEmail not found


} catch (Throwable $error) {
    
    //Throwable -> выдает все ошибки работы скрипта. // Method -> setEmail not found

    echo $error->getMessage();
}
