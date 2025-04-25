<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Mail\EmployeeEmail;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends Controller
{
    public function getEmployees() {
        return response()->json([
            "status" => 200,
            "data" => Employee::all()
        ]);
    }

    public function createEmployee(Request $request) {
        try {
            DB::beginTransaction();

            $employee = new Employee();
            $employee->name = $request->name;
            $employee->age = $request->age;
            $employee->job_title = $request->job_title;
            $employee->save();

            DB::commit();

            dispatch(new SendEmailJob([
                "email" => "testreceiver@gmail.com",
                "name" => $employee->name
            ]));

            return response()->json([
                "status" => 200,
                "message" => "success"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                "status"=> 500,
                "message"=> $e->getMessage()
            ]);
        }
    }

    public function updateEmployee(Request $request, $id) {
        try {
            DB::beginTransaction();

            $employee = Employee::find($id);
            if (! $employee) throw new \Exception("Employee not found");

            $employee->name = $request->name;
            $employee->age = $request->age;
            $employee->job_title = $request->job_title;
            $employee->save();

            DB::commit();

            return response()->json([
                "status" => 200,
                "message" => "success"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                "status"=> 500,
                "message"=> $e->getMessage()
            ]);
        }
    }

    public function deleteEmployee(Request $request, $id) {
        try {
            DB::beginTransaction();

            $employee = Employee::find($id);
            if (! $employee) throw new \Exception("Employee not found");

            $employee->delete();

            DB::commit();

            return response()->json([
                "status" => 200,
                "message" => "success"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                "status"=> 500,
                "message"=> $e->getMessage()
            ]);
        }
    }
}
