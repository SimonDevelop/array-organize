# ArrayOrganize references

### __constructor (array $data = [], int $byPage = 20, int $page = 1)
```php
<?php

// $data is a array of data
// example
// $data = [ // optionnal
//     ["id" => 2, "name" => "example 4"],
//     ["id" => 1, "name" => "example 5"],
//     ["id" => 3, "name" => "example 3"],
//     ["id" => 5, "name" => "example 7"],
//     ["id" => 4, "name" => "example 6"]
// ];

$byPage = 3 // number of lines per page (optionnal)
$page = 1 // current page (optionnal)

$obj = new ArrayOrganize($data, byPage, $page);
```

- [Global functions](https://github.com/SimonDevelop/array-organize/blob/master/docs/chapter01.md)
- [Front functions](https://github.com/SimonDevelop/array-organize/blob/master/docs/chapter02.md)
