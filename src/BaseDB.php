<?php

namespace Kerim\Basedb;

use PDO;

class BaseDB {
    private static $connection = null;
    private static $query = "";
    private static $where = "";
    private static $whereData = [];
    private static $table = "";

    private static function c($data)
    {
        $a = "";
        $keys = array_keys($data);
        foreach ($keys as $array_key) {
            if ($array_key === end($keys)) {
                $a .= "$array_key = :$array_key";
            } else {
                $a .= "$array_key = :$array_key,";
            }
        }
        self::$query .= $a;
    }

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
                $w .= "$item = :$item";
            }
            else {
                $w .= ", $item = :$item";
            }
        }
        self::$where = $w;
        self::$whereData = array_map("strval", $where);
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
        self::$table = "";
        try {
            $p = self::$connection->prepare(self::$query);
            $p->execute(self::$whereData);
            $d = $p->fetch(PDO::FETCH_ASSOC);
            self::$query = "";
            self::$where = "";
            self::$whereData = [];
            return $d;
        } catch (\PDOException $e) {
            self::showError($e);
        }
        return [];
    }

    public static function all(): array
    {
        self::$table = "";
        try {
            $p = self::$connection->prepare(self::$query);
            $p->execute(self::$whereData);
            $d = $p->fetchAll(PDO::FETCH_ASSOC);
            self::$query = "";
            self::$where = "";
            self::$whereData = [];
            return $d;
        } catch (\PDOException $e) {
            self::showError($e);
        }
        return [];
    }

    public static function insert($data): int
    {
        self::$query = "INSERT INTO " . self::$table . " SET ";
        self::c($data);
        self::$table = "";
        try {
            $i = self::$connection->prepare(self::$query)->execute(array_merge($data, self::$whereData));
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
        self::c($data);
        self::$query .= " WHERE " . self::$where;
        self::$table = "";
        self::$where = "";
        self::$whereData = [];
        try {
            $u = self::$connection->prepare(self::$query)->execute(array_merge($data, self::$whereData));
            self::$query = "";
            return $u;
        } catch (\PDOException $e) {
            self::showError($e);
        }
        self::$query = "";
        return false;
    }

    public static function delete(): int
    {
        if (self::$where) {
            self::$query = "DELETE FROM " . self::$table . " WHERE " . self::$where;
            $p = self::$connection->prepare(self::$query);
            $p->execute(self::$whereData);
            $u = $p->rowCount();
        } else {
            self::$query = "DELETE FROM " . self::$table;
            $u = self::$connection->query(self::$query)->rowCount();
        }
        self::$table = "";
        self::$query = "";
        self::$where = "";
        self::$whereData = [];
        return $u;
    }
}