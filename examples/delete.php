<?php

require_once __DIR__ . "/connection.php";
use Kerim\Basedb\BaseDB;

/* USER DELETE ON DATABASE */
$i = BaseDB::table("users")::where([
    "id" => 3,
])::delete();

echo "$i row(s) deleted";

/* ALL USERS DELETE ON DATABASE */
$i = BaseDB::table("users")::delete();

echo "$i row(s) deleted";
