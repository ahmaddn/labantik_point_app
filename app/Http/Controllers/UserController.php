<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\CoreEmployee;
use App\Models\RefStudent;
use App\Models\CoreRole;
use App\Models\CorePermission;

class UserController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            // Optimasi: Update menggunakan query builder
            User::where('id', Auth::id())->update([
                'last_login' => now()
            ]);

            $user = Auth::user();

            // Optimasi: Eager load relationships yang diperlukan saja
            $userRoles = $user->roles()->select('core_roles.id', 'core_roles.code', 'core_roles.name')->get();

            if ($userRoles->isEmpty()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak memiliki akses ke sistem.',
                ])->withInput();
            }

            // Jika user hanya memiliki 1 peran, langsung arahkan ke dashboard
            if ($userRoles->count() === 1) {
                $roleCode = strtolower($userRoles->first()->code);
                session(['active_role' => $roleCode]);
                
                $redirectRoute = $this->getRedirectRoute($roleCode);
                return redirect()->intended($redirectRoute)->with('success', 'Login berhasil!');
            }

            // Jika user memiliki lebih dari 1 peran, simpan di session dan arahkan ke halaman pilih peran
            session(['user_roles' => $userRoles]);
            return redirect()->route('role.select')->with('success', 'Silakan pilih peran Anda untuk masuk.');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }

    private function getRedirectRoute($roleCode)
    {
        switch (strtolower($roleCode)) {
            case 'guru':
                return '/guru/dashboard';
            case 'guru-bk':
            case 'kesiswaan':
                return '/kesiswaan-bk/dashboard';
            case 'super-admin':
                return '/superadmin/dashboard';
            default:
                return '/';
        }
    }

    public function selectRole()
    {
        if (!session()->has('user_roles')) {
            return redirect()->route('login');
        }

        $roles = session('user_roles');
        return view('auth.select-role', compact('roles'));
    }

    public function storeActiveRole(Request $request)
    {
        $request->validate([
            'role_code' => 'required|string',
        ]);

        $roleCode = $request->role_code;
        $userRoles = session('user_roles');

        // Validasi apakah role yang dikirim benar-benar dimiliki oleh user ini
        if (!$userRoles || !$userRoles->contains('code', $roleCode)) {
            return redirect()->back()->withErrors(['role_code' => 'Peran yang dipilih tidak valid atau Anda tidak memiliki akses.']);
        }

        // Set role aktif
        session(['active_role' => strtolower($roleCode)]);
        
        $redirectRoute = $this->getRedirectRoute($roleCode);
        return redirect()->intended($redirectRoute)->with('success', 'Peran berhasil dipilih!');
    }

    public function profile()
    {
        $user = Auth::user();

        // Optimasi: Load hanya field yang diperlukan
        $employee = $user->employee()->select('id', 'user_id', 'full_name', 'nip')->first();
        $student = $user->student()->select('id', 'user_id', 'full_name', 'student_number')->first();

        return view('profile.index', compact('user', 'employee', 'student'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:core_users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'updated_by' => $user->id,
            'updated_at' => now(),
        ];

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = $avatarPath;
        }

        User::where('id', $user->id)->update($updateData);

        return back()->with('success', 'Profile berhasil diperbarui!');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'updated_by' => $user->id,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function getUserPermissions()
    {
        $userId = Auth::id();

        // Optimasi: Select hanya field yang diperlukan
        $user = User::select('id')
            ->with([
                'roles:core_roles.id',
                'roles.permissions:core_permissions.id,name',
                'roles.permissions.actions:id,permission_id,action_name'
            ])
            ->find($userId);

        if (!$user) {
            return [];
        }

        $permissions = [];
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissionName = $permission->name;

                if (!isset($permissions[$permissionName])) {
                    $permissions[$permissionName] = [];
                }

                foreach ($permission->actions as $action) {
                    if (!in_array($action->action_name, $permissions[$permissionName])) {
                        $permissions[$permissionName][] = $action->action_name;
                    }
                }
            }
        }

        return $permissions;
    }
}
