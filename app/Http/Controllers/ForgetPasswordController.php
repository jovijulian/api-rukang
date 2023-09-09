<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function sendEmail()
    {
        return view('pages.forget-password.send-email');
    }

    public function verifEmail()
    {
        return view('pages.forget-password.verif-email');
    }
    
    public function changePasswordSuccess()
    {
        return view('pages.forget-password.reset-password-success');
    }

    public function newPassword(Request $request)
    {
        $user = User::query()->where('email', $request->email)->first();
        // dd($user);
        if ($request->token === $user->reset_password_token && Carbon::now()->timezone('Asia/Jakarta') <= $user->token_expire) {
            return view('pages.forget-password.new-password');
        } else {
            //fail return
            $message = "Token Expire atau User tidak ditemukan";
            return view('pages.forget-password.reset-password-failed', compact('message'));
        }
    }
}
