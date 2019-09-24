<?php

namespace App;

abstract class AbstractModel
{
    protected static $table;
    protected $connections = [];
    protected $connectionConds = [];
    protected $isNew = true;
    protected $dbFields = [];

    public function __construct($fields = [])
    {
        foreach ($fields as $field => $value) {
            $this->{$field} = $value;
        }
    }

    /**
     * @param null $cond
     * @return array
     */
    public static function find($cond = null)
    {
        $query = 'select * from ' . static::$table;
        if ($cond) {
            $query .= ' WHERE ' . $cond;
        }
        $query .= ';';
        $results = static::fetch($query);
        $models = [];
        foreach ($results as $result) {
            $model = new static($result);
            $model->isNew = false;
            $model->afterFind();
            $models[] = $model;
        }
        return $models;
    }

    protected static function fetch($query)
    {
        return ServiceLocator::getInstance()->getService('db')->fetch($query);
    }

    protected static function query($query)
    {
        return ServiceLocator::getInstance()->getService('db')->exec($query);
    }

    public function __get($name)
    {
        foreach ($this->connections as $connection) {
            if ($name == $connection[3] && !$this->{$name}) {
                $this->getConnection($connection);
                break;
            }
        }
        return $this->{$name};
    }

    protected function getConnection($connection)
    {
        if (class_exists($connection[0])) {
            $attributeName = $connection[3] ?? $connection[0];
            $where[] = $this->getWhereForConnect($connection);
            if ($this->connectionConds[$attributeName]) {
                $where = array_merge($where, $this->connectionConds[$attributeName]);
            }
            $where = implode(' AND ', $where);
            $models = $connection[0]::find($where);

            $this->{$attributeName} = count($models) > 1 ? $models : $models[0];
        }
    }

    protected function getWhereForConnect($connection)
    {
        $fieldName = $connection[2];
        $fieldValue = $this->{$fieldName};
        return "$connection[1] = $fieldValue";
    }

    public function addCondToConnection($connection, $cond)
    {
        $this->connectionConds[$connection][] = $cond;
    }

    public function save()
    {
        if (!$this->isNew) {
            $this->update();
        } else {
            $this->add();
        }
    }

    protected function afterFind()
    {

    }

    protected function update()
    {

    }

    protected function add()
    {
        $fields = implode(', ', array_keys($this->dbFields));
        $sql = 'INSERT INTO ' . static::$table . ' (' . $fields . ') VALUES (';
        $values = [];
        foreach ($this->dbFields as $field => $type) {
            if ($type == 'string') {
                $values[] = "'" . $this->{$field} . "'";
                continue;
            }
            $values[] = $this->{$field};
        }
        $sql .= implode(', ', $values);
        $sql .= ');';
        static::query($sql);
    }
}
