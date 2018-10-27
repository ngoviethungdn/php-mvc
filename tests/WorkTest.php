<?php

require dirname(__FILE__) . '/database.php';

use PHPUnit\Framework\TestCase;
use App\Controllers\WorkController;

final class WorkTest extends TestCase
{
    protected $work;

    private $data = [
        [
            "name" => "Test",
            "status" => 1,
            "start_date" => "2018-10-10",
            "end_date" => "2018-10-11",
        ],
        [
            "name" => "Test",
            "status" => 2,
            "start_date" => "2018-10-11",
            "end_date" => "2018-10-11",
        ],
        [
            "name" => "Test",
            "status" => 3,
            "start_date" => "2018-10-10",
            "end_date" => "2018-10-11",
        ]
    ];

    private $invalidData = [
        [
            "data" => ["status" => 100, "status" => 0, "status" => 4],
            "errors" => ["Status is invalid"]
        ],
        [
            "data" => [
                "start_date" => "2018-10",
                "start_date" => "10-10",
                "end_date" => "10-2018-10",
                "end_date" => "2018-10-32",
                "start_date" => "2018-13-32",
            ],
            "errors" => ["Invalid format Y-m-d"]
        ],
        [
            "data" => [
                "start_date" => "2018-10-15",
                "end_date" => "2017-10-11"
            ],
            "errors" => ["The start date must be a date before end date"]
        ]
    ];

    protected function setUp()
    {
        $this->work =new WorkController();
    }

    public function testValidate(): void
    {
        // Test case: valid validate
        foreach($this->data as $item) {
            $validate = $this->work->validate($item);
            $this->assertTrue($validate["status"]);
        }

        // Test case: invalid validate
        $fields = ["name", "status", "start_date", "end_date"];
        $errors = [];

        // Case: input empty
        $validate = $this->work->validate([]);
        foreach($fields as $item) {
            $errors[] = "$item must be required";
        }
        $this->assertFalse($validate["status"]);
        $this->assertSame($errors, $validate["errors"]);

        // Case: invalid status, start_date, end_date
        $sampleData = $this->data[0];
        foreach($this->invalidData as $item) {
            foreach($item["data"] as $key => $val) {
                $sampleData = $this->data[0];
                $sampleData[$key] = $val;
                $validate = $this->work->validate($sampleData);
                $this->assertFalse($validate["status"]);
                $this->assertSame($item["errors"], $validate["errors"]);
            }
        }
    }

}