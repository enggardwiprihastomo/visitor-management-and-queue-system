<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    //
    public function logOut(Request $request)
    {
        session()->flush();
        Auth::logout();
        return redirect('/');
    }
}
