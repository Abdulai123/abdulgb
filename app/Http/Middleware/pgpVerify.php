<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class pgpVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user) {
            if ($user->twofa_enable == 'yes' && !session('pgp_verified')) {
                // No need to return here, just continue to the next middleware
                //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
                $role = auth()->user()->role;

                switch ($role) {
                    case 'user':
                        return redirect('/auth/pgp/verify');
                        break;

                    case 'share':
                        return redirect('/auth/share/pgp/verify');
                        break;

                    case 'store':
                        return redirect('/auth/store/pgp/verify');
                        break;

                    case 'junior':
                        return redirect('/auth/staff/junior/pgp/verify');
                        break;

                    case 'senior':
                        return redirect('/auth/staff/senior/pgp/verify');
                        break;

                    case 'admin':
                        return redirect('/auth/staff/admin/pgp/verify');
                        break;

                    default:
                        return redirect('/auth/pgp/verify');
                        break;
                }
            } elseif ($user->twofa_enable == 'no') {
                // Code specific to the 'no' case
                return $next($request);
            }
        }

        // Continue to the next middleware
        return $next($request);
    }
}
