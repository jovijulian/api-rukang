<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\StatusToolMaterial;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatusToolMaterialResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StatusToolMaterialController extends Controller
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
                $conditions .= " AND status_tools_materials.status LIKE '%$search_term%'";
            }

            $paginate = StatusToolMaterial::query()->select(['status_tools_materials.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = StatusToolMaterial::query()
                ->count();

            // paging response.
            $response = StatusToolMaterialResource::collection($paginate);
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
            'status' => ['required', 'string', 'min:1', 'max:40', 'unique:status_tool_products,status,NULL,id'],
            'need_expedition' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $statusToolMaterialData = new StatusToolMaterial();

        // input data status tool material
        $statusToolMaterialData->status = $data['status'];
        $statusToolMaterialData->need_expedition = $data['need_expedition'];
        $statusToolMaterialData->created_at = $timeNow;
        $statusToolMaterialData->updated_at = null;
        $statusToolMaterialData->created_by = auth()->user()->fullname;
        $statusToolMaterialData->updated_by = null;

        // save process
        $statusToolMaterialData->save();

        return $statusToolMaterialData;
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
            $single = new StatusToolMaterialResource($model);
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
            $model = StatusToolMaterial::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Status Alat dan Bahan tidak ada", 404);
            }
            $single = new StatusToolMaterialResource($model);
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

        // Find status tool material by id
        $statusToolMaterial = StatusToolMaterial::find($id);

        if (empty($statusToolMaterial)) {
            throw new \Exception("Invalid status tool material id", 406);
        }
        $statusToolMaterial->id = $id;
        $statusToolMaterial->status = $data['status'];
        $statusToolMaterial->need_expedition = $data['need_expedition'];
        $statusToolMaterial->updated_at = $timeNow;
        $statusToolMaterial->updated_by = auth()->user()->fullname;
        //Save
        $statusToolMaterial->save();

        return $statusToolMaterial;
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
            $single = new StatusToolMaterialResource($data);
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

    //     $status = StatusToolMaterial::find($id);
    //     if ($status == null) {
    //         throw new \Exception("Status Alat dan Bahan tidak ada", 404);
    //     }

    //     // $product = Product::query()->where('status_id', $status->id)->first();

    //     // if ($product != null) {
    //     //     return throw new \Exception("Data Status digunakan oleh Produk", 409);
    //     // }
    //     $status->deleted_by = auth()->user()->fullname;
    //     $status->save();

    //     $status->delete();

    //     return $status;
    // }
    // public function destroy(string $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $this->delete($id);
    //         DB::commit();
    //         // return
    //         return ResponseStd::okNoOutput("Status Alat dan Bahan berhasil dihapus.");
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
        $order = $columns[$request->has('order.0.column')] ? 'status'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = StatusToolMaterial::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = StatusToolMaterial::offset($start)->limit($limit)->orderBy($order, $dir)->get();
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
            $data =  StatusToolMaterial::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = StatusToolMaterial::whereRaw($conditions)->count();
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
