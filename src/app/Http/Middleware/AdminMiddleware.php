<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем авторизован ли пользователь
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Требуется авторизация');
        }

        // Проверяем является ли пользователь администратором
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Доступ запрещен. Требуются права администратора.');
        }

        return $next($request);
    }
}
