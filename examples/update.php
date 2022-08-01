<?php

require_once __DIR__ . "/connection.php";
use Kerim\Basedb\BaseDB;

/* USER UPDATE */
$u = BaseDB::table("users")::where([
    "name" => "Insert Test"
])::update([
    "name" => "Update Test",
    "email" => "updatetest@gmail.com"
]);

if ($u) {
    echo "update successful";
}