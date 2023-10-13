<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Status;
use App\Models\Product;
use App\Models\Category;
use App\Models\StatusLog;
use App\Models\LocationLog;
use Illuminate\Http\Request;
use App\Models\StatusProduct;
use App\Exports\ProductExport;
use App\Libraries\ResponseStd;
use Illuminate\Validation\Rule;
use App\Models\StatusProductLog;
use App\Models\LocationProductLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ProductResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\StatusLogResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LocationLogResource;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\StatusProductLogResource;
use App\Http\Resources\LocationProductLogResource;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductController extends Controller
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
                $conditions .= " AND products.barcode LIKE '%$search_term%'";
            }

            $paginate = Product::query()->select(['products.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Product::query()
                ->count();

            // paging response.
            $response = ProductResource::collection($paginate);
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
            'barcode' => [
                'required', 'min:1', 'max:100',
                'unique:products,barcode,NULL,id',
            ],
            // 'status_photo' => ['image', 'mimes:jpg,png,jpeg,gif,svg'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {
        $timeNow = Carbon::now();
        $productData = new Product();
        $image_url = null;

        Storage::exists('product') or Storage::makeDirectory('product');
        if ($data['status_photo']) {
            $image = Storage::putFile('product', $data['status_photo'], 'public');
            $image_url = Storage::url($image);
        }
        if ($data['status_id'] == 25 || $data['status_id'] == 16 || $data['status_id'] == null) {
            $productData->qty = 0;
        } else {
            $productData->qty = 1;
        }


        if ($data['status_id'] == 17) {
            $productData->finish_production_date = $data['status_date'];
        } else if ($data['status_id'] != 17 && $productData->qty == 1) {
            $productData->finish_production_date = '2023-03-27';
        } else {
            $productData->finish_production_date = null;
        }

        if ($data['status_id'] == 20) {
            $productData->delivery_date = $data['status_date'];
        }

        $productId = Uuid::uuid4()->toString();
        $productData->id = $productId;
        $productData->category_id = $data['category_id'];
        $productData->category = $data['category'];
        $productData->segment_id = $data['segment_id'];
        $productData->segment_name = $data['segment_name'];
        $productData->segment_place = $data['segment_place'];
        $productData->barcode = $data['barcode'];
        $productData->module_id = $data['module_id'];
        $productData->module_number = $data['module_number'];
        $productData->bilah_number = $data['bilah_number'];
        $productData->start_production_date = $data['start_production_date'];
        $productData->shelf_id = $data['shelf_id'];
        $productData->shelf_name = $data['shelf_name'];
        $productData->description = $data['description'];
        $productData->status_id = $data['status_id'];
        $productData->status = $data['status'];
        $productData->status_photo = $image_url;
        $productData->note = $data['note'];
        $productData->shipping_id = $data['shipping_id'];
        $productData->shipping_name = $data['shipping_name'];
        $productData->current_location = $data['current_location'];
        $productData->group_id = $data['group_id'];
        $productData->group_name = $data['group_name'];

        $productData->created_at = $timeNow;
        $productData->updated_at = null;
        $productData->created_by = auth()->user()->fullname;
        $productData->updated_by = null;

        // save product
        $productData->save();

        $statusLogData = new StatusProductLog();
        $statusLogId = Uuid::uuid4()->toString();
        $statusLogData->id = $statusLogId;
        $statusLogData->product_id = $productData->id;
        $statusLogData->status_id = $productData->status_id;
        $statusLogData->status_name = $productData->status;
        $statusLogData->status_date =  $data['status_date'];;
        $statusLogData->status_photo = $productData->status_photo;
        $statusLogData->note = $productData->note;
        $statusLogData->shipping_id = $productData->shipping_id;
        $statusLogData->shipping_name = $productData->shipping_name;
        $statusLogData->number_plate = $data['number_plate'];
        $statusLogData->created_at = $timeNow;
        $statusLogData->updated_at = null;
        $statusLogData->created_by = auth()->user()->fullname;
        $statusLogData->updated_by = null;

        //save status logs
        $statusLogData->save();

        $locationLogData = new LocationProductLog();
        $locationLogId = Uuid::uuid4()->toString();
        $locationLogData->id = $locationLogId;
        $locationLogData->status_product_log_id = $statusLogData->id;
        $locationLogData->product_id = $productData->id;
        $locationLogData->current_location = $productData->current_location;
        $locationLogData->created_at = $timeNow;
        $locationLogData->updated_at = null;
        $locationLogData->created_by = auth()->user()->fullname;
        $locationLogData->updated_by = null;

        //save location logs
        $locationLogData->save();


        return $productData;
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
            // dd($request->all());
            $model = $this->create($request->all(), $request);
            DB::commit();

            // return
            $single = new ProductResource($model);
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
            $model = Product::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Produk tidak ada", 404);
            }
            $single = new ProductResource($model);
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
            'barcode' => [
                'required', 'min:1', 'max:100',
            ],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find product by id
        $productData = Product::find($id);

        if (empty($productData)) {
            throw new \Exception("Invalid product id", 406);
        }

        // $image_url = $data['status_photo'];
        // if ($data['status_photo']) {
        //     $image = Storage::putFile('product', $data['status_photo'], 'public');
        //     $image_url = Storage::url($image);
        //     //hapus picture sebelumnya
        //     if (isset($productData->status_photo)) {
        //         $old = parse_url($productData->status_photo);
        //         if (Storage::exists($old['path'])) {
        //             Storage::delete($old['path']);
        //         }
        //     }
        // }
        $productData->id = $id;
        $productData->category_id = $data['category_id'];
        $productData->category = $data['category'];
        $productData->segment_id = $data['segment_id'];
        $productData->segment_name = $data['segment_name'];
        $productData->segment_place = $data['segment_place'];
        $productData->barcode = $data['barcode'];
        $productData->module_id = $data['module_id'];
        $productData->module_number = $data['module_number'];
        $productData->bilah_number = $data['bilah_number'];
        $productData->start_production_date = $data['start_production_date'];
        $productData->finish_production_date;
        $productData->shelf_id = $data['shelf_id'];
        $productData->shelf_name = $data['shelf_name'];
        $productData->description = $data['description'];
        $productData->delivery_date;
        $productData->qty;
        $productData->status_id;
        $productData->status;
        $productData->status_photo;
        $productData->note;
        $productData->shipping_id;
        $productData->shipping_name;
        $productData->current_location;
        $productData->group_id = $data['group_id'];
        $productData->group_name  = $data['group_name'];
        $productData->updated_at = $timeNow;
        $productData->updated_by = auth()->user()->fullname;

        //Save
        $productData->save();

        return $productData;
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
            $single = new ProductResource($data);
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

        $product = Product::find($id);

        if ($product == null) {
            throw new \Exception("Produk tidak ada", 404);
        }

        if (isset($product->status_photo)) {
            $old = parse_url($product->status_photo);
            if (Storage::exists($old['path'])) {
                Storage::delete($old['path']);
            }
        }

        $statusLogs = StatusProductLog::where('product_id', $id)->get();

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
            $LocationLogs = LocationProductLog::where('status_product_log_id', $statusLog->id)->get();
            foreach ($LocationLogs as $LocationLog) {
                $LocationLog->deleted_by = auth()->user()->fullname;
                $LocationLog->save();
                $LocationLog->delete();
            }
            $statusLog->deleted_by = auth()->user()->fullname;
            $statusLog->save();
            $statusLog->delete();
        }

        $product->barcode = 'deleted at ' . Carbon::now();
        $product->deleted_by = auth()->user()->fullname;
        $product->save();
        $product->delete();

        return $product;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Produk berhasil dihapus.");
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

    protected function validateStatusLogProduct(array $data)
    {
        $arrayValidator = [
            // 'status_photo' => ['image', 'mimes:jpg,png,jpeg,gif,svg'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    public function addStatusLogProduct($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateStatusLogProduct($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->insertStatusLogProduct($id, $request->all(), $request);

            DB::commit();
            $single = new StatusProductLogResource($data);
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

    protected function insertStatusLogProduct($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();
        $statusLog = new StatusProductLog();

        if (empty($statusLog)) {
            throw new \Exception("Invalid product id", 406);
        }

        $statusLog->id = Uuid::uuid4()->toString();
        $statusLog->product_id = $id;
        $statusLog->status_id = $data['status_id'];
        $statusLog->status_name = $data['status_name'];
        $statusLog->status_date = $data['status_date'];
        // Input data multiple image
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    // $image_url = null;
                    Storage::exists('product') or Storage::makeDirectory('product');

                    // Simpan gambar ke penyimpanan
                    $image = Storage::putFile('product', $file, 'public');

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

        $productData = Product::find($id);
        if ($data['status_id'] == 25 || $data['status_id'] == 16 || $data['status_id'] == null) {
            $productData->qty = 0;
        } else {
            $productData->qty = 1;
        }


        if ($data['status_id'] == 17) {
            $productData->finish_production_date = $data['status_date'];
        } else if ($data['status_id'] != 17 && $productData->qty == 1) {
            $productData->finish_production_date = '2023-03-27';
        } else {
            $productData->finish_production_date = null;
        }

        if ($data['status_id'] == 20) {
            $productData->delivery_date = $data['status_date'];
        }
        $productData->status_id = $statusLog->status_id;
        $productData->status = $statusLog->status_name;
        $productData->status_photo = $statusLog->status_photo;
        $productData->note = $statusLog->note;
        $productData->shipping_id = $statusLog->shipping_id;
        $productData->shipping_name = $statusLog->shipping_name;
        $productData->current_location = $data['current_location'];
        $productData->save();

        $locationLog = new LocationProductLog();
        $locationLog->id = Uuid::uuid4()->toString();
        $locationLog->status_product_log_id = $statusLog->id;
        $locationLog->product_id = $id;
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
        $order = $columns[$request->has('order.0.column')] ? 'barcode'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = Product::count();
        $search = $request->input('search.value');
        $query = Product::select('products.*', 'status_product_logs.id as status_product_log')
            ->join('status_product_logs', function ($join) {
                $join->on('products.id', '=', 'status_product_logs.product_id')
                    ->where('status_product_logs.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_product_logs WHERE product_id = products.id)'))
                    ->whereNull('products.deleted_at');
            });

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('products.barcode', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.category', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.segment_name', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.segment_place', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.module_number', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.bilah_number', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.start_production_date', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.finish_production_date', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.shelf_name', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.description', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.delivery_date', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.status', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.shipping_name', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('group_name', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.created_by', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('products.updated_by', 'LIKE', '%' . trim($search) . '%');
            });
        }
        // dd($query);
        if ($request->input('category') != null) {
            $query->where('category_id', $request->category);
        }

        if ($request->input('segment') != null) {
            $query->where('segment_id', $request->segment);
        }

        if ($request->input('module') != null) {
            $query->where('module_id', $request->module);
        }

        $totalData = $query->count();
        $totalFiltered = $totalData;

        $data = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $json_data = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data
        ];
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
            $single = new LocationProductLogResource($data);
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

        $statusLog = StatusProductLog::find($id);
        // dd($statusLog);

        if (empty($statusLog)) {
            throw new \Exception("Invalid status product log id", 406);
        }
        $statusLog->shipping_id;
        $statusLog->shipping_name;
        $statusLog->number_plate;
        $statusLog->updated_at = $timeNow;
        $statusLog->updated_by = auth()->user()->fullname;
        //Save
        $statusLog->save();


        $productData = Product::where('id', $statusLog->product_id)->first();
        $productData->current_location = $data['current_location'];
        $productData->save();


        $locationLog = new LocationProductLog();
        $locationLog->id = Uuid::uuid4()->toString();
        $locationLog->status_product_log_id = $id;
        $locationLog->product_id = $statusLog->product_id;
        $locationLog->current_location = $data['current_location'];
        $locationLog->created_at = $timeNow;
        $locationLog->created_by = auth()->user()->fullname;
        $locationLog->updated_at = null;
        $locationLog->updated_by = null;
        $locationLog->save();

        // $locationLog = LocationProductLog::where('status_product_log_id', $id)->first();
        // $locationLog->current_location = $data['current_location'];
        // $locationLog->updated_at = $timeNow;
        // $locationLog->updated_by = auth()->user()->fullname;
        // $locationLog->save();

        return $locationLog;
    }

    protected function validateStatusProduct(array $data)
    {
        $arrayValidator = [
            'status_photo' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    public function updateStatusProduct($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateStatusProduct($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->editStatusProduct($id, $request->all(), $request);

            DB::commit();
            $single = new StatusProductLogResource($data);
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

    protected function editStatusProduct($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        $statusLog = StatusProductLog::find($id);
        if (empty($statusLog)) {
            throw new \Exception("Invalid status product log id", 406);
        }

        $statusLog->status_id;
        $statusLog->status_name;
        $statusLog->status_date = $data['status_date'] ? $data['status_date'] : $statusLog->status_date;
        // Input data multiple image
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $image_url = $data[$key];
                    if ($data[$key]) {
                        $image = Storage::putFile('product', $data[$key], 'public');
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
        $statusLog->note = $data['note'] ? $data['note'] : $statusLog->note;
        $statusLog->shipping_id = $data['shipping_id'] ? $data['shipping_id'] : $statusLog->shipping_id;
        $statusLog->shipping_name = $data['shipping_name'] ? $data['shipping_name'] : $statusLog->shipping_name;
        $statusLog->number_plate = $data['number_plate'] ? $data['number_plate'] : $statusLog->number_plate;
        $statusLog->updated_at = $timeNow;
        $statusLog->updated_by = auth()->user()->fullname;
        //Save
        $statusLog->save();

        $productData = Product::where('id', $statusLog->product_id)->first();
        $productData->current_location = $data['current_location'];
        $productData->save();

        $searchLocation = LocationProductLog::where('status_product_log_id', $id)->latest()->first();
        if ($searchLocation->current_location != $data['current_location']) {
            $locationLog = new LocationProductLog();
            $locationLog->id = Uuid::uuid4()->toString();
            $locationLog->status_product_log_id = $id;
            $locationLog->product_id = $statusLog->product_id;
            $locationLog->current_location = $data['current_location'];
            $locationLog->created_at = $timeNow;
            $locationLog->created_by = auth()->user()->fullname;
            $locationLog->updated_at = null;
            $locationLog->updated_by = null;
            $locationLog->save();
        }




        return $statusLog;
    }

    public function export(Request $request)
    {
        set_time_limit(300);
        $segment = $request->has('segment') ? $request->input('segment') : null;
        return Excel::download(new ProductExport($segment), 'laporan-produk-' . now()->format('Y-m-d H:i:s') . '.xlsx');
    }

    public function datatableProductPerStatus(Request $request)
    {
        //SETUP
        $columns = array();

        foreach ($request->columns as $columnData) {
            $columns[] = $columnData['data'];
        }
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->has('order.0.column')] ? 'barcode'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = Product::count();
        $filterStatus = $request->input('status_id');
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Product::select('products.*', 'status_product_logs.id as status_product_log')
                ->join('status_product_logs', function ($join) {
                    $join->on('products.id', '=', 'status_product_logs.product_id')
                        ->where('status_product_logs.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_product_logs WHERE product_id = products.id)'));
                })
                ->where('products.status_id', $filterStatus)
                ->offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND barcode LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category LIKE '%" . trim($search) . "%'";
                $conditions .= " OR segment_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR segment_place LIKE '%" . trim($search) . "%'";
                $conditions .= " OR module_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR bilah_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR start_production_date LIKE '%" . trim($search) . "%'";
                $conditions .= " OR finish_production_date LIKE '%" . trim($search) . "%'";
                $conditions .= " OR shelf_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR description LIKE '%" . trim($search) . "%'";
                $conditions .= " OR delivery_date LIKE '%" . trim($search) . "%'";
                $conditions .= " OR status LIKE '%" . trim($search) . "%'";
                $conditions .= " OR products.shipping_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR group_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR products.created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR products.updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Product::select('products.*', 'status_product_logs.id as status_product_log')
                ->join('status_product_logs', function ($join) {
                    $join->on('products.id', '=', 'status_product_logs.product_id')
                        ->where('status_product_logs.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_product_logs WHERE product_id = products.id)'));
                })
                ->where('products.status_id', $filterStatus)
                ->whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Product::whereRaw($conditions)->count();
        }

        // FILTER DATA
        // dd($data);
        $statuses = StatusProduct::select('status')->where('id', $filterStatus)->first();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "status"          => $statuses->status,
            "data"            => $data
        );
        return json_encode($json_data);
    }

    public function continueStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->executeContinueStatus($request);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dilanjutkan statusnya.',
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

    protected function executeContinueStatus($request)
    {
        $selected_product = $request->input('selected_product');
        $current_status = $request->input('current_status');
        foreach ($selected_product as $sp) {
            $timeNow = Carbon::now();
            $statusLogData = StatusProductLog::where('product_id', $sp)->first();
            $statusLogData->id;
            $statusLogData->product_id = $sp;

            $newStatus = $current_status + 1;
            if ($current_status == 19) {
                $statusLogData->status_id = 32;
                $getStatusName = StatusProduct::select('status')->where('id', $statusLogData->status_id)->first();
                $statusLogData->status_name = $getStatusName->status;
            } else if ($current_status == 32) {
                $statusLogData->status_id = 20;
                $getStatusName = StatusProduct::select('status')->where('id', $statusLogData->status_id)->first();
                $statusLogData->status_name = $getStatusName->status;
            } else {
                $statusLogData->status_id = $newStatus;
                $getStatusName = StatusProduct::select('status')->where('id', $statusLogData->status_id)->first();
                $statusLogData->status_name = $getStatusName->status;
            }

            $statusLogData->status_date =  $request->status_date;
            // Input data multiple image
            foreach ($request->file() as $key => $file) {
                if ($request->hasFile($key)) {
                    if ($request->file($key)->isValid()) {
                        $image_url = $request[$key];
                        if ($request[$key]) {
                            $image = Storage::putFile('product', $request[$key], 'public');
                            $image_url = Storage::url($image);
                            //hapus picture sebelumnya
                            if (isset($statusLogData->$key)) {
                                $old = parse_url($statusLogData->$key);
                                if (Storage::exists($old['path'])) {
                                    Storage::delete($old['path']);
                                }
                            }
                        }
                        if ($key == 'status_photo') {
                            $statusLogData->status_photo = $image_url;
                        } elseif ($key == 'status_photo2') {
                            $statusLogData->status_photo2 = $image_url ?? '';
                        } elseif ($key == 'status_photo3') {
                            $statusLogData->status_photo3 = $image_url ?? '';
                        } elseif ($key == 'status_photo4') {
                            $statusLogData->status_photo4 = $image_url ?? '';
                        } elseif ($key == 'status_photo5') {
                            $statusLogData->status_photo5 =  $image_url ?? '';
                        } elseif ($key == 'status_photo6') {
                            $statusLogData->status_photo6 = $image_url ?? '';
                        } elseif ($key == 'status_photo7') {
                            $statusLogData->status_photo7 = $image_url ?? '';
                        } elseif ($key == 'status_photo8') {
                            $statusLogData->status_photo8 = $image_url ?? '';
                        } elseif ($key == 'status_photo9') {
                            $statusLogData->status_photo9 = $image_url ?? '';
                        } elseif ($key == 'status_photo10') {
                            $statusLogData->status_photo10 = $image_url ?? '';
                        }
                    } else {
                        $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                        $statusLogData->$key = $key_id;
                    }
                }
            }
            $statusLogData->note = $request->note;
            $statusLogData->shipping_id = $request->shipping_id;
            $statusLogData->shipping_name = $request->shipping_name;
            $statusLogData->number_plate = $request->number_plate;
            $statusLogData->created_at = $timeNow;
            $statusLogData->updated_at = null;
            $statusLogData->created_by = auth()->user()->fullname;
            $statusLogData->updated_by = null;

            //save status logs
            $statusLogData->save();

            $productData = Product::find($sp);

            $productData->status_id = $statusLogData->status_id;
            $productData->status = $statusLogData->status_name;
            $productData->status_photo = $statusLogData->status_photo;
            $productData->note = $statusLogData->note;
            $productData->shipping_id = $statusLogData->shipping_id;
            $productData->shipping_name = $statusLogData->shipping_name;
            $productData->current_location = $request->current_location;
            $productData->save();

            $locationLog = LocationProductLog::where('product_id', $sp)->first();
            $locationLog->id;
            $locationLog->status_product_log_id = $statusLogData->id;
            $locationLog->product_id = $sp;
            $locationLog->current_location = $request->current_location;
            $locationLog->created_at = $timeNow;
            $locationLog->created_by = auth()->user()->fullname;
            $locationLog->updated_at = null;
            $locationLog->updated_by = null;
            $locationLog->save();
        }
        return $statusLogData;
    }

    public function rewindStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->executeRewindStatus($request);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dimundurkan statusnya.',
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

    protected function executeRewindStatus($request)
    {
        $selected_product = $request->input('selected_product');
        $current_status = $request->input('current_status');
        foreach ($selected_product as $sp) {
            $timeNow = Carbon::now();
            $statusLogData = StatusProductLog::where('product_id', $sp)->first();
            $statusLogData->id;
            $statusLogData->product_id = $sp;

            $newStatus = $current_status - 1;
            if ($current_status == 20) {
                $statusLogData->status_id = 32;
                $getStatusName = StatusProduct::select('status')->where('id', $statusLogData->status_id)->first();
                $statusLogData->status_name = $getStatusName->status;
            } else if ($current_status == 32) {
                $statusLogData->status_id = 19;
                $getStatusName = StatusProduct::select('status')->where('id', $statusLogData->status_id)->first();
                $statusLogData->status_name = $getStatusName->status;
            } else {
                $statusLogData->status_id = $newStatus;
                $getStatusName = StatusProduct::select('status')->where('id', $statusLogData->status_id)->first();
                $statusLogData->status_name = $getStatusName->status;
            }

            $statusLogData->status_date =  $request->status_date;
            // Input data multiple image
            foreach ($request->file() as $key => $file) {
                if ($request->hasFile($key)) {
                    if ($request->file($key)->isValid()) {
                        $image_url = $request[$key];
                        if ($request[$key]) {
                            $image = Storage::putFile('product', $request[$key], 'public');
                            $image_url = Storage::url($image);
                            //hapus picture sebelumnya
                            if (isset($statusLogData->$key)) {
                                $old = parse_url($statusLogData->$key);
                                if (Storage::exists($old['path'])) {
                                    Storage::delete($old['path']);
                                }
                            }
                        }
                        if ($key == 'status_photo') {
                            $statusLogData->status_photo = $image_url;
                        } elseif ($key == 'status_photo2') {
                            $statusLogData->status_photo2 = $image_url ?? '';
                        } elseif ($key == 'status_photo3') {
                            $statusLogData->status_photo3 = $image_url ?? '';
                        } elseif ($key == 'status_photo4') {
                            $statusLogData->status_photo4 = $image_url ?? '';
                        } elseif ($key == 'status_photo5') {
                            $statusLogData->status_photo5 =  $image_url ?? '';
                        } elseif ($key == 'status_photo6') {
                            $statusLogData->status_photo6 = $image_url ?? '';
                        } elseif ($key == 'status_photo7') {
                            $statusLogData->status_photo7 = $image_url ?? '';
                        } elseif ($key == 'status_photo8') {
                            $statusLogData->status_photo8 = $image_url ?? '';
                        } elseif ($key == 'status_photo9') {
                            $statusLogData->status_photo9 = $image_url ?? '';
                        } elseif ($key == 'status_photo10') {
                            $statusLogData->status_photo10 = $image_url ?? '';
                        }
                    } else {
                        $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                        $statusLogData->$key = $key_id;
                    }
                }
            }
            $statusLogData->note = $request->note;
            $statusLogData->shipping_id = $request->shipping_id;
            $statusLogData->shipping_name = $request->shipping_name;
            $statusLogData->number_plate = $request->number_plate;
            $statusLogData->created_at = $timeNow;
            $statusLogData->updated_at = null;
            $statusLogData->created_by = auth()->user()->fullname;
            $statusLogData->updated_by = null;

            //save status logs
            $statusLogData->save();

            $productData = Product::find($sp);

            $productData->status_id = $statusLogData->status_id;
            $productData->status = $statusLogData->status_name;
            $productData->status_photo = $statusLogData->status_photo;
            $productData->note = $statusLogData->note;
            $productData->shipping_id = $statusLogData->shipping_id;
            $productData->shipping_name = $statusLogData->shipping_name;
            $productData->current_location = $request->current_location;
            $productData->save();

            $locationLog = LocationProductLog::where('product_id', $sp)->first();
            $locationLog->id;
            $locationLog->status_product_log_id = $statusLogData->id;
            $locationLog->product_id = $sp;
            $locationLog->current_location = $request->current_location;
            $locationLog->created_at = $timeNow;
            $locationLog->created_by = auth()->user()->fullname;
            $locationLog->updated_at = null;
            $locationLog->updated_by = null;
            $locationLog->save();
        }
        return $statusLogData;
    }
}
