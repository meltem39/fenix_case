<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware(['auth:sanctum', 'type.admin'])->except('logout');
//
//    }

public function customLogin(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        $credentials = $request->only('email', 'password');
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            $validator['emailPassword'] = 'Mail adresi ya da şifre yanlış.';
            return redirect("/")->withErrors($validator);

        }
        Auth::guard("admin")->attempt(
            [
                'email' => $request->email,
                'password' => $request->password
            ]) ;
        $admin->createToken('mobile', ['role:admin'])->plainTextToken;
        $request->session()->regenerate();
        return redirect()->intended('/list');
    }

    public function adminLogin(Request $request)
    {
        if ($this->guardLogin($request, Config::get('constants.guards.admin'))) {

        }

        return back()->withInput($request->only('email', 'remember'));
    }

//    public function login(Request $request)
//    {
//        $request->validate([
//            'email' => 'required|email',
//            'password' => 'required',
//        ]);
//
//        $admin = Admin::where('email', $request->email)->first();
//
//        if (!$admin || !Hash::check($request->password, $admin->password)) {
//            $validator['emailPassword'] = 'Mail adresi ya da şifre yanlış.';
//            return redirect("/")->withErrors($validator);
//
//        }
//
//
//        return redirect("list")->withSuccess('Signed in');
//
//        return response()->json([
//            'admin' => $admin,
//            'token' => $admin->createToken('mobile', ['role:admin'])->plainTextToken
//        ]);
//
//    }
//
    public function dashboard(){
        return view("welcome");
    }

}
