<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request)
    {
        return Inertia::render('admin/Dashboard', [
            'admin' => $request->user(),
        ]);
    }
}
