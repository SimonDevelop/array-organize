# Front functions

### generateTable ( array $cssClass [, array $pager = [ ] ] )
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

// My number page
if (isset($_GET['p'])) {
  $page = $_GET['p'];
} else {
  $page = 1;
}

$obj = new ArrayOrganize($data, 3, $page);
// Or
$obj = new ArrayOrganize();
$obj->setData($data);
$obj->setByPage(3);
$obj->setPage($page);

// Css class for the table balise (example with bootstrap 3)
$cssClass = ['table', 'table-striped'];

// Settings to pager buttom with lang and url pagination
$pager = [
  "lang" => "fr", // Language for buttons / Default "en"
  "position" => "bottom", // Position of pagination
  "cssClass" => [ // Css class for pagination
    "ul" => "pagination",
    "li" => "page-item",
    "a" => "page-link",
    "disabled" => [ // Css class for disabled style (<li> and <a> only supported)
      "li" => "disabled"
    ],
    "active" => [ // Css class for active style (<li> and <a> only supported)
      "li" => "active"
    ]
  ],
  "url" => "index.php?p={}" // Url links for buttons (take into account your $page)
];

// This function return the html code of your table
$obj->generateTable($cssClass, $pager);
```
Check the example on the [README.md](https://github.com/SimonDevelop/array-organize/blob/master/README.md) file.

| Introduction | Previous chapter |
| :---------------------: | :--------------: |
| [Introduction](https://github.com/SimonDevelop/array-organize/blob/master/docs/introduction.md) | [Global functions](https://github.com/SimonDevelop/array-organize/blob/master/docs/chapter01.md) |
