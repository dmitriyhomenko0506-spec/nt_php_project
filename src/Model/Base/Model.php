<?php

namespace App\Model\Base;

use App\Class\DB;
use PDO;

use App\Attributes\Relation;
use ReflectionClass;

abstract class Model
{
    protected string $table; // Имя таблицы (задается в Clinic.php)
    protected array $selectColumns = ['*'];
    protected string $selectWhere = '';

    // Массив для хранения связей, переданных через with() (например, ['vet'])
    protected array $relationsToLoad = [];


    // Метод select — начинает цепочку.
    public static function select(string ...$columns): static
    {
        $instance = new static(); // Создаем объект дочерней модели (например, Clinic)

        if (!empty($columns)) {
            $instance->selectColumns = $columns;
        }

        return $instance; // Возвращаем созданный объект для продолжения цепочки
    }


    // метод with — указывает, какие связи нужно загрузить вместе с моделью
    public static function with(string ...$relations): static
    {
        // Начинаем стандартный select()
        $instance = static::select();
        // Запоминаем массив связей (например: ['clinic'] или ['clinic.vet'])
        $instance->relationsToLoad = $relations;

        return $instance;
    }


    // Перехват метода where при static вызове (Clinic::where())
    public static function __callStatic(string $method, array $arguments)
    {
        if ($method === 'where') {
            return static::select()->queryWhere(...$arguments);
        }
    }


    // Перехват метода where при вызове в цепочке select()->where()
    public function __call(string $method, array $arguments)
    {
        if ($method === 'where') {
            return $this->queryWhere(...$arguments);
        }
    }


    // Private метод Where - для magic function
    private function queryWhere(string $where = ''): self
    {
        if (!empty($where)) {
            $this->selectWhere = $where;
        }

        return $this;
    }


