[![version](https://img.shields.io/badge/Version-0.1.1-brightgreen.svg)](https://github.com/SimonDevelop/array-organize/releases/tag/0.1.1)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/SimonDevelop/array-organize.svg?branch=master)](https://travis-ci.org/SimonDevelop/array-organize)
[![Github Actions PHP](https://github.com/SimonDevelop/array-organize/workflows/PHP/badge.svg)](https://github.com/SimonDevelop/array-organize/actions)
[![GitHub license](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/SimonDevelop/array-organize/blob/master/LICENSE)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FSimonDevelop%2Farray-organize.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FSimonDevelop%2Farray-organize?ref=badge_shield)
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
  ["id" => 9, "name" => "example 7"],
  ["id" => 10, "name" => "example 5"]
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
// $obj->dataFilter(["name" => "example 5"]);

// Css class for the table balise (example with bootstrap 4)
$cssClass = ['table', 'table-striped'];

// Settings of pagination
$pager = [
  "position" => "bottom",
  "lang" => [
    "previous" => "<< Previous",
    "next" => "Next >>"
  ],
  "cssClass" => [
    "ul" => "pagination",
    "li" => "page-item",
    "a" => "page-link",
    "disabled" => [
      "li" => "disabled"
    ],
    "active" => [
      "li" => "active"
    ]
  ],
  "url" => "index.php?p={}"
];

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ArrayOrganize</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
    integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
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
- Check the [Code of Conduct](https://github.com/SimonDevelop/array-organize/blob/master/.github/CODE_OF_CONDUCT.md)
- Check the [Contributing file](https://github.com/SimonDevelop/array-organize/blob/master/.github/CONTRIBUTING.md)
- Check the [Pull Request Template](https://github.com/SimonDevelop/array-organize/blob/master/.github/PULL_REQUEST_TEMPLATE.md)


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FSimonDevelop%2Farray-organize.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FSimonDevelop%2Farray-organize?ref=badge_large)
