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
            ->where('role', User::ROLE_USER)
            ->orderBy('id')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function adminIndex()
    {
        $users = User::withCount('complaints')
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])
            ->orderBy('id')
            ->get();
        $roleOptions = [
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_SUPER_ADMIN => 'Super Admin',
        ];

        return view('admin.users.admins', compact('users', 'roleOptions'));
    }

    public function storeAppUser(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_USER,
        ]);

        AdminAuditLog::record(
            'user.created',
            $user,
            null,
            AdminAuditLog::snapshot($user, $this->userAuditFields())
        );

        return back()->with('success', 'Pengguna aplikasi berhasil ditambahkan.');
    }

    public function storeAdminUser(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])],
        ]);

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

        return back()->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function updateAppUser(Request $request, User $user)
    {
        if (! $this->isAppUser($user)) {
            return back()->withErrors(['user' => 'Endpoint ini hanya untuk pengguna aplikasi.']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $before = AdminAuditLog::snapshot($user, $this->userAuditFields());
        $passwordChanged = ! empty($data['password']);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
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

        return back()->with('success', 'Pengguna aplikasi berhasil diperbarui.');
    }

    public function updateAdminUser(Request $request, User $user)
    {
        if (! $this->isAdminAccount($user)) {
            return back()->withErrors(['user' => 'Endpoint ini hanya untuk akun admin.']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])],
        ]);

        if (auth()->id() === $user->id && $user->role !== $data['role']) {
            return back()->withErrors(['role' => 'Role akun yang sedang digunakan tidak bisa diubah.']);
        }

        if ($user->role === User::ROLE_SUPER_ADMIN
            && $data['role'] !== User::ROLE_SUPER_ADMIN
            && ! $this->canModifyCurrentSuperAdminCount($user)
        ) {
            return back()->withErrors(['role' => 'Minimal harus ada satu super admin aktif.']);
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

        return back()->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function destroyAppUser(User $user)
    {
        if (! $this->isAppUser($user)) {
            return back()->withErrors(['user' => 'Endpoint ini hanya untuk pengguna aplikasi.']);
        }

        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'Akun yang sedang digunakan tidak bisa dihapus.']);
        }

        $before = AdminAuditLog::snapshot($user, $this->userAuditFields());

        $user->delete();

        AdminAuditLog::record(
            'user.soft_deleted',
            $user,
            $before,
            AdminAuditLog::snapshot($user, $this->userAuditFields())
        );

        return back()->with('success', 'Pengguna aplikasi berhasil dihapus.');
    }

    public function destroyAdminUser(User $user)
    {
        if (! $this->isAdminAccount($user)) {
            return back()->withErrors(['user' => 'Endpoint ini hanya untuk akun admin.']);
        }

        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'Akun yang sedang digunakan tidak bisa dihapus.']);
        }

        if ($user->role === User::ROLE_SUPER_ADMIN && ! $this->canModifyCurrentSuperAdminCount($user)) {
            return back()->withErrors(['user' => 'Minimal harus ada satu super admin aktif.']);
        }

        $before = AdminAuditLog::snapshot($user, $this->userAuditFields());

        $user->delete();

        AdminAuditLog::record(
            'user.soft_deleted',
            $user,
            $before,
            AdminAuditLog::snapshot($user, $this->userAuditFields())
        );

        return back()->with('success', 'Akun admin berhasil dihapus.');
    }

    public function banAppUser(User $user)
    {
        if (! $this->isAppUser($user)) {
            return back()->withErrors(['user' => 'Endpoint ini hanya untuk pengguna aplikasi.']);
        }

        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'Akun yang sedang digunakan tidak bisa diban.']);
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

        return back()->with('success', 'Pengguna aplikasi berhasil diban.');
    }

    public function banAdminUser(User $user)
    {
        if (! $this->isAdminAccount($user)) {
            return back()->withErrors(['user' => 'Endpoint ini hanya untuk akun admin.']);
        }

        if (auth()->id() === $user->id) {
            return back()->withErrors(['user' => 'Akun yang sedang digunakan tidak bisa diban.']);
        }

        if ($user->role === User::ROLE_SUPER_ADMIN && ! $this->canModifyCurrentSuperAdminCount($user)) {
            return back()->withErrors(['user' => 'Minimal harus ada satu super admin aktif.']);
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

        return back()->with('success', 'Akun admin berhasil diban.');
    }

    public function unbanAppUser(User $user)
    {
        if (! $this->isAppUser($user)) {
            return back()->withErrors(['user' => 'Endpoint ini hanya untuk pengguna aplikasi.']);
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

        return back()->with('success', 'Ban pengguna aplikasi berhasil dibuka.');
    }

    public function unbanAdminUser(User $user)
    {
        if (! $this->isAdminAccount($user)) {
            return back()->withErrors(['user' => 'Endpoint ini hanya untuk akun admin.']);
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

        return back()->with('success', 'Ban akun admin berhasil dibuka.');
    }

    private function isAppUser(User $user): bool
    {
        return $user->role === User::ROLE_USER;
    }

    private function isAdminAccount(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN], true);
    }

    private function canModifyCurrentSuperAdminCount(User $targetUser): bool
    {
        if ($targetUser->role !== User::ROLE_SUPER_ADMIN) {
            return true;
        }

        return User::query()
            ->where('role', User::ROLE_SUPER_ADMIN)
            ->whereNull('deleted_at')
            ->whereNull('banned_at')
            ->count() > 1;
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
