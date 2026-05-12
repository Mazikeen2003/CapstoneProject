<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return session('mock_user.role')
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    $role = session('mock_user.role');

    if (! $role) {
        return redirect()->route('login');
    }

    return match ($role) {
        'admin' => redirect('/admin/dashboard'),
        'city' => redirect('/city/dashboard'),
        'barangay' => redirect('/barangay/dashboard'),
        'department' => redirect('/department/dashboard'),
        default => view('dashboard'),
    };
})->name('dashboard');

// // Public routes
// Route::get('/public/map', function () {
//     return view('public.map');
// })->name('public.map');

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('admin.users.index');
    Route::get('/users/create', function () {
        return view('admin.users.create');
    })->name('admin.users.create');
    Route::get('/users/{id}/edit', function () {
        return view('admin.users.edit');
    })->name('admin.users.edit');
    Route::get('/audit-logs', function () {
        return view('admin.audit-logs.index');
    })->name('admin.audit-logs.index');
    Route::get('/reports', function () {
        return view('admin.reports.index');
    })->name('admin.reports.index');
});

// Department routes
Route::prefix('department')->group(function () {
    Route::get('/dashboard', function () {
        return view('department.dashboard');
    })->name('department.dashboard');
    Route::get('/projects', function () {
        return view('department.projects.index');
    })->name('department.projects.index');
    Route::get('/projects/create', function () {
        return view('department.projects.create');
    })->name('department.projects.create');
    Route::get('/projects/{id}/edit', function () {
        return view('department.projects.edit');
    })->name('department.projects.edit');
    Route::get('/projects/{id}', function () {
        return view('department.projects.show');
    })->name('department.projects.show');
    Route::get('/map', function () {
        return view('department.map.index');
    })->name('department.map.index');
    Route::get('/analytics', function () {
        return view('department.analytics.index');
    })->name('department.analytics.index');
    Route::get('/reports', function () {
        return view('department.reports.index');
    })->name('department.reports.index');
});

// City Official routes
Route::prefix('city')->group(function () {
    Route::get('/dashboard', function () {
        return view('city-official.dashboard');
    })->name('city.dashboard');
    Route::get('/reports', function () {
        return view('city-official.reports.index');
    })->name('city.reports.index');
});

// Barangay Official routes
Route::prefix('barangay')->group(function () {
    Route::get('/dashboard', function () {
        return view('barangay-official.dashboard');
    })->name('barangay.dashboard');
    Route::get('/projects', function () {
        return view('barangay-official.projects.index');
    })->name('barangay.projects.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/map', function () {
    return view('public.map');
})->name('public.map');

require __DIR__.'/auth.php';
