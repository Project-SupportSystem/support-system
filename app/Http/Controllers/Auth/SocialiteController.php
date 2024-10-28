<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // <-- เพิ่มการ import Controller


class SocialiteController extends Controller
{
    // ส่งผู้ใช้ไปที่หน้าล็อกอินของ Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // จัดการข้อมูลหลังจากผู้ใช้ล็อกอินสำเร็จ
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // ตรวจสอบ email domain เพื่อกำหนด role
            if (str_ends_with($googleUser->email, '@kkumail.com')) {
                $role = 'student';
                $redirectRoute = 'register_courses';
                //$redirectRoute = 'table_report';
            } elseif (str_ends_with($googleUser->email, '@gmail.com')) { //kku.ac.th หากใช้จริง
                $role = 'advisor';
                $redirectRoute = 'table_report';
            } else {
                return redirect()->route('login')->with('error', 'ไม่สามารถใช้ email นี้ได้');
            }

            // สร้างหรืออัปเดตข้อมูลผู้ใช้
            $user = User::updateOrCreate(
                ['username' => $googleUser->email],
                ['role' => $role]
            );

            // Login ผู้ใช้และพาไปยังเส้นทางตาม role
            Auth::login($user);

            return redirect()->route($redirectRoute);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'เกิดข้อผิดพลาดในการล็อกอิน');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
