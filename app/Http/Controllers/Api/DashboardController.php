<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Models\ModuleCompleteness;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DashboardController extends Controller
{
    public function countStatus()
    {
        $selesaiProduksi = Product::where('status_id', 17)->count();
        $pengecekan = Product::where('status_id', 18)->count();
        $siapKirim = Product::where('status_id', 19)->count();
        $pengiriman = Product::where('status_id', 20)->count();
        $diterima = Product::where('status_id', 21)->count();
        $disimpan = Product::where('status_id', 22)->count();
        $perakitan = Product::where('status_id', 23)->count();
        $perbaikan = Product::where('status_id', 26)->count();
        $pemasangan = Product::where('status_id', 27)->count();
        $belumDiproduksi = Product::where('status_id', 25)->count();
        $materialOnSite = Product::where('status_id', 24)->count();
        $disetujuiPp = Product::where('status_id', 28)->count();
        $tidakDipakai = Product::where('status_id', 29)->count();
        $duplikat = Product::where('status_id', 30)->count();
        $revisi = Product::where('status_id', 31)->count();
        $persiapanSuratJalan = Product::where('status_id', 32)->count();
        $data = [
            (object)['title' => 'Belum Diproduksi', 'total' => $belumDiproduksi],
            (object)['title' => 'Selesai Produksi', 'total' => $selesaiProduksi],
            (object)['title' => 'Pengecekan', 'total' => $pengecekan],
            (object)['title' => 'Siap Kirim', 'total' => $siapKirim],
            (object)['title' => 'Pengiriman', 'total' => $pengiriman],
            (object)['title' => 'Diterima', 'total' => $diterima],
            (object)['title' => 'Disimpan', 'total' => $disimpan],
            (object)['title' => 'Perakitan', 'total' => $perakitan],
            (object)['title' => 'Perbaikan', 'total' => $perbaikan],
            (object)['title' => 'Pemasangan', 'total' => $pemasangan],
            (object)['title' => 'Material On Site', 'total' => $materialOnSite],
            (object)['title' => 'Disetujui PP', 'total' => $disetujuiPp],
            (object)['title' => 'Tidak Dipakai', 'total' => $tidakDipakai],
            (object)['title' => 'Duplikat', 'total' => $duplikat],
            (object)['title' => 'Revisi', 'total' => $revisi],
            (object)['title' => 'Persiapan Surat Jalan', 'total' => $persiapanSuratJalan],
        ];
        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah produk berdasarkan status:',
            'data' => $data,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function dashboardGaruda(Request $request)
    {
        try {
            $data = ModuleCompleteness::select('segment', 'module', 'completeness')->get()->map(function ($moduleCompleteness) {
                if ($moduleCompleteness->completeness == 1) {
                    $moduleCompleteness->completeness = true;
                }
                if ($moduleCompleteness->completeness == 0) {
                    $moduleCompleteness->completeness = false;
                }
                $changeSegment = 'S' . substr($moduleCompleteness->segment, 7);
                $moduleCompleteness->segment = $changeSegment;
                return $moduleCompleteness;
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
}
