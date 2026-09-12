<?php

namespace App\Controller;

use App\Model\Clinic;
use App\Model\Vet;
use App\Model\Roll;
use App\Model\Appointments;
use App\Class\View;

class AdminController
{
    /**
     * Главная панель Главного Администратора (status = 0)
     */
    public function admin(): void
    {
        $clinicsData = Clinic::with('vet.appointment.pet.owner')->getModels();

        View::render('admin/admin', [
            'name' => $_SESSION['user']['name'] ?? 'Администратор',
            'email' => $_SESSION['user']['email'] ?? '',
            'status' => $_SESSION['user']['status'] ?? 0,
            'clinics' => $clinicsData
        ]);
    }

    public function createClinicPage(): void
    {
        View::render('admin/create_clinic');
    }

    public function createClinic(): void
    {
        if (!empty($_POST)) {
            $name = trim($_POST['name']);
            $city = trim($_POST['city']);
            $email = trim($_POST['email']);
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }

        $queryRoll = Roll::findBy('email', $email);
        if (empty($queryRoll)) {
            $queryClinic = Clinic::findBy('name', $name);
            if (!empty($queryClinic)) {
                header('Location: /clinic/admin/admin?error=1');
                exit;
            }

            $dataClinic = [
                'name' => $name,
                'city' => $city
            ];

            $createClinic = Clinic::create($dataClinic);
            if ($createClinic == true) {
                $lastId = Clinic::where("name = '{$name}' ORDER BY id DESC LIMIT 1")->get();

                //dd($lastId[0]['id']);

                $dataRoll = [
                    'email' => $email,
                    'pass' => $pass,
                    'clinic_id' => $lastId[0]['id'],
                    'name' => (string) $name,
                    'status' => (int) 1
                ];

                $createClinicRoll = Roll::create($dataRoll);
                if ($createClinicRoll == true) {
                    header('Location: /clinic/admin/admin?success=1');
                    exit;
                }
            } else {
                header('Location: /clinic/admin/admin?error=1');
                exit;
            }
        } else {
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }
    }

    public function createDoctorPage(): void
    {
        $clinicsData = Clinic::with('vet')->getModels();
        View::render('admin/create_doctor', [
            'clinics' => $clinicsData
        ]);
    }

    /**
     * Создание нового доктора
     */
    public function createDoctor(): void
    {
        if (!empty($_POST)) {
            $name = trim($_POST['name']);
            $specialty = trim($_POST['specialty']);
            $clinic_id = (int)$_POST['clinic_id'];
            $email = trim($_POST['email']);
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }

        $queryRoll = Roll::findBy('email', $email);

        if (empty($queryRoll)) {
            $queryVet = Vet::findBy('name', $name);
            if (!empty($queryVet)) {
                header('Location: /clinic/admin/admin?error=1');
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
                    header("Location: /clinic/admin/admin?success=1");
                    exit;
                }
            }
        } else {
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }
    }

    public function deleteDoctor(int $id): void
    {
        // 1. Ищем доктора в таблице Vets по его ID
        $result = Vet::findBy('id', $id);

        //dd($result[0]['name']);        

        if (empty($result)) {
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }

        $vet = Vet::deleteByColumn('id', $id);
        Appointments::deleteByColumn('vet_id', $id);
        Roll::deleteByColumn('name', $result[0]['name']);

        header("Location: /clinic/admin/admin?success=1");
        exit;
    }


    /**
     * Загрузка данных доктора в форму редактирования (Исправлено под массивы PHP)
     */
    public function editeDoctor(int $id): void
    {
        $vetResult = Vet::findBy('id', $id);

        if (empty($vetResult)) {
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }

        $currentVet = isset($vetResult[0]) ? $vetResult[0] : $vetResult;

        // Работаем строго через квадратные скобки ['clinic_id']
        $rollResult = Roll::findBy('clinic_id', (int)$currentVet['clinic_id']);
        $currentRoll = isset($rollResult[0]) ? $rollResult[0] : $rollResult;

        $currentVet['email'] = !empty($currentRoll) ? $currentRoll['email'] : '';

        $clinicsData = Clinic::with('vet')->getModels();

        View::render('admin/edit_doctor', [
            'vet' => $currentVet,
            'clinics' => $clinicsData
        ]);
    }

    /**
     * Сохранение измененных данных доктора (Исправлено под массивы PHP)
     */
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
            header("Location: /clinic/admin/admin?success=1");
            exit;
        } else {
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }
    }

    /**
     * Показ страницы филиала 
     */
    public function showClinicInfo(int $id): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ЖЕСТКАЯ ПРОВЕРКА: Если зашел Локальный Админ (status = 1), 
        // и он пытается подменить цифру {id} в URL на чужой филиал
        $userStatus = isset($_SESSION['user']['status']) ? (int)$_SESSION['user']['status'] : -1;
        $userClinicId = isset($_SESSION['user']['clinic_id']) ? (int)$_SESSION['user']['clinic_id'] : -1;

        if ($userStatus === 1 && $id !== $userClinicId) {
            // Мгновенно блокируем доступ и выкидываем с ошибкой
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }

        // Если проверка пройдена или зашел Главный Админ (status = 0) — отдаем данные
        $clinicData = Clinic::with('vet.appointment.pet.owner')
            ->where("id = {$id}")
            ->getModels();

        if (empty($clinicData)) {
            header('Location: /clinic/admin/admin?error=1');
            exit;
        }

        $currentClinic = isset($clinicData[0]) ? $clinicData[0] : $clinicData;

        View::render('admin/clinic_detail', [
            'clinic' => $currentClinic,
            'name' => $_SESSION['user']['name'] ?? 'Администратор',
            'email' => $_SESSION['user']['email'] ?? '',
            'status' => $userStatus
        ]);
    }
}
