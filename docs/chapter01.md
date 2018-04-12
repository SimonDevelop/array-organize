# Global functions

### dataSort (string $value, string $order = 'ASC')
```php
<?php

// My data
$data = [
    ["id" => 2, "name" => "example 4"],
    ["id" => 1, "name" => "example 5"],
    ["id" => 3, "name" => "example 3"],
    ["id" => 5, "name" => "example 7"],
    ["id" => 4, "name" => "example 6"]
];

$obj = new ArrayOrganize($data);
// Or
$obj = new ArrayOrganize();
$obj->setData($data);

$obj->dataSort("id", "ASC");

$sorted = $obj->getData();
$sorted = [
    ["id" => 1, "name" => "example 5"],
    ["id" => 2, "name" => "example 4"],
    ["id" => 3, "name" => "example 3"],
    ["id" => 4, "name" => "example 6"],
    ["id" => 5, "name" => "example 7"]
];

// dataSort return a exception if $data is bad array format or column not found
// dataSort return true if data sorted, false if not
```

### dataFilter (array $columns = [])
```php
<?php

// It's a simple WHERE function

// My data
$data = [
    ["id" => 2, "name" => "example 4"],
    ["id" => 1, "name" => "example 5"],
    ["id" => 3, "name" => "example 3"]
];

$obj = new ArrayOrganize($data);
// Or
$obj = new ArrayOrganize();
$obj->setData($data);

$obj->dataFilter(["name" => "example 5"]);
// use ["name" => "%example%"] for like filter
// use ["id[<]" => 2] for id less than 2
// use ["id[<=]" => 2] for id less than or equal to 2
// use ["id[>]" => 2] for id greater than 2
// use ["id[>=]" => 2] for id greater than or equal to 2
// for date value, use string format YYYY-MM-DD

// Filters only work 'and' algorithm serie

$filted = $obj->getData();
$filted = [
    ["id" => 1, "name" => "example 5"]
];

// dataFilter return a exception if $data is bad array format
// dataFilter return true if data filtered, false if not
```

### dataColumnFilter (string $value, array $columns = [])
```php
<?php

// Examples params :
// dataColumnFilter("skip", ["id"]) -> skip "id" column
// dataColumnFilter("keep", ["id"]) -> keep "id" column only


// My data
$data = [
    ["id" => 2, "name" => "example 4"],
    ["id" => 1, "name" => "example 5"],
    ["id" => 3, "name" => "example 3"],
    ["id" => 5, "name" => "example 7"],
    ["id" => 4, "name" => "example 6"]
];

$obj = new ArrayOrganize($data);
// Or
$obj = new ArrayOrganize();
$obj->setData($data);

$obj->dataColumnFilter("keep", ["name"]);

$filted = $obj->getData();
$filted = [
    ["name" => "example 5"],
    ["name" => "example 4"],
    ["name" => "example 3"],
    ["name" => "example 6"],
    ["name" => "example 7"]
];

// dataFilter return a exception if $data is bad array format
// dataFilter return true if data column(s) filtered, false if not
```

| Introduction | Next chapter |
| :---------------------: | :--------------: |
| [Introduction](https://github.com/SimonDevelop/array-organize/blob/master/docs/introduction.md) | [Front functions](https://github.com/SimonDevelop/array-organize/blob/master/docs/chapter02.md) |
