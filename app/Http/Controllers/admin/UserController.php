<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('complaints')
            ->orderBy('id')
            ->get();
        $roleOptions = auth()->user()->isSuperAdmin()
            ? User::roleOptions()
            : [User::ROLE_USER => 'Pengguna'];

        return view('admin.users.index', compact('users', 'roleOptions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(User::roles())],
        ]);

        if (! $this->canAssignRole($data['role'])) {
            return back()
                ->withErrors(['role' => 'Hanya super admin yang boleh membuat akun admin.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        AdminAuditLog::record(
            'user.created',
            $user,
            null,
            AdminAuditLog::snapshot($user, $this->userAuditFields())
        );

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(User::roles())],
        ]);

        if (! $this->canManageUser($user)) {
            return back()->withErrors(['user' => 'Hanya super admin yang boleh mengelola akun admin lain.']);
        }

        if (! $this->canAssignRole($data['role'])) {
            return back()
                ->withErrors(['role' => 'Hanya super admin yang boleh memberi role admin.'])
                ->withInput();
        }

        if (auth()->id() === $user->id && $user->role !== $data['role']) {
            return back()->withErrors(['role' => 'Role akun yang sedang digunakan tidak bisa diubah.']);
        }

        $before = AdminAuditLog::snapshot($user, $this->userAuditFields());
        $passwordChanged = ! empty($data['password']);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $after = AdminAuditLog::snapshot($user, $this->userAuditFields());

        if ($before !== $after || $passwordChanged) {
            AdminAuditLog::record('user.updated', $user, $before, $after, [
                'password_changed' => $passwordChanged,
            ]);
        }

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'Akun yang sedang digunakan tidak bisa dihapus.']);
        }

        if (! $this->canManageUser($user)) {
            return back()->withErrors(['user' => 'Hanya super admin yang boleh menghapus akun admin lain.']);
        }

        $before = AdminAuditLog::snapshot($user, $this->userAuditFields());

        $user->delete();

        AdminAuditLog::record(
            'user.soft_deleted',
            $user,
            $before,
            AdminAuditLog::snapshot($user, $this->userAuditFields())
        );

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function ban(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'Akun yang sedang digunakan tidak bisa diban.']);
        }

        if (! $this->canManageUser($user)) {
            return back()->withErrors(['user' => 'Hanya super admin yang boleh ban akun admin lain.']);
        }

        $before = AdminAuditLog::snapshot($user, $this->userAuditFields());

        $user->forceFill([
            'banned_at' => now(),
        ])->save();

        AdminAuditLog::record(
            'user.banned',
            $user,
            $before,
            AdminAuditLog::snapshot($user, $this->userAuditFields())
        );

        return back()->with('success', 'Pengguna berhasil diban.');
    }

    public function unban(User $user)
    {
        if (! $this->canManageUser($user)) {
            return back()->withErrors(['user' => 'Hanya super admin yang boleh membuka ban akun admin lain.']);
        }

        $before = AdminAuditLog::snapshot($user, $this->userAuditFields());

        $user->forceFill([
            'banned_at' => null,
        ])->save();

        AdminAuditLog::record(
            'user.unbanned',
            $user,
            $before,
            AdminAuditLog::snapshot($user, $this->userAuditFields())
        );

        return back()->with('success', 'Ban pengguna berhasil dibuka.');
    }

    public function show($id)
    {
        $user = User::with('complaints')->findOrFail($id);

        return view('admin.users.detail', compact('user'));
    }

    private function canAssignRole(string $role): bool
    {
        return $role === User::ROLE_USER || auth()->user()->isSuperAdmin();
    }

    private function canManageUser(User $user): bool
    {
        return $user->role === User::ROLE_USER || auth()->user()->isSuperAdmin();
    }

    private function userAuditFields(): array
    {
        return [
            'id',
            'name',
            'email',
            'role',
            'banned_at',
            'deleted_at',
        ];
    }
}
