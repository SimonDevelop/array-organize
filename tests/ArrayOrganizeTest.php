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
        $this->assertEquals("en", $ArrayOrganize->getLang());
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
        $ArrayOrganize = new ArrayOrganize([], 10, 2, "fr");
        $this->assertEquals("fr", $ArrayOrganize->getLang());
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
    }

    /**
     * DataFilter function test
     */
    public function testDataFilter()
    {
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
          ["id" => 4, "name" => "example 6"],
        ];

        $ArrayOrganize = new ArrayOrganize($datas, 3, 1);

        $css = ['table', 'table-striped'];
        $pager = [
          "position" => "bottom",
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
        $this->assertContains('<a class="page-link">Previous</a>', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<li class="page-item active">', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<a class="page-link">1</a>', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains('<li class="page-item">', $ArrayOrganize->generateTable($css, $pager));
        $this->assertContains(
            '<a class="page-link" href="index.php?p=2">2</a>',
            $ArrayOrganize->generateTable($css, $pager)
        );
    }

    /**
     * Getters and Setters test
     * @depends testInitConstructor
     */
    public function testGetterSetter($ArrayOrganize)
    {
        $ArrayOrganize->setLang("en");
        $ArrayOrganize->setByPage(10);
        $ArrayOrganize->setPage(2);
        $ArrayOrganize->setData(["test1", "test2"]);

        $lang = $ArrayOrganize->getLang();
        $byPage = $ArrayOrganize->getByPage();
        $page = $ArrayOrganize->getPage();
        $url = $ArrayOrganize->getUrl();
        $data = $ArrayOrganize->getData();

        $this->assertEquals("en", $lang);
        $this->assertEquals(10, $byPage);
        $this->assertEquals(2, $page);
        $this->assertEquals("#", $url);
        $this->assertEquals(["test1", "test2"], $data);
    }
}
