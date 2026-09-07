<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoggerController extends Controller
{
    public function index()
    {
        // Get recent login/activity logs from DB (based on session/users)
        $users = \App\Models\User::with('profile')->latest()->get();
        return view('logger.index', compact('users'));
    }
}
