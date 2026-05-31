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
    public function index()
    {
        $complaints = Complaint::with(['user', 'category'])->latest()->get();
        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.complaints.index', compact('complaints', 'categories', 'users'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['is_anonymous'] = $request->boolean('is_anonymous');

        $complaint = Complaint::create($data);

        AdminAuditLog::record(
            'complaint.created',
            $complaint,
            null,
            AdminAuditLog::snapshot($complaint, $this->complaintAuditFields())
        );

        return back()->with('success', 'Pengaduan berhasil ditambahkan.');
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'category', 'responses.admin', 'response.admin', 'attachments.uploader', 'attachments.validator']);
        $categories = Category::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.complaints.show', compact('complaint', 'categories', 'users'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $data = $this->validatedData($request);
        $data['is_anonymous'] = $request->boolean('is_anonymous');

        $before = AdminAuditLog::snapshot($complaint, $this->complaintAuditFields());

        $complaint->update($data);

        $after = AdminAuditLog::snapshot($complaint, $this->complaintAuditFields());
        $action = $before['status'] !== $after['status']
            ? 'complaint.status_changed'
            : 'complaint.updated';

        if ($before !== $after) {
            AdminAuditLog::record($action, $complaint, $before, $after);
        }

        return back()->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function destroy(Complaint $complaint)
    {
        $before = AdminAuditLog::snapshot($complaint, $this->complaintAuditFields());

        $complaint->delete();

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
