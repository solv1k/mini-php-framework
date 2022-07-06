<?php

namespace Core\Classes;

use Core\Classes\Base\Singleton;
use Exception, PDO, PDOStatement;

class DB extends Singleton
{
    /**
     * Конфиг с данными для подключения к базе данных.
     */
    private $config = [];

    /**
     * Объект PDO.
     * 
     * @var PDO
     */
    private $pdo = null;
    
    /**
     * Текущая выбранная таблица для работы.
     */
    private $table;

    /**
     * Конструктор класса базы данных.
     */
    protected function __construct()
    {
        $config = config('database');

        $this->prepareConfig($config);

        $this->connectAndSavePDO();
    }

    /**
     * Приводит конфиг в нормальный вид для подключения к базе данных.
     */
    private function prepareConfig(array $config)
    {
        if (empty($config['host'])) {
            $config['host'] = 'localhost';
        }

        if (empty($config['port'])) {
            $config['port'] = 3306;
        }

        if (empty($config['user'])) {
            $config['user'] = 'root';
        }

        if (empty($config['password'])) {
            $config['password'] = '';
        }

        if (empty($config['database'])) {
            $config['database'] = '';
        }

        $this->config = $config;
    }

    /**
     * Подключает к базе данных и сохраняет подключение PDO.
     */
    private function connectAndSavePDO()
    {
        $this->pdo = new PDO(
            'mysql:host=' . $this->config['host'] . ';port=' . $this->config['port'] . ';dbname=' . $this->config['database'],
            $this->config['user'],
            $this->config['password'],
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
        );
    }

    /**
     * Установка таблицы для дальнейшей работы.
     * 
     * @return DB
     */
    public static function table(string $table)
    {
        $instance = self::getInstance();
        $instance->setTable($table);

        return $instance;
    }

    /**
     * Установка таблицы для дальнейшей работы.
     */
    public function setTable(string $table)
    {
        $this->table = $table;
    }

    /**
     * Прямой запрос к БД.
     */
    public function query()
    {
        return call_user_func_array([&$this->pdo, 'query'], func_get_args());
    }

    /**
     * Возвращает массив с результатами запроса.
     */
    public function fetchQuery(string $query, array $params = [])
    {
        $st = $this->query($query);

        if (!$st->execute($params)) {
            $this->dieWithErrorArray($st);
        }

        $items = $st->fetchAll(PDO::FETCH_OBJ);

        return $items;
    }

    /**
     * Вставка новой записи.
     */
    public function insert(array $attributes)
    {
        $keys = implode(',', array_keys($attributes));
        $values = [];
        $params = [];
        foreach ($attributes as $key => $value) {
            $values[] = ':' . $key;
            $params[':' . $key] = $value;
        }
        $values = implode(',', $values);

        $st = $this->pdo->prepare("INSERT INTO $this->table ($keys) VALUES ($values)");

        if (!$st->execute($params)) {
            $this->dieWithErrorArray($st);
        }
    }

    /**
     * Обновление записи.
     */
    public function update(int $id, array $attributes, string $field = 'id')
    {
        $condition = [];
        $conditionParams = [];
        foreach ($attributes as $key => $value) {
            $condition[$key] = $key . '=:' . $key;
            $conditionParams[':' . $key] = $value;
        }
        $condition = implode(',', $condition);

        $st = $this->pdo->prepare("UPDATE $this->table SET $condition WHERE $field = $id");

        if (!$st->execute($conditionParams)) {
            $this->dieWithErrorArray($st);
        }
    }

    /**
     * Удаление записи.
     */
    public function delete(int $id, string $field = 'id')
    {
        $st = $this->pdo->prepare("DELETE FROM $this->table WHERE $field = $id");

        if (!$st->execute()) {
            $this->dieWithErrorArray($st);
        }
    }

    /**
     * Получение записи по ключу.
     */
    public function getById(int $id, string $idField = 'id')
    {
        $st = $this->pdo->prepare("SELECT * FROM $this->table WHERE `$idField` = $id");

        if (!$st->execute()) {
            $this->dieWithErrorArray($st);
        }

        $item = $st->fetch(PDO::FETCH_OBJ);

        return $item;
    }

    /**
     * Получение всех записей с условием и сортировкой.
     */
    public function getAll(array $fields = ['*'], array $where = [], array $orderBy = [], int $limit = 0)
    {
        $fields = implode(',', $fields);

        $whereCondition = '';
        $orderByCondition = '';
        $limitCondition = $limit ? 'LIMIT ' . $limit : '';

        if (!empty($where)) {
            $condition = [];
            $whereParams = [];
            foreach ($where as $key => $value) {
                $condition[$key] = $key . '=:' . $key;
                $whereParams[':' . $key] = $value;
            }
            $whereCondition = 'WHERE ' . implode(' AND ', $condition);
        }

        if (!empty($orderBy)) {
            $condition = [];
            foreach ($orderBy as $key => $value) {
                $condition[$key] = $key . ' ' . $value;
            }
            $orderByCondition = 'ORDER BY ' . implode(',', $condition);
        }


        $st = $this->pdo->prepare("SELECT $fields FROM $this->table $whereCondition $orderByCondition $limitCondition");
        $stExecuted = !empty($whereCondition) ? $st->execute($whereParams) : $st->execute();

        if (!$stExecuted) {
            $this->dieWithErrorArray($st);
        }

        $items = $st->fetchAll(PDO::FETCH_OBJ);

        return $items;
    }

    /**
     * Получение первой записи с условием и сортировкой.
     */
    public function getFirst(array $fields = ['*'], array $where = [], array $orderBy = [])
    {
        $items = $this->getAll($fields, $where, $orderBy, 1);

        if (!empty($items)) {
            return array_shift($items);
        }

        return $items;
    }

    /**
     * Возвращает количество записей соответствующих условию.
     */
    public function count(array $where = [])
    {
        $whereCondition = '';

        if (!empty($where)) {
            $condition = [];
            $whereParams = [];
            foreach ($where as $key => $value) {
                $condition[$key] = $key . '=:' . $key;
                $whereParams[':' . $key] = $value;
            }
            $whereCondition = 'WHERE ' . implode(' AND ', $condition);
        }

        $st = $this->pdo->prepare("SELECT COUNT(*) FROM $this->table $whereCondition");
        
        if (!$st->execute($whereParams)) {
            $this->dieWithErrorArray($st);
        }

        $count = $st->fetchColumn();

        return $count;
    }

    /**
     * Прерывание запроса с ошибкой.
     */
    private function dieWithErrorArray(PDOStatement $st)
    {
        throw new Exception("PDO Error: " . implode(', ', $st->errorInfo()));
    }
}
