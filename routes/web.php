<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentApplicationController;
use App\Http\Controllers\AdmissionOfficerController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/overview-pdf', function () {
    return view('project_overview_pdf');
})->name('overview.pdf');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->hasRole('Admission_Officer')) {
        return redirect()->route('officer.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // مسارات الملف الشخصي والإشعارات
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markRead');
    
    // مسارات تذاكر الدعم العامة
    Route::get('/tickets', [TicketController::class, 'studentIndex'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');

    // مسارات الطالب
    Route::middleware(['role:Student'])->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentApplicationController::class, 'index'])->name('student.dashboard');
        Route::post('/applications', [StudentApplicationController::class, 'store'])->name('student.applications.store');
        Route::put('/applications/{application}', [StudentApplicationController::class, 'update'])->name('student.applications.update');
    });

    // مسارات موظف القبول والتصدير والتذاكر
    Route::middleware(['role:Admission_Officer'])->prefix('officer')->group(function () {
        Route::get('/dashboard', [AdmissionOfficerController::class, 'index'])->name('officer.dashboard');
        Route::patch('/applications/{application}/status', [AdmissionOfficerController::class, 'updateStatus'])->name('officer.applications.updateStatus');
        Route::patch('/admission-settings', [AdmissionOfficerController::class, 'updateAdmissionSettings'])->name('officer.admissionSettings.update');
        
        // مسارات تذاكر الموظف
        Route::get('/tickets', [TicketController::class, 'officerIndex'])->name('officer.tickets.index');
        Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('officer.tickets.updateStatus');

        // مسارات تصدير التقارير
        Route::get('/export/csv', [ExportController::class, 'exportCsv'])->name('officer.export.csv');
        Route::get('/export/print', [ExportController::class, 'printReport'])->name('officer.export.print');

        // مسارات إدارة التخصصات
        Route::post('/majors', [AdmissionOfficerController::class, 'storeMajor'])->name('officer.majors.store');
        Route::put('/majors/{major}', [AdmissionOfficerController::class, 'updateMajor'])->name('officer.majors.update');
        Route::delete('/majors/{major}', [AdmissionOfficerController::class, 'destroyMajor'])->name('officer.majors.destroy');
    });

    // مسارات إدارة المستخدمين والأدوار
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.updateRole');
    });

});

require __DIR__.'/auth.php';

// توجيه تلقائي لمنع ظهور 404 وتوجيه المستخدم دائماً للوحة التحكم
Route::get('/home', function() {
    return redirect()->route('dashboard');
});

Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});