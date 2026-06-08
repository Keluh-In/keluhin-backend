<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
<<<<<<< HEAD
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Statistik (Selalu dihitung)
=======

class DashboardController extends Controller
{
    public function index()
    {
>>>>>>> origin/main
        $totalComplaint = Complaint::count();
        $pending = Complaint::where('status', 'menunggu')->count();
        $proses = Complaint::where('status', 'diproses')->count();
        $selesai = Complaint::where('status', 'selesai')->count();
        $ditolak = Complaint::where('status', 'ditolak')->count();
<<<<<<< HEAD
        $users = User::count();

        // 2. Data Tabel (Dapat difilter oleh pencarian)
        $query = Complaint::with(['user', 'category']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $latestComplaints = $query->latest()->get();

        // 3. Kirim semua ke view
        return view('admin.dashboard', compact(
            'totalComplaint', 'pending', 'proses', 'selesai', 'ditolak', 'users', 'latestComplaints'
=======
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
>>>>>>> origin/main
        ));
    }
}
