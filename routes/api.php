<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\RepaymentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SettingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Kikundi API is running',
        'timestamp' => now()
    ]);
});

// Authentication Routes (Public)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Members
    Route::prefix('members')->group(function () {
        Route::get('/', [MemberController::class, 'index']);
        Route::post('/', [MemberController::class, 'store']);
        Route::get('/statistics', [MemberController::class, 'statistics']);
        Route::get('/{id}', [MemberController::class, 'show']);
        Route::put('/{id}', [MemberController::class, 'update']);
        Route::delete('/{id}', [MemberController::class, 'destroy']);
        Route::post('/{id}/check-eligibility', [MemberController::class, 'checkEligibility']);
    });

    // Investments
    Route::prefix('investments')->group(function () {
        Route::get('/', [InvestmentController::class, 'index']);
        Route::post('/', [InvestmentController::class, 'store']);
        Route::get('/statistics', [InvestmentController::class, 'statistics']);
        Route::get('/member/{memberId}', [InvestmentController::class, 'totalByMember']);
        Route::get('/{id}', [InvestmentController::class, 'show']);
        Route::put('/{id}', [InvestmentController::class, 'update']);
        Route::delete('/{id}', [InvestmentController::class, 'destroy']);
    });

    // Loans
    Route::prefix('loans')->group(function () {
        Route::get('/', [LoanController::class, 'index']);
        Route::post('/', [LoanController::class, 'store']);
        Route::get('/statistics', [LoanController::class, 'statistics']);
        Route::get('/overdue', [LoanController::class, 'overdueLoans']);
        Route::get('/{id}', [LoanController::class, 'show']);
        Route::put('/{id}', [LoanController::class, 'update']);
        Route::delete('/{id}', [LoanController::class, 'destroy']);
    });

    // Repayments
    Route::prefix('repayments')->group(function () {
        Route::get('/', [RepaymentController::class, 'index']);
        Route::post('/', [RepaymentController::class, 'store']);
        Route::get('/statistics', [RepaymentController::class, 'statistics']);
        Route::get('/loan/{loanId}', [RepaymentController::class, 'byLoan']);
        Route::get('/{id}', [RepaymentController::class, 'show']);
        Route::put('/{id}', [RepaymentController::class, 'update']);
        Route::delete('/{id}', [RepaymentController::class, 'destroy']);
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard']);
        Route::get('/quarterly', [ReportController::class, 'quarterly']);
        Route::get('/biannual', [ReportController::class, 'biannual']);
        Route::get('/annual', [ReportController::class, 'annual']);
        Route::get('/custom', [ReportController::class, 'custom']);
        Route::get('/member/{memberId}/statement', [ReportController::class, 'memberStatement']);
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::post('/', [SettingController::class, 'store']);
        Route::get('/all', [SettingController::class, 'getAll']);
        Route::post('/bulk-update', [SettingController::class, 'bulkUpdate']);
        Route::get('/{key}', [SettingController::class, 'show']);
        Route::put('/{key}', [SettingController::class, 'update']);
    });
});
