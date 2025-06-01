<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CheckRole;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes - landing page is homepage
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lessen', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessen/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContactForm'])->name('contact.send');
Route::get('/over-ons', [HomeController::class, 'about'])->name('about');

// Include auth routes from auth.php
require __DIR__.'/auth.php';

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profiel', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profiel', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profiel', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking routes (for all authenticated users)
    Route::get('/boekingen', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/boekingen/aanmaken', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/boekingen', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/boekingen/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/boekingen/{booking}/bewerken', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::patch('/boekingen/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/boekingen/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::patch('/boekingen/{booking}/annuleren', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Instructor routes - using direct middleware class reference
    Route::middleware([CheckRole::class.':instructor'])->group(function () {
        Route::get('/instructeur/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
        Route::get('/instructeur/rooster', [InstructorController::class, 'schedule'])->name('instructor.schedule');
        Route::get('/instructeur/boekingen', [InstructorController::class, 'bookings'])->name('instructor.bookings');
        Route::patch('/instructeur/boekingen/{booking}/status', [InstructorController::class, 'updateBookingStatus'])->name('instructor.bookings.status');
    });

    // Admin routes - using direct middleware class reference
    Route::middleware([CheckRole::class.':admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/lessen', [AdminController::class, 'lessons'])->name('admin.lessons');
        Route::get('/admin/lessen/aanmaken', [AdminController::class, 'createLesson'])->name('admin.lessons.create');
        Route::post('/admin/lessen', [AdminController::class, 'storeLesson'])->name('admin.lessons.store');
        Route::get('/admin/lessen/{lesson}/bewerken', [AdminController::class, 'editLesson'])->name('admin.lessons.edit');
        Route::patch('/admin/lessen/{lesson}', [AdminController::class, 'updateLesson'])->name('admin.lessons.update');
        Route::delete('/admin/lessen/{lesson}', [AdminController::class, 'destroyLesson'])->name('admin.lessons.destroy');
        Route::get('/admin/boekingen', [AdminController::class, 'bookings'])->name('admin.bookings');
        Route::get('/admin/boekingen/{booking}/bewerken', [AdminController::class, 'editBooking'])->name('admin.bookings.edit');
        Route::patch('/admin/boekingen/{booking}', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');
        Route::delete('/admin/boekingen/{booking}', [AdminController::class, 'destroyBooking'])->name('admin.bookings.destroy');
        Route::get('/admin/gebruikers', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/gebruikers/aanmaken', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/gebruikers', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/gebruikers/{user}/bewerken', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::patch('/admin/gebruikers/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/gebruikers/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    });
});
