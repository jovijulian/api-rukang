<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Module;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Models\StatusProductLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SegmentResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SeederProductController extends Controller
{
    protected function createModule()
    {
        $timeNow = Carbon::now();
        for ($i = 1; $i <= 45; $i++) {
            $moduleData = new Module();

            $module_code = 'M' . str_pad($i, 2, '0', STR_PAD_LEFT);

            $moduleData->module_number = 'M' . $i;
            $moduleData->module_code = $module_code;
            $moduleData->created_at = $timeNow;
            $moduleData->created_by = 'Admin';
            $moduleData->updated_at = null;
            $moduleData->updated_by = null;
            // Simpan module
            $moduleData->save();
        }

        return $moduleData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeModule()
    {
        DB::beginTransaction();
        try {

            $model = $this->createModule();
            DB::commit();

            // return
            $single = new ModuleResource($model);
            return ResponseStd::okArray($single);
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

    protected function createSegment()
    {
        $timeNow = Carbon::now();
        for ($i = 1; $i <= 17; $i++) {
            $segment = new Segment();

            $segment_code = 'S' . str_pad($i, 2, '0', STR_PAD_LEFT);

            $segment->segment_name = 'Segmen ' . $i;
            $segment->barcode_color = null;
            $segment->segment_code = $segment_code;
            $segment->created_at = $timeNow;
            $segment->updated_at = null;
            $segment->created_by = 'Admin';
            $segment->updated_by = null;
            // Simpan segment
            $segment->save();
        }

        return $segment;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSegment()
    {
        DB::beginTransaction();
        try {

            $model = $this->createSegment();
            DB::commit();

            // return
            $single = new SegmentResource($model);
            return ResponseStd::okArray($single);
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

    protected function createProduct($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();
        $moduleData = Module::select('id', 'module_number', 'module_code')->get();
        $categoryData = Category::select('id', 'category')
            ->where('category', 'LIKE', '%Kulit%')
            ->orWhere('category', 'LIKE', '%Rangka%')
            ->get();
        $bilahData = ["B01", "B02", "B03", "B04", "B05", "B06", "B07", "B08", "B09", "B10", "B11", "B12", "B13", "B14", "B15"];
        $segmentCode = str_pad($id, 2, '0', STR_PAD_LEFT);
        $timeNow = now();
        $productData = [];

        foreach ($moduleData as $md) {
            foreach ($bilahData as $bd) {
                foreach ($categoryData as $cd) {
                    $categoryCode = substr($cd->category, 0, 1);
                    $productId = Uuid::uuid4()->toString();

                    $productData[] = [
                        'id' => $productId,
                        'category_id' => $cd->id,
                        'category' => $cd->category,
                        'segment_id' => $id,
                        'segment_name' => 'Segmen ' . $id,
                        'segment_place' => null,
                        'barcode' => 'S' . $segmentCode . $md->module_code . $bd . $categoryCode,
                        'module_id' => $md->id,
                        'module_number' => $md->module_number,
                        'bilah_number' => $bd,
                        'production_date' => null,
                        'shelf_id' => null,
                        'shelf_name' => null,
                        'description' => null,
                        'delivery_date' => null,
                        'status_id' => 25,
                        'status' => '00. Belum Diproduksi',
                        'status_photo' => null,
                        'note' => null,
                        'shipping_id' => null,
                        'shipping_name' => null,
                        'current_location' => null,
                        'group_id' => 1,
                        'group_name' => 'Kelompok 1',
                        'created_at' => $timeNow,
                        'updated_at' => null,
                        'created_by' => 'Admin',
                        'updated_by' => null,
                    ];
                }
            }
        }

        // Mass insert data produk
        Product::insert($productData);

        // Buat status log untuk setiap produk
        $statusLogData = [];
        foreach ($productData as $product) {
            $statusLogId = Uuid::uuid4()->toString();
            $statusLogData[] = [
                'id' => $statusLogId,
                'product_id' => $product['id'],
                'status_id' => $product['status_id'],
                'status_name' => $product['status'],
                'status_photo' => $product['status_photo'],
                'note' => $product['note'],
                'shipping_id' => $product['shipping_id'],
                'shipping_name' => $product['shipping_name'],
                'number_plate' => null,
                'created_at' => $timeNow,
                'updated_at' => null,
                'created_by' => 'Admin',
                'updated_by' => null,
            ];
        }

        // Mass insert data status log
        StatusProductLog::insert($statusLogData);

        return $productData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeProduct($id, Request $request)
    {
        DB::beginTransaction();
        try {

            $model = $this->createProduct($id, $request->all(), $request);
            DB::commit();

            // return
            $single = new ProductResource($model);
            return response()->json([
                'status' => 'success', // Ganti ini dengan status yang sesuai
                'message' => 'Data berhasil disimpan.', // Ganti ini dengan pesan yang sesuai
            ], 200);
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
}
