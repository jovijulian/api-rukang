<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\WorkProgress;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Models\ModuleCompleteness;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WorkProgressResource;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WorkProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'process_name';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND work_progresses.process_name LIKE '%$search_term%'";
            }

            $paginate = WorkProgress::query()->select(['work_progresses.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = WorkProgress::query()
                ->count();

            // paging response.
            $response = WorkProgressResource::collection($paginate);
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


    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $workProgressData = new WorkProgress();

        // input data work progress
        $workProgressData->process_name = $data['process_name'];
        // Input data multiple image
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    // $image_url = null;
                    Storage::exists('work-progress') or Storage::makeDirectory('work-progress');

                    // Simpan gambar ke penyimpanan
                    $image = Storage::putFile('work-progress', $file, 'public');

                    // Dapatkan URL gambar yang baru diunggah
                    $image_url = Storage::url($image);
                    if ($key == 'photo_01') {
                        $workProgressData->photo_01 = $image_url;
                    } elseif ($key == 'photo_02') {
                        $workProgressData->photo_02 = $image_url ?? '';
                    } elseif ($key == 'photo_03') {
                        $workProgressData->photo_03 = $image_url ?? '';
                    } elseif ($key == 'photo_04') {
                        $workProgressData->photo_04 = $image_url ?? '';
                    } elseif ($key == 'photo_05') {
                        $workProgressData->photo_05 =  $image_url ?? '';
                    } elseif ($key == 'photo_06') {
                        $workProgressData->photo_06 = $image_url ?? '';
                    } elseif ($key == 'photo_07') {
                        $workProgressData->photo_07 = $image_url ?? '';
                    } elseif ($key == 'photo_08') {
                        $workProgressData->photo_08 = $image_url ?? '';
                    } elseif ($key == 'photo_09') {
                        $workProgressData->photo_09 = $image_url ?? '';
                    } elseif ($key == 'photo_10') {
                        $workProgressData->photo_10 = $image_url ?? '';
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $workProgressData->$key = $key_id;
                }
            }
        }
        $workProgressData->created_at = $timeNow;
        $workProgressData->created_by = auth()->user()->fullname;
        $workProgressData->updated_at = null;
        $workProgressData->updated_by = null;

        // save shipping
        $workProgressData->save();

        return $workProgressData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $model = $this->create($request->all(), $request);
            DB::commit();

            // return
            $single = new WorkProgressResource($model);
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
            $model = WorkProgress::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Progres Pekerjaan tidak ada", 404);
            }
            $single = new WorkProgressResource($model);
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
            'process_name' => ['required', 'string', 'min:1', 'max:60'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find work progress by id
        $workProgressData = WorkProgress::find($id);

        if (empty($workProgressData)) {
            throw new \Exception("Invalid work progress id", 406);
        }
        $workProgressData->id = $id;
        $workProgressData->process_name = $data['process_name'];
        $workProgressData->updated_at = $timeNow;
        $workProgressData->updated_by = auth()->user()->fullname;
        //Save
        $workProgressData->save();

        return $workProgressData;
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
            $single = new WorkProgressResource($data);
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

        $workProgress = WorkProgress::find($id);
        if ($workProgress == null) {
            throw new \Exception("Progres Pekerjaan tidak ada", 404);
        }
        for ($i = 1; $i <= 10; $i++) {
            $columnName = "photo_" . str_pad($i, 2, '0', STR_PAD_LEFT);
            if (isset($workProgress->$columnName)) {
                $old = parse_url($workProgress->$columnName);
                if (Storage::exists($old['path'])) {
                    Storage::delete($old['path']);
                }
            }
        }

        $workProgress->deleted_by = auth()->user()->fullname;
        $workProgress->save();

        $workProgress->delete();

        return $workProgress;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Progres Pekerjaan berhasil dihapus.");
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
        $order = $columns[$request->has('order.0.column')] ? 'process_name'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = WorkProgress::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalData = $data->count();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND process_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  WorkProgress::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = WorkProgress::whereRaw($conditions)->count();
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


    public function updateImageWorkProgress($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->editImageWorkProgress($id, $request->all(), $request);

            DB::commit();
            $single = new WorkProgressResource($data);
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
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }

    protected function editImageWorkProgress($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        $workProgress = WorkProgress::find($id);
        if (empty($workProgress)) {
            throw new \Exception("Invalid work progress id", 406);
        }

        $workProgress->id = $id;
        $workProgress->process_name;
        // Input data multiple image
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $image_url = $data[$key];
                    if ($data[$key]) {
                        $image = Storage::putFile('work-progress', $data[$key], 'public');
                        $image_url = Storage::url($image);
                        //hapus picture sebelumnya
                        if (isset($workProgress->$key)) {
                            $old = parse_url($workProgress->$key);
                            if ($old['path'] != "") {
                                Storage::delete($old['path']);
                            } else {
                                $old['path'] = '';
                            }
                        }
                    }
                    if ($key == 'photo_01') {
                        $workProgress->photo_01 = $image_url;
                    } elseif ($key == 'photo_02') {
                        $workProgress->photo_02 = $image_url ?? '';
                    } elseif ($key == 'photo_03') {
                        $workProgress->photo_03 = $image_url ?? '';
                    } elseif ($key == 'photo_04') {
                        $workProgress->photo_04 = $image_url ?? '';
                    } elseif ($key == 'photo_05') {
                        $workProgress->photo_05 =  $image_url ?? '';
                    } elseif ($key == 'photo_06') {
                        $workProgress->photo_06 = $image_url ?? '';
                    } elseif ($key == 'photo_07') {
                        $workProgress->photo_07 = $image_url ?? '';
                    } elseif ($key == 'photo_08') {
                        $workProgress->photo_08 = $image_url ?? '';
                    } elseif ($key == 'photo_09') {
                        $workProgress->photo_09 = $image_url ?? '';
                    } elseif ($key == 'photo_10') {
                        $workProgress->photo_10 = $image_url ?? '';
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $workProgress->$key = $key_id;
                }
            }
        }
        $workProgress->updated_at = $timeNow;
        $workProgress->updated_by = auth()->user()->fullname;
        //Save
        $workProgress->save();
        return $workProgress;
    }

    protected function deleteSelectedImageWorkProgress($id, $request)
    {
        $selected_image = $request->input('selected_image');
        $workProgress = WorkProgress::find($id);
        if (empty($workProgress)) {
            throw new \Exception("Invalid work progress id", 406);
        }

        foreach ($selected_image as $si) {
            if (isset($workProgress->$si)) {
                $old = parse_url($workProgress->$si);
                if (Storage::exists($old['path'])) {
                    Storage::delete($old['path']);
                }
            }
            $workProgress->$si = '';
            $workProgress->save();
        }
        return $workProgress;
    }
    public function destroySelectedImageWorkProgress(string $id, Request $request)
    {

        DB::beginTransaction();
        try {
            $this->deleteSelectedImageWorkProgress($id, $request);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Gambar berhasil dihapus.',
            ], 200);
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
}
