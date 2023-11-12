<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Models\StatusProductLog;
use App\Models\ModuleCompleteness;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Resources\CategoryResource;
use App\Models\StatusProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DashboardController extends Controller
{
    public function countStatus()
    {
        $getAllProduct = Product::count();
        $getAllProductKulit = Product::where('category_id', 1)->count();
        $getAllProductRangka = Product::where('category_id', 2)->count();
        $dataProduct = [
            (object)[
                'title' => 'Total Bilah',
                'total' => $getAllProduct,
                'kategori_kulit' => $getAllProductKulit,
                'kategori_rangka' => $getAllProductRangka
            ],
        ];

        $statusIds = [17, 20, 21, 26, 27];
        $data = [];

        foreach ($statusIds as $statusId) {
            $status_name = Product::select('status')->where('status_id', $statusId)->first();
            $total_product = Product::where('status_id', $statusId)->count();
            $kategoriKulit = Product::where('status_id', $statusId)->where('category_id', 1)->count();
            $kategoriRangka = Product::where('status_id', $statusId)->where('category_id', 2)->count();
            if ($status_name == null) {
                $getStatusName = StatusProduct::select('status')->where('id', $statusId)->first();
                $statusNameDefault = $getStatusName->status;
            }
            $data[] = (object)[
                'title' => $status_name->status ?? $statusNameDefault,
                'total' => $total_product,
                'kategori_kulit' => $kategoriKulit,
                'kategori_rangka' => $kategoriRangka,
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah Produk:',
            'data_product' => $dataProduct,
            'data_product_per_status' => $data,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */

    public function dashboardGaruda(Request $request)
    {
        try {
            $data = ModuleCompleteness::select('segment_id', 'segment', 'module', 'completeness')->get()->map(function ($moduleCompleteness) {
                if ($moduleCompleteness->completeness == 1) {
                    $moduleCompleteness->completeness = true;
                }
                if ($moduleCompleteness->completeness == 0) {
                    $moduleCompleteness->completeness = false;
                }
                $changeSegment = 'S' . substr($moduleCompleteness->segment, 7);
                $moduleCompleteness->segment = $changeSegment;
                $moduleCompleteness->barcode_color = $moduleCompleteness->segments->barcode_color ?? null;
                return $moduleCompleteness->only(['segment', 'module', 'completeness', 'barcode_color']);;
            });
            return response()->json([
                'status' => 'success',
                'message' => 'List Segmen dan Modul untuk garuda:',
                'data' => $data,
            ], 200);
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

    public function dashboardGrafikStatus(Request $request)
    {
        try {
            $bulan = $request->input('bulan', date("m"));
            $statusId = $request->input('status_id', null);
            $getStatusName = StatusProduct::select('status')->where('id', $statusId)->first();

            $query = StatusProductLog::select('status_date as daily');

            if (!is_null($statusId)) {
                $query->where('status_id', $statusId);
            }
            $dataHarian = $query
                ->groupBy('status_name', 'status_date')
                ->selectRaw('status_date as daily, COUNT(*) as total_data')
                ->orderBy('status_date', 'ASC')
                ->where('status_id', $statusId)
                ->whereMonth('status_date', $bulan)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'List Total Data:',
                'status_name' => $getStatusName->status,
                'data' =>  $dataHarian,
            ], 200);
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

    public function countAgregatStatus()
    {

        $statusIds = [16, 17, 18, 19, 32, 20, 21, 22, 23, 25, 24, 26, 27, 28, 29];
        $data = [];

        foreach ($statusIds as $statusId) {
            $status_name = Product::select('status')->where('status_id', $statusId)->first();
            $total_product = Product::where('status_id', $statusId)->count();
            $kategoriKulit = Product::where('status_id', $statusId)->where('category_id', 1)->count();
            $kategoriRangka = Product::where('status_id', $statusId)->where('category_id', 2)->count();
            if ($status_name == null) {
                $getStatusName = StatusProduct::select('status')->where('id', $statusId)->first();
                $statusNameDefault = $getStatusName->status;
            }
            $data[] = (object)[
                'nama_status' => $status_name->status ?? $statusNameDefault,
                'total' => $total_product,
                'kategori_kulit' => $kategoriKulit,
                'kategori_rangka' => $kategoriRangka,
            ];
        }
        $totalProducts = array_reduce($data, function ($carry, $item) {
            return $carry + $item->total;
        }, 0);
        $totalKategoriKulit = array_reduce($data, function ($carry, $item) {
            return $carry + $item->kategori_kulit;
        }, 0);
        $totalKategoriRangka = array_reduce($data, function ($carry, $item) {
            return $carry + $item->kategori_rangka;
        }, 0);

        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah Produk:',
            'data_product_per_status' => $data,
            'total_product' => $totalProducts,
            'total_kategori_kulit' => $totalKategoriKulit,
            'total_kategori_rangka' => $totalKategoriRangka,
        ], 200);
    }

    public function countAgregatSegment()
    {

        $segmentIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17];
        $statusIds = [17, 20, 21, 26, 27];
        $data = [];

        foreach ($segmentIds as $segmentId) {
            $segmentName = Product::select('segment_name')->where('segment_id', $segmentId)->first();
            $dataPerStatus = [];
            $totalKategoriKulit1 = Product::where('segment_id', $segmentId)
                ->where('category_id', 1)
                ->whereIn('status_id', $statusIds)
                ->count();

            $totalKategoriRangka1 = Product::where('segment_id', $segmentId)
                ->where('category_id', 2)
                ->whereIn('status_id', $statusIds)
                ->count();
            foreach ($statusIds as $statusId) {
                $kategoriKulitPerStatus = Product::where('segment_id', $segmentId)->where('status_id', $statusId)->where('category_id', 1)->count();
                $kategoriRangkaPerStatus = Product::where('segment_id', $segmentId)->where('status_id', $statusId)->where('category_id', 2)->count();
                $status_name = Product::select('status')->where('status_id', $statusId)->first();
                if ($status_name == null) {
                    $getStatusName = StatusProduct::select('status')->where('id', $statusId)->first();
                    $statusNameDefault = $getStatusName->status;
                }
                $dataPerStatus[] = (object)[
                    'status_name' => $status_name->status ?? $statusNameDefault,
                    'kategori_kulit_per_status' => $kategoriKulitPerStatus,
                    'kategori_rangka_per_status' => $kategoriRangkaPerStatus,
                ];
            }

            if ($segmentName == null) {
                $getSegmentName = Segment::select('segment_name')->where('id', $segmentId)->first();
                $segmentNameDefault = $getSegmentName->segment_name;
            }

            $data[] = (object)[
                'nama_segment' => $segmentName->segment_name ?? $segmentNameDefault,
                'data_per_status' => $dataPerStatus,
                'total_kategori_kulit' => $totalKategoriKulit1,
                'total_kategori_rangka' => $totalKategoriRangka1
            ];
        }

        $totalKategoriKulit = Product::where('category_id', 1)
            ->whereIn('status_id', $statusIds)
            ->count();

        $totalKategoriRangka = Product::where('category_id', 2)
            ->whereIn('status_id', $statusIds)
            ->count();

        $totalKategoriKulitPerStatus1 = 0;
        $totalKategoriKulitPerStatus2 = 0;
        $totalKategoriKulitPerStatus3 = 0;
        $totalKategoriKulitPerStatus4 = 0;
        $totalKategoriKulitPerStatus5 = 0;

        foreach ($data as $item) {
            $totalKategoriKulitPerStatus1 += $item->data_per_status[0]->kategori_kulit_per_status;
            $totalKategoriKulitPerStatus2 += $item->data_per_status[1]->kategori_kulit_per_status;
            $totalKategoriKulitPerStatus3 += $item->data_per_status[2]->kategori_kulit_per_status;
            $totalKategoriKulitPerStatus4 += $item->data_per_status[3]->kategori_kulit_per_status;
            $totalKategoriKulitPerStatus5 += $item->data_per_status[4]->kategori_kulit_per_status;
        }

        $totalKategoriRangkaPerStatus1 = 0;
        $totalKategoriRangkaPerStatus2 = 0;
        $totalKategoriRangkaPerStatus3 = 0;
        $totalKategoriRangkaPerStatus4 = 0;
        $totalKategoriRangkaPerStatus5 = 0;

        foreach ($data as $item) {
            $totalKategoriRangkaPerStatus1 += $item->data_per_status[0]->kategori_rangka_per_status;
            $totalKategoriRangkaPerStatus2 += $item->data_per_status[1]->kategori_rangka_per_status;
            $totalKategoriRangkaPerStatus3 += $item->data_per_status[2]->kategori_rangka_per_status;
            $totalKategoriRangkaPerStatus4 += $item->data_per_status[3]->kategori_rangka_per_status;
            $totalKategoriRangkaPerStatus5 += $item->data_per_status[4]->kategori_rangka_per_status;
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah Produk:',
            'data_product_per_segment' => $data,
            'total_kategori_kulit_per_status1' => $totalKategoriKulitPerStatus1,
            'total_kategori_kulit_per_status2' => $totalKategoriKulitPerStatus2,
            'total_kategori_kulit_per_status3' => $totalKategoriKulitPerStatus3,
            'total_kategori_kulit_per_status4' => $totalKategoriKulitPerStatus4,
            'total_kategori_kulit_per_status5' => $totalKategoriKulitPerStatus5,
            'total_kategori_rangka_per_status1' => $totalKategoriRangkaPerStatus1,
            'total_kategori_rangka_per_status2' => $totalKategoriRangkaPerStatus2,
            'total_kategori_rangka_per_status3' => $totalKategoriRangkaPerStatus3,
            'total_kategori_rangka_per_status4' => $totalKategoriRangkaPerStatus4,
            'total_kategori_rangka_per_status5' => $totalKategoriRangkaPerStatus5,
            'total_kategori_kulit' => $totalKategoriKulit,
            'total_kategori_rangka' => $totalKategoriRangka,
        ], 200);
    }
}
