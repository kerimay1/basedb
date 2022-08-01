<?php

require_once __DIR__ . '/connection.php';
use Kerim\Basedb\BaseDB;

/* ONE SELECT */
$user = BaseDB::table("users")::where([
        "id" => 1
    ])::select()::one();

echo $user["name"] . "<br />";

/* ALL SELECT IN TABLE */
$users = BaseDB::table("users")::select()::all();
foreach ($users as $user) {
    echo $user["name"] . "<br />";
}