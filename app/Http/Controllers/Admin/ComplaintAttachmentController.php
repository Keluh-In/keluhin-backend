<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplaintAttachmentController extends Controller
{
    public function __construct(private readonly FileUploadService $fileUploadService)
    {
    }

    public function store(Request $request, Complaint $complaint)
    {
        $data = $request->validate([
            'attachment' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx'],
        ]);

        $file = $data['attachment'];
        $path = $this->fileUploadService->upload($file, 'complaint-attachments');

        $attachment = $complaint->attachments()->create([
            'uploaded_by' => auth()->id(),
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType() ?: $file->getMimeType() ?: 'application/octet-stream',
            'file_size' => $file->getSize(),
        ]);

        AdminAuditLog::record(
            'complaint_attachment.created',
            $attachment,
            null,
            AdminAuditLog::snapshot($attachment, $this->auditFields())
        );

        return back()->with('success', 'Lampiran berhasil diunggah.');
    }

    public function update(Request $request, Complaint $complaint, ComplaintAttachment $attachment)
    {
        if (! $this->belongsToComplaint($complaint, $attachment)) {
            return back()->withErrors(['attachment' => 'Lampiran tidak sesuai dengan pengaduan ini.']);
        }

        $data = $request->validate([
            'attachment' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx'],
        ]);

        $before = AdminAuditLog::snapshot($attachment, $this->auditFields());
        $file = $data['attachment'];
        $newPath = $this->fileUploadService->upload($file, 'complaint-attachments');

        $oldPath = $attachment->file_path;

        $attachment->update([
            'uploaded_by' => auth()->id(),
            'file_path' => $newPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType() ?: $file->getMimeType() ?: 'application/octet-stream',
            'file_size' => $file->getSize(),
            'is_validated' => false,
            'validated_by' => null,
            'validated_at' => null,
            'validation_note' => null,
        ]);

        $this->fileUploadService->delete($oldPath);

        AdminAuditLog::record(
            'complaint_attachment.updated',
            $attachment,
            $before,
            AdminAuditLog::snapshot($attachment, $this->auditFields())
        );

        return back()->with('success', 'Lampiran berhasil diganti.');
    }

    public function destroy(Complaint $complaint, ComplaintAttachment $attachment)
    {
        if (! $this->belongsToComplaint($complaint, $attachment)) {
            return back()->withErrors(['attachment' => 'Lampiran tidak sesuai dengan pengaduan ini.']);
        }

        $before = AdminAuditLog::snapshot($attachment, $this->auditFields());

        $attachment->delete();
        $this->fileUploadService->delete($attachment->file_path);

        AdminAuditLog::record(
            'complaint_attachment.soft_deleted',
            $attachment,
            $before,
            AdminAuditLog::snapshot($attachment, $this->auditFields())
        );

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }

    public function validateAttachment(Request $request, Complaint $complaint, ComplaintAttachment $attachment)
    {
        if (! $this->belongsToComplaint($complaint, $attachment)) {
            return back()->withErrors(['attachment' => 'Lampiran tidak sesuai dengan pengaduan ini.']);
        }

        $data = $request->validate([
            'is_validated' => ['required', 'boolean'],
            'validation_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $before = AdminAuditLog::snapshot($attachment, $this->auditFields());

        $isValidated = (bool) $data['is_validated'];

        $attachment->update([
            'is_validated' => $isValidated,
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'validation_note' => $data['validation_note'] ?? null,
        ]);

        AdminAuditLog::record(
            $isValidated ? 'complaint_attachment.validated' : 'complaint_attachment.validation_revoked',
            $attachment,
            $before,
            AdminAuditLog::snapshot($attachment, $this->auditFields())
        );

        return back()->with('success', $isValidated ? 'Lampiran berhasil divalidasi.' : 'Validasi lampiran dibatalkan.');
    }

    public function file(Complaint $complaint, ComplaintAttachment $attachment)
    {
        if (! $this->belongsToComplaint($complaint, $attachment)) {
            abort(404);
        }

        if (! Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->response(
            $attachment->file_path,
            $attachment->original_name,
            ['Content-Disposition' => 'inline; filename="'.$attachment->original_name.'"']
        );
    }

    private function belongsToComplaint(Complaint $complaint, ComplaintAttachment $attachment): bool
    {
        return $attachment->complaint_id === $complaint->id;
    }

    private function auditFields(): array
    {
        return [
            'id',
            'complaint_id',
            'uploaded_by',
            'validated_by',
            'file_path',
            'original_name',
            'mime_type',
            'file_size',
            'is_validated',
            'validated_at',
            'validation_note',
            'deleted_at',
        ];
    }
}
