<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\UserResource;
use App\Libraries\ResponseStd;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
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
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }

    protected function store(array $data): Model
    {
        //        dd(Uuid::uuid4()->toString());
        $user = User::query()->create([
            'id' =>  Uuid::uuid4()->toString(),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'fullname' => $data['fullname'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'birthdate' => $data['birthdate'],
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
                'unique:users,email,NULL,id'
            ],
            'password' => ['required', 'string', 'min:3'],
        ];


        return Validator::make($data, $arrayValidator);
    }
}
