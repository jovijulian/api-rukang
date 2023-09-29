<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
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
        $selesaiProduksi = Product::where('status_id', 16)->count();
        $pengecekan = Product::where('status_id', 17)->count();
        $siapKirim = Product::where('status_id', 18)->count();
        $pengiriman = Product::where('status_id', 19)->count();
        $diterima = Product::where('status_id', 20)->count();
        $disimpan = Product::where('status_id', 21)->count();
        $perakitan = Product::where('status_id', 22)->count();
        $perbaikan = Product::where('status_id', 23)->count();
        $pemasangan = Product::where('status_id', 24)->count();
        $belumDiproduksi = Product::where('status_id', 25)->count();
        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah produk berdasarkan status:',
            'data' => [
                'belum_diproduksi' => $belumDiproduksi,
                'selesai_produksi' => $selesaiProduksi,
                'pengecekan' => $pengecekan,
                'siap_kirim' => $siapKirim,
                'pengiriman' => $pengiriman,
                'diterima' => $diterima,
                'disimpan' => $disimpan,
                'perakitan' => $perakitan,
                'perbaikan' => $perbaikan,
                'pemasangan' => $pemasangan,
            ],
        ], 200);
    }
}
