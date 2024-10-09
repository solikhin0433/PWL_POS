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
    public function handle(Request $request, Closure $next, string $role = ''): Response
    {
        $user = $request->user(); // Ambil data user yang login

        if ($user->hasRole($role)) {
            return $next($request); // Jika user punya role yang diinginkan, lanjutkan ke proses selanjutnya
        }

        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini.'); // Jika tidak punya role, tampilkan error 403
    }
}