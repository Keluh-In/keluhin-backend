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
     * Menampilkan halaman utama pengaduan.
     */
    public function index()
    {
        $complaints = Complaint::with(['user', 'category', 'attachments'])
            ->latest()
            ->get();

        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        $statuses = [
            'menunggu',
            'diproses',
            'selesai',
            'ditolak'
        ];

        return view(
            'admin.complaints.index',
            compact(
                'complaints',
                'categories',
                'users',
                'statuses'
            )
        );
    }

    /**
     * Simpan pengaduan baru + lampiran bukti.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in([
                'menunggu',
                'diproses',
                'selesai',
                'ditolak'
            ])],

            // Lampiran bukti
            'attachment' => [
                'nullable',
                'file',
                'max:5120',
                'mimes:jpg,jpeg,png,webp,pdf,doc,docx'
            ],
        ]);

        $data['is_anonymous'] = $request->has('is_anonymous');

        $complaint = Complaint::create([
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'] ?? null,
            'status' => $data['status'],
            'is_anonymous' => $data['is_anonymous'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Lampiran Bukti
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('attachment')) {

            $file = $request->file('attachment');

            $path = $file->store(
                'complaint-attachments',
                'public'
            );

            $complaint->attachments()->create([
                'uploaded_by' => auth()->id(),

                'file_path' => $path,

                'original_name' => $file->getClientOriginalName(),

                'mime_type' => $file->getClientMimeType()
                    ?: $file->getMimeType()
                    ?: 'application/octet-stream',

                'file_size' => $file->getSize(),

                'is_validated' => false,
            ]);
        }

        AdminAuditLog::record(
            'complaint.created',
            $complaint,
            null,
            AdminAuditLog::snapshot(
                $complaint,
                $this->complaintAuditFields()
            )
        );

        return redirect()
            ->route('admin.complaints.index')
            ->with(
                'success',
                'Pengaduan berhasil ditambahkan.'
            );
    }

    /**
     * Detail pengaduan.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load([
            'user',
            'category',
            'responses.admin',
            'response.admin',
            'attachments.uploader',
            'attachments.validator',
        ]);

        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        $statuses = [
            'menunggu',
            'diproses',
            'selesai',
            'ditolak'
        ];

        return view(
            'admin.complaints.show',
            compact(
                'complaint',
                'categories',
                'users',
                'statuses'
            )
        );
    }

    /**
     * Update pengaduan.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $data = $this->validatedData($request);

        $data['is_anonymous'] =
            $request->has('is_anonymous');

        $before = AdminAuditLog::snapshot(
            $complaint,
            $this->complaintAuditFields()
        );

        $complaint->update($data);

        $after = AdminAuditLog::snapshot(
            $complaint,
            $this->complaintAuditFields()
        );

        $action =
            $before['status'] !== $after['status']
                ? 'complaint.status_changed'
                : 'complaint.updated';

        if ($before !== $after) {
            AdminAuditLog::record(
                $action,
                $complaint,
                $before,
                $after
            );
        }

        return back()->with(
            'success',
            'Pengaduan berhasil diperbarui.'
        );
    }

    /**
     * Hapus pengaduan.
     */
    public function destroy(Complaint $complaint)
    {
        $before = AdminAuditLog::snapshot(
            $complaint,
            $this->complaintAuditFields()
        );

        $complaint->delete();

        AdminAuditLog::record(
            'complaint.soft_deleted',
            $complaint,
            $before,
            AdminAuditLog::snapshot(
                $complaint,
                $this->complaintAuditFields()
            )
        );

        return redirect()
            ->route('admin.complaints.index')
            ->with(
                'success',
                'Pengaduan berhasil dihapus.'
            );
    }

    /**
     * Validasi data.
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'user_id' => [
                'required',
                'exists:users,id'
            ],

            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'required',
                'string'
            ],

            'location' => [
                'nullable',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                Rule::in([
                    'menunggu',
                    'diproses',
                    'selesai',
                    'ditolak'
                ])
            ],
        ]);
    }

    /**
     * Field audit.
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
