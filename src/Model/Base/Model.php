<?php

namespace App\Model\Base;

use App\Class\DB;
use PDO;

abstract class Model
{
    protected string $table; // Имя таблицы (задается в Clinic.php)
    protected array $selectColumns = ['*'];
    protected string $selectWhere = '';


    // Метод select — начинает цепочку.

    public static function select(string ...$columns): static
    {
        $instance = new static(); // Создаем объект дочерней модели (например, Clinic)

        if (!empty($columns)) {
            $instance->selectColumns = $columns;
        }

        return $instance; // Возвращаем созданный объект для продолжения цепочки
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


    //Выполняет SQL-запрос к БД

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

        // Возвращаем реальный массив строк из базы данных
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // findBy — быстрый поиск по принципу: колонка = значение

    public static function findBy(string $column, string $value): array
    {
        // Вызываем внутренний рабочий queryWhere напрямую через созданный select()
        return static::select()->queryWhere("{$column} = '{$value}'")->get();
    }


    // find — поиск одной строки ID

    public static function find(int $id): ?array
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE id = :id";
        $stmt = DB::connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    // create — static метод записи новых значений в БД

    public static function create(array $data): bool
    {
        $instance = new static();
        $columns = implode(', ', array_keys($data));

        // Для безопасности используем именованные плейсхолдеры (:title, :city)
        $placeholders = [];
        foreach ($data as $key => $val) {
            $placeholders[] = ":" . $key;
        }
        $placeholdersStr = implode(', ', $placeholders);

        $sql = "INSERT INTO {$instance->table} ({$columns}) VALUES ({$placeholdersStr});";

        $stmt = DB::connect()->prepare($sql);
        return $stmt->execute($data); // Выполняем и возвращаем статус (успешно или нет)
    }
}
