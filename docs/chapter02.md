# Front functions

### generateTable (array $cssClass, array $pager = [])
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
    "lang" => [ // Text or HTML for buttons | Default text in english ("Previous" and "Next")
        "previous" => "<< Previous",
        "next" => "Next >>"
    ],
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

### addFunction (string $column, string $function, array $params = [])
##### (Only used for generate table)
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

// Custom function for 'name' column of table (the first parameter must always be the value of the column)
function test($col, string $text) {
  return "(".$col.") ".$text;
}

// Using custom function for value on column name
$obj->addFunction("name", "test", ["it's easy"]);

// This function return the html code of your table
$obj->generateTable();

// table returned with formatted column 'name' with the custom function
```

### addTotal (string $column, string $text = "")
##### (Only used for generate table)
```php
<?php

// My data
$data = [
    ["id" => 2, "val" => 6],
    ["id" => 1, "val" => 6],
    ["id" => 3, "val" => 3],
    ["id" => 6, "val" => 5],
    ["id" => 5, "val" => 3],
    ["id" => 4, "val" => 6],
    ["id" => 7, "val" => 6],
    ["id" => 8, "val" => 7],
    ["id" => 9, "val" => 7],
    ["id" => 10, "val" => 5]
];

$obj = new ArrayOrganize($data);

// Create line with total (the first parameter must be the column name)
$obj->addTotal("val", "<strong>Total :</strong> ");

// This function return the html code of your table
$obj->generateTable();

// table returned with additional line column 'val' for the total
```

### generateList (array $cssClass = [])
```php
<?php

// My data list
$data = [
    ["title" => "Test list 1"],
    [
        "title" => "Test list 2",
        "url" => "http://ddg.gg/",
        "target_blank" => true
    ],
    [
        "title" => "Test list 3",
        "active" => true
    ],
    [
        "title" => "Test list 4",
        "disabled" => true
    ],
];

// Init object with data
$obj = new ArrayOrganize($data);

// Css settings for style balises (example with bootstrap 4)
$cssClass = [
    "ul" => "list-group",
    "li" => "list-group-item",
    "disabled" => [ // Css class for disabled style (<li> and <a> only supported)
        "li" => "disabled",
    ],
    "active" => [ // Css class for active style (<li> and <a> only supported)
        "li" => "active",
    ],
    "balise" => "li/a" // ("li", "a", "li/a" and "a/li" ) default "li/a"
];

// This function return the html code of your list
$obj->generateList($cssClass);
// the data listed must always have the "title" key
// the url of data listed must always have the "url" key (url optionnal)
```

| Introduction | Previous chapter |
| :---------------------: | :--------------: |
| [Introduction](https://github.com/SimonDevelop/array-organize/blob/master/docs/introduction.md) | [Global functions](https://github.com/SimonDevelop/array-organize/blob/master/docs/chapter01.md) |
