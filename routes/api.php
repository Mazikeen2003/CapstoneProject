<?php

use App\Http\Controllers\Api\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/projects/geojson', [ProjectController::class, 'geojson'])->name('api.projects.geojson');