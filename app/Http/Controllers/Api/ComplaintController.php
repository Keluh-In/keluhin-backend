<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Http\Requests\Complaint\StoreComplaintRequest;
use App\Http\Requests\Complaint\UpdateComplaintRequest;
use App\Services\FileUploadService;
use App\Helpers\ResponseHelper;

class ComplaintController extends Controller
{
    protected $fileUpload;

    public function __construct(FileUploadService $fileUpload)
    {
        $this->fileUpload = $fileUpload;
    }

    /**
     * LIST USER COMPLAINT
     */
    public function index()
    {
        $data = Complaint::where('user_id', auth()->id())
            ->with('category')
            ->latest()
            ->get();

        return ResponseHelper::success($data);
    }

    /**
     * STATS - jumlah complaint per status milik user
     */
    public function stats()
    {
        $counts = Complaint::where('user_id', auth()->id())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return ResponseHelper::success([
            'total'    => (int) $counts->sum(),
            'menunggu' => (int) ($counts['menunggu'] ?? 0),
            'diproses' => (int) ($counts['diproses'] ?? 0),
            'selesai'  => (int) ($counts['selesai'] ?? 0),
            'ditolak'  => (int) ($counts['ditolak'] ?? 0),
        ]);
    }

    /**
     * STORE
     */
    public function store(StoreComplaintRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['status'] = 'menunggu';

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUpload->upload($request->file('image'));
        }

        $complaint = Complaint::create($data);

        return ResponseHelper::success($complaint->load('category'), 'Pengaduan berhasil dibuat');
    }

    /**
     * DETAIL
     */
    public function show($id)
    {
        $data = Complaint::with(['category', 'response'])
            ->where('user_id', auth()->id())
            ->find($id);

        if (!$data) {
            return ResponseHelper::notFound();
        }

        return ResponseHelper::success($data);
    }

    /**
     * UPDATE
     */
    public function update(UpdateComplaintRequest $request, $id)
    {
        $complaint = Complaint::where('user_id', auth()->id())->find($id);

        if (!$complaint) {
            return ResponseHelper::notFound();
        }

        if ($complaint->status !== 'menunggu') {
            return ResponseHelper::error('Tidak bisa diubah');
        }

        $data = $request->validated();
        unset($data['status']);

        if ($request->hasFile('image')) {
            $this->fileUpload->delete($complaint->image);
            $data['image'] = $this->fileUpload->upload($request->file('image'));
        }

        $complaint->update($data);

        return ResponseHelper::success($complaint->load('category'), 'Berhasil diupdate');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $complaint = Complaint::where('user_id', auth()->id())->find($id);

        if (!$complaint) {
            return ResponseHelper::notFound();
        }

        if ($complaint->status !== 'menunggu') {
            return ResponseHelper::error('Tidak bisa dihapus');
        }

        $complaint->delete();

        return ResponseHelper::success(null, 'Berhasil dihapus');
    }
}