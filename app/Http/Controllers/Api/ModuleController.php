<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Module;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'module_number';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND modules.module_number LIKE '%$search_term%'";
            }

            $paginate = Module::query()->select(['modules.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Module::query()
                ->count();

            // paging response.
            $response = ModuleResource::collection($paginate);
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
            'module_number' => ['required', 'string', 'min:1', 'max:10'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $moduleData = new Module();

        // input data module
        $moduleData->module_number = $data['module_number'];
        $moduleData->created_at = $timeNow;
        $moduleData->created_by = auth()->user()->fullname;
        $moduleData->updated_at = null;
        $moduleData->updated_by = null;

        // save module
        $moduleData->save();

        return $moduleData;
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
            $single = new ModuleResource($model);
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
            $model = Module::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Modul tidak ada", 404);
            }
            $single = new ModuleResource($model);
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
            'module_number' => ['required', 'string', 'min:1', 'max:10'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find module by id
        $moduleData = Module::find($id);

        if (empty($moduleData)) {
            throw new \Exception("Invalid module id", 406);
        }
        $moduleData->id = $id;
        $moduleData->module_number = $data['module_number'];
        $moduleData->updated_at = $timeNow;
        $moduleData->updated_by = auth()->user()->fullname;
        //Save
        $moduleData->save();

        return $moduleData;
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
            $single = new ModuleResource($data);
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

        $module = Module::find($id);
        if ($module == null) {
            throw new \Exception("Modul tidak ada", 404);
        }

        $product = Product::query()->where('module_id', $module->id)->first();

        if ($product != null) {
            return throw new \Exception("Data Modul digunakan oleh Produk", 409);
        }

        $module->deleted_by = auth()->user()->fullname;
        $module->save();

        $module->delete();

        return $module;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Modul berhasil dihapus.");
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
        $totalData = Module::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Module::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND module_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Module::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Module::whereRaw($conditions)->count();
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
