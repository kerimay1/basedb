<?php

namespace Kerim\Basedb;

use PDO;

class BaseDB {
    private static $connection = null;
    private static $query = "";
    private static $where = "";
    private static $table = "";

    private static function showError($error) {
        ?>
        <div style="background-color: red; color:#fefefe">
            <?php echo $error->getMessage(); ?>
        </div>
        <?php
    }

    public static function connect($db, $user = ["user" => "root", "pass" => "root"], $host = "localhost", $charset="utf8"): BaseDB
    {
        if (self::$connection == null) {
            try {
                self::$connection = new PDO("mysql:dbname=$db;host=$host", $user["user"], $user["pass"]);
                self::$connection->query("SET CHARACTER SET $charset");
            } catch (\PDOException $e) {
                self::showError($e);
            }
        }
        return new self;
    }

    public static function table($table): BaseDB
    {
        self::$table = $table;
        return new self;
    }

    public static function where($where = []): BaseDB
    {
        $w = "";
        foreach ($where as $item => $value) {
            if (empty($w)) {
                $w .= "$item = '$value'";
            }
            else {
                $w .= ", $item = '$value'";
            }
        }
        self::$where = $w;
        return new self;
    }

    public static function select($columns=[]): BaseDB
    {
        $columns = implode(", ", $columns);
        if (empty($columns)) {
            $columns = "*";
        }
        self::$query = "SELECT $columns FROM ". self::$table;
        if (self::$where) {
            self::$query  .= " WHERE " . self::$where;
        }
        return new self;
    }

    public static function one(): array
    {
        try {
            $d = self::$connection->query(self::$query)->fetch(PDO::FETCH_ASSOC);
            self::$query = "";
            self::$where = "";
            return $d;
        } catch (\PDOException $e) {
            self::showError($e);
        }
        return [];
    }

    public static function all(): array
    {
        try {
            $d = self::$connection->query(self::$query)->fetchAll(PDO::FETCH_ASSOC);
            self::$query = "";
            self::$where = "";
            return $d;
        } catch (\PDOException $e) {
            self::showError($e);
        }
        return [];
    }

    public static function insert($data): int
    {
        self::$query = "INSERT INTO " . self::$table . " SET ";
        $a = "";
        $keys = array_keys($data);
        foreach ($keys as $array_key) {
            if ($array_key === end($keys)) {
                $a .= $array_key . " = :" . $array_key;
            }
            else {
                $a .= $array_key . " = :" . $array_key . ", ";
            }
        }
        self::$query .= $a;
        try {
            $i = self::$connection->prepare(self::$query)->execute($data);
            self::$query = "";
            if ($i) {
                return self::$connection->lastInsertId();
            }
        } catch (\PDOException $e) {
            self::showError($e);
        }
        self::$query = "";
        return 0;
    }

    public static function update($data): bool
    {
        self::$query = "UPDATE " . self::$table . " SET ";
        $a = "";
        $keys = array_keys($data);
        foreach ($keys as $array_key) {
            if ($array_key === end($keys)) {
                $a .= $array_key . " = :" . $array_key;
            }
            else {
                $a .= $array_key . " = :" . $array_key . ", ";
            }
        }
        self::$query .= $a;
        self::$query .= " WHERE " . self::$where;
        self::$where = "";
        try {
            $u = self::$connection->prepare(self::$query)->execute($data);
            self::$query = "";
            return $u;
        } catch (\PDOException $e) {
            self::showError($e);
        }
        self::$query = "";
        return false;
    }
}