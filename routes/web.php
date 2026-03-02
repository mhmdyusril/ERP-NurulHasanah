<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/set-telegram-webhook', function () {
    $url = url('/api/telegram/webhook');
    try {
        $response = \Telegram\Bot\Laravel\Facades\Telegram::setWebhook(['url' => $url]);
        return "✅ Webhook Berhasil Disetel ke: " . $url . "<br>Response: " . json_encode($response);
    } catch (\Exception $e) {
        return "❌ Gagal menyetel Webhook: " . $e->getMessage();
    }
});

Route::get('/get-telegram-webhook-info', function () {
    try {
        $response = \Telegram\Bot\Laravel\Facades\Telegram::getWebhookInfo();
        return "🔍 Webhook Info: <pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";
    } catch (\Exception $e) {
        return "❌ Gagal mengambil info Webhook: " . $e->getMessage();
    }
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modul yang bisa diakses Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('teachers', \App\Http\Controllers\TeacherController::class)->except(['show']);
        Route::resource('classes', \App\Http\Controllers\ClassesController::class);
        Route::patch('students/{student}/status', [\App\Http\Controllers\StudentController::class, 'updateStatus'])->name('students.updateStatus');
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::resource('payments', \App\Http\Controllers\PaymentController::class);
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'finance'])->name('reports.finance');
        Route::get('reports/export', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('reports.export');
    });

    // Modul yang bisa diakses Admin dan Guru
    Route::middleware(['role:admin,guru'])->group(function () {
        Route::resource('students', \App\Http\Controllers\StudentController::class);
        Route::resource('academics', \App\Http\Controllers\AcademicRecordController::class);
        Route::get('attendances', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendances.index');
        Route::post('attendances', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendances.store');
        Route::resource('savings', \App\Http\Controllers\SavingController::class)->only(['index', 'show', 'store']);
    });
});

require __DIR__.'/auth.php';
