<?php

namespace App\Controller;

use App\Class\View;
use App\Model\Clinic;
use App\Model\Appointments;
use App\Model\Vet;
use App\Model\Pet_owners;
use App\Model\Pet;
use App\Model\Roll;


class VetClinicController
{
    public function vetClinic(int $id): void
    {

        //dd($id);

        $clinicData = Clinic::with('vet.appointment.pet.owner')
            ->where("id = {$id}")
            ->getModels();

        // Так как getModels() возвращает массив, берем первую (и единственную найденную) клинику
        $currentClinic = $clinicData[0];

        // Вызываем метод render, передавая имя шаблона и массив с переменными
        View::render('vet_clinic/vet-clinic', [
            'name' => $_SESSION['user']['name'] ?? 'Клиника',
            'email' => $_SESSION['user']['email'] ?? '',
            'status' => $_SESSION['user']['status'] ?? 0,
            'clinic' => $currentClinic
        ]);
    }

    public function createDoctorPage(int $id): void
    {
        $clinicsData = Clinic::with('vet')
            ->where("id = {$id}")
            ->getModels();

        View::render('vet_clinic/create_doctor', [
            'clinics' => $clinicsData,
            'clinic_id' => $id
        ]);
    }

    public function createAppointmentPage(int $id): void
    {
        $clinicsData = Clinic::with('vet.appointment')
            ->where("id = {$id}")
            ->getModels();

        //dd($clinicsData);

        View::render('vet_clinic/create_appointment', [
            'clinics' => $clinicsData,
            'clinic_id' => $id
        ]);
    }

    public function createAppointment(int $id): void
    {
        if (empty($_POST)) {
            header('Location: /clinic/admin/vet-clinic/' . $id . '?error=1');
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

                /*$UsserPet =  Pet::findBy('name', $_POST['petName']);
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
                        header('Location: /clinic/admin/vet-clinic/' . $id);
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
            header('Location: /clinic/admin/vet-clinic/' . $_SESSION['user']['clinic_id']);
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
            header('Location: /clinic/admin/vet-clinic/' . $_SESSION['user']['clinic_id']);
            exit;
        }
    }

    public function createDoctor(int $id): void
    {
        if (!empty($_POST)) {
            $name = trim($_POST['name']);
            $specialty = trim($_POST['specialty']);
            $clinic_id = (int)$id;
            $email = trim($_POST['email']);
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            header('Location: /clinic/admin/vet-clinic/' . $id . '?error=1');
            exit;
        }

        $queryRoll = Roll::findBy('email', $email);

        if (empty($queryRoll)) {
            $queryVet = Vet::findBy('name', $name);
            if (!empty($queryVet)) {
                header('Location: /clinic/admin/vet-clinic/' . $id . '?error=1');
                exit;
            }

            $dataVet = [
                'name' => $name,
                'specialty' => $specialty,
                'clinic_id' => $clinic_id
            ];

            $createVet = Vet::create($dataVet);
            if ($createVet == true) {

                $dataRoll = [
                    'clinic_id' => (int) $clinic_id,
                    'email' => $email,
                    'pass' => $pass,
                    'name' => (string) $name,
                    'status' => (int) 2
                ];

                $createClinicRoll = Roll::create($dataRoll);
                if ($createClinicRoll == true) {
                    header('Location: /clinic/admin/vet-clinic/' . $id . '?success=1');
                    exit;
                }
            }
        } else {
            header('Location: /clinic/admin/vet-clinic/' . $id . '?error=1');
            exit;
        }
    }


    public function deleteDoctor(int $id): void
    {
        // 1. Ищем доктора в таблице Vets по его ID
        $result = Vet::findBy('id', $id);

        //dd($result[0]['name']);        

        if (empty($result)) {
            header('Location: /clinic/admin/vet-clinic/' . $result[0]['clinic_id'] . '?error=1');
            exit;
        }

        $vet = Vet::deleteByColumn('id', $id);
        Appointments::deleteByColumn('vet_id', $id);
        Roll::deleteByColumn('name', $result[0]['name']);

        header('Location: /clinic/admin/vet-clinic/' . $result[0]['clinic_id'] . '?success=1');
        exit;
    }

    public function editeDoctor(int $id): void
    {
        $vetResult = Vet::findBy('id', $id);

        if (empty($vetResult)) {
            header('Location: /clinic/admin/vet-clinic/' . $vetResult[0]['clinic_id'] . '?error=1');
            exit;
        }

        $currentVet = isset($vetResult[0]) ? $vetResult[0] : $vetResult;

        // Работаем строго через квадратные скобки ['clinic_id']
        $rollResult = Roll::findBy('clinic_id', (int)$currentVet['clinic_id']);
        $currentRoll = isset($rollResult[0]) ? $rollResult[0] : $rollResult;

        $currentVet['email'] = !empty($currentRoll) ? $currentRoll['email'] : '';

        $clinicsData = Clinic::with('vet')->getModels();

        View::render('vet_clinic/edit_doctor', [
            'vet' => $currentVet,
            'clinic_id' => $vetResult[0]['clinic_id'],
            'clinics' => $clinicsData
        ]);
    }

    public function updateDoctor(): void
    {
        if (!empty($_POST) && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $name = trim($_POST['name']);
            $specialty = trim($_POST['specialty']);
            $clinic_id = (int)$_POST['clinic_id'];
            $email = trim($_POST['email']);
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            exit;
        }

        //dd($_POST);

        $dataVet = [
            'name' => $name,
            'specialty' => $specialty,
            'clinic_id' => $clinic_id
        ];

        $updateVet = Vet::update($dataVet, $id);

        //dd($updateVet);

        if ($updateVet == true) {
            $dataRoll = [
                'clinic_id' => $clinic_id,
                'email' => $email,
                'name' => $name
            ];

            $currentRollResult = Roll::findBy('name', $dataRoll['name']);

            if (!empty($currentRollResult)) {
                $currentRoll = isset($currentRollResult[0]) ? $currentRollResult[0] : $currentRollResult;
                $rollId = (int)$currentRoll['id'];

                // dd($rollId);

                Roll::update($dataRoll, $rollId);
            }
            header('Location: /clinic/admin/vet-clinic/' . $clinic_id . '?success=1');
            exit;
        } else {
            header('Location: /clinic/admin/vet-clinic/' . $clinic_id . '?error=1');
            exit;
        }
    }
}
