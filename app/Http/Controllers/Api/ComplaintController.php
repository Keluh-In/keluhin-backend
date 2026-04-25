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

        return ResponseHelper::success($complaint, 'Pengaduan berhasil dibuat');
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

        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUpload->upload($request->file('image'));
        }

        $complaint->update($data);

        return ResponseHelper::success($complaint, 'Berhasil diupdate');
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