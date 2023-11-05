<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Models\ModuleCompleteness;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\ModuleCompletenessResource;

class ModuleCompletenessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'segment';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND module_completeness.segment LIKE '%$search_term%'";
            }

            $paginate = ModuleCompleteness::query()->select(['module_completeness.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = ModuleCompleteness::query()
                ->count();

            // paging response.
            $response = ModuleCompletenessResource::collection($paginate);
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
            'segment' => ['required', 'string', 'min:1', 'max:40', 'unique:module_completeness,segment,NULL,id,module,' . request('module')],
            'module' => ['required', 'string', 'min:1', 'max:40', 'unique:module_completeness,module,NULL,id,segment,' . request('segment')],
            'completeness' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $moduleCompletenessData = new ModuleCompleteness();

        // input data module completeness
        $moduleCompletenessData->segment_id = $data['segment_id'];
        $moduleCompletenessData->segment = $data['segment'];
        $moduleCompletenessData->module_id = $data['module_id'];
        $moduleCompletenessData->module = $data['module'];
        $moduleCompletenessData->completeness = $data['completeness'];
        $moduleCompletenessData->created_at = $timeNow;
        $moduleCompletenessData->created_by = auth()->user()->fullname;
        $moduleCompletenessData->updated_at = null;
        $moduleCompletenessData->updated_by = null;

        // save shipping
        $moduleCompletenessData->save();

        return $moduleCompletenessData;
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
            $single = new ModuleCompletenessResource($model);
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
            $model = ModuleCompleteness::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Kelengkapan Modul tidak ada", 404);
            }
            $single = new ModuleCompletenessResource($model);
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

    protected function validateUpdate($id, array $data)
    {
        $getData = ModuleCompleteness::where('id', $id)->first();
        if ($getData->segment != $data['segment'] || $getData->module != $data['module']) {
            $arrayValidator = [
                'segment' => ['required', 'string', 'min:1', 'max:40', 'unique:module_completeness,segment,NULL,id,module,' . request('module')],
                'module' => ['required', 'string', 'min:1', 'max:40', 'unique:module_completeness,module,NULL,id,segment,' . request('segment')],
                'completeness' => ['required'],
            ];
        } else {
            $arrayValidator = [
                'segment' => ['required', 'string', 'min:1', 'max:40'],
                'module' => ['required', 'string', 'min:1', 'max:40'],
                'completeness' => ['required'],
            ];
        }

        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find module completeness by id
        $moduleCompletenessData = ModuleCompleteness::find($id);

        if (empty($moduleCompletenessData)) {
            throw new \Exception("Invalid module completeness id", 406);
        }
        $moduleCompletenessData->id = $id;
        $moduleCompletenessData->segment_id = $data['segment_id'];
        $moduleCompletenessData->segment = $data['segment'];
        $moduleCompletenessData->module_id = $data['module_id'];
        $moduleCompletenessData->module = $data['module'];
        $moduleCompletenessData->completeness = $data['completeness'];
        $moduleCompletenessData->updated_at = $timeNow;
        $moduleCompletenessData->updated_by = auth()->user()->fullname;
        //Save
        $moduleCompletenessData->save();

        return $moduleCompletenessData;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateUpdate($id, $request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->edit($id, $request->all(), $request);

            DB::commit();

            // return.
            $single = new ModuleCompletenessResource($data);
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

        $moduleCompleteness = ModuleCompleteness::find($id);
        if ($moduleCompleteness == null) {
            throw new \Exception("Kelengkapan Modul tidak ada", 404);
        }

        $moduleCompleteness->deleted_by = auth()->user()->fullname;
        $moduleCompleteness->save();

        $moduleCompleteness->delete();

        return $moduleCompleteness;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Kelengkapan Modul berhasil dihapus.");
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
        $order = $columns[$request->has('order.0.column')] ? 'segment'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = ModuleCompleteness::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalData = $data->count();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND segment LIKE '%" . trim($search) . "%'";
                $conditions .= " OR module LIKE '%" . trim($search) . "%'";
                $conditions .= " OR completeness LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  ModuleCompleteness::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = ModuleCompleteness::whereRaw($conditions)->count();
            $totalData = $totalFiltered;
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
