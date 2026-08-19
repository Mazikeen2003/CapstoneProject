<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

class NotificationPageController extends Controller
{
    public function index(Request $request): Response
    {
        $layout = match ($request->user()->role_slug) {
            'admin' => 'layouts.admin',
            'city' => 'layouts.city',
            'barangay' => 'layouts.barangay',
            'department' => 'layouts.department',
            default => 'layouts.public',
        };

        return response()->view('notifications.index', compact('layout'));
    }
}