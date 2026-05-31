<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Complaint;
use App\Models\Response;
use Illuminate\Http\Request;

class ComplaintResponseController extends Controller
{
    public function store(Request $request, Complaint $complaint)
    {
        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        $response = $complaint->responses()->create([
            'admin_id' => auth()->id(),
            'message' => $data['message'],
        ]);

        AdminAuditLog::record(
            'response.created',
            $response,
            null,
            AdminAuditLog::snapshot($response, $this->responseAuditFields())
        );

        return back()->with('success', 'Tanggapan berhasil ditambahkan.');
    }

    public function update(Request $request, Complaint $complaint, Response $response)
    {
        if ($response->complaint_id !== $complaint->id) {
            return back()->withErrors(['response' => 'Tanggapan tidak sesuai dengan pengaduan ini.']);
        }

        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        $before = AdminAuditLog::snapshot($response, $this->responseAuditFields());

        $response->update([
            'message' => $data['message'],
            'admin_id' => auth()->id(),
        ]);

        $after = AdminAuditLog::snapshot($response, $this->responseAuditFields());

        if ($before !== $after) {
            AdminAuditLog::record('response.updated', $response, $before, $after);
        }

        return back()->with('success', 'Tanggapan berhasil diperbarui.');
    }

    public function destroy(Complaint $complaint, Response $response)
    {
        if ($response->complaint_id !== $complaint->id) {
            return back()->withErrors(['response' => 'Tanggapan tidak sesuai dengan pengaduan ini.']);
        }

        $before = AdminAuditLog::snapshot($response, $this->responseAuditFields());

        $response->delete();

        AdminAuditLog::record(
            'response.soft_deleted',
            $response,
            $before,
            AdminAuditLog::snapshot($response, $this->responseAuditFields())
        );

        return back()->with('success', 'Tanggapan berhasil dihapus.');
    }

    private function responseAuditFields(): array
    {
        return [
            'id',
            'complaint_id',
            'admin_id',
            'message',
            'deleted_at',
        ];
    }
}
