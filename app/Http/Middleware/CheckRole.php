<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $activeRole = session('active_role');
        $userRoles = session('user_roles', []);

        // Jika tidak ada active_role, atau active_role tidak termasuk dalam roles rute ini
        if (!$activeRole || !in_array($activeRole, $roles)) {
            
            $hasRole = false;
            $matchedRole = null;

            // Cek apakah user sebenarnya memiliki role yang dibutuhkan (di database/session)
            foreach ($roles as $role) {
                $roleFound = false;
                // Cek dari session dulu biar cepat
                if (!empty($userRoles)) {
                    foreach ($userRoles as $userRole) {
                        if (isset($userRole['code']) && $userRole['code'] === $role) {
                            $roleFound = true;
                            break;
                        }
                    }
                } else {
                    // Fallback ke database
                    if (auth()->user()->hasRole($role)) {
                        $roleFound = true;
                    }
                }

                if ($roleFound) {
                    $hasRole = true;
                    $matchedRole = $role;
                    break;
                }
            }

            if ($hasRole) {
                // Auto-switch role untuk mendukung multi-tab
                session(['active_role' => $matchedRole]);
                $activeRole = $matchedRole;
            } else {
                // Jika tidak ada active_role sama sekali dan mereka punya multiple roles, mungkin mereka harus select role dulu
                if (!$activeRole && !empty($userRoles) && count($userRoles) > 1) {
                    return redirect()->route('role.select');
                }
                
                // Jika memang tidak punya akses
                abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI DENGAN PERAN YANG SEDANG AKTIF.');
            }
        }

        return $next($request);
    }
}
