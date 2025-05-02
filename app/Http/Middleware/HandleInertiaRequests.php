<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Roles;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'permission' => fn () => [
                'role' => $request->user() ? Roles::from('roles as t1')
                ->leftJoin('rol_usuario as t2', 't1.id', '=', 't2.fk_rol')
                ->select(
                    't1.nombre_rol as name',
                )
                ->where('t2.fk_usuario', $request->user()->id)->first() : null,
            ],         
        ];
    }
}
