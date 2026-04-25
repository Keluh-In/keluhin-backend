<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalComplaint = Complaint::count();
        $pending = Complaint::where('status', 'pending')->count();
        $proses = Complaint::where('status', 'proses')->count();
        $selesai = Complaint::where('status', 'selesai')->count();

        $users = User::count();

        return view('admin.dashboard', compact(
            'totalComplaint',
            'pending',
            'proses',
            'selesai',
            'users'
        ));
    }
}