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
            // Update last login menggunakan query builder
            User::where('id', Auth::id())->update([
                'last_login' => now()
            ]);

            $user = Auth::user();

            // Get user roles and determine redirect based on app_type
            $userRoles = $user->roles;  // PERBAIKAN: hilangkan ->with('pivot')->get()

            if ($userRoles->isEmpty()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak memiliki akses ke sistem.',
                ])->withInput();
            }

            // Check if user has employee or student data
            $employee = $user->employee;  // PERBAIKAN: gunakan relationship
            $student = $user->student;    // PERBAIKAN: gunakan relationship

            // Determine user type and redirect accordingly
            $redirectRoute = $this->getRedirectRoute($userRoles, $employee, $student);
            return redirect()->intended($redirectRoute)->with('success', 'Login berhasil!');
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

    /**
     * Determine redirect route based on user roles and profile
     */
    private function getRedirectRoute($userRoles, $employee = null, $student = null)
    {
        // Check for admin/staff roles first
        foreach ($userRoles as $role) {
            $appType = $role->pivot->app_type ?? null;

            switch (strtolower($role->code)) {



                case 'teacher':
                case 'guru':
                    return '/kesiswaan/dashboard';

                case 'bk':
                case 'guru-bk':
                    return '/bk/dashboard';

                case 'wakel':
                case 'wal':
                    return '/student/dashboard';

                case 'admin':
                case 'super-admin':
                    return '/superadmin/dashboard';
            }
        }

        // Fallback: determine by profile type
        if ($employee) {
            return '/employee/dashboard';
        }

        if ($student) {
            return '/student/dashboard';
        }

        // Default fallback
        return '/dashboard';
    }

    /**
     * Get user profile data
     */
    public function profile()
    {
        $user = Auth::user();
        $employee = $user->employee;  // PERBAIKAN: gunakan relationship
        $student = $user->student;    // PERBAIKAN: gunakan relationship

        return view('profile.index', compact('user', 'employee', 'student'));
    }

    /**
     * Update user profile
     */
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

        // PERBAIKAN: Update menggunakan query builder
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'updated_by' => $user->id,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar'] = $avatarPath;
        }

        User::where('id', $user->id)->update($updateData);

        return back()->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Change password
     */
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

        // PERBAIKAN: Update menggunakan query builder
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Get user permissions for current session
     */
    public function getUserPermissions()
    {
        $userId = Auth::id();
        $user = User::with('roles.permissions.actions')
            ->find($userId);

        // Get all user roles with their permissions
        $userRoles =
            $user->roles()->with(['permissions.actions'])->get();




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
