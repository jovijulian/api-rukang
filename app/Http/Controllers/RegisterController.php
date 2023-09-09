<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\QrCodeLib;
use App\Models\User;
use App\Services\Qrcode\QrCodeService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.register');
    }

    //   hit ke api guzzle
    public function create()
    {
    }

    public function verif()
    {
        return view('pages.verification.verif-user');
    }

    public function verification_success(Request $request)
    {
        DB::beginTransaction();
        try {
            $time = Carbon::now();
            $user = User::query()->find($request->id);
            $message = "null";
            if (!$user) {
                $message = "User Tidak Ditemukan";
                return view('pages.verification.verification-failed', compact('message'));
            }
            //Cek jika user sudah terverifikasi.
            if ($user->hasVerifiedEmail()) {
                $message = "User Sudah Terverifikasi";
                return view('pages.verification.verification-failed', compact('message'));
            }
            if ($request->hash === sha1(substr($user->id, 4, 6) . $user->email)) {
                // Generate default data.
                if ($user->markEmailAsVerified($time)) {
                    event(new Verified($user));
                }
                DB::commit();
                return view('pages.verification.verification-success');
            } else {
                return view('pages.verification.verification-failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $message = "Terjadi Kesalahan pada Server";
            return view('pages.verification.verification-failed', compact('message'));
        }
    }
}
