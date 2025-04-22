<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\penjualanModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
        $page = (object) [
            'title' => 'Dashboard'
        ];
        $activeMenu = 'Dashboard'; 
        $userCount = UserModel::count();
        $productCount = BarangModel::count();
        $saleCount = PenjualanModel::count();
        $supplierCount = SupplierModel::count();
        $categoryCount = KategoriModel::count();

        // Get recent sales
        $recentSales = PenjualanModel::orderBy('penjualan_tanggal', 'desc')
            ->with('user')
            ->take(5)
            ->get();

        // Calculate low stock products (stock < 10)
        $lowStockCount = StokModel::where('stok_jumlah', '<', 10)
            ->count();

        // Get active suppliers (those who supplied in the last month)
        $activeSupplierCount = StokModel::where('stok_tanggal', '>=', Carbon::now()->subMonth())
            ->distinct()
            ->count('supplier_id');

        // Get top products by sales quantity
        $topProducts = PenjualanModel::join('t_penjualan_detail', 't_penjualan_detail.penjualan_id', '=', 't_penjualan_detail.penjualan_id')
            ->join('m_barang', 't_penjualan_detail.barang_id', '=', 'm_barang.barang_id')
            ->join('m_kategori', 'm_barang.kategori_id', '=', 'm_kategori.kategori_id')
            ->select(
                'm_barang.barang_id',
                'm_barang.barang_nama',
                'm_kategori.kategori_nama',
                DB::raw('SUM(t_penjualan_detail.jumlah) as total_sold')
            )
            ->groupBy('m_barang.barang_id', 'm_barang.barang_nama', 'm_kategori.kategori_nama')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Chart data
        // Chart data using details to calculate total
        $salesByMonth = PenjualanModel::join('t_penjualan_detail', 't_penjualan_detail.penjualan_id', '=', 't_penjualan_detail.penjualan_id')
            ->select(
                DB::raw("DATE_FORMAT(penjualan_tanggal, '%M') as month"),
                DB::raw('SUM(t_penjualan_detail.jumlah * t_penjualan_detail.harga) as total')
            )
            ->groupBy(DB::raw("DATE_FORMAT(penjualan_tanggal, '%M')"))
            ->orderBy('penjualan_tanggal')
            ->get();

        $chartLabels = $salesByMonth->pluck('month');
        $chartData = $salesByMonth->pluck('total');

        return view('welcome', [
            'userCount'=> $userCount,
            'productCount'=> $productCount,
            'categoryCount'=> $categoryCount,
            'saleCount' => $saleCount,
            'supplierCount' => $supplierCount,
            'recentSales' => $recentSales,
            'lowStockCount' => $lowStockCount,
            'activeSupplierCount' => $activeSupplierCount,
            'topProducts' => $topProducts,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'breadcrumb'=> $breadcrumb,
            'page'=> $page,
            'activeMenu'=> $activeMenu,
            ]);
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'profileImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $username = $user->username;

        // Define the storage path
        $imagePath = public_path('Adminlte/dist/img/');
        $imageName = $username . '.png';

        // Check if the file already exists and delete it
        if (file_exists($imagePath . $imageName)) {
            unlink($imagePath . $imageName);
        }

        // Move the uploaded file to the target directory
        $request->profileImage->move($imagePath, $imageName);

        return response()->json([
            'status' => true,
            'message' => 'Profile image updated successfully',
        ]);
    }
}
