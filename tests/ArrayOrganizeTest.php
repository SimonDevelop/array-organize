<?php

namespace SimonDevelop\Test;

use \PHPUnit\Framework\TestCase;
use \SimonDevelop\ArrayOrganize;

/**
 * @coversDefaultClass \SimonDevelop\ArrayOrganize
 * @covers ::__construct
 */
class ArrayOrganizeTest extends TestCase
{
    /**
     * Constructor test
     * @covers ::getPage
     * @covers ::getByPage
     * @covers ::getData
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testInitConstructor(): ArrayOrganize
    {
        $ArrayOrganize = new ArrayOrganize();
        $this->assertEquals(1, $ArrayOrganize->getPage());
        $this->assertEquals(20, $ArrayOrganize->getByPage());
        $this->assertEquals([], $ArrayOrganize->getData());
        return $ArrayOrganize;
    }

    /**
     * Constructor test with params
     * @covers ::getPage
     * @covers ::getByPage
     * @covers ::getData
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testInitConstructorWithParam(): void
    {
        $ArrayOrganize = new ArrayOrganize([], 10, 2);
        $this->assertEquals(2, $ArrayOrganize->getPage());
        $this->assertEquals(10, $ArrayOrganize->getByPage());
        $this->assertEquals([], $ArrayOrganize->getData());
    }

    /**
     * Constructor test invalid argument
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testInitConstructorInvalidArgumentOne(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ArrayOrganize([], 0, 2);
    }

    /**
     * Constructor test invalid argument
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testInitConstructorInvalidArgumentTwo(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ArrayOrganize([], 10, 0);
    }

    /**
     * DataSort function test
     * @covers ::dataSort
     * @covers ::getData
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataSort(): void
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

        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "test4"],
            ["id" => 1, "name" => "test5"],
            ["id" => 3, "name" => "test3"]
        ]);
        $result = $ArrayOrganize->dataSort('id', 'ASC');

        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 1, "name" => "test5"],
            ["id" => 2, "name" => "test4"],
            ["id" => 3, "name" => "test3"]
        ], $ArrayOrganize->getData());

        $ArrayOrganize = new ArrayOrganize();
        $result = $ArrayOrganize->dataSort('id', 'DESC');

        $this->assertEquals(false, $result);
        $this->assertEquals([], $ArrayOrganize->getData());
    }

    /**
     * DataSort function invalid argument test
     * @covers ::dataSort
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataSortInvalidArgument(): void
    {
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "test4"],
            ["id" => 1, "name" => "test5"],
            ["id" => 3, "name" => "test3"]
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $ArrayOrganize->dataSort('text', 'DESC');
    }

    /**
     * DataSort function invalid data test
     * @covers ::dataSort
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataSortInvalidData(): void
    {
        $ArrayOrganize = new ArrayOrganize([
            0 => "test4",
            1 => "test5",
            2 => "test3"
        ]);
        $this->expectException(\Exception::class);
        $ArrayOrganize->dataSort('id', 'DESC');
    }

    /**
     * DataColumnFilter function test
     * @covers ::dataColumnFilter
     * @covers ::getData
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataColumnFilter(): void
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

        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "test3"],
            ["id" => 1, "name" => "test4"],
            ["id" => 3, "name" => "test5"]
        ]);
        $result = $ArrayOrganize->dataColumnFilter("keep", ["id"]);

        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2,],
            ["id" => 1,],
            ["id" => 3,]
        ], $ArrayOrganize->getData());
    }

    /**
     * DataColumnFilter function invalid argument test
     * @covers ::dataColumnFilter
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataColumnFilterInvalidArgument(): void
    {
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "test3"],
            ["id" => 1, "name" => "test4"],
            ["id" => 3, "name" => "test5"]
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $ArrayOrganize->dataColumnFilter("test", ["id"]);
    }

    /**
     * dataColumnFilter function invalid data test
     * @covers ::dataColumnFilter
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataColumnFilterInvalidData(): void
    {
        $ArrayOrganize = new ArrayOrganize([
            0 => "test4",
            1 => "test5",
            2 => "test3"
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $ArrayOrganize->dataColumnFilter("skip", ["id"]);
    }

    /**
     * DataFilter function test
     * @covers ::dataFilter
     * @covers ::getData
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataFilter(): void
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

        // date lower
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ]);
        $result = $ArrayOrganize->dataFilter(["date[<]" => "2021-07-24"]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2, "date" => "2021-08-21"]
        ], $ArrayOrganize->getData());

        // date lower and equal
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ]);
        $result = $ArrayOrganize->dataFilter(["date[<=]" => "2021-07-24"]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ], $ArrayOrganize->getData());

        // date upper
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ]);
        $result = $ArrayOrganize->dataFilter(["date[>]" => "2021-07-24"]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 1, "date" => "2021-07-21"]
        ], $ArrayOrganize->getData());

        // date upper and equal
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ]);
        $result = $ArrayOrganize->dataFilter(["date[>=]" => "2021-07-24"]);
        $this->assertEquals(true, $result);
        $this->assertEquals([
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ], $ArrayOrganize->getData());
        
        // error
        $ArrayOrganize = new ArrayOrganize();
        $result = $ArrayOrganize->dataFilter(["id" => 2]);

        $this->assertEquals(false, $result);
        $this->assertEquals([], $ArrayOrganize->getData());
    }

    /**
     * DataFilter function invalid data one test
     * @covers ::dataFilter
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataFilterInvalidDataOne(): void
    {
        $ArrayOrganize = new ArrayOrganize([
            0 => "test3",
            1 => "test4",
            2 => "test5"
        ]);
        $this->expectException(\Exception::class);
        $ArrayOrganize->dataFilter(["id" => 2]);
    }

    /**
     * DataFilter function invalid data two test
     * @covers ::dataFilter
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataFilterInvalidDataTwo(): void
    {
        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ]);
        $this->expectException(\Exception::class);
        $ArrayOrganize->dataFilter(["id" => []]);
    }

    /**
     * GenerateTable function test
     * @covers ::generateTable
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testGenerateTable(): void
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

        $html = $ArrayOrganize->generateTable($css, $pager);
        $this->assertStringContainsString('<table class="table table-striped">', $html);

        $this->assertStringContainsString('<tr><th>id</th><th>name</th></tr>', $html);
        $this->assertStringContainsString('<tr><td>2</td><td>example 5</td></tr>', $html);
        $this->assertStringContainsString('<tr><td>1</td><td>example 5</td></tr>', $html);
        $this->assertStringContainsString('<tr><td>3</td><td>example 3</td></tr>', $html);

        $this->assertStringContainsString('<ul class="pagination">', $html);
        $this->assertStringContainsString('<li class="page-item disabled">', $html);
        $this->assertStringContainsString('<a class="page-link"><< Previous</a>', $html);
        $this->assertStringContainsString('<li class="page-item active">', $html);
        $this->assertStringContainsString('<a class="page-link">1</a>', $html);
        $this->assertStringContainsString('<li class="page-item">', $html);
        $this->assertStringContainsString(
            '<a class="page-link" href="index.php?p=2">2</a>',
            $html
        );

        $ArrayOrganize = new ArrayOrganize([
            ["id" => 2, "name" => "example 5"],
            ["id" => 1, "name" => []],
        ], 3, 1);
        $this->assertEquals(false, $ArrayOrganize->generateTable($css, $pager));

        $ArrayOrganize = new ArrayOrganize([], 3, 1);
        $this->assertEquals(false, $ArrayOrganize->generateTable([], []));

        $ArrayOrganize = new ArrayOrganize($datas, 3, 1);
        $pager = [
            "position" => "top",
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

        $html = $ArrayOrganize->generateTable([], $pager);
        $this->assertStringContainsString('<table class="">', $html);

        $this->assertStringContainsString('<tr><th>id</th><th>name</th></tr>', $html);
        $this->assertStringContainsString('<tr><td>2</td><td>example 5</td></tr>', $html);
        $this->assertStringContainsString('<tr><td>1</td><td>example 5</td></tr>', $html);
        $this->assertStringContainsString('<tr><td>3</td><td>example 3</td></tr>', $html);

        $this->assertStringContainsString('<ul class="pagination">', $html);
        $this->assertStringContainsString('<li class="page-item disabled">', $html);
        $this->assertStringContainsString('<a class="page-link"><< Previous</a>', $html);
        $this->assertStringContainsString('<li class="page-item active">', $html);
        $this->assertStringContainsString('<a class="page-link">1</a>', $html);
        $this->assertStringContainsString('<li class="page-item">', $html);
        $this->assertStringContainsString(
            '<a class="page-link" href="index.php?p=2">2</a>',
            $html
        );
    }

    /**
     * GeneratePagination function test
     * @covers ::generatePagination
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testGeneratePagination(): void
    {
        $datas = [
            ["id" => 2, "name" => "example 5"],
            ["id" => 1, "name" => "example 5"],
            ["id" => 3, "name" => "example 3"],
            ["id" => 6, "name" => "example 5"],
            ["id" => 5, "name" => "example 3"],
            ["id" => 4, "name" => "example 6"]
        ];

        $ArrayOrganize = new ArrayOrganize($datas, 4, 1);

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

        $html = $ArrayOrganize->generatePagination($pager);

        $this->assertStringContainsString('<ul class="pagination">', $html);
        $this->assertStringContainsString('<li class="page-item disabled">', $html);
        $this->assertStringContainsString('<a class="page-link"><< Previous</a>', $html);
        $this->assertStringContainsString('<li class="page-item active">', $html);
        $this->assertStringContainsString('<a class="page-link">1</a>', $html);
        $this->assertStringContainsString('<li class="page-item">', $html);
        $this->assertStringContainsString(
            '<a class="page-link">1</a>',
            $html
        );
        $this->assertStringContainsString(
            '<a class="page-link" href="#">2</a>',
            $html
        );

        $pager = [
            "position" => "bottom",
            "lang" => [
                "previous" => "<< Previous",
                "next" => "Next >>"
            ],
            "cssClass" => [
                "disabled" => [
                    "a" => "disabled"
                ],
                "active" => [
                    "a" => "active"
                ]
            ],
            "url" => "index.php?p={}"
        ];

        $html = $ArrayOrganize->generatePagination($pager);

        $this->assertStringContainsString('<ul class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<a class="disabled"><< Previous</a>', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<a class="active">1</a>', $html);
        $this->assertStringContainsString('<li class="">', $html);

        $pager = [
            "position" => "bottom",
            "lang" => [
                "previous" => "<< Previous",
                "next" => "Next >>"
            ],
            "cssClass" => [],
            "url" => "index.php?p={}"
        ];

        $html = $ArrayOrganize->generatePagination($pager);

        $this->assertStringContainsString('<ul class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);

        $pager = [
            "position" => "bottom",
            "lang" => [
                "previous" => "<< Previous",
                "next" => "Next >>"
            ],
            "url" => "index.php?p={}"
        ];

        $html = $ArrayOrganize->generatePagination($pager);

        $this->assertStringContainsString('<ul class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);

        $pager = [
            "position" => "bottom",
        ];

        $html = $ArrayOrganize->generatePagination($pager);

        $this->assertStringContainsString('<ul class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);

        $ArrayOrganize->setByPage(3);
        $ArrayOrganize->setPage(2);
        $ArrayOrganize->setUrl("index.php?p={}");
        $html = $ArrayOrganize->generatePagination($pager);

        $this->assertStringContainsString('<ul class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
        $this->assertStringContainsString('<li class="">', $html);
    }

    /**
     * AddFunction function test
     * @covers ::addFunction
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testAddFunction(): void
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
        $this->assertStringContainsString('<tr><td>2</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertStringContainsString('<tr><td>1</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertStringContainsString('<tr><td>3</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertStringContainsString('<tr><td>6</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertStringContainsString('<tr><td>5</td><td>example</td></tr>', $ArrayOrganize->generateTable());

        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
        $this->assertStringContainsString('<tr><td>4</td><td>example</td></tr>', $ArrayOrganize->generateTable());
    }

    /**
     * AddFunction function invalid data test
     * @covers ::addFunction
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testAddFunctionInvalidData(): void
    {
        $datas = [
            2 => "example 5",
            1 => "example 5",
            3 => "example 3"
        ];
        $ArrayOrganize = new ArrayOrganize($datas);
        $this->expectException(\Exception::class);
        $ArrayOrganize->addFunction("name", "substr", [0, -2]);
    }

    /**
     * AddTotal function test
     * @covers ::addTotal
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testAddTotal(): void
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

        $this->assertStringContainsString(
            '<tr><td></td><td><strong>Total :</strong> 54</td></tr></tbody>',
            $ArrayOrganize->generateTable()
        );
    }

    /**
     * AddTotal function invalid data test
     * @covers ::addTotal
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testAddTotalInvalidData(): void
    {
        $datas = [
            2 => 6,
            1 => 6,
            3 => 3
        ];
        $ArrayOrganize = new ArrayOrganize($datas);
        $this->expectException(\Exception::class);
        $ArrayOrganize->addTotal("val", "<strong>Total :</strong> ");
    }

    /**
     * AddTotal function invalid filtre test
     * @covers ::addTotal
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testAddTotalInvalidFiltre(): void
    {
        $datas = [
            ["id" => 2, "val" => 6],
            ["id" => 1, "val" => 6],
            ["id" => 3, "val" => 3],
            ["id" => 6, "val" => 5]
        ];
        $ArrayOrganize = new ArrayOrganize($datas);
        $result = $ArrayOrganize->addTotal("text", "<strong>Total :</strong> ");
        $this->assertEquals(false, $result);
    }

    /**
     * GenerateList function test
     * @covers ::generateList
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testGenerateList(): void
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

        $html = $ArrayOrganize->generateList($cssClass);
        $this->assertStringContainsString('<ul class="list-group">', $html);
        $this->assertStringContainsString('<li class="list-group-item">', $html);
        $this->assertStringContainsString('<a class="">Test list 1</a>', $html);
        $this->assertStringContainsString(
            '<a class="" href="http://ddg.gg/" target="_blank">Test list 2</a>',
            $html
        );

        $ArrayOrganize = new ArrayOrganize([
            ["title" => "title 1"],
            ["test" => "title 2"],
        ]);
        $this->assertEquals(false, $ArrayOrganize->generateList($cssClass));

        $ArrayOrganize = new ArrayOrganize($datas);

        $cssClass = [
            "a" => "list-link",
            "disabled" => [
                "a" => "disabled",
            ],
            "active" => [
                "a" => "active",
            ],
            "balise" => "a/li"
        ];

        $html = $ArrayOrganize->generateList($cssClass);
        $this->assertStringContainsString('<ul class="">', $html);
        $this->assertStringContainsString('<a class="list-link">', $html);
        $this->assertStringContainsString('<li class="">Test list 1</li>', $html);
        $this->assertStringContainsString(
            '<a class="list-link" href="http://ddg.gg/" target="_blank"><li class="">Test list 2</li></a>',
            $html
        );

        $ArrayOrganize = new ArrayOrganize($datas);

        $cssClass = [
            "li" => "list-item",
            "balise" => "li"
        ];

        $html = $ArrayOrganize->generateList($cssClass);
        $this->assertStringContainsString('<ul class="">', $html);
        $this->assertStringContainsString('<li class="list-item">Test list 1</li>', $html);

        $ArrayOrganize = new ArrayOrganize($datas);

        $cssClass = [
            "a" => "list-link",
            "balise" => "a"
        ];

        $html = $ArrayOrganize->generateList($cssClass);
        $this->assertStringContainsString('<ul class="">', $html);
        $this->assertStringContainsString('<a class="list-link" >Test list 1</a>', $html);
        $this->assertStringContainsString(
            '<a class="list-link"  href="http://ddg.gg/" target="_blank">Test list 2</a>',
            $html
        );

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

        $html = $ArrayOrganize->generateList([]);
        $this->assertStringContainsString('<ul class=""></ul>', $html);
    }

    /**
     * Getters and Setters test
     * @depends testInitConstructor
     * @covers ::setByPage
     * @covers ::setPage
     * @covers ::setUrl
     * @covers ::setData
     * @covers ::getByPage
     * @covers ::getPage
     * @covers ::getUrl
     * @covers ::getData
     */
    public function testGetterSetter($ArrayOrganize): void
    {
        $ArrayOrganize->setByPage(10);
        $ArrayOrganize->setPage(2);
        $ArrayOrganize->setUrl("index.php?={}");
        $ArrayOrganize->setData(["test1", "test2"]);

        $byPage = $ArrayOrganize->getByPage();
        $page = $ArrayOrganize->getPage();
        $url = $ArrayOrganize->getUrl();
        $data = $ArrayOrganize->getData();

        $this->assertEquals(10, $byPage);
        $this->assertEquals(2, $page);
        $this->assertEquals("index.php?=2", $url);
        $this->assertEquals(["test1", "test2"], $data);
    }

    /**
     * setByPage test invalid argument
     * @depends testInitConstructor
     * @covers ::setByPage
     */
    public function testSetByPage($ArrayOrganize): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ArrayOrganize->setByPage(0);
    }

    /**
     * setPage test invalid argument
     * @depends testInitConstructor
     * @covers ::setPage
     */
    public function testSetPage($ArrayOrganize): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ArrayOrganize->setPage(0);
    }

    /**
     * setUrl test invalid argument
     * @depends testInitConstructor
     * @covers ::setUrl
     */
    public function testSetUrl($ArrayOrganize): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $ArrayOrganize->setUrl('');
    }
}
