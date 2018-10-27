<?php

namespace App\Controllers;

use App\Models\Work;
use Core\Controller;
use Core\View;

class WorkController extends Controller
{

    /**
     * @var work model
     */
    private $work;

    public function __construct()
    {
        $this->work = new Work();
    }

    /**
     * Render home page
     */
    public function index()
    {
        View::render('home');
    }

    /**
     * List all works
     */
    public function list() {
        try {
            $data = $this->work->get();
            return $this->responseJSON($data);
        } catch (Exception $e) {
            return $this->responseJSON('Error', 500);
        }
    }

    /**
     * @param $request Request data
     * @return JSON
     */
    public function create($request)
    {
        try {
            $validate = $this->validate($request);
            if ($validate["status"]) {
                return $this->responseJSON($this->work->create($request));
            } else {
                return $this->responseJSON($validate["errors"], 400);
            }
        } catch (Exception $e) {
            return $this->responseJSON('Error', 500);
        }
    }

    /**
     * @param $id      Work id
     * @param $request Request data
     * @return JSON
     */
    public function update($id, $request)
    {
        try {
            $validate = $this->validate($request);
            if ($validate["status"]) {
                return $this->responseJSON($this->work->where('id', '=', $id)->update($request));
            } else {
                return $this->responseJSON($validate["errors"], 400);
            }
        } catch (Exception $e) {
            return $this->responseJSON('Error', 500);
        }
    }

    /**
     * @param $id Work id
     * @return JSON
     */
    public function delete($id)
    {
        try {
            return $this->responseJSON($this->work->where('id', '=', $id)->delete());
        } catch (Exception $e) {
            return $this->responseJSON('Error', 500);
        }
    }

    /**
     * @param $data Array data from request
     */
    public function validate($data)
    {
        $errors = [];
        // Validate required fields
        $fields = ["name", "status", "start_date", "end_date"];
        foreach ($fields as $item) {
            if (empty($data[$item])) {
                $errors[] = "$item must be required";
            }
        }
        if (empty($errors)) {
            // Validate status must be Planning, Doing, Complete
            if (!in_array($data["status"], Work::STATUS)) {
                $errors[] = "Status is invalid";
            }
            // Using for check format of date is Y-m-d
            $checkDateFormat = function ($date) {
                $tempDate = explode('-', $date);
                // checkdate(month, day, year)
                return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
            };
            // Validate format of date
            if (!$checkDateFormat($data["start_date"]) || !$checkDateFormat($data["end_date"])) {
                $errors[] = "Invalid format Y-m-d";
            } elseif ($data["start_date"] > $data["end_date"]) {
                // Validate the start date < end date
                $errors[] = "The start date must be a date before end date";
            }
            unset($checkDateFormat);
        }
        unset($fields);
        return [
            "status" => count($errors) === 0,
            "errors" => $errors,
        ];
    }

}
