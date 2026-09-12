<?php

namespace App\Model;

use App\Model\Base\Model;
use App\Attributes\Relation;

class Appointments extends Model
{
    // Имя таблицы
    protected string $table = 'appointments';

    public int $id;
    public int $clinic_id;
    public int $vet_id;
    public int $pet_id;
    public string $scheduled_for;
    public string $status;
    public ?Pet $pet = null;

    #[Relation(
        name: 'pet',
        relatedModel: Pet::class,
        localKey: 'pet_id',
        foreignKey: 'id',
        isCollection: false
    )]
    public function pet() {}
}
