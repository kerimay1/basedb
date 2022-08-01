<?php

require_once __DIR__ . "/connection.php";
use Kerim\Basedb\BaseDB;

/* USER INSERT TO DATABASE */
echo BaseDB::table("users")::insert([
    "name" => "Insert Test",
    "email" => "inserttest@gmail.com",
    "password" => md5("test")
]);
