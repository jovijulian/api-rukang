<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Product;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\StatusLogResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
            'status_photo' => ['required'],
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

        $productId = Uuid::uuid4()->toString();
        $productData->id = $productId;
        $productData->category_id = $data['category_id'];
        $productData->category = $data['category'];
        $productData->segment_id = $data['segment_id'];
        $productData->segment_name = $data['segment_name'];
        $productData->barcode = $data['barcode'];
        $productData->module_id = $data['module_id'];
        $productData->module_number = $data['module_number'];
        $productData->bilah_number = $data['bilah_number'];
        $productData->production_date = $data['production_date'];
        $productData->shelf_number = $data['shelf_number'];
        $productData->quantity = $data['quantity'];
        $productData->nut_bolt = $data['nut_bolt'];
        $productData->description_id = $data['description_id'];
        $productData->description = $data['description'];
        $productData->delivery_date = $data['delivery_date'];
        $productData->status_id = $data['status_id'];
        $productData->status = $data['status'];
        $productData->status_date = $timeNow;
        $productData->status_photo = $image_url;
        $productData->note = $data['note'];
        $productData->shipping_id = $data['shipping_id'];
        $productData->shipping_name = $data['shipping_name'];
        $productData->current_location = $data['current_location'];
        $productData->group_id = auth()->user()->group_id;
        $productData->group_name = auth()->user()->group_name;

        $productData->created_at = $timeNow;
        $productData->updated_at = $timeNow;
        $productData->created_by = auth()->user()->fullname;
        $productData->updated_by = null;

        // save status
        $productData->save();

        $statusLogData = new StatusLog();
        $statusLogId = Uuid::uuid4()->toString();
        $statusLogData->id = $statusLogId;
        $statusLogData->product_id = $productData->id;
        $statusLogData->status_id = $productData->status_id;
        $statusLogData->status_name = $productData->status;
        $statusLogData->status_date = $productData->status_date;
        $statusLogData->status_photo = $productData->status_photo;
        $statusLogData->note = $productData->note;
        $statusLogData->created_at = $timeNow;
        $statusLogData->updated_at = $timeNow;
        $statusLogData->created_by = auth()->user()->fullname;
        $statusLogData->updated_by = null;
        $statusLogData->save();


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
                throw new BadRequestHttpException("Produk tidak ada");
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
        $productData->barcode = $data['barcode'];
        $productData->module_id = $data['module_id'];
        $productData->module_number = $data['module_number'];
        $productData->bilah_number = $data['bilah_number'];
        $productData->production_date = $data['production_date'];
        $productData->shelf_number = $data['shelf_number'];
        $productData->quantity = $data['quantity'];
        $productData->nut_bolt = $data['nut_bolt'];
        $productData->description_id = $data['description_id'];
        $productData->description = $data['description'];
        $productData->delivery_date = $data['delivery_date'];
        $productData->status_id;
        $productData->status;
        $productData->status_date;
        $productData->status_photo;
        $productData->note;
        $productData->group_id = auth()->user()->group_id;
        $productData->group_name = auth()->user()->group_name;
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

        $statusLogs = StatusLog::where('product_id', $id)->get();

        foreach ($statusLogs as $statusLog) {
            if (isset($statusLog->status_photo)) {
                $old = parse_url($statusLog->status_photo);
                if (Storage::exists($old['path'])) {
                    Storage::delete($old['path']);
                }
            }
            $statusLog->deleted_by = auth()->user()->fullname;
            $statusLog->save();
            $statusLog->delete();
        }

        $product->deleted_by = auth()->user()->fullname;
        $product->deleted_flag = 1;
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
            'status_photo' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    public function setStatusLogProduct($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateStatusLogProduct($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->insertStatusLogProduct($id, $request->all(), $request);

            DB::commit();
            $single = new StatusLogResource($data);
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
        $statusLog = new StatusLog();

        if (empty($statusLog)) {
            throw new \Exception("Invalid status log id", 406);
        }

        $image_url = null;
        Storage::exists('product') or Storage::makeDirectory('product');
        if ($data['status_photo']) {
            $image = Storage::putFile('product', $data['status_photo'], 'public');
            $image_url = Storage::url($image);
        }

        $statusLog->id = Uuid::uuid4()->toString();
        $statusLog->product_id = $id;
        $statusLog->status_id = $data['status_id'];
        $statusLog->status_name = $data['status_name'];
        $statusLog->status_date = $timeNow;
        $statusLog->status_photo = $image_url;
        $statusLog->note = $data['note'];
        $statusLog->updated_at = $timeNow;
        $statusLog->updated_by = auth()->user()->fullname;

        //Save
        $statusLog->save();

        $productData = Product::find($id);
        $productData->status_id = $statusLog->status_id;
        $productData->status = $statusLog->status_name;
        $productData->status_date = $statusLog->status_date;
        $productData->status_photo = $statusLog->status_photo;
        $productData->note = $statusLog->note;
        $productData->save();

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
        $totalData = Product::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Product::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND barcode LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category LIKE '%" . trim($search) . "%'";
                $conditions .= " OR segment_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR module_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR bilah_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR production_date LIKE '%" . trim($search) . "%'";
                $conditions .= " OR shelf_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR description LIKE '%" . trim($search) . "%'";
                $conditions .= " OR delivery_date LIKE '%" . trim($search) . "%'";
                $conditions .= " OR status LIKE '%" . trim($search) . "%'";
                $conditions .= " OR shipping_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR group_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Product::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Product::whereRaw($conditions)->count();
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
