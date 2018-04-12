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
