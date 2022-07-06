<?php

namespace App\Models;

use Core\Classes\DB;
use Exception;

class Model
{
    /**
     * Список методов для класса, которые могут быть вызваны статично.
     */
    private static $methods = [
        'create',
        'update',
        'delete',
        'all',
        'first',
        'find',
    ];

    /**
     * Набор полей для сохранения в БД.
     */
    protected $fillable = [];

    /**
     * Имя таблицы в БД.
     */
    protected $table;

    /**
     * Вызов стандартного метода.
     */
    public function __call($method, $arguments)
    {
        if (self::canCalledStatically($method)) {
            return call_user_func_array([new static, 'db' . $method], $arguments);
        } else {
            $modelClass = static::class;
            throw new Exception("Method \"$method\" does not exists in \"$modelClass\" model.");
        }
    }

    /**
     * Вызов стандартного метода через статичный.
     */
    public static function __callStatic($method, $arguments)
    {
        if (self::canCalledStatically($method)) {
            return call_user_func_array([new static, 'db' . $method], $arguments);
        } else {
            $modelClass = static::class;
            throw new Exception("Method \"$method\" does not exists in \"$modelClass\" model.");
        }
    }

    public static function canCalledStatically(string $method)
    {
        return in_array($method, self::$methods);
    }

    /**
     * Возвращает имя таблицы в БД.
     */
    public function table()
    {
        return $this->table ?? $this->generateTableName();
    }

    /**
     * Генерирует имя таблицы по имени класса.
     */
    public function generateTableName()
    {
        $vars = explode('\\', $this::class);
        $this->table = strtolower(end($vars)) . 's';

        return $this->table;
    }

    protected function beforeCreate()
    {
        //
    }

    /**
     * Привязывает аттрибуты к модели.
     */
    private function bindAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Заполняет данные модели в соответствии с массивом.
     */
    public function fill(array $attributes)
    {
        return $this->bindAttributes($attributes);
    }

    /**
     * Возвращает массив с аттрибутами разрешенными для сохранения в БД.
     */
    public function fillableAttributes()
    {
        $attributes = [];

        foreach ($this->fillable as $attribute) {
            if (property_exists($this, $attribute)) {
                $attributes[$attribute] = $this->{$attribute};
            }
        }

        return $attributes;
    }

    /**
     * Создает модель в БД и возвращает модель.
     */
    private function dbCreate(array $attributes)
    {
        $this->bindAttributes($attributes);
        
        $this->beforeCreate();

        DB::table($this->table())->insert($this->fillableAttributes());

        return $this;
    }

    /**
     * Обновляет модель в БД и возвращает модель.
     */
    private function dbUpdate(int $id, array $attributes)
    {
        $this->bindAttributes($attributes);

        $this->beforeUpdate();

        DB::table($this->table())->update($id, $this->fillableAttributes());

        return $this;
    }

    /**
     * Удаляет модель из БД и возвращает флаг результата.
     */
    private function dbDelete(int $id)
    {
        return DB::table($this->table())->delete($id);
    }

    /**
     * Получает все записи из БД согласно указанным условиям и сортировкам.
     */
    private function dbAll(array $fields = ['*'], array $where = [], array $orderBy = [], int $limit = 0)
    {
        return DB::table($this->table())->getAll($fields, $where, $orderBy, $limit);
    }

    /**
     * Получает первую запись из БД согласно указанным условиям и сортировкам.
     */
    private function dbFirst(array $fields = ['*'], array $where = [], array $orderBy = [])
    {
        return DB::table($this->table())->getFirst($fields, $where, $orderBy);
    }

    /**
     * Получает первую запись из БД согласно указанным условиям и сортировкам.
     */
    private function dbFind(int $id, string $field = 'id')
    {
        return self::dbFirst(['*'], [$field => $id]);
    }
}