<?php

use App\Http\Controllers\BankStatementController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::controller(BankStatementController::class)->group(function () {
    Route::get("/", "index")->name("Welcome");
});

Route::prefix('api')->group(function () {
    Route::get('dashboard/summary', [DashboardController::class, 'index']);
    Route::get('deals/merchents', [DashboardController::class, 'getDeals']);
    Route::post('/merchant/{id}/upload-bank-statement', [BankStatementController::class,'store'])->name('statement.store');

});
