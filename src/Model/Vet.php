<?php

namespace App\Model;

use App\Model\Base\Model;
use App\Attributes\Relation;

class Vet extends Model
{
    // Имя таблицы
    protected string $table = 'vets';
    public int $id;
    public int $clinic_id;
    public string $name;
    public string $specialty;
    public ?Clinic $clinic = null;   /// Многие к одному -- > ?Модель /// Один ко многим  --> ?array

    public ?array $appointment = null;

    #[Relation(
        name: 'clinic',                // Имя свойства выше, куда положить результат
        relatedModel: Clinic::class,   // В какую модель идти за данными
        localKey: 'clinic_id',         // Своя колонка (из таблицы vets), откуда берем значение
        foreignKey: 'id',              // Чужая колонка (из таблицы clinics), где искать
        isCollection: false            // FALSE, так как клиника у врача только одна (вернется один объект)
    )]
    public function clinic() {}


    //Посмотреть записи только врача
    #[Relation(
        name: 'appointment',
        relatedModel: Appointments::class,
        localKey: 'id',
        foreignKey: 'vet_id',
        isCollection: true
    )]
    public function appointment() {}
}
