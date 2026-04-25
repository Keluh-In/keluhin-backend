<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class ResponseController extends Controller
{
    /**
     * ADMIN CREATE RESPONSE
     */
    public function store(Request $request, $complaintId)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $complaint = Complaint::find($complaintId);

        if (!$complaint) {
            return ResponseHelper::notFound();
        }

        $response = Response::updateOrCreate(
            ['complaint_id' => $complaintId],
            [
                'admin_id' => auth()->id(),
                'message' => $request->message
            ]
        );

        // update status otomatis
        $complaint->update([
            'status' => 'diproses'
        ]);

        return ResponseHelper::success($response, 'Response terkirim');
    }
}