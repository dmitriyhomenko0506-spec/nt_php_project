<?php

namespace App\Controller;

use App\Class\View;
use App\Model\Clinic;
use App\Model\Appointments;
use App\Model\Vet;
use App\Model\Pet_owners;
use App\Model\Pet;
use App\Model\Roll;


class VetController
{
    public function vet(int $id): void
    {

        //dd($id);

        $clinicData = Vet::with('appointment.pet.owner')
            ->where("id = {$id}")
            ->getModels();

        // Так как getModels() возвращает массив, берем первую (и единственную найденную) клинику
        $Vet = $clinicData;

        // Вызываем метод render, передавая имя шаблона и массив с переменными
        View::render('vet/vet', [
            'name' => $_SESSION['user']['name'] ?? 'Врач',
            'email' => $_SESSION['user']['email'] ?? '',
            'status' => $_SESSION['user']['status'] ?? 0,
            'vet' => $Vet
        ]);
    }
}
