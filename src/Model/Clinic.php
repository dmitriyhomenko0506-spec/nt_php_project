<?php

namespace App\Model;

use App\Model\Base\Model;
use App\Attributes\Relation;

class Clinic extends Model
{
    // Имя таблицы
    protected string $table = 'clinics';
    public int $id;
    public string $name;
    public string $city;
    public ?array $vet = null;
    public ?array $allappointments = null;

    #[Relation(
        name: 'vet',
        relatedModel: Vet::class,
        localKey: 'id',
        foreignKey: 'clinic_id',
        isCollection: true
    )]
    public function vet() {}

    // Посмотреть все записи клиники
    #[Relation(
        name: 'allappointments',
        relatedModel: Appointments::class,
        localKey: 'id',
        foreignKey: 'clinic_id',
        isCollection: true
    )]
    public function allappointments() {}
}
