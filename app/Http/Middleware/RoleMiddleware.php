<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // ตรวจสอบว่า user มีบทบาทตามที่กำหนดไว้หรือไม่
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // ถ้าไม่มีสิทธิ์ในการเข้าถึง ให้เปลี่ยนเส้นทางไปหน้า login พร้อมข้อความแจ้งเตือน
        return redirect()->route('login')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
    }
}
