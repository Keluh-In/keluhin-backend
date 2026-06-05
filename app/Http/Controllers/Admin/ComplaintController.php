<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    /**
     * Menampilkan halaman utama pengaduan dengan pembagian status & statistik.
     */
    public function index()
    {
        // Mengambil data dengan eager loading agar query efisien (mencegah N+1 query issue)
        $complaints = Complaint::with(['user', 'category'])->latest()->get();
        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // Definisikan array status agar sinkron dengan yang dibutuhkan oleh modal tambah dan edit di Blade
        $statuses = ['menunggu', 'diproses', 'selesai', 'ditolak'];

        return view('admin.complaints.index', compact('complaints', 'categories', 'users', 'statuses'));
    }

    /**
     * Menyimpan data pengaduan baru dan merekam ke log audit.
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        // Memastikan is_anonymous dikonversi menjadi boolean/integer yang tepat untuk database
        $data['is_anonymous'] = $request->has('is_anonymous') ? 1 : 0;

        $complaint = Complaint::create($data);

        // Merekam aktivitas penambahan ke log audit
        AdminAuditLog::record(
            'complaint.created',
            $complaint,
            null,
            AdminAuditLog::snapshot($complaint, $this->complaintAuditFields())
        );

        return back()->with('success', 'Pengaduan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengaduan (jika Anda memiliki halaman show khusus).
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'category', 'responses.admin', 'response.admin', 'attachments.uploader', 'attachments.validator']);
        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // Sediakan juga status di halaman show untuk keperluan update status langsung
        $statuses = ['menunggu', 'diproses', 'selesai', 'ditolak'];

        return view('admin.complaints.show', compact('complaint', 'categories', 'users', 'statuses'));
    }

    /**
     * Memperbarui data pengaduan dan merekam perubahan ke log audit.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $data = $this->validatedData($request);
        $data['is_anonymous'] = $request->has('is_anonymous') ? 1 : 0;

        // Ambil snapshot data SEBELUM diupdate untuk log audit
        $before = AdminAuditLog::snapshot($complaint, $this->complaintAuditFields());

        $complaint->update($data);

        // Ambil snapshot data SESUDAH diupdate
        $after = AdminAuditLog::snapshot($complaint, $this->complaintAuditFields());

        // Tentukan jenis aksi log berdasarkan perubahan status
        $action = $before['status'] !== $after['status']
            ? 'complaint.status_changed'
            : 'complaint.updated';

        if ($before !== $after) {
            AdminAuditLog::record($action, $complaint, $before, $after);
        }

        return back()->with('success', 'Pengaduan berhasil diperbarui.');
    }

    /**
     * Menghapus data pengaduan (Soft Delete / Hard Delete sesuai konfigurasi model).
     */
    public function destroy(Complaint $complaint)
    {
        $before = AdminAuditLog::snapshot($complaint, $this->complaintAuditFields());

        $complaint->delete();

        // Merekam aktivitas penghapusan ke log audit
        AdminAuditLog::record(
            'complaint.soft_deleted',
            $complaint,
            $before,
            AdminAuditLog::snapshot($complaint, $this->complaintAuditFields())
        );

        return redirect()
            ->route('admin.complaints.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }

    /**
     * Validasi data input pengaduan.
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['menunggu', 'diproses', 'selesai', 'ditolak'])],
        ]);
    }

    /**
     * Field-field yang akan direkam ke dalam log audit snapshot.
     */
    private function complaintAuditFields(): array
    {
        return [
            'id',
            'user_id',
            'category_id',
            'title',
            'description',
            'location',
            'is_anonymous',
            'status',
            'deleted_at',
        ];
    }
}
