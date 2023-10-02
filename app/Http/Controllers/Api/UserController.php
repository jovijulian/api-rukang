<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Rules\OnlyVerifiedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    public function getUserInactive(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 50;
            $sort = $request->has('sort') ? $request->input('sort') : 'users.created_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 50) {
                $limit = 50;
            }
            if (!empty($search_term)) {
                $conditions .= " AND email LIKE '%$search_term%'";
            }
            $paginate = User::query()->where('isActive', 0)->whereNotNull('email_verified_at')
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = User::query()
                ->count();

            // paging response.
            $response = UserResource::collection($paginate);
            return ResponseStd::pagedFrom($response, $paginate, $countAll);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), 400);
                }
            }
        }
    }

    public function setActiveUser($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->updateActiveUser($id, $request->all(), $request);
            if (empty($data)) {
                throw new BadRequestHttpException("User Not Found.");
            }
            DB::commit();
            $single = new UserResource($data);
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
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    protected function updateActiveUser($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();
        $user = User::find($id);

        if (empty($user)) {
            throw new \Exception("Invalid user id", 406);
        }
        $user->id = $id;
        $user->isActive = $data['isActive'];
        $user->updated_at = $timeNow;
        $user->updated_by = auth()->user()->fullname;

        //Save
        $user->save();

        return $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'created_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND users.fullname LIKE '%$search_term%'";
            }

            $paginate = User::query()->select(['users.*'])->where('isAdmin', 0)
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = User::query()
                ->count();

            // paging response.
            $response = UserResource::collection($paginate);
            return ResponseStd::pagedFrom($response, $paginate, $countAll);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */

    protected function validateCreate(array $data)
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
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $user = new User();
        $user->id = Uuid::uuid4()->toString();
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->fullname = $data['fullname'];
        $user->phone_number = $data['phone_number'];
        $user->address = $data['address'];
        $user->birthdate = $data['birthdate'];
        $user->group_id = $data['group_id'];
        $user->group_name = $data['group_name'];
        $user->isAdmin = 0;
        $user->email_verified_at = $timeNow;
        $user->created_at = $timeNow;
        $user->created_by = auth()->user()->fullname;
        $user->updated_by = null;
        $user->save();
        return $user;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateCreate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = $this->create($request->all(), $request);
            DB::commit();

            // return
            $single = new UserResource($model);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $model = User::query()->find($id);
            if (empty($model)) {
                throw new \Exception("User tidak ada", 404);
            }
            $single = new UserResource($model);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
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

    /**
     * Show the form for editing the specified resource.
     */

    protected function validateUpdate(array $data)
    {
        $arrayValidator = [
            'fullname' => ['required', 'string', 'min:3'],
            'email' => [
                'required',
                'email',
                'min:3',
                'max:80',
                new OnlyVerifiedMail
            ],
            'password' => ['required', 'string', 'min:3'],
            'phone_number' => [
                'required',
                'min:9',
                'max:15',
            ],
            'group_id' => ['required'],
            'group_name' => ['required', 'string', 'between:1,100'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find user by id
        $user = User::find($id);

        if (empty($user)) {
            throw new \Exception("Invalid user id", 406);
        }
        $user->id = $id;
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->fullname = $data['fullname'];
        $user->phone_number = $data['phone_number'];
        $user->address = $data['address'];
        $user->birthdate = $data['birthdate'];
        $user->group_id = $data['group_id'];
        $user->group_name = $data['group_name'];
        $user->updated_at = $timeNow;
        $user->updated_by = auth()->user()->fullname;
        //Save
        $user->save();

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateUpdate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->edit($id, $request->all(), $request);

            DB::commit();

            // return.
            $single = new UserResource($data);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    protected function delete($id)
    {

        $user = User::find($id);

        $user->forceDelete();

        return $user;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("User berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    public function datatable(Request $request)
    {
        //SETUP
        $columns = array();

        foreach ($request->columns as $columnData) {
            $columns[] = $columnData['data'];
        }
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->has('order.0.column')] ? 'fullname'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = User::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = User::offset($start)->limit($limit)->orderBy($order, $dir)->where('isAdmin', '!=', 1)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND fullname LIKE '%" . trim($search) . "%'";
                $conditions .= " OR email LIKE '%" . trim($search) . "%'";
                $conditions .= " OR phone_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR group_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  User::whereRaw($conditions)
                ->where('isAdmin', 0)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = User::whereRaw($conditions)->count();
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        return json_encode($json_data);
    }

    public function datatableInactive(Request $request)
    {
        //SETUP
        $columns = array();

        foreach ($request->columns as $columnData) {
            $columns[] = $columnData['data'];
        }
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->has('order.0.column')] ? 'fullname'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = User::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = User::offset($start)->limit($limit)->orderBy($order, $dir)->where('isActive', 0)->whereNotNull('email_verified_at')->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND fullname LIKE '%" . trim($search) . "%'";
                $conditions .= " OR email LIKE '%" . trim($search) . "%'";
                $conditions .= " OR phone_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR group_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  User::whereRaw($conditions)
                ->where('isActive', 0)
                ->whereNotNull('email_verified_at')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = User::whereRaw($conditions)->count();
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        return json_encode($json_data);
    }
}
