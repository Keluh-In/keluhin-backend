<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Statistik (Selalu dihitung)
        $totalComplaint = Complaint::count();
        $pending = Complaint::where('status', 'menunggu')->count();
        $proses = Complaint::where('status', 'diproses')->count();
        $selesai = Complaint::where('status', 'selesai')->count();
        $ditolak = Complaint::where('status', 'ditolak')->count();
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
        ));
    }
}
