<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Order;
use Ramsey\Uuid\Uuid;
use App\Models\Talent;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'customer_name';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND orders.customer_name LIKE '%$search_term%'";
            }

            $paginate = Order::query()->select(['orders.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Order::query()
                ->count();

            // paging response.
            $response = OrderResource::collection($paginate);
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
            'talent_id' => ['required'],
            'order_date' => ['required'],
            'category_id' => ['required'],
            'phone_number' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $orderData = new Order();
        $talentData = Talent::where('id', $data['talent_id'])->first();
        $categoryData = Category::where('id', $data['category_id'])->first();

        // input data order
        $orderData->id = Uuid::uuid4()->toString();
        $orderData->customer_id = auth()->user()->id;
        $orderData->customer_name = auth()->user()->fullname;
        $orderData->talent_id = $data['talent_id'];
        $orderData->talent_name = $talentData->fullname;
        $orderData->talent_phone_number = $talentData->phone_number;
        $orderData->order_date = $data['order_date'];
        $orderData->repair_time = $data['repair_time'];
        $orderData->repair_address = $data['repair_address'];
        $orderData->category_id = $data['category_id'];
        $orderData->category_name = $categoryData->category_name;
        $orderData->detail_requirement = $data['detail_requirement'];
        $orderData->phone_number = $data['phone_number'];

        $orderData->created_at = $timeNow;
        $orderData->updated_at = null;

        // save order
        $orderData->save();

        return $orderData;
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
            $single = new OrderResource($model);
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
            $model = Order::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Order tidak ada", 404);
            }
            $single = new OrderResource($model);
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

    // protected function validateUpdate(array $data)
    // {
    //     $arrayValidator = [
    //         'customer_id' => ['required'],
    //         'customer_name' => ['required'],
    //         'talent_id' => ['required'],
    //         'talent_name' => ['required'],
    //         'order_date' => ['required'],
    //         'category_id' => ['required'],
    //         'category_name' => ['required'],
    //         'phone_number' => ['required'],
    //     ];

    //     return Validator::make($data, $arrayValidator);
    // }

    // protected function edit($id, array $data, Request $request)
    // {
    //     $timeNow = Carbon::now();

    //     // Find order by id
    //     $orderData = Order::find($id);

    //     if (empty($orderData)) {
    //         throw new \Exception("Invalid customer id", 406);
    //     }
    //     $orderData->id = $id;
    //     $customerData->fullname = $data['fullname'];
    //     $customerData->email = $data['email'];
    //     $customerData->phone_number = $data['phone_number'];
    //     $customerData->address = $data['address'];
    //     $customerData->birthdate = $data['birthdate'];
    //     $customerData->gender = $data['gender'];
    //     $customerData->email = $data['email'];
    //     $customerData->updated_at = $timeNow;
    //     //Save
    //     $customerData->save();


    //     return $customerData;
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update($id, Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $validate = $this->validateUpdate($request->all());
    //         if ($validate->fails()) {
    //             throw new ValidationException($validate);
    //         }
    //         $data = $this->edit($id, $request->all(), $request);

    //         DB::commit();

    //         // return.
    //         $single = new CustomerResource($data);
    //         return ResponseStd::okSingle($single);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         if ($e instanceof ValidationException) {
    //             return ResponseStd::validation($e->validator);
    //         } else {
    //             Log::error($e->getMessage());
    //             if ($e instanceof QueryException) {
    //                 return ResponseStd::fail(trans('error.global.invalid-query'));
    //             } else {
    //                 return ResponseStd::fail($e->getMessage(), $e->getCode());
    //             }
    //         }
    //     }
    // }

    public function datatable(Request $request)
    {
        //SETUP
        $columns = array();

        foreach ($request->columns as $columnData) {
            $columns[] = $columnData['data'];
        }
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->has('order.0.column')] ? 'order_date'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = Order::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Order::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND order_date LIKE '%" . trim($search) . "%'";
                $conditions .= " OR customer_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR talent_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR status LIKE '%" . trim($search) . "%'";
                $conditions .= " OR phone_number LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Order::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Order::whereRaw($conditions)->count();
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        return json_encode($json_data);
    }

    // protected function delete($id)
    // {

    //     $customer = Order::find($id);

    //     $customer->delete();

    //     return $customer;
    // }
    // public function destroy(string $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $this->delete($id);
    //         DB::commit();
    //         // return
    //         return ResponseStd::okNoOutput("Pelanggan berhasil dihapus.");
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         if ($e instanceof ValidationException) {
    //             return ResponseStd::validation($e->validator);
    //         } else {
    //             Log::error($e->getMessage());
    //             if ($e instanceof QueryException) {
    //                 return ResponseStd::fail(trans('error.global.invalid-query'));
    //             } else {
    //                 return ResponseStd::fail($e->getMessage(), $e->getCode());
    //             }
    //         }
    //     }
    // }
}