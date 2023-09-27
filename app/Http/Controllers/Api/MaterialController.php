<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Models\StatusMaterialLog;
use Illuminate\Support\Facades\DB;
use App\Models\LocationMaterialLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MaterialResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\StatusMaterialLogResource;
use App\Http\Resources\LocationMaterialLogResource;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MaterialController extends Controller
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
                $conditions .= " AND materials.material_name LIKE '%$search_term%'";
            }

            $paginate = Material::query()->select(['materials.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Material::query()
                ->count();

            // paging response.
            $response = MaterialResource::collection($paginate);
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
            'material_name' => ['required', 'min:1', 'max:70'],
            'material_note' => ['required', 'min:1', 'max:100'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $materialData = new Material();
        $image_url = null;
        Storage::exists('material') or Storage::makeDirectory('material');
        if ($data['status_photo']) {
            $image = Storage::putFile('material', $data['status_photo'], 'public');
            $image_url = Storage::url($image);
        }

        $materialId = Uuid::uuid4()->toString();
        $materialData->id = $materialId;
        $materialData->category_id = $data['category_id'];
        $materialData->category = $data['category'];
        $materialData->material_name = $data['material_name'];
        $materialData->material_note = $data['material_note'];
        $materialData->status_id = $data['status_id'];
        $materialData->status = $data['status'];
        $materialData->status_photo = $image_url;
        $materialData->status_note = $data['status_note'];
        $materialData->shipping_id = $data['shipping_id'];
        $materialData->shipping_name = $data['shipping_name'];
        $materialData->current_location = $data['current_location'];
        $materialData->group_id = auth()->user()->group_id;
        $materialData->group_name = auth()->user()->group_name;

        $materialData->created_at = $timeNow;
        $materialData->updated_at = null;
        $materialData->created_by = auth()->user()->fullname;
        $materialData->updated_by = null;

        // save material
        $materialData->save();

        $statusLogData = new StatusMaterialLog();
        $statusLogId = Uuid::uuid4()->toString();
        $statusLogData->id = $statusLogId;
        $statusLogData->material_id = $materialData->id;
        $statusLogData->status_id = $materialData->status_id;
        $statusLogData->status_name = $materialData->status;
        $statusLogData->status_photo = $materialData->status_photo;
        $statusLogData->note = $materialData->status_note;
        $statusLogData->shipping_id =  $materialData->shipping_id;
        $statusLogData->shipping_name = $materialData->shipping_name;
        $statusLogData->number_plate =  $data['number_plate'];
        $statusLogData->created_at = $timeNow;
        $statusLogData->updated_at = null;
        $statusLogData->created_by = auth()->user()->fullname;
        $statusLogData->updated_by = null;

        //save status material logs
        $statusLogData->save();

        $locationLogData = new LocationMaterialLog();
        $locationLogId = Uuid::uuid4()->toString();
        $locationLogData->id = $locationLogId;
        $locationLogData->status_material_log_id = $statusLogData->id;
        $locationLogData->material_id = $materialData->id;
        $locationLogData->current_location = $materialData->current_location;
        $locationLogData->created_at = $timeNow;
        $locationLogData->updated_at = null;
        $locationLogData->created_by = auth()->user()->fullname;
        $locationLogData->updated_by = null;

        //save location logs
        $locationLogData->save();


        return $materialData;
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
            $single = new MaterialResource($model);
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
            $model = Material::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Bahan tidak ada", 404);
            }
            $single = new MaterialResource($model);
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
            'material_name' => ['required', 'min:1', 'max:70'],
            'material_note' => ['required', 'min:1', 'max:100'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find material by id
        $materialData = Material::find($id);

        if (empty($materialData)) {
            throw new \Exception("Invalid material id", 406);
        }

        $materialData->id = $id;
        $materialData->category_id = $data['category_id'];
        $materialData->category = $data['category'];
        $materialData->material_name = $data['material_name'];
        $materialData->material_note = $data['material_note'];
        $materialData->status_id;
        $materialData->status;
        $materialData->status_photo;
        $materialData->status_note;
        $materialData->shipping_id;
        $materialData->shipping_name;
        $materialData->current_location;
        $materialData->group_id;
        $materialData->group_name;
        $materialData->updated_at = $timeNow;
        $materialData->updated_by = auth()->user()->fullname;
        //Save
        $materialData->save();

        return $materialData;
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
            $single = new MaterialResource($data);
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

        $material = Material::find($id);

        if ($material == null) {
            throw new \Exception("Bahan tidak ada", 404);
        }

        if (isset($material->status_photo)) {
            $old = parse_url($material->status_photo);
            if (Storage::exists($old['path'])) {
                Storage::delete($old['path']);
            }
        }

        $statusLogs = StatusMaterialLog::where('material_id', $id)->get();

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
            $LocationLogs = LocationMaterialLog::where('status_material_log_id', $statusLog->id)->get();
            foreach ($LocationLogs as $LocationLog) {
                $LocationLog->deleted_by = auth()->user()->fullname;
                $LocationLog->save();
                $LocationLog->delete();
            }
            $statusLog->deleted_by = auth()->user()->fullname;
            $statusLog->save();
            $statusLog->delete();
        }

        $material->deleted_by = auth()->user()->fullname;
        $material->save();
        $material->delete();

        return $material;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Bahan berhasil dihapus.");
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

    protected function validateStatusLogMaterial(array $data)
    {
        $arrayValidator = [
            // 'material_id' => ['required'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    public function setStatusLogMaterial($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateStatusLogMaterial($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->insertStatusLogMaterial($id, $request->all(), $request);

            DB::commit();
            $single = new StatusMaterialLogResource($data);
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

    protected function insertStatusLogMaterial($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();
        $statusLog = new StatusMaterialLog();

        if (empty($statusLog)) {
            throw new \Exception("Invalid status material log id", 406);
        }
        $statusLog->id = Uuid::uuid4()->toString();
        $statusLog->material_id = $id;
        $statusLog->status_id = $data['status_id'];
        $statusLog->status_name = $data['status_name'];


        // Input data multiple image
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    // $image_url = null;
                    Storage::exists('material') or Storage::makeDirectory('material');

                    // Simpan gambar ke penyimpanan
                    $image = Storage::putFile('material', $file, 'public');

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

        $materialData = Material::find($id);
        $materialData->status_id = $statusLog->status_id;
        $materialData->status = $statusLog->status_name;
        $materialData->status_photo = $statusLog->status_photo;
        $materialData->status_note = $statusLog->status_note;
        $materialData->current_location = $data['current_location'];
        $materialData->save();

        $locationLog = new LocationMaterialLog();
        $locationLog->id = Uuid::uuid4()->toString();
        $locationLog->status_material_log_id = $statusLog->id;
        $locationLog->material_id = $id;
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
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = Material::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Material::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND material_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR material_note LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category LIKE '%" . trim($search) . "%'";
                $conditions .= " OR status LIKE '%" . trim($search) . "%'";
                $conditions .= " OR shipping_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR group_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Material::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Material::whereRaw($conditions)->count();
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
            $single = new LocationMaterialLogResource($data);
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

        $statusLog = StatusMaterialLog::find($id);
        // dd($statusLog);

        if (empty($statusLog)) {
            throw new \Exception("Invalid status material log id", 406);
        }
        $statusLog->shipping_id;
        $statusLog->shipping_name;
        $statusLog->number_plate;
        $statusLog->updated_at = $timeNow;
        $statusLog->updated_by = auth()->user()->fullname;
        //Save
        $statusLog->save();


        $materialData = Material::where('id', $statusLog->material_id)->first();
        $materialData->current_location = $data['current_location'];
        $materialData->save();

        $locationLog = LocationMaterialLog::where('status_material_log_id', $id)->first();
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
            $single = new StatusMaterialLogResource($data);
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

        $statusLog = StatusMaterialLog::find($id);
        if (empty($statusLog)) {
            throw new \Exception("Invalid status material log id", 406);
        }

        $statusLog->status_id;
        $statusLog->status_name;
        // Input data multiple image
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $image_url = $data[$key];
                    if ($data[$key]) {
                        $image = Storage::putFile('material', $data[$key], 'public');
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
}
