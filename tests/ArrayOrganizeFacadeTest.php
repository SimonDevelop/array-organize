<?php

namespace SimonDevelop\Test;

use \PHPUnit\Framework\TestCase;
use \SimonDevelop\ArrayOrganizeFacade;

/**
 * @coversDefaultClass \SimonDevelop\ArrayOrganizeFacade
 */
class ArrayOrganizeFacadeTest extends TestCase
{
    /**
     * DataSort function test
     * @covers ::dataSort
     * @uses \SimonDevelop\ArrayOrganizeFacade
     */
    public function testDataSort()
    {
        $data = [
            ["id" => 2, "name" => "test4"],
            ["id" => 1, "name" => "test5"],
            ["id" => 3, "name" => "test3"]
        ];
        $result = ArrayOrganizeFacade::dataSort($data, 'id', 'DESC');

        $this->assertEquals([
            ["id" => 3, "name" => "test3"],
            ["id" => 2, "name" => "test4"],
            ["id" => 1, "name" => "test5"]
        ], $result);

        $result = ArrayOrganizeFacade::dataSort($data, 'id', 'ASC');

        $this->assertEquals([
            ["id" => 1, "name" => "test5"],
            ["id" => 2, "name" => "test4"],
            ["id" => 3, "name" => "test3"]
        ], $result);

        $this->assertEquals(false, ArrayOrganizeFacade::dataSort([], 'id', 'ASC'));
    }

    /**
     * DataSort function invalid argument test
     * @covers ::dataSort
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataSortInvalidArgument(): void
    {
        $data = [
            ["id" => 2, "name" => "test4"],
            ["id" => 1, "name" => "test5"],
            ["id" => 3, "name" => "test3"]
        ];
        $this->expectException(\InvalidArgumentException::class);
        ArrayOrganizeFacade::dataSort($data, 'text', 'DESC');
    }

    /**
     * DataSort function invalid data test
     * @covers ::dataSort
     * @uses \SimonDevelop\ArrayOrganize
     */
    public function testDataSortInvalidData(): void
    {
        $data = [
            0 => "test4",
            1 => "test5",
            2 => "test3"
        ];
        $this->expectException(\Exception::class);
        ArrayOrganizeFacade::dataSort($data, 'id', 'DESC');
    }

    /**
     * DataColumnFilter function test
     * @covers ::dataColumnFilter
     * @uses \SimonDevelop\ArrayOrganizeFacade
     */
    public function testDataColumnFilter()
    {
        $data = [
            ["id" => 2, "name" => "test3"],
            ["id" => 1, "name" => "test4"],
            ["id" => 3, "name" => "test5"]
        ];
        $result = ArrayOrganizeFacade::dataColumnFilter($data, "skip", ["id"]);

        $this->assertEquals([
            ["name" => "test3"],
            ["name" => "test4"],
            ["name" => "test5"]
        ], $result);

        $result = ArrayOrganizeFacade::dataColumnFilter($data, "keep", ["name"]);
        $this->assertEquals([
            ["name" => "test3"],
            ["name" => "test4"],
            ["name" => "test5"]
        ], $result);

        $result = ArrayOrganizeFacade::dataColumnFilter([], "skip", ["id"]);
        $this->assertEquals(false, $result);
    }

    /**
     * DataColumnFilter function invalid argument test
     * @covers ::dataColumnFilter
     * @uses \SimonDevelop\ArrayOrganizeFacade
     */
    public function testDataColumnFilterInvalidArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ArrayOrganizeFacade::dataColumnFilter([
            ["id" => 2, "name" => "test3"],
            ["id" => 1, "name" => "test4"],
            ["id" => 3, "name" => "test5"]
        ], "test", ["id"]);
    }

    /**
     * dataColumnFilter function invalid data test
     * @covers ::dataColumnFilter
     * @uses \SimonDevelop\ArrayOrganizeFacade
     */
    public function testDataColumnFilterInvalidData(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ArrayOrganizeFacade::dataColumnFilter([
            0 => "test4",
            1 => "test5",
            2 => "test3"
        ], "skip", ["id"]);
    }

    /**
     * DataFilter function test
     * @covers ::dataFilter
     * @uses \SimonDevelop\ArrayOrganizeFacade
     */
    public function testDataFilter()
    {
        // is
        $data = [
            ["id" => 2, "name" => "test3"],
            ["id" => 1, "name" => "test4"],
            ["id" => 3, "name" => "test5"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["id" => 2]);

        $this->assertEquals([
            ["id" => 2, "name" => "test3"]
        ], $result);

        // is not
        $data = [
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["name[!=]" => "js"]);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"]
        ], $result);

        // like
        $data = [
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["name" => "%php%"]);

        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"]
        ], $result);

        // upper
        $data = [
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["id[>]" => 1]);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 3, "name" => "js"]
        ], $result);

        // upper and equal
        $data = [
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["id[>=]" => 1]);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ], $result);

        // lower
        $data = [
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["id[<]" => 2]);
        $this->assertEquals([
            ["id" => 1, "name" => "php is a live"]
        ], $result);

        // lower and equal
        $data = [
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
            ["id" => 3, "name" => "js"]
        ];
        $result = ArrayOrganizefacade::dataFilter($data, ["id[<=]" => 2]);
        $this->assertEquals([
            ["id" => 2, "name" => "php"],
            ["id" => 1, "name" => "php is a live"],
        ], $result);

        // date lower
        $data = [
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["date[<]" => "2021-07-24"]);
        $this->assertEquals([
            ["id" => 2, "date" => "2021-08-21"]
        ], $result);

        // date lower and equal
        $data = [
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["date[<=]" => "2021-07-24"]);
        $this->assertEquals([
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ], $result);

        // date upper
        $data = [
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["date[>]" => "2021-07-24"]);
        $this->assertEquals([
            ["id" => 1, "date" => "2021-07-21"]
        ], $result);

        // date upper and equal
        $data = [
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ];
        $result = ArrayOrganizeFacade::dataFilter($data, ["date[>=]" => "2021-07-24"]);
        $this->assertEquals([
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ], $result);
        
        // error
        $result = ArrayOrganizeFacade::dataFilter([], ["id" => 2]);
        $this->assertEquals(false, $result);
    }

    /**
     * DataFilter function invalid data one test
     * @covers ::dataFilter
     * @uses \SimonDevelop\ArrayOrganizeFacade
     */
    public function testDataFilterInvalidDataOne(): void
    {
        $this->expectException(\Exception::class);
        ArrayOrganizeFacade::dataFilter([
            0 => "test3",
            1 => "test4",
            2 => "test5"
        ], ["id" => 2]);
    }

    /**
     * DataFilter function invalid data two test
     * @covers ::dataFilter
     * @uses \SimonDevelop\ArrayOrganizeFacade
     */
    public function testDataFilterInvalidDataTwo(): void
    {
        $this->expectException(\Exception::class);
        ArrayOrganizeFacade::dataFilter([
            ["id" => 2, "date" => "2021-08-21"],
            ["id" => 1, "date" => "2021-07-21"],
            ["id" => 3, "date" => "2021-07-24"]
        ], ["id" => []]);
    }
}
