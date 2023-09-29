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

        ];
        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah produk berdasarkan status:',
            'data' => $data,
        ], 200);
    }
}
