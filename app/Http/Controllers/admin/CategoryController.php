<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        AdminAuditLog::record(
            'category.created',
            $category,
            null,
            AdminAuditLog::snapshot($category, $this->categoryAuditFields())
        );

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
        ]);

        $before = AdminAuditLog::snapshot($category, $this->categoryAuditFields());

        $category->update([
            'name' => $request->name
        ]);

        $after = AdminAuditLog::snapshot($category, $this->categoryAuditFields());

        if ($before !== $after) {
            AdminAuditLog::record('category.updated', $category, $before, $after);
        }

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $before = AdminAuditLog::snapshot($category, $this->categoryAuditFields());

        $category->delete();

        AdminAuditLog::record(
            'category.soft_deleted',
            $category,
            $before,
            AdminAuditLog::snapshot($category, $this->categoryAuditFields())
        );

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    private function categoryAuditFields(): array
    {
        return [
            'id',
            'name',
            'deleted_at',
        ];
    }
}
