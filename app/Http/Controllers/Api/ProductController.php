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
                Rule::unique('products')->where(function ($query) {
                    $query->whereNotNull('deleted_at');
                }),
            ],
            // '"1/0"' => ['required', 'min:1', 'max:100'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $productData = new Product();
        // $imagePath = $request->file('image')->store('images', 'digitalocean');
        $productId = Uuid::uuid4()->toString();
        $productData->id = $productId;
        $productData->segment_id = $data['segment_id'];
        $productData->segment_name = $data['segment_name'];
        $productData->barcode = $data['barcode'];
        $productData->module_number = $data['module_number'];
        $productData->bilah_number = $data['bilah_number'];
        $productData->production_date = $data['production_date'];
        $productData->shelf_number = $data['shelf_number'];
        $productData->{'"1/0"'} = $data['1/0'];
        $productData->nut_bolt = $data['nut_bolt'];
        $productData->description_id = $data['description_id'];
        $productData->description = $data['description'];
        $productData->delivery_date = $data['delivery_date'];
        $productData->image = $data['image'];
        $productData->category_id = $data['category_id'];
        $productData->category = $data['category'];
        $productData->created_at = $timeNow;
        $productData->updated_at = $timeNow;
        $productData->created_by = auth()->user()->fullname;
        $productData->updated_by = null;

        // save category
        $productData->save();

        $statusLogData = new StatusLog();
        $statusLogId = Uuid::uuid4()->toString();
        $statusLogData->id = $statusLogId;
        $statusLogData->product_id = $productData->id;;
        $statusLogData->process_id = 1;
        $statusLogData->process_name = 'Selesai Produksi';
        $statusLogData->status_date = $timeNow;
        $statusLogData->process_attachment = null;
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
                    return ResponseStd::fail($e->getMessage());
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
                    return ResponseStd::fail($e->getMessage());
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
            'barcode' => ['required', 'min:1', 'max:100'],
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
        $productData->id = $id;
        $productData->segment_id = $data['segment_id'];
        $productData->segment_name = $data['segment_name'];
        $productData->barcode = $data['barcode'];
        $productData->module_number = $data['module_number'];
        $productData->bilah_number = $data['bilah_number'];
        $productData->production_date = $data['production_date'];
        $productData->shelf_number = $data['shelf_number'];
        $productData->{'"1/0"'} = $data['1/0'];
        $productData->nut_bolt = $data['nut_bolt'];
        $productData->description_id = $data['description_id'];
        $productData->description = $data['description'];
        $productData->delivery_date = $data['delivery_date'];
        $productData->image = $data['image'];
        $productData->category_id = $data['category_id'];
        $productData->category = $data['category'];
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
                    return ResponseStd::fail($e->getMessage());
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

        $statusLog = StatusLog::where('product_id', $id)->first();
        $statusLog->deleted_by = auth()->user()->fullname;
        $statusLog->save();
        $statusLog->delete();

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
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }

    public function setStatusProduct($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->updateStatusProduct($id, $request->all(), $request);
            if (empty($data)) {
                throw new BadRequestHttpException("Product Not Found.");
            }
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
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }

    protected function updateStatusProduct($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();
        $statusLog = StatusLog::find($id);

        if (empty($statusLog)) {
            throw new \Exception("Invalid status log id", 406);
        }
        $statusLog->id = $id;
        $statusLog->product_id = $data['product_id'];
        $statusLog->process_id = $data['process_id'];
        $statusLog->process_name = $data['process_name'];
        $statusLog->status_date = $timeNow;
        $statusLog->process_attachment = $data['process_attachment'];
        $statusLog->updated_at = $timeNow;
        $statusLog->updated_by = auth()->user()->fullname;

        //Save
        $statusLog->save();

        return $statusLog;
    }
}
