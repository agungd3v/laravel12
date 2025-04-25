<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, "login"])->name('login.api');
Route::post('/register', [AuthController::class, "register"])->name('register.api');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, "logout"])->name('logout.api');
});

Route::group(([
    "prefix" => "employee",
    "controller" => EmployeeController::class,
    "middleware" => ["throttle:api", "role:admin", "auth:api"]
]), function() {
    Route::get("/", "getEmployees");
    Route::post("/create", "createEmployee");
    Route::put("/update/{id}", "updateEmployee");
    Route::delete("/delete/{id}", "deleteEmployee");
});
