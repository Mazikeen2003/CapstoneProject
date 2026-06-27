<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Department\DashboardController as DepartmentDashboard;
use App\Http\Controllers\Department\ProjectController as DepartmentProjectController;
use App\Http\Controllers\Department\MapController as DepartmentMapController;
use App\Http\Controllers\Department\AnalyticsController as DepartmentAnalyticsController;
use App\Http\Controllers\Department\ReportExportController as DepartmentReportController;
use App\Http\Controllers\CityOfficial\DashboardController as CityDashboard;
use App\Http\Controllers\CityOfficial\MapController as CityMapController;
use App\Http\Controllers\CityOfficial\AnalyticsController as CityAnalyticsController;
use App\Http\Controllers\CityOfficial\ReportController as CityReportController;
use App\Http\Controllers\BarangayOfficial\DashboardController as BarangayDashboard;
use App\Http\Controllers\BarangayOfficial\ProjectController as BarangayProjectController;
use App\Http\Controllers\BarangayOfficial\MapController as BarangayMapController;
use App\Http\Controllers\BarangayOfficial\AnalyticsController as BarangayAnalyticsController;
use App\Http\Controllers\Public\MapController as PublicMapController;
use App\Http\Controllers\Public\AnalyticsController as PublicAnalyticsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Rate Limiting
|--------------------------------------------------------------------------
*/
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->input('email').'|'.$request->ip());
});

/*
|--------------------------------------------------------------------------
| Root redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Role-aware dashboard redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role_slug) {
        'admin'      => redirect()->route('admin.dashboard'),
        'city'       => redirect()->route('city.dashboard'),
        'barangay'   => redirect()->route('barangay.dashboard'),
        'department' => redirect()->route('department.dashboard'),
        default      => view('dashboard'),
    };
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Public routes (no auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/map',       [PublicMapController::class,       'index'])->name('map');
    Route::get('/analytics', [PublicAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/map', [\App\Http\Controllers\Public\MapController::class, 'index'])->name('public.map');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('users', AdminUserController::class)->names([
            'index'   => 'users.index',
            'create'  => 'users.create',
            'store'   => 'users.store',
            'edit'    => 'users.edit',
            'update'  => 'users.update',
            'destroy' => 'users.destroy',
        ]);

        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/reports',    [AdminReportController::class,   'index'])->name('reports.index');
    });

/*
|--------------------------------------------------------------------------
| Department routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'department'])
    ->prefix('department')
    ->name('department.')
    ->group(function () {

        Route::get('/dashboard', [DepartmentDashboard::class, 'index'])->name('dashboard');

        Route::resource('projects', DepartmentProjectController::class)->names([
            'index'   => 'projects.index',
            'create'  => 'projects.create',
            'store'   => 'projects.store',
            'show'    => 'projects.show',
            'edit'    => 'projects.edit',
            'update'  => 'projects.update',
            'destroy' => 'projects.destroy',
        ]);

        Route::get('/map',       [DepartmentMapController::class,      'index'])->name('map.index');
        Route::get('/analytics', [DepartmentAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/reports',   [DepartmentReportController::class,    'index'])->name('reports.index');
        Route::get('/reports', [\App\Http\Controllers\Department\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/projects/pdf', [\App\Http\Controllers\Department\ReportController::class, 'projectsPdf'])->name('reports.projects-pdf');
        Route::get('/reports/budget/pdf', [\App\Http\Controllers\Department\ReportController::class, 'budgetPdf'])->name('reports.budget-pdf');

    });

/*
|--------------------------------------------------------------------------
| City Official routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'city'])
    ->prefix('city')
    ->name('city.')
    ->group(function () {

        Route::get('/dashboard', [CityDashboard::class,          'index'])->name('dashboard');
        Route::get('/map',       [CityMapController::class,       'index'])->name('map.index');
        Route::get('/analytics', [CityAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/reports',   [CityReportController::class,    'index'])->name('reports.index');
        Route::get('/reports', [\App\Http\Controllers\CityOfficial\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/projects/pdf', [\App\Http\Controllers\CityOfficial\ReportController::class, 'projectsPdf'])->name('reports.projects-pdf');
        Route::get('/reports/budget/pdf', [\App\Http\Controllers\CityOfficial\ReportController::class, 'budgetPdf'])->name('reports.budget-pdf');
    });

/*
|--------------------------------------------------------------------------
| Barangay Official routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'barangay'])
    ->prefix('barangay')
    ->name('barangay.')
    ->group(function () {

        Route::get('/dashboard', [BarangayDashboard::class,          'index'])->name('dashboard');
        Route::get('/map',       [BarangayMapController::class,       'index'])->name('map');
        Route::get('/analytics', [BarangayAnalyticsController::class, 'index'])->name('analytics');
        Route::get('/reports', [\App\Http\Controllers\BarangayOfficial\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/projects/pdf', [\App\Http\Controllers\BarangayOfficial\ReportController::class, 'projectsPdf'])->name('reports.projects-pdf');
        Route::get('/reports/budget/pdf', [\App\Http\Controllers\BarangayOfficial\ReportController::class, 'budgetPdf'])->name('reports.budget-pdf');

        Route::resource('projects', BarangayProjectController::class)->names([
            'index'  => 'projects.index',
            'show'   => 'projects.show',
        ])->only(['index', 'show']);
    });

/*
|--------------------------------------------------------------------------
| Profile (any authenticated user)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/api/projects/geojson', [\App\Http\Controllers\Api\ProjectController::class, 'geojson'])->name('api.projects.geojson');
});