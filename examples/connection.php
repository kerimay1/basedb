<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Kerim\Basedb\BaseDB;

BaseDB::connect('basedb', ['user' => 'root', 'pass' => ''], 'localhost', 'utf8');