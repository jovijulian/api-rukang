<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Customer;
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
use App\Http\Resources\CustomerResource;
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
            // Notification::send($model, new VerificationUserNotify($model));
            $single = new CustomerResource($model);

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
        $userData = new User();

        // input data user
        $userData->id = Uuid::uuid4()->toString();
        $userData->email = $data['email'];
        $userData->password = Hash::make($data['password']);
        $userData->fullname = $data['fullname'];
        $userData->phone_number = $data['phone_number'];
        $userData->role = $data['role'];
        $userData->created_at = $timeNow;
        $userData->updated_at = null;
        // save user
        $userData->save();

        $customerData = new Customer();

        // input data user
        $customerData->id = $userData->id;
        $customerData->email = $data['email'];
        $customerData->fullname = $data['fullname'];
        $customerData->phone_number = $data['phone_number'];
        $customerData->address = $data['address'];
        $customerData->birthdate = $data['birthdate'];
        $customerData->gender = $data['gender'];
        $customerData->image_profile = $data['image_profile'];
        $customerData->created_at = $timeNow;
        // save customer
        $customerData->save();

        return $customerData;
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
            ],
            'password' => ['required', 'string', 'min:3'],
            'phone_number' => [
                'min:3',
                'max:15',
                'unique:users,phone_number,NULL,id'
            ],
        ];


        return Validator::make($data, $arrayValidator);
    }

    // public function forgotPassword(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $validate = $this->validateEmail($request->all());
    //         if ($validate->fails()) {
    //             throw new ValidationException($validate);
    //         }

    //         $user = User::query()->where('email', $request->email)->first();

    //         if (empty($user)) {
    //             throw new BadRequestHttpException("User Not Found.");
    //         }

    //         $token = Str::random(60);

    //         $user->update([
    //             'reset_password_token' => $token,
    //             'token_expire' => Carbon::now()->addDays(1)->timezone('Asia/Jakarta')
    //         ]);

    //         Notification::send((object)$user, new ResetPasswordNotify((object)$user));

    //         DB::commit();
    //         return ResponseStd::okNoOutput();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         if ($e instanceof ValidationException) {
    //             return ResponseStd::validation($e->validator);
    //         } else {
    //             Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
    //             if ($e instanceof QueryException) {
    //                 return ResponseStd::fail(trans('error.global.invalid-query'));
    //             } else if ($e instanceof BadRequestHttpException) {
    //                 return ResponseStd::fail($e->getMessage(), $e->getStatusCode());
    //             } else {
    //                 return ResponseStd::fail($e->getMessage(), $e->getCode());
    //             }
    //         }
    //     }
    // }

    //     private function validateEmail(array $data)
    //     {
    //         $arrayValidator = [
    //             'email' => [
    //                 'required',
    //                 'email',
    //                 'min:3',
    //                 'max:80',
    //                 new OnlyVerifiedMail
    //             ],
    //         ];

    //         return Validator::make($data, $arrayValidator);
    //     }
}
