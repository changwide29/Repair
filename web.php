<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RepairTicketController;
use App\Http\Controllers\AuthController;

// หน้า Login & Logout (เข้าถึงได้ทุกคน)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// กลุ่มที่ต้อง Login ก่อนถึงจะเข้าใช้งานได้
Route::middleware('auth')->group(function () {
    
    // หน้าฟอร์มแจ้งซ่อม (คนทั่วไปที่ Login แล้ว)
    Route::get('/', [RepairTicketController::class, 'create'])->name('repair.create');
    Route::post('/store', [RepairTicketController::class, 'store'])->name('repair.store');

    // หน้า Admin (สำหรับ admin และ technical)
    Route::get('/admin', [RepairTicketController::class, 'index'])->name('admin.index');
    Route::post('/admin/update/{id}', [RepairTicketController::class, 'updateStatus'])->name('admin.updateStatus');
    Route::delete('/admin/delete/{id}', [RepairTicketController::class, 'destroy'])->name('admin.delete');
    Route::get('/repair/receipt/{id}', [App\Http\Controllers\RepairTicketController::class, 'receipt'])->name('repair.receipt');
});