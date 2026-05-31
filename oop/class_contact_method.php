<?php

class Contact_method
{

    private $name;
    private $surname;
    private $email;
    private $phone;
    private $address;

    public function __construct($name = '', $surname = '', $email = '', $phone = '', $address = '')
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
    }

    private function name($name): self
    {
        $this->name = $name;
        return $this;
    }
    private function surname($surname): self
    {
        $this->surname = $surname;
        return $this;
    }
    private function email($email): self
    {
        $this->email = $email;
        return $this;
    }
    private function phone($phone): self
    {
        $this->phone = $phone;
        return $this;
    }
    private function address($address): self
    {
        $this->address = $address;
        return $this;
    }
    public function __call($method, $args)
    {
        // Проверяем существование приватного метода
        if (method_exists($this, $method)) {
            // ВЫЗЫВАЕМ приватный метод и возвращаем то, что он вернул ($this)
            return call_user_func_array([$this, $method], $args);
        } else {
            throw new Exception('Method ' . $method . ' not found');
        }
    }

    public function build()
    {
        return $this;
    }
}


try {

    $contact = new Contact_method();
    $NewContact = $contact->phone('050 - 000 - 111 - 22')
        ->name("John")
        ->surname("Surname")
        ->email("jonh@email.com")
        ->address("Some address")
        ->build();

    print_r($NewContact);

} catch (\Exception $e) {
    print_r($e->getMessage());
}
