<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Libraries\FilesLibrary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'fullname';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND customers.fullname LIKE '%$search_term%'";
            }

            $paginate = Customer::query()->select(['customers.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Customer::query()
                ->count();

            // paging response.
            $response = CustomerResource::collection($paginate);
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
            'fullname' => ['required', 'string', 'min:1', 'max:100'],
            'email' => ['required', 'email', 'unique:talents,email,NULL,id'],
            'phone_number' => ['required'],
            'address' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $customerData = new Customer();

        // input data customer
        $customerData->id = Uuid::uuid4()->toString();
        $customerData->fullname = $data['fullname'];
        $customerData->email = $data['email'];
        $customerData->phone_number = $data['phone_number'];
        $customerData->address = $data['address'];
        $customerData->birthdate = $data['birthdate'];
        $customerData->gender = $data['gender'];
        $customerData->email = $data['email'];
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $imageId = (new FilesLibrary())
                        ->saveImage(
                            $request->file($key),
                            'images/profile-customer',
                            false,
                            300,
                            300,
                            'profile-customer'
                        );
                    if ($key == 'image_profile') {
                        $customerData->image_profile = $imageId;
                    } else {
                        $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                        $customerData->$key = $key_id;
                    }
                }
            }
        }
        $customerData->created_at = $timeNow;
        $customerData->updated_at = null;

        // save customer
        $customerData->save();

        //input users
        $userData = new User();
        $userData->id = $customerData->id;;
        $userData->email = $data['email'];
        $userData->password = Hash::make($data['password']);
        $userData->fullname = $data['fullname'];
        $userData->phone_number = $data['phone_number'];
        $userData->role = 0;
        $userData->created_at = $timeNow;
        $userData->updated_at = null;
        $userData->save();

        return $customerData;
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
            $single = new CustomerResource($model);
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
            $model = Customer::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Pelanggan tidak ada", 404);
            }
            $single = new CustomerResource($model);
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
            'fullname' => ['required', 'string', 'min:1', 'max:100'],
            'email' => ['required', 'email', 'unique:talents,email,NULL,id'],
            'phone_number' => ['required'],
            'address' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find customer by id
        $customerData = Customer::find($id);

        if (empty($customerData)) {
            throw new \Exception("Invalid customer id", 406);
        }
        $customerData->id = $id;
        $customerData->fullname = $data['fullname'];
        $customerData->email = $data['email'];
        $customerData->phone_number = $data['phone_number'];
        $customerData->address = $data['address'];
        $customerData->birthdate = $data['birthdate'];
        $customerData->gender = $data['gender'];
        $customerData->email = $data['email'];
        $customerData->updated_at = $timeNow;
        //Save
        $customerData->save();


        return $customerData;
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
            $single = new CustomerResource($data);
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
        $totalData = Customer::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Customer::offset($start)->limit($limit)->orderBy($order, $dir)->get()->map(function ($customer) {
                if ($customer->image_profile !== null) {
                    $customer->image_profile = url(Storage::url($customer->image_profile));
                }
                return $customer;
            });
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND fullname LIKE '%" . trim($search) . "%'";
                $conditions .= " OR email LIKE '%" . trim($search) . "%'";
                $conditions .= " OR phone_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR address LIKE '%" . trim($search) . "%'";
                $conditions .= " OR birthdate LIKE '%" . trim($search) . "%'";
                $conditions .= " OR gender LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Customer::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get()
                ->map(function ($customer) {
                    if ($customer->image_profile !== null) {
                        $customer->image_profile = url(Storage::url($customer->image_profile));
                    }
                    return $customer;
                });

            //QUERI CUSTOM
            $totalFiltered = Customer::whereRaw($conditions)->count();
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        return json_encode($json_data);
    }

    protected function delete($id)
    {

        $customer = Customer::find($id);

        $customer->delete();

        return $customer;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Pelanggan berhasil dihapus.");
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
}
