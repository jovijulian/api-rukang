<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\StatusProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatusProductResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StatusProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'status';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND status_products.status LIKE '%$search_term%'";
            }

            $paginate = StatusProduct::query()->select(['status_products.*'])
                ->where('id', '!=', 25)
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = StatusProduct::query()
                ->count();

            // paging response.
            $response = StatusProductResource::collection($paginate);
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
            'status' => ['required', 'string', 'min:1', 'max:40'],
            'need_expedition' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $statusProductData = new StatusProduct();

        // input data status
        $statusProductData->status = $data['status'];
        $statusProductData->need_expedition = $data['need_expedition'];
        $statusProductData->created_at = $timeNow;
        $statusProductData->created_by = auth()->user()->fullname;
        $statusProductData->updated_at = null;
        $statusProductData->updated_by = null;

        // save status
        $statusProductData->save();

        return $statusProductData;
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
            $single = new StatusProductResource($model);
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
            $model = StatusProduct::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Status Produk tidak ada", 404);
            }
            $single = new StatusProductResource($model);
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
            'status' => ['required', 'string', 'min:1', 'max:40'],
            'need_expedition' => ['required'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find status product by id
        $statusProductData = StatusProduct::find($id);

        if (empty($statusProductData)) {
            throw new \Exception("Invalid status product id", 406);
        }
        $statusProductData->id = $id;
        $statusProductData->status = $data['status'];
        $statusProductData->need_expedition = $data['need_expedition'];
        $statusProductData->updated_at = $timeNow;
        $statusProductData->updated_by = auth()->user()->fullname;
        //Save
        $statusProductData->save();

        return $statusProductData;
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
            $single = new StatusProductResource($data);
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

        $status = StatusProduct::find($id);
        if ($status == null) {
            throw new \Exception("Status Produk tidak ada", 404);
        }

        // $product = Product::query()->where('status_id', $status->id)->first();

        // if ($product != null) {
        //     return throw new \Exception("Data Status digunakan oleh Produk", 409);
        // }
        $status->deleted_by = auth()->user()->fullname;
        $status->save();

        $status->delete();

        return $status;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Status Produk berhasil dihapus.");
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
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = StatusProduct::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = StatusProduct::where('id', '!=', 25)->offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND status LIKE '%" . trim($search) . "%'";
                $conditions .= " AND need_expedition LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  StatusProduct::whereRaw($conditions)
                ->where('id', '!=', 25)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = StatusProduct::whereRaw($conditions)->count();
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
