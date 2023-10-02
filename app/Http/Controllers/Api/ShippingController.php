<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Resources\ShippingResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'shipping_name';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND shippings.shipping_name LIKE '%$search_term%'";
            }

            $paginate = Shipping::query()->select(['shippings.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Shipping::query()
                ->count();

            // paging response.
            $response = ShippingResource::collection($paginate);
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
            'shipping_name' => ['required', 'string', 'min:1', 'max:40'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $shippingData = new Shipping();

        // input data shipping
        $shippingData->shipping_name = $data['shipping_name'];
        $shippingData->created_at = $timeNow;
        $shippingData->created_by = auth()->user()->fullname;
        $shippingData->updated_at = null;
        $shippingData->updated_by = null;

        // save shipping
        $shippingData->save();

        return $shippingData;
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
            $single = new ShippingResource($model);
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
            $model = Shipping::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Ekspedisi tidak ada", 404);
            }
            $single = new ShippingResource($model);
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
            'shipping_name' => ['required', 'string', 'min:1', 'max:40'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find shipping by id
        $shippingData = Shipping::find($id);

        if (empty($shippingData)) {
            throw new \Exception("Invalid shipping id", 406);
        }
        $shippingData->id = $id;
        $shippingData->shipping_name = $data['shipping_name'];
        $shippingData->updated_at = $timeNow;
        $shippingData->updated_by = auth()->user()->fullname;
        //Save
        $shippingData->save();

        return $shippingData;
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
            $single = new ShippingResource($data);
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
    // protected function delete($id)
    // {

    //     $shipping = Shipping::find($id);
    //     if ($shipping == null) {
    //         throw new \Exception("Ekspedisi tidak ada", 404);
    //     }

    //     $product = Product::query()->where('shipping_id', $shipping->id)->first();
    //     if ($product != null) {
    //         return throw new \Exception("Data Ekspedisi digunakan oleh Produk", 409);
    //     }
    //     $shipping->deleted_by = auth()->user()->fullname;
    //     $shipping->save();

    //     $shipping->delete();

    //     return $shipping;
    // }
    // public function destroy(string $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $this->delete($id);
    //         DB::commit();
    //         // return
    //         return ResponseStd::okNoOutput("Ekspedisi berhasil dihapus.");
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
        $order = $columns[$request->has('order.0.column')] ? 'shipping_name'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = Shipping::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Shipping::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND shipping_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Shipping::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Shipping::whereRaw($conditions)->count();
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