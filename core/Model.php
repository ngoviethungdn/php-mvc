<?php

namespace Core;

use PDO;

abstract class Model
{
    /**
     * @var mixed
     */
    private static $db = null;
    /**
     * @var string
     */
    protected $table = '';
    /**
     * @var array
     */
    public $params = [];
    /**
     * @var string
     */
    public $conditionQuery = '';

    protected static function getDB()
    {
        if (is_null(self::$db)) {
            self::$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
            // Throw an Exception when an error occurs
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$db;
    }

    /**
     * @param $field     Field name
     * @param $condition Mysql condition
     * @param $value     Value
     * @return Model
     */
    public function where($field, $condition, $value)
    {
        $key = "w_$field" . count($this->params);
        $this->conditionQuery = !empty($this->conditionQuery) ? $this->conditionQuery . " AND " : $this->conditionQuery;
        $this->conditionQuery .= "$field $condition :$key";
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * @param array $fields Array fileds
     * @return array
     */
    public function get($fields = ['*'])
    {
        $query = "SELECT " . implode(", ", $fields) . " FROM " . $this->table;
        if (!empty($this->conditionQuery)) {
            $query .= " WHERE " . $this->conditionQuery;
        }
        return $this->execute($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data Data
     */
    public function update($data = [])
    {
        $tmp = [];
        foreach ($data as $key => $value) {
            $tmp[] = "$key = :$key";
            $this->params[$key] = $value;
        }
        $query = "UPDATE " . $this->table . " SET " . implode(", ", $tmp);
        if (!empty($this->conditionQuery)) {
            $query .= " WHERE " . $this->conditionQuery;
        }
        unset($data);
        unset($tmp);
        $this->execute($query);
        return null;
    }

    /**
     * @param array $data Data
     */
    public function create($data = [])
    {
        $keys = array_keys($data);
        $this->params = array_merge($this->params, $data);
        $func = function ($value) {
            return ":$value";
        };
        $query = "INSERT INTO " . $this->table . " (" . implode(", ", $keys) . ") VALUES (" . implode(", ", array_map($func, $keys)) . ")";
        unset($data);
        unset($func);
        $this->execute($query);
        return ["id" => self::$db->lastInsertId()];
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table;
        if (!empty($this->conditionQuery)) {
            $query .= " WHERE " . $this->conditionQuery;
        }
        $this->execute($query);
    }

    /**
     * @param $query String sql
     * @return Object
     */
    public function execute($query)
    {
        self::getDB();
        $sth = self::$db->prepare($query);
        foreach ($this->params as $key => $item) {
            $sth->bindParam(":" . $key, $this->params[$key]);
        }
        $sth->execute();
        $this->conditionQuery = '';
        $this->params = [];
        return $sth;
    }

}
