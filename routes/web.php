<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    $roleSlug = $user->role_slug;

    return match ($roleSlug) {
        'admin' => redirect()->route('admin.dashboard'),
        'city' => redirect()->route('city.dashboard'),
        'barangay' => redirect()->route('barangay.dashboard'),
        'department' => redirect()->route('department.dashboard'),
        default => view('dashboard'),
    };
})->name('dashboard');

// Public routes
Route::prefix('public')->group(function () {
    Route::get('/map', function () {
        return view('public.map');
    })->name('public.map');
    Route::get('/analytics', function () {
        return view('public.analytics');
    })->name('public.analytics');
});

// Admin routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class, [
        'names' => [
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]
    ]);
    
    Route::get('/audit-logs', function () {
        return view('admin.audit-logs.index');
    })->name('admin.audit-logs.index');
    Route::get('/reports', function () {
        return view('admin.reports.index');
    })->name('admin.reports.index');
});

// Department routes
Route::middleware('auth')->prefix('department')->group(function () {
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
Route::middleware('auth')->prefix('city')->group(function () {
    Route::get('/dashboard', function () {
        return view('city-official.dashboard');
    })->name('city.dashboard');
    Route::get('/map', function () {
        return view('city-official.map.index');
    })->name('city.map.index');
    Route::get('/analytics', function () {
        return view('city-official.analytics.index');
    })->name('city.analytics.index');
});

// Barangay Official routes
Route::middleware('auth')->prefix('barangay')->group(function () {
    Route::get('/dashboard', function () {
        return view('barangay-official.dashboard');
    })->name('barangay.dashboard');
    Route::get('/projects', function () {
        return view('barangay-official.projects.index');
    })->name('barangay.projects.index');
    Route::get('/map', function () {
        return view('barangay-official.map');
    })->name('barangay.map');
    Route::get('/analytics', function () {
        return view('barangay-official.analytics');
    })->name('barangay.analytics');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
