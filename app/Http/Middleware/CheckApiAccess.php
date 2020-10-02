<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class CheckApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('X-API-KEY')) {
            return response()->json(['message' => 'Пользователь не авторизован'], 401);
        }

        $token = $request->header('X-API-KEY');

        $user = User::findByToken($token);

        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден или истек срок действия сессии.'], 403);
        }

        Auth::login($user, true);

        return $next($request);
    }
}
