<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(([
    "prefix" => "employee",
    "controller" => EmployeeController::class,
    "middleware" => ["throttle:api"]
]), function() {
    Route::get("/", "getEmployees");
    Route::post("/create", "createEmployee");
    Route::put("/update/{id}", "updateEmployee");
    Route::delete("/delete/{id}", "deleteEmployee");
});
