<?php

trait GetArguments
{
    public function __call(string $property, array $arguments)
    {

        if (property_exists($this, $property)) {
            $this->$property = $arguments[0];
            return $this;
        } else {
            throw new \Exception(" ERROR - >" . $property . "Not found ");
        }

    }

    public function build()
    {
        return $this;
    }
}

class Contact
{
    use GetArguments;

    private $name;
    private $surname;
    private $email;
    private $phone;
    private $address;
}


try {

    $contact = new Contact();
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
