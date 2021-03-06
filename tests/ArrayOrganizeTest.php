<?php

namespace SimonDevelop\Test;

use \PHPUnit\Framework\TestCase;
use \SimonDevelop\ArrayOrganize;

class ArrayOrganizeTest extends TestCase
{
    /**
     * Constructor test
     */
    public function testInitConstructor()
    {
        $ArrayOrganize = new ArrayOrganize();
        $this->assertEquals(1, $ArrayOrganize->getPage());
        $this->assertEquals(20, $ArrayOrganize->getByPage());
        $this->assertEquals([], $ArrayOrganize->getData());
        return $ArrayOrganize;
    }

    /**
     * Constructor test with params
     */
    public function testInitConstructorWithParam()
    {
        $ArrayOrganize = new ArrayOrganize([], 10, 2);
        $this->assertEquals(2, $ArrayOrganize->getPage());
        $this->assertEquals(10, $ArrayOrganize->getByPage());
        $this->assertEquals([], $ArrayOrganize->getData());
    }

    /**
     * DataSort function test
     */
    public function testDataSort()
    {
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "test4"],
            ["id" => 1, "name" => "test5"],
            ["id" => 3, "name" => "test3"]
        ]);
        $result = $ArrayOrganize->dataSort('id', 'DESC');

        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 3, "name" => "test3"],
            ["id" => 2, "name" => "test4"],
            ["id" => 1, "name" => "test5"]
        ], $ArrayOrganize->getData());

        $ArrayOrganize = new ArrayOrganize();
        $result = $ArrayOrganize->dataSort('id', 'DESC');

        $this->assertEquals(false, $result);
        $this->assertEquals([], $ArrayOrganize->getData());
    }

    /**
     * DataColumnFilter function test
     */
    public function testDataColumnFilter()
    {
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "test3"],
            ["id" => 1, "name" => "test4"],
            ["id" => 3, "name" => "test5"]
        ]);
        $result = $ArrayOrganize->dataColumnFilter("skip", ["id"]);

        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["name" => "test3"],
            ["name" => "test4"],
            ["name" => "test5"]
        ], $ArrayOrganize->getData());

        $ArrayOrganize = new ArrayOrganize();
        $result = $ArrayOrganize->dataColumnFilter("skip", ["id"]);

        $this->assertEquals(false, $result);
        $this->assertEquals([], $ArrayOrganize->getData());
    }

    /**
     * DataFilter function test
     */
    public function testDataFilter()
    {
        // is
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "test3"],
            ["id" => 1, "name" => "test4"],
            ["id" => 3, "name" => "test5"]
        ]);
        $result = $ArrayOrganize->dataFilter(["id" => 2]);

        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2, "name" => "test3"]
        ], $ArrayOrganize->getData());

        // is not
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ]);
        $result = $ArrayOrganize->dataFilter(["name[!=]" => "js"]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"]
        ], $ArrayOrganize->getData());

        // like
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ]);
        $result = $ArrayOrganize->dataFilter(["name" => "%php%"]);

        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"]
        ], $ArrayOrganize->getData());

        // upper
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ]);
        $result = $ArrayOrganize->dataFilter(["id[>]" => 1]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 3, "name" => "js"]
        ], $ArrayOrganize->getData());

        // upper and equal
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ]);
        $result = $ArrayOrganize->dataFilter(["id[>=]" => 1]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ], $ArrayOrganize->getData());

        // lower
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ]);
        $result = $ArrayOrganize->dataFilter(["id[<]" => 2]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 1, "name" => "php is a live"]
        ], $ArrayOrganize->getData());

        // lower and equal
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ]);
        $result = $ArrayOrganize->dataFilter(["id[<=]" => 2]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
        ], $ArrayOrganize->getData());

        // error
        $ArrayOrganize = new ArrayOrganize();
        $result = $ArrayOrganize->dataFilter(["id" => 2]);

        $this->assertEquals(false, $result);
        $this->assertEquals([], $ArrayOrganize->getData());
    }

    /**
     * GenerateTable function test
     */
    public function testGenerateTable()
    {
        $datas = [
            ["id" => 2, "name" => "example 5"],
            ["id" => 1, "name" => "example 5"],
            ["id" => 3, "name" => "example 3"],
            ["id" => 6, "name" => "example 5"],
            ["id" => 5, "name" => "example 3"],
            ["id" => 4, "name" => "example 6"]
        ];

        $ArrayOrganize = new ArrayOrganize($datas, 3, 1);

        $css = ['table', 'table-striped'];
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

        $this->assertContains('<table class="table table-striped">', $ArrayOrganize->generateTable($css, $pager));

        $this->assertContains('<tr><th>id</th><th>name</th></tr>', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<tr><td>2</td><td>example 5</td></tr>', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<tr><td>1</td><td>example 5</td></tr>', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<tr><td>3</td><td>example 3</td></tr>', $ArrayOrganize->generateTable($css, $pager));

        $this->assertContains('<ul class="pagination">', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<li class="page-item disabled">', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<a class="page-link"><< Previous</a>', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<li class="page-item active">', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<a class="page-link">1</a>', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<li class="page-item">', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains(
            '<a class="page-link" href="index.php?p=2">2</a>',
            $ArrayOrganize->generateTable($css, $pager)
        );

        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "example 5"],
            ["id" => 1, "name" => []],
        ], 3, 1);
        $this->assertEquals(false, $ArrayOrganize->generateTable($css, $pager));
    }

    /**
     * AddFunction function test
     */
    public function testAddFunction()
    {
        $datas = [
            ["id" => 2, "name" => "example 5"],
            ["id" => 1, "name" => "example 5"],
            ["id" => 3, "name" => "example 3"],
            ["id" => 6, "name" => "example 5"],
            ["id" => 5, "name" => "example 3"],
            ["id" => 4, "name" => "example 6"]
        ];

        $ArrayOrganize = new ArrayOrganize($datas);

        $result = $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertEquals(true, $result);
        $this->assertContains('<tr><td>2</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertContains('<tr><td>1</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertContains('<tr><td>3</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertContains('<tr><td>6</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertContains('<tr><td>5</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertContains('<tr><td>4</td><td>example</td></tr>', $ArrayOrganize->generateTable());
    }

    /**
     * AddTotal function test
     */
    public function testAddTotal()
    {
        $datas = [
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

        $ArrayOrganize = new ArrayOrganize($datas);
        $result = $ArrayOrganize->addTotal("val", "<strong>Total :</strong> ");
        $this->assertEquals(true, $result);

        $this->assertContains(
            '<tr><td></td><td><strong>Total :</strong> 54</td></tr></tbody>',
            $ArrayOrganize->generateTable()
        );
    }

    /**
     * GenerateList function test
     */
    public function testGenerateList()
    {
        $datas = [
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
            ]
        ];

        $ArrayOrganize = new ArrayOrganize($datas);

        $cssClass = [
            "ul" => "list-group",
            "li" => "list-group-item",
            "disabled" => [
                "li" => "disabled",
            ],
            "active" => [
                "li" => "active",
            ],
            "balise" => "li/a"
        ];

        $this->assertContains('<ul class="list-group">', $ArrayOrganize->generateList($cssClass));
        $this->assertContains('<li class="list-group-item">', $ArrayOrganize->generateList($cssClass));
        $this->assertContains('<a class="">Test list 1</a>', $ArrayOrganize->generateList($cssClass));
        $this->assertContains(
            '<a class="" href="http://ddg.gg/" target="_blank">Test list 2</a>',
            $ArrayOrganize->generateList($cssClass)
        );

        $ArrayOrganize = new ArrayOrganize([
            ["title" => "title 1"],
            ["test" => "title 2"],
        ]);
        $this->assertEquals(false, $ArrayOrganize->generateList($cssClass));
    }

    /**
     * Getters and Setters test
     * @depends testInitConstructor
     */
    public function testGetterSetter($ArrayOrganize)
    {
        $ArrayOrganize->setByPage(10);
        $ArrayOrganize->setPage(2);
        $ArrayOrganize->setData(["test1", "test2"]);

        $byPage = $ArrayOrganize->getByPage();
        $page = $ArrayOrganize->getPage();
        $url = $ArrayOrganize->getUrl();
        $data = $ArrayOrganize->getData();

        $this->assertEquals(10, $byPage);
        $this->assertEquals(2, $page);
        $this->assertEquals("#", $url);
        $this->assertEquals(["test1", "test2"], $data);
    }
}
