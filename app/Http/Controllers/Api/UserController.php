<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Libraries\ResponseStd;
use App\Models\User;
use App\Rules\OnlyVerifiedMail;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends Controller
{
    protected function validateResetPassword(array $data)
    {
        $arrayValidator = [
            'email' => [
                'required',
                'email',
                'min:3',
                'max:80',
                new OnlyVerifiedMail
            ],
            'token' => ['required'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    public function resetPassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::query()->where('email', $request->email)->first();
            if (empty($user)) {
                throw new BadRequestHttpException("User Not Found.");
            }

            if ($request->token !== $user->reset_password_token || Carbon::now()->timezone('Asia/Jakarta') >= $user->token_expire) {
                throw new BadRequestHttpException("Token invalid or expire.");
            }

            $user->update([
                'password' => Hash::make($request->password),
                'reset_password_token' => null,
                'token_expire' => null
            ]);
            $single = new UserResource($user);
            DB::commit();
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else if ($e instanceof BadRequestHttpException) {
                    return ResponseStd::fail($e->getMessage(), $e->getStatusCode());
                } else {
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }
}
