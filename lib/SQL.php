<?php

class SQL {

    private static $_instance;
    private $db;

    public static function instance()
    {
        if(self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        setlocale(LC_ALL, 'ru_Ru.UTF8');
        $this->db = new PDO('mysql:host='.HOST.';dbname='.DB_NAME, USER_NAME, PASSWORD);
        $this->db->exec('SET NAMES UTF8');
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function select($query)
    {
        $q = $this->db->prepare($query);
        $q->execute();

        if ($q->errorCode() != PDO::ERR_NONE)
        {
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->fetchAll();
    }

    public function insert($table, $obj)
    {
        $columns = [];

        foreach ($obj as $key => $value)
        {
            $columns[] = $key;
            $masks[] = ":$key";

            if ($value === null) {
                $obj[$key] = 'NULL';
            }
        }

        $columns_s = implode(',', $columns);
        $masks_s = implode(',', $masks);

        $query = "INSERT INTO $table ($columns_s) VALUES ($masks_s)";

        $q = $this->db->prepare($query);
        $q->execute($obj);

        if ($q->errorCode() != PDO::ERR_NONE)
        {
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $this->db->lastInsertId();
    }

    public function update($table, $obj, $where)
    {
        $sets = [];

        foreach ($obj as $key => $value)
        {
            $sets[] = "$key=:$key";

            if ($value === null) {
                $obj[$key] = 'NULL';
            }
        }

        $sets_s = implode(',', $sets);
        $query = "UPDATE $table SET $sets WHERE $where";

        $q = $this->db->prepare($query);
        $q->execute($obj);

        if ($q->errorCode() != PDO::ERR_NONE)
        {
            $info = $q->errorInfo();
            die($info[2]);
        }

        return $q->rowCount();
    }
}