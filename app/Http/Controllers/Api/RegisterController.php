<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use Illuminate\Support\Carbon;
use App\Rules\OnlyVerifiedMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ResetPasswordNotify;
use App\Http\Controllers\BaseApiController;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerificationUserNotify;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RegisterController extends BaseApiController
{
    public function create(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validate = $this->registerValidate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }

            $model = $this->store($request->all());
            Notification::send($model, new VerificationUserNotify($model));
            $single = new UserResource($model);

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
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    protected function store(array $data): Model
    {
        $timeNow = Carbon::now();
        $user = User::query()->create([
            'id' =>  Uuid::uuid4()->toString(),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'fullname' => $data['fullname'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'birthdate' => $data['birthdate'],
            'group_id' => $data['group_id'],
            'group_name' => $data['group_name'],
            'created_by' => $data['email'],
            'isAdmin' => 0,
            'email_verified_at' => $timeNow,
        ]);
        return $user;
    }

    private function registerValidate(array $data): \Illuminate\Validation\Validator
    {
        $arrayValidator = [
            'fullname' => ['required', 'string', 'min:3'],
            'email' => [
                'required',
                'email',
                'min:3',
                'max:80',
                'unique:users,email,NULL,id',
                new OnlyVerifiedMail
            ],
            'password' => ['required', 'string', 'min:3'],
            'phone_number' => [
                'required',
                'min:9',
                'max:15',
                'unique:users,phone_number,NULL,id'
            ],
            'group_id' => ['required'],
            'group_name' => ['required', 'string', 'between:1,100'],
        ];


        return Validator::make($data, $arrayValidator);
    }

    public function forgotPassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateEmail($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }

            $user = User::query()->where('email', $request->email)->first();

            if (empty($user)) {
                throw new BadRequestHttpException("User Not Found.");
            }

            $token = Str::random(60);

            $user->update([
                'reset_password_token' => $token,
                'token_expire' => Carbon::now()->addDays(1)->timezone('Asia/Jakarta')
            ]);

            Notification::send((object)$user, new ResetPasswordNotify((object)$user));

            DB::commit();
            return ResponseStd::okNoOutput();
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
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    private function validateEmail(array $data)
    {
        $arrayValidator = [
            'email' => [
                'required',
                'email',
                'min:3',
                'max:80',
                new OnlyVerifiedMail
            ],
        ];

        return Validator::make($data, $arrayValidator);
    }
}
