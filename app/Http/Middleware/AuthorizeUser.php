<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)   
  $next
     * @param  string  $role   

     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next,...$roles): Response
    {
        $user_role = $request->user(); // Ambil data user yang login

        $user_role = $request->user()->getRole(); // Ambil data level_kode dari user yang login
        if (in_array($user_role, $roles)) { // Cek apakah level_kode user ada di dalam array roles
            return $next($request); // Jika ada, maka lanjutkan request
        }

        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini.'); // Jika tidak punya role, tampilkan error 403
    }
}