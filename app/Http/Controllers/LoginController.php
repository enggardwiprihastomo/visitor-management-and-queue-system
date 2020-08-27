<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Terminal;
use App\StaffStatus;

class LoginController extends Controller
{
    //
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        if (Auth::check())
        {
            if (Auth::user()->is_admin) {
                return redirect('/admin');
            }
            else if (! session()->has(['terminal', 'counter'])) {
                return redirect('/');
            }
            else if (Auth::user()->is_staff) {
                return redirect('/queue');
            }
        }

        return redirect('/');
    }

    public function index()
    {
        if (Auth::check())
        {
            if (Auth::user()->is_admin) {
                return redirect('/admin');
            }
            else if (! session()->has(['terminal', 'counter'])) {
                return view('index');
            }
            else if (Auth::user()->is_staff) {
                return redirect('/queue');
            }
        }
        
        $terminals = Terminal::orderBy('code', 'asc')->get();
        return view('auth/login')->with('terminals', $terminals);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->is_admin) {
                return redirect('/admin');
            }
            else if (Auth::user()->is_staff) {
                if ($request->terminal !='' && $request->counter != '') {
                    
                    session([
                        'terminal' => $request->terminal, 
                        'counter' => $request->counter
                    ]);

                    return redirect('/queue');
                }
                else if (! $request->has(['terminal', 'counter'])) {
                    return redirect('/');
                }
                
            }
        }

        $request->session()->flash('error', 'User tidak ditemukan');
        return redirect('/');
    }
}
