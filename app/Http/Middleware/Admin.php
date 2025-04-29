<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use App\Models\Roles;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user_id = Auth::id();

        if (!$user_id) {
            return redirect()->route('login');
        }

        $rol =  Roles::from('roles as t1')
                ->leftJoin('rol_usuario as t2', 't1.id', '=', 't2.fk_rol')
                ->select(
                    't1.nombre_rol',
                )
                ->where('t2.fk_usuario', $user_id)->first();

        if ($rol->nombre_rol !== 'Admin') {
            return redirect()->route('dashboard');
        }

        return $next($request);

    }
}
