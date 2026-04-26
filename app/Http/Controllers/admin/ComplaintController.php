<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        Complaint::create($data);

        return back()->with('success', 'Pengaduan berhasil ditambahkan.');
    }

    public function update(Request $request, Complaint $complaint)
    {
        $data = $this->validatedData($request);
        $data['is_anonymous'] = $request->boolean('is_anonymous');

        $complaint->update($data);

        return back()->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return back()->with('success', 'Pengaduan berhasil dihapus.');
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
}
