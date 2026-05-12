<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index()
    {
        return view('department.projects.index');
    }

    public function create()
    {
        return view('department.projects.create');
    }

    public function edit($id)
    {
        return view('department.projects.edit');
    }

    public function show($id)
    {
        return view('department.projects.show');
    }
}