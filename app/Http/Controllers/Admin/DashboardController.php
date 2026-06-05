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
        $pending = Complaint::where('status', 'menunggu')->count();
        $proses = Complaint::where('status', 'diproses')->count();
        $selesai = Complaint::where('status', 'selesai')->count();
        $ditolak = Complaint::where('status', 'ditolak')->count();
        $latestComplaints = Complaint::with(['user', 'category'])
            ->latest()
            ->take(6)
            ->get();

        $users = User::count();

        return view('admin.dashboard', compact(
            'totalComplaint',
            'pending',
            'proses',
            'selesai',
            'ditolak',
            'latestComplaints',
            'users'
        ));
    }
}