    // Выполняет SQL-запрос к БД 
    public function get(): array
    {
        $cols = implode(', ', $this->selectColumns);

        $sqlwhere = "";
        if (!empty($this->selectWhere)) {
            $sqlwhere = " WHERE " . $this->selectWhere;
        }

        // Склеиваем итоговую SQL-строку
        $fullSql = "SELECT {$cols} FROM {$this->table}{$sqlwhere};";

        // Подключаем БД
        $stmt = DB::connect()->prepare($fullSql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function log($sql): void
    {
        $file = BASE_DIR . '/database/logSql.txt';
        file_put_contents($file, $sql . PHP_EOL, FILE_APPEND);
    }


    public function getModels(): array
    {
        $cols = implode(', ', $this->selectColumns);

        $sqlwhere = "";
        if (!empty($this->selectWhere)) {
            $sqlwhere = " WHERE " . $this->selectWhere;
        }

        $fullSql = "SELECT {$cols} FROM {$this->table}{$sqlwhere};";

        $this->log($fullSql);

        $stmt = DB::connect()->prepare($fullSql);

        // Тут оставляем FETCH_CLASS строго для объектов и рефлексии
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        $stmt->execute();

        $items = $stmt->fetchAll();

        // Подгружаем связи через loadRelation
        if (!empty($this->relationsToLoad) && !empty($items)) {
            foreach ($this->relationsToLoad as $relationPath) {
                $this->loadRelation($items, $relationPath);
            }
        }

        return $items;
    }


    /**
     * ВНУТРЕННИЙ МЕТОД: Читает атрибут #[Relation] рефлексией и делает подзапрос в БД
     */
    private function loadRelation(array $items, string $relationPath): void
    {
        // Поддержка цепочек через точку: делим 'vet.appointment.pet.owner'
        $parts = explode('.', $relationPath, 2);
        $currentRelation = $parts[0];
        $nestedRelation = $parts[1] ?? null;

        // Включаем «сканер» (Рефлексию) для класса наших объектов
        $reflection = new ReflectionClass($items[0]);

        // Проверяем, есть ли вообще метод с именем связи в вашей модели
        if (!$reflection->hasMethod($currentRelation)) {
            return;
        }

        $method = $reflection->getMethod($currentRelation);
        //dd($method);

        // Ищем наш кастомный стикер-атрибут Relation над этим методом
        $attributes = $method->getAttributes(Relation::class);

        //dd($attributes);

        if (empty($attributes)) {
            return; // Если стикера #[Relation] нет над методом — ничего не делаем
        }

        // ИСПРАВЛЕНИЕ ОШИБКИ INTELEPHENSE: 
        //getAttributes() возвращает массив. Берем первый элемент через reset() и вызываем newInstance()
        /** @var Relation $config */
        $config = reset($attributes)->newInstance();

        //dd($config);

        // Собираем ключи из текущих объектов (например, все ID наших клиник: [1, 2, 3...])
        $localKeys = [];
        $localKeyName = $config->localKey;
        foreach ($items as $item) {
            if (isset($item->$localKeyName)) {
                $localKeys[] = $item->$localKeyName;
            }
        }

        if (empty($localKeys)) return;
        $localKeys = array_unique($localKeys); // Убираем дубликаты ID

        // Готовим подзапрос в ДРУГУЮ таблицу
        $relatedModelClass = $config->relatedModel;
        $foreignKeyName = $config->foreignKey;

        // Делаем плейсхолдеры (?, ?, ?) для безопасного PDO IN-запроса
        $placeholders = implode(',', array_fill(0, count($localKeys), '?'));

        // Создаем пустой объект чужой модели, просто чтобы узнать имя её таблицы
        $relatedInstance = new $relatedModelClass();
        $relatedTable = $relatedInstance->table;

        // dd($relatedTable);

        // Строим SQL для чужой таблицы
        $sql = "SELECT * FROM {$relatedTable} WHERE {$foreignKeyName} IN ($placeholders)";
        $stmt = DB::connect()->prepare($sql);
        $this->log($sql);

        // Чужие строки тоже превращаем в объекты чужого класса
        $stmt->setFetchMode(PDO::FETCH_CLASS, $relatedModelClass);
        $stmt->execute(array_values($localKeys));

        // Получили массив связанных объектов
        $relatedItems = $stmt->fetchAll();

        // Если у нас была цепочка через точку (запускаем код заново)
        if ($nestedRelation && !empty($relatedItems)) {
            $this->loadRelation($relatedItems, $nestedRelation);
        }

        // Финальный этап: раскладываем полученные чужие объекты по своим "коробкам"
        foreach ($items as $item) {
            $currentLocalValue = $item->$localKeyName;

            // Отбираем только те чужие строки, которые подходят текущему объекту по ключу
            $matched = array_filter($relatedItems, function ($relatedItem) use ($foreignKeyName, $currentLocalValue) {
                return $relatedItem->$foreignKeyName == $currentLocalValue;
            });

            // Имя свойства, куда запишем результат 
            $propName = $config->name;

            // Смотрим на наш флаг isCollection, который вы руками проставили в модели
            if ($config->isCollection) {
                // Если true — сохраняем как массив объектов
                $item->$propName = array_values($matched);
            } else {
                // Если false — берем только первый объект или null, если ничего не нашлось
                $item->$propName = !empty($matched) ? reset($matched) : null;
            }
        }
    }


    // findBy — быстрый поиск по принципу: колонка = значение
    public static function findBy(string $column, string $value): array
    {
        return static::select()->queryWhere("{$column} = '{$value}'")->get();
    }


    // find — поиск одной строки ID
    public static function find(int $id): ?array
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE id = :id";
        $stmt = DB::connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_CLASS, static::class) ?: null;
    }


    // create — static метод записи новых значений в БД
    public static function create(array $data): bool
    {
        $instance = new static();
        $columns = implode(', ', array_keys($data));

        $placeholders = [];
        foreach ($data as $key => $val) {
            $placeholders[] = ":" . $key;
        }
        $placeholdersStr = implode(', ', $placeholders);

        $sql = "INSERT INTO {$instance->table} ({$columns}) VALUES ({$placeholdersStr});";

        $stmt = DB::connect()->prepare($sql);
        return $stmt->execute($data);
    }
}
