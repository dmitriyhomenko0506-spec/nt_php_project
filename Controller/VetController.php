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
        $Vet = $clinicData[0];

        $clinicData = Clinic::where("id = {$Vet->clinic_id}")
            ->getModels();

        // Извлекаем объект клиники из массива по нулевому индексу
        $clinic = $clinicData[0] ?? null;

        // Вызываем метод render, передавая имя шаблона и массив с переменными
        View::render('vet/vet', [
            'name' => $_SESSION['user']['name'] ?? 'Врач',
            'email' => $_SESSION['user']['email'] ?? '',
            'status' => $_SESSION['user']['status'] ?? 0,
            'vet' => $Vet,
            'clinic' => $clinic
        ]);
    }


    public function createAppointmentPage(int $id): void
    {
        $clinicsData = Clinic::with('vet.appointment')
            ->where("id = {$id}")
            ->getModels();

        $Vet_id = Vet::findBy('name', $_SESSION['user']['name']);

        View::render('vet/create_appointment', [
            'clinics' => $clinicsData,
            'clinic_id' => $id,
            'vet_id' => $Vet_id[0]['id']
        ]);
    }

    public function createAppointment(int $id): void
    {
        if (empty($_POST)) {
            exit;
        }
        //dd($_POST);

        if (isset($_POST['ownerName']) && ($_POST['ownerPhone'])) {
            $dataOwner = [
                'name' => $_POST['ownerName'],
                'phone' => trim((int)$_POST['ownerPhone'])
            ];

            $Usser =  Pet_owners::findBy('phone', $_POST['ownerPhone']);

            if (!empty($Usser)) {
                $createOwner = true;
            } else {
                $createOwner = Pet_owners::create($dataOwner);
            }

            if ($createOwner === true) {
                $OwnerID =  Pet_owners::where("name = '{$_POST['ownerName']}' ORDER BY id DESC LIMIT 1")->get();

                $dataPet = [
                    'owner_id' => (int)$OwnerID[0]['id'],
                    'name' => $_POST['petName'],
                    'species' => $_POST['petType']
                ];

                /* $UsserPet =  Pet::findBy('name', $_POST['petName']);
                if (!empty($UsserPet)) {
                    $createPet  = true;
                } else {
                    $createPet = Pet::create($dataPet);
                }*/

                $createPet = Pet::create($dataPet);
                if ($createPet === true) {
                    $PetID =  Pet::where("name = '{$_POST['petName']}' ORDER BY id DESC LIMIT 1")->get();

                    $dataAppointments = [
                        'clinic_id' => (int)$_POST['clinic_id'],
                        'vet_id' => (int)$_POST['vet_id'],
                        'pet_id' => $PetID[0]['id'],
                        'scheduled_for' => $_POST['visitDate'] . ' ' . $_POST['visitTime'],
                        'status' => 'confirm'
                    ];

                    $createAppointments = Appointments::create($dataAppointments);
                    if ($createAppointments === true) {
                        header('Location: /clinic/admin/vet/' . (int)$_POST['vet_id']);
                        exit;
                    }
                }
            }
        }
    }

    public function deleteAppointment(int $id): void
    {
        $result = Appointments::findBy('id', $id);
        if (!empty($result)) {
            Appointments::deleteByColumn('id', $id);
            header('Location: /clinic/admin/vet/' . $result[0]['vet_id']);
            exit;
        }
    }

    public function confirmAppointment(int $id): void
    {

        $result = Appointments::findBy('id', $id);
        if (!empty($result)) {
            $dataApp = [
                'status' => 'close'
            ];
            Appointments::update($dataApp, $id);
            header('Location: /clinic/admin/vet/' . $result[0]['vet_id']);
            exit;
        }
    }
}
