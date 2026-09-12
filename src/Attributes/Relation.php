<?php

namespace App\Attributes;

use Attribute;

/**
 * Кастомный атрибут-стикер для разметки связей между моделями.
 * 
 * Системная пометка #[Attribute] указывает PHP, что этот класс
 * можно использовать ТОЛЬКО как маркер над методами (TARGET_METHOD).
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Relation
{
    public string $name;
    public string $relatedModel;
    public string $localKey;
    public string $foreignKey;
    public bool $isCollection;

    /**
     * Конструктор принимает все настройки, которые мы указываем в модели
     * 
     * @param string $name Имя свойства, в которое запишется результат 
     * @param string $relatedModel Класс модели, с которой строится связь 
     * @param string $localKey Ключ в ТЕКУЩЕЙ таблице, откуда мы стартуем 
     * @param string $foreignKey Ключ в ЧУЖОЙ таблице, куда мы пришли искать данные 
     * @param bool $isCollection Массив на выходе (true) или один объект (false)
     */
    public function __construct(
        string $name,
        string $relatedModel,
        string $localKey,
        string $foreignKey,
        bool $isCollection = false
    ) {
        $this->name = $name;
        $this->relatedModel = $relatedModel;
        $this->localKey = $localKey;
        $this->foreignKey = $foreignKey;
        $this->isCollection = $isCollection;
    }
}
