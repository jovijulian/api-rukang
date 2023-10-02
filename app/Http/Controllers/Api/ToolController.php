<?php

namespace App\Http\Controllers\Api;

use App\Exports\ToolExport;
use Carbon\Carbon;
use App\Models\Tool;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\StatusToolLog;
use App\Libraries\ResponseStd;
use App\Models\LocationToolLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ToolResource;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StatusToolLogResource;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\LocationToolLogResource;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ToolController extends Controller
{
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
                $conditions .= " AND tools.tool_name LIKE '%$search_term%'";
            }

            $paginate = Tool::query()->select(['tools.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Tool::query()
                ->count();

            // paging response.
            $response = ToolResource::collection($paginate);
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
            'type' => ['required', 'min:1', 'max:50'],
            'tool_name' => ['required', 'min:1', 'max:100'],
            'serial_number' => ['required', 'min:1', 'max:100'],
            'amount' => ['required'],
            'note' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $toolData = new Tool();
        $image_url = null;
        Storage::exists('tool') or Storage::makeDirectory('tool');
        if ($data['status_photo']) {
            $image = Storage::putFile('tool', $data['status_photo'], 'public');
            $image_url = Storage::url($image);
        }

        $toolId = Uuid::uuid4()->toString();
        $toolData->id = $toolId;
        $toolData->category_id = $data['category_id'];
        $toolData->category = $data['category'];
        $toolData->type = $data['type'];
        $toolData->tool_name = $data['tool_name'];
        $toolData->serial_number = $data['serial_number'];
        $toolData->amount = $data['amount'];
        $toolData->note = $data['note'];
        $toolData->status_id = $data['status_id'];
        $toolData->status = $data['status'];
        $toolData->status_photo = $image_url;
        $toolData->status_note = $data['status_note'];
        $toolData->shipping_id = $data['shipping_id'];
        $toolData->shipping_name = $data['shipping_name'];
        $toolData->current_location = $data['current_location'];
        $toolData->group_id = auth()->user()->group_id;
        $toolData->group_name = auth()->user()->group_name;

        $toolData->created_at = $timeNow;
        $toolData->updated_at = null;
        $toolData->created_by = auth()->user()->fullname;
        $toolData->updated_by = null;

        // save tool
        $toolData->save();

        $statusLogData = new StatusToolLog();
        $statusLogId = Uuid::uuid4()->toString();
        $statusLogData->id = $statusLogId;
        $statusLogData->tool_id = $toolData->id;
        $statusLogData->status_id = $toolData->status_id;
        $statusLogData->status_name = $toolData->status;
        $statusLogData->status_photo = $toolData->status_photo;
        $statusLogData->note = $toolData->status_note;
        $statusLogData->shipping_id =  $toolData->shipping_id;
        $statusLogData->shipping_name = $toolData->shipping_name;
        $statusLogData->number_plate =  $data['number_plate'];
        $statusLogData->created_at = $timeNow;
        $statusLogData->updated_at = null;
        $statusLogData->created_by = auth()->user()->fullname;
        $statusLogData->updated_by = null;

        //save status tool logs
        $statusLogData->save();

        $locationLogData = new LocationToolLog();
        $locationLogId = Uuid::uuid4()->toString();
        $locationLogData->id = $locationLogId;
        $locationLogData->status_tool_log_id = $statusLogData->id;
        $locationLogData->tool_id = $toolData->id;
        $locationLogData->current_location = $toolData->current_location;
        $locationLogData->created_at = $timeNow;
        $locationLogData->updated_at = null;
        $locationLogData->created_by = auth()->user()->fullname;
        $locationLogData->updated_by = null;

        //save location logs
        $locationLogData->save();


        return $toolData;
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
            $single = new ToolResource($model);
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
            $model = Tool::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Alat tidak ada", 404);
            }
            $single = new ToolResource($model);
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
            'type' => ['required', 'min:1', 'max:50'],
            'tool_name' => ['required', 'min:1', 'max:100'],
            'serial_number' => ['required', 'min:1', 'max:100'],
            'amount' => ['required'],
            'note' => ['required'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find tool by id
        $toolData = Tool::find($id);

        if (empty($toolData)) {
            throw new \Exception("Invalid tool id", 406);
        }

        $toolData->id = $id;
        $toolData->category_id = $data['category_id'];
        $toolData->category = $data['category'];
        $toolData->type = $data['type'];
        $toolData->tool_name = $data['tool_name'];
        $toolData->serial_number = $data['serial_number'];
        $toolData->amount = $data['amount'];
        $toolData->note = $data['note'];
        $toolData->status_id;
        $toolData->status;
        $toolData->status_photo;
        $toolData->status_note;
        $toolData->shipping_id;
        $toolData->shipping_name;
        $toolData->current_location;
        $toolData->group_id;
        $toolData->group_name;
        $toolData->updated_at = $timeNow;
        $toolData->updated_by = auth()->user()->fullname;
        //Save
        $toolData->save();

        return $toolData;
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
            $single = new ToolResource($data);
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

        $tool = Tool::find($id);

        if ($tool == null) {
            throw new \Exception("Alat tidak ada", 404);
        }

        if (isset($tool->status_photo)) {
            $old = parse_url($tool->status_photo);
            if (Storage::exists($old['path'])) {
                Storage::delete($old['path']);
            }
        }

        $statusLogs = StatusToolLog::where('tool_id', $id)->get();

        foreach ($statusLogs as $statusLog) {
            for ($i = 1; $i <= 10; $i++) {
                $columnName = "status_photo" . $i;
                if (isset($statusLog->$columnName)) {
                    $old = parse_url($statusLog->$columnName);
                    if (Storage::exists($old['path'])) {
                        Storage::delete($old['path']);
                    }
                }
            }
            $LocationLogs = LocationToolLog::where('status_tool_log_id', $statusLog->id)->get();
            foreach ($LocationLogs as $LocationLog) {
                $LocationLog->deleted_by = auth()->user()->fullname;
                $LocationLog->save();
                $LocationLog->delete();
            }
            $statusLog->deleted_by = auth()->user()->fullname;
            $statusLog->save();
            $statusLog->delete();
        }

        $tool->deleted_by = auth()->user()->fullname;
        $tool->save();
        $tool->delete();

        return $tool;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Alat berhasil dihapus.");
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

    protected function validateStatusLogTool(array $data)
    {
        $arrayValidator = [
            // 'status_photo' => ['image', 'mimes:jpg,png,jpeg,gif,svg'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    public function setStatusLogTool($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateStatusLogTool($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->insertStatusLogTool($id, $request->all(), $request);

            DB::commit();
            $single = new StatusToolLogResource($data);
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

    protected function insertStatusLogTool($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();
        $statusLog = new StatusToolLog();

        if (empty($statusLog)) {
            throw new \Exception("Invalid status tool log id", 406);
        }
        $statusLog->id = Uuid::uuid4()->toString();
        $statusLog->tool_id = $id;
        $statusLog->status_id = $data['status_id'];
        $statusLog->status_name = $data['status_name'];


        // Input data multiple image
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    // $image_url = null;
                    Storage::exists('tool') or Storage::makeDirectory('tool');

                    // Simpan gambar ke penyimpanan
                    $image = Storage::putFile('tool', $file, 'public');

                    // Dapatkan URL gambar yang baru diunggah
                    $image_url = Storage::url($image);
                    if ($key == 'status_photo') {
                        $statusLog->status_photo = $image_url;
                    } elseif ($key == 'status_photo2') {
                        $statusLog->status_photo2 = $image_url ?? '';
                    } elseif ($key == 'status_photo3') {
                        $statusLog->status_photo3 = $image_url ?? '';
                    } elseif ($key == 'status_photo4') {
                        $statusLog->status_photo4 = $image_url ?? '';
                    } elseif ($key == 'status_photo5') {
                        $statusLog->status_photo5 =  $image_url ?? '';
                    } elseif ($key == 'status_photo6') {
                        $statusLog->status_photo6 = $image_url ?? '';
                    } elseif ($key == 'status_photo7') {
                        $statusLog->status_photo7 = $image_url ?? '';
                    } elseif ($key == 'status_photo8') {
                        $statusLog->status_photo8 = $image_url ?? '';
                    } elseif ($key == 'status_photo9') {
                        $statusLog->status_photo9 = $image_url ?? '';
                    } elseif ($key == 'status_photo10') {
                        $statusLog->status_photo10 = $image_url ?? '';
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $statusLog->$key = $key_id;
                }
            }
        }

        $statusLog->note = $data['note'];
        $statusLog->shipping_id = $data['shipping_id'];
        $statusLog->shipping_name = $data['shipping_name'];
        $statusLog->number_plate = $data['number_plate'];
        $statusLog->created_at = $timeNow;
        $statusLog->created_by = auth()->user()->fullname;
        $statusLog->updated_at = null;
        $statusLog->updated_by = null;

        //Save
        $statusLog->save();

        $toolData = Tool::find($id);
        $toolData->status_id = $statusLog->status_id;
        $toolData->status = $statusLog->status_name;
        $toolData->status_photo = $statusLog->status_photo;
        $toolData->status_note = $statusLog->status_note;
        $toolData->current_location = $data['current_location'];
        $toolData->save();

        $locationLog = new LocationToolLog();
        $locationLog->id = Uuid::uuid4()->toString();
        $locationLog->status_tool_log_id = $statusLog->id;
        $locationLog->tool_id = $id;
        $locationLog->current_location = $data['current_location'];
        $locationLog->created_at = $timeNow;
        $locationLog->created_by = auth()->user()->fullname;
        $locationLog->updated_at = null;
        $locationLog->updated_by = null;
        $locationLog->save();

        return $statusLog;
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
        $order = $columns[$request->has('order.0.column')] ? 'type'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = Tool::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Tool::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND type LIKE '%" . trim($search) . "%'";
                $conditions .= " OR tool_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR serial_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR amount LIKE '%" . trim($search) . "%'";
                $conditions .= " OR note LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category LIKE '%" . trim($search) . "%'";
                $conditions .= " OR status LIKE '%" . trim($search) . "%'";
                $conditions .= " OR shipping_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR group_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Tool::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Tool::whereRaw($conditions)->count();
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        return json_encode($json_data);
    }

    protected function validateEditLocation(array $data)
    {
        $arrayValidator = [
            'current_location' => ['required'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    public function editLocation($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateEditLocation($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->updateLocation($id, $request->all(), $request);

            DB::commit();
            $single = new LocationToolLogResource($data);
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

    protected function updateLocation($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        $statusLog = StatusToolLog::find($id);
        // dd($statusLog);

        if (empty($statusLog)) {
            throw new \Exception("Invalid status tool log id", 406);
        }
        $statusLog->shipping_id;
        $statusLog->shipping_name;
        $statusLog->number_plate;
        $statusLog->updated_at = $timeNow;
        $statusLog->updated_by = auth()->user()->fullname;
        //Save
        $statusLog->save();


        $toolData = Tool::where('id', $statusLog->tool_id)->first();
        $toolData->current_location = $data['current_location'];
        $toolData->save();

        $locationLog = LocationToolLog::where('status_tool_log_id', $id)->first();
        $locationLog->current_location = $data['current_location'];
        $locationLog->updated_at = $timeNow;
        $locationLog->updated_by = auth()->user()->fullname;
        $locationLog->save();

        return $locationLog;
    }

    protected function validateMultipleImages(array $data)
    {
        $arrayValidator = [
            'status_photo' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    public function addMultipleImagesStatus($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateMultipleImages($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->insertMultipleImageStatus($id, $request->all(), $request);

            DB::commit();
            $single = new StatusToolLogResource($data);
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

    protected function insertMultipleImageStatus($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        $statusLog = StatusToolLog::find($id);
        if (empty($statusLog)) {
            throw new \Exception("Invalid status tool log id", 406);
        }

        $statusLog->status_id;
        $statusLog->status_name;
        // Input data multiple image
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $image_url = $data[$key];
                    if ($data[$key]) {
                        $image = Storage::putFile('tool', $data[$key], 'public');
                        $image_url = Storage::url($image);
                        //hapus picture sebelumnya
                        if (isset($statusLog->$key)) {
                            $old = parse_url($statusLog->$key);
                            if (Storage::exists($old['path'])) {
                                Storage::delete($old['path']);
                            }
                        }
                    }
                    if ($key == 'status_photo') {
                        $statusLog->status_photo = $image_url;
                    } elseif ($key == 'status_photo2') {
                        $statusLog->status_photo2 = $image_url ?? '';
                    } elseif ($key == 'status_photo3') {
                        $statusLog->status_photo3 = $image_url ?? '';
                    } elseif ($key == 'status_photo4') {
                        $statusLog->status_photo4 = $image_url ?? '';
                    } elseif ($key == 'status_photo5') {
                        $statusLog->status_photo5 =  $image_url ?? '';
                    } elseif ($key == 'status_photo6') {
                        $statusLog->status_photo6 = $image_url ?? '';
                    } elseif ($key == 'status_photo7') {
                        $statusLog->status_photo7 = $image_url ?? '';
                    } elseif ($key == 'status_photo8') {
                        $statusLog->status_photo8 = $image_url ?? '';
                    } elseif ($key == 'status_photo9') {
                        $statusLog->status_photo9 = $image_url ?? '';
                    } elseif ($key == 'status_photo10') {
                        $statusLog->status_photo10 = $image_url ?? '';
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $statusLog->$key = $key_id;
                }
            }
        }
        $statusLog->note;
        $statusLog->shipping_id;
        $statusLog->shipping_name;
        $statusLog->number_plate;
        $statusLog->updated_at = $timeNow;
        $statusLog->updated_by = auth()->user()->fullname;
        //Save
        $statusLog->save();


        return $statusLog;
    }

    public function export()
    {
        set_time_limit(300);
        return Excel::download(new ToolExport, 'laporan-alat-' . now()->format('Y-m-d H:i:s') . '.xlsx');
    }
}
