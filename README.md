[![version](https://img.shields.io/badge/Version-0.0.1-brightgreen.svg)](https://github.com/SimonDevelop/array-organize/releases/tag/0.0.1)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/SimonDevelop/array-organize.svg?branch=master)](https://travis-ci.org/SimonDevelop/array-organize)
[![GitHub license](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/SimonDevelop/array-organize/blob/master/LICENSE)
# array-organize
Php library for easy sorting of data, generate html table and more.

```bash
composer require simondevelop/array-organize
```

## Example

```php
<?php
// index.php
require "vendor/autoload.php";
use SimonDevelop\ArrayOrganize;

$data = [
  ["id" => 2, "name" => "example 5"],
  ["id" => 1, "name" => "example 5"],
  ["id" => 3, "name" => "example 3"],
  ["id" => 6, "name" => "example 5"],
  ["id" => 5, "name" => "example 3"],
  ["id" => 4, "name" => "example 6"],
  ["id" => 7, "name" => "example 6"],
  ["id" => 8, "name" => "example 7"],
  ["id" => 9, "name" => "example 7"]
];

if (isset($_GET['p'])) {
  $page = $_GET['p'];
} else {
  $page = 1;
}

// Init object with data, number by page and current page number
$obj = new ArrayOrganize($data, 3, $page);

// Sort data
$obj->dataSort("id", "ASC");

// Filter data
$obj->dataFilter(["name" => "example 5"]);

// Css class for the table balise (example with bootstrap 3)
$cssClass = ['table', 'table-striped'];

// Settings to pager buttom
$pager = [
  "position" => "bottom",
  "cssClass" => ["pager"],
  "url" => "index.php?p={}"
];

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ArrayOrganize</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <?php
        // Generate html table (with pager on second parameter)
        echo $obj->generateTable($cssClass, $pager);
      ?>
    </div>
  </body>
</html>
```

Check this [docs](https://github.com/SimonDevelop/array-organize/blob/master/docs/introduction.md) for more.

#### Go to contribute !
