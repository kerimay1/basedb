# BaseDB
Simple PDO database library

## Install
```shell
composer require kerim/basedb
```

## Usage
```php
use Kerim\Basedb\BaseDB;
BaseDB::method();
```

#### Methods:
- [connect()](#connect)
- [table()](#table)
- [where()](#where)
- [select()](#select)
- [one()](#one)
- [all()](#all)
- [insert()](#insert)
- [update()](#update)
- [delete()](#delete)

### Connect
```php
use Kerim\Basedb\BaseDB;
BaseDB::connect("dbname", ["user" => "username", "pass" => "password"], "host", "charset")
```

### Table
```php
use Kerim\Basedb\BaseDB;
BaseDB::table("table")
```

### Where
```php
use Kerim\Basedb\BaseDB;
BaseDB::table("table")::where(["key" => "value"])
```

### Select
```php
use Kerim\Basedb\BaseDB;
BaseDB::table("table")::select(["column1", "column2", "..."])
/*** OR ***/
BaseDB::table("table")::select()
```

#### One
```php
use Kerim\Basedb\BaseDB;
BaseDB::table("table")::where(["key" => "value"])::select()::one();
```

#### All
```php
use Kerim\Basedb\BaseDB;
BaseDB::table("table")::select()::all();
```

### Insert
```php
use Kerim\Basedb\BaseDB;
BaseDB::table("table")::insert([
    "key" => "value",
    "other_key" => "other_value"
])
```

### Update
```php
use Kerim\Basedb\BaseDB;
BaseDB::table("table")::where(["key" => "value"])::update([
    "key" => "changed_value",
])
```

### Delete
```php
use Kerim\Basedb\BaseDB;
BaseDB::table("table")::where(["key" => "value"])::delete();
/*** OR ***/
BaseDB::table("table")::delete();
```
