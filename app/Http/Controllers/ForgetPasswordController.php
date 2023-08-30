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
        return view('forget-password.send-email');
    }

    public function verifEmail()
    {
        return view('forget-password.verif-email');
    }

    public function newPassword(Request $request)
    {
        $user = User::query()->where('email', $request->email)->first();
        // dd($user);
        if ($request->token === $user->reset_password_token && Carbon::now()->timezone('Asia/Jakarta') <= $user->token_expire) {
            return view('forget-password.new-password');
        } else {
            //fail return
            $message = "Token Expire atau User tidak ditemukan";
            return view('forget-password.reset-password-failed', compact('message'));
        }
    }
}
