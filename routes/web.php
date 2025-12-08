<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\TeacherEnrollmentController;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CompanyAddressController;
use App\Http\Controllers\SpecificCourseController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyLocationController;
use App\Http\Controllers\CalendarEventController;

use App\Http\Controllers\ChatController;
use App\Http\Controllers\EnrollmentssController;
use App\Http\Controllers\SubCourseController;
use App\Http\Controllers\TeacherChatController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\SettingsController;


// 🏠 Welcome Page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// 📊 Dashboard (Controller-based)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 👤 Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Chat
    

    // Enrollmentss Routes (only define once)
    
    
   
Route::resource('enrollmentss', EnrollmentssController::class)->middleware('auth');


    Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/messages/{userId}', [ChatController::class, 'fetchMessages']);
    Route::post('/chat', [ChatController::class, 'store']);
});

});

   Route::middleware('auth')->prefix('enrollmentss')->name('enrollmentss.')->group(function () {
    Route::get('{enrollmentss}/available-students', [EnrollmentssController::class, 'availableStudents'])
        ->name('available-students');

    Route::post('{enrollmentss}/assign-student', [EnrollmentssController::class, 'assignStudent'])
        ->name('assign-student');

    Route::post('{enrollmentss}/unassign-student', [EnrollmentssController::class, 'unassignStudent'])
        ->name('unassign-student');
});



















Route::get('/calendar-events', function () {
    return Inertia::render('CalendarEvents'); // <-- matches the page above
});

Route::get('/calendar-events', [CalendarEventController::class, 'index']);
Route::post('/calendar-events', [CalendarEventController::class, 'store']);
Route::put('/calendar-events/{calendarEvent}', [CalendarEventController::class, 'update']);
Route::delete('/calendar-events/{calendarEvent}', [CalendarEventController::class, 'destroy']);
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});
// Vue app route (serve your Vue component)
Route::get('/calendar', function () {
    return view('calendar');
});


Route::get('/calendar-events', [CalendarEventController::class, 'index']);
Route::post('/calendar-events', [CalendarEventController::class, 'store']);
Route::put('/calendar-events/{id}', [CalendarEventController::class, 'update']);
Route::delete('/calendar-events/{id}', [CalendarEventController::class, 'destroy']);



Route::get('/enrollments/simple', [EnrollmentssController::class, 'listSimple']);


Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/chat', [TeacherChatController::class, 'index'])->name('teacher.chat');
    Route::post('/teacher/chat/send', [TeacherChatController::class, 'send'])->name('teacher.chat.send');
});



Route::middleware(['auth'])->group(function () {
    // SubCourses
    Route::get('/sub-courses/create', [SubCourseController::class, 'create'])->name('sub-courses.create');
    Route::post('/sub-courses', [SubCourseController::class, 'store'])->name('sub-courses.store');
    Route::get('/sub-courses/{subCourse}/edit', [SubCourseController::class, 'edit'])->name('sub-courses.edit');
    Route::put('/sub-courses/{subCourse}', [SubCourseController::class, 'update'])->name('sub-courses.update');
    Route::delete('/sub-courses/{subCourse}', [SubCourseController::class, 'destroy'])->name('sub-courses.destroy');





});


// 🧭 Authenticated CRUD routes (excluding 'show')
Route::middleware(['auth'])->group(function () {
    Route::resource('admins', AdminController::class)->except(['show']);
    Route::resource('resources', ResourceController::class)->except(['show']);
    Route::resource('batches', BatchController::class)->except(['show']);
    Route::resource('teacher-enrollments', TeacherEnrollmentController::class)->except(['show']);
    Route::resource('student-enrollments', StudentEnrollmentController::class)->except(['show']);
    Route::resource('courses', CourseController::class)->except(['show']);
    Route::resource('students', StudentController::class)->except(['show']);
    Route::resource('company-addresses', CompanyAddressController::class)->except(['show']);
    Route::resource('specific-courses', SpecificCourseController::class)->except(['show']);
    Route::resource('teachers', TeacherController::class)->except(['show']);
    Route::resource('teachers', TeacherController::class);
// or if you need to exclude some other methods:
Route::resource('teachers', TeacherController::class)->except(['someMethod']);

    Route::resource('locations', LocationController::class)->except(['show']);
    Route::resource('company-locations', CompanyLocationController::class)->except(['show']);
    Route::resource('calendar-events', CalendarEventController::class)->except(['show']);
Route::resource('company-addresses', CompanyAddressController::class);
Route::resource('course-type', CourseTypeController::class);

Route::resource('calendar-events', CalendarEventController::class);




    // Student enrollment actions
    Route::post('/students/{student}/assign-enrollment', [StudentController::class, 'assignToEnrollment'])
        ->name('students.assign-enrollment');
    Route::post('/students/{student}/unassign-enrollment', [StudentController::class, 'unassignFromEnrollment'])
        ->name('students.unassign-enrollment');
    Route::get('/students/{student}/enrollment-status/{enrollment}', [StudentController::class, 'getEnrollmentStatus'])
        ->name('students.enrollment-status');
});
   Route::resource('students', StudentController::class);
// 🔐 Auth routes
require __DIR__ . '/auth.php';