<?php

namespace App\Model;

use App\Model\Base\Model;
use App\Attributes\Relation;

class Pet extends Model
{
    // Имя таблицы
    protected string $table = 'pets';

    public int $id;
    public int $owner_id;
    public string $name;
    public string $species;

    public ?array $owner = null;

    #[Relation(
        name: 'owner',
        relatedModel: Pet_owners::class,
        localKey: 'owner_id',
        foreignKey: 'id',
        isCollection: true
    )]
    public function owner() {}
}
