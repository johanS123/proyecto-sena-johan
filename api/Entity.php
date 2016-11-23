<?php

class Entity {
    private $_db;
    private $_table;
    public $_sql;

    public function __construct ($table) {
        $options = parse_ini_file(__DIR__ . '/config.ini');

        try {
            $this->_db = new PDO("{$options['driver']}:host={$options['host']};dbname={$options['name']}", $options['user'], $options['password']);
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
            exit;
        }

        $this->_table = $table;
    }

    private function _stringify_fields ($fields, $values = true, $delimiter = ',') {
        $fields_pair = [];

        foreach ($fields as $key => $value) {
            array_push($fields_pair, $key . ($values ? " = {$value}" : ''));
        }

        return implode($delimiter, $fields_pair);
    }

    public function select ($fields) {
        $this->_sql = "SELECT {$fields} FROM {$this->_table}";
        return $this;
    }

    public function update ($fields) {
        $this->_sql = "UPDATE {$this->_table} SET {$this->_stringify_fields($fields)}";
        return $this;
    }

    public function insert ($fields) {
        $keys = [];
        $values = [];

        foreach ($fields as $key => $value) {
            array_push($keys, $key);
            array_push($values, $value);
        }

        $keys = implode(',', $keys);
        $values = implode(',', $values);

        $this->_sql = "INSERT INTO {$this->_table}({$keys}) VALUES ({$values})";
        return $this;
    }

    public function where ($conditions) {
        if (isset($this->_sql)) {
            $this->_sql .= " WHERE {$this->_stringify_fields($conditions, true, ' AND ')}";
        }

        return $this;
    }

    public function delete ($conditions) {
        $this->_sql = "DELETE FROM {$this->_table}";
        $this->where($conditions)->execute();
        return $this;
    }

    public function execute ($fields = []) {
        $sth = $this->_db->prepare($this->_sql);
        $sth->execute($fields);
        $this->_sql = null;
        return $sth;
    }
}
