<?php

use App\Http\Controllers\Api\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/projects/geojson', [ProjectController::class, 'geojson'])->name('api.projects.geojson');
    Route::get('/public/projects/geojson', [\App\Http\Controllers\Public\MapController::class, 'geojson'])->name('api.public.geojson');
});