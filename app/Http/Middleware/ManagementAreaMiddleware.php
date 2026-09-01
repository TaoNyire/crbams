<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagementAreaMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string $area
    ): Response {

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | USER MUST BE AUTHENTICATED
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        /*
        |--------------------------------------------------------------------------
        | SYSTEM ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        if ($area === 'system_admin') {

            if ($user->role === 'system_admin') {
                return $next($request);
            }

            abort(
                403,
                'You are not authorized to access System Administration.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | HARDWARE MANAGEMENT
        |--------------------------------------------------------------------------
        */

        if ($area === 'hardware') {

            if (
                $user->role === 'hardware_officer' &&
                $user->management_area === 'hardware'
            ) {
                return $next($request);
            }

            abort(
                403,
                'You are not authorized to access Hardware Management.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATION MANAGEMENT
        |--------------------------------------------------------------------------
        */

        if ($area === 'administration') {

            if (
                $user->role === 'administration_officer' &&
                $user->management_area === 'administration'
            ) {
                return $next($request);
            }

            abort(
                403,
                'You are not authorized to access Administration Management.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UNKNOWN MANAGEMENT AREA
        |--------------------------------------------------------------------------
        */

        abort(
            403,
            'Invalid management area.'
        );
    }
}