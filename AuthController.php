<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // เช็ค Role เพื่อส่งไปหน้าต่างๆ
            $role = Auth::user()->role;
            if ($role == 'admin' || $role == 'technical') {
                return redirect()->route('admin.index');
            }
            
            // ถ้าเป็น user ธรรมดา ให้ไปหน้าแจ้งซ่อม
            return redirect()->route('repair.create');
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ])->onlyInput('email');
    }
    
    // แสดงหน้าสมัครสมาชิก
    public function showRegister()
    {
        return view('register');
    }

    // รับข้อมูลสมัครสมาชิกลง Database
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // ต้องมีรหัสผ่านยืนยัน
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user' // บังคับให้เป็นแค่ user ธรรมดา
        ]);

        // สมัครเสร็จให้ Login อัตโนมัติ แล้วเด้งไปหน้าแจ้งซ่อม
        Auth::login($user);
        return redirect()->route('repair.create');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}