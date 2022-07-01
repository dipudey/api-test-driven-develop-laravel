<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CheckedinController;
use App\Http\Controllers\CheckedoutController;

Route::apiResource("book", BookController::class);

Route::group(["middleware" => ['auth:sanctum']], function () {
    Route::post("checkout/{book_id}", [CheckedoutController::class, 'checkedout']);
    Route::post("checkin/{book_id}", [CheckedinController::class, 'checkedin']);
});
