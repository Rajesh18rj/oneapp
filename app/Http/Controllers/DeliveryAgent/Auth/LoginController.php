<?php

namespace App\Http\Controllers\DeliveryAgent\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Session;
use App\CentralLogics\Helpers;

class LoginController extends Controller
{
    public function __construct()
    {
        //$this->middleware('guest:delivery_men', ['except' => 'logout']);
    }

    public function login()
    {
        $custome_recaptcha = new CaptchaBuilder;
        $custome_recaptcha->build();
        Session::put('six_captcha', $custome_recaptcha->getPhrase());
        return view('delivery-agent-views.auth.login', compact('custome_recaptcha'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:9'
        ]);

        if (auth('delivery_men')->attempt(['email' => $request->email, 'password' => $request->password],$request->remember)) {              
             if(auth('delivery_men')->user()->delivery_type == 'agent' || auth('delivery_men')->user()->employee_status == 'approve' ){
               return redirect()->route('delivery-agent.dashboard');
             }
   	}

        
   

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
    }

    public function logout(Request $request)
    {
        auth()->guard('delivery_men')->logout();
        return redirect()->route('delivery-agent.auth.login');
    }
}
