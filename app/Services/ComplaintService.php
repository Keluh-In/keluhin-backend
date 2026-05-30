<?php

namespace App\Services;

use App\Models\Complaint;

class ComplaintService
{
    /**
     * GET ALL USER COMPLAINTS
     */
    public function getUserComplaints($user)
    {
        return Complaint::where('user_id', $user->id)
            ->latest()
            ->get();
    }

    /**
     * CREATE COMPLAINT
     */
    public function create($user, array $data)
    {
        return Complaint::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'location' => $data['location'] ?? null,
            'image' => $data['image'] ?? null,
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'status' => 'menunggu'
        ]);
    }

    /**
     * UPDATE COMPLAINT (ONLY MENUNGGU)
     */
    public function update($complaint, array $data)
    {
        if ($complaint->status !== 'menunggu') {
            return null;
        }

        $complaint->update($data);

        return $complaint;
    }

    /**
     * DELETE COMPLAINT (ONLY MENUNGGU)
     */
    public function delete($complaint)
    {
        if ($complaint->status !== 'menunggu') {
            return false;
        }

        return $complaint->delete();
    }
}