<?php

namespace SimonDevelop\Test;

use \PHPUnit\Framework\TestCase;
use \SimonDevelop\ArrayOrganizeFacade;

class ArrayOrganizeFacadeTest extends TestCase
{
    /**
     * DataSort function test
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
    }

    /**
     * DataColumnFilter function test
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
    }

    /**
     * DataFilter function test
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

        // error
        $result = ArrayOrganizeFacade::dataFilter([], ["id" => 2]);

        $this->assertEquals(false, $result);
    }
}
