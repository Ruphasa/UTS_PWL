<?php

namespace App\Http\Controllers;

use App\Models\penjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
        $page = (object) [
            'title' => 'Daftar Penjualan yang terdaftar dalam sistem'
        ];
        $user = UserModel::all();
        $activeMenu = 'penjualan'; // set menu yang sedang aktif
        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function create_ajax()
    {
        $user = UserModel::all();
        $stok = StokModel::all();
        return view('penjualan.create_ajax', ['user' => $user, 'stok' => $stok]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer|exists:m_user,user_id',
                'penjualan_kode' => 'required|string|min:3|unique:t_penjualan,penjualan_kode',
                'penjualan_tanggal' => 'required|date',
                'stok_id' => 'required|array',
                'stok_id.*' => 'required|integer|exists:t_stok,stok_id',
                'jumlah' => 'required|array',
                'jumlah.*' => 'required|integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            \DB::beginTransaction();
            try {
                // Simpan data penjualan utama
                $penjualan = PenjualanModel::create([
                    'user_id' => $request->user_id,
                    'penjualan_kode' => $request->penjualan_kode,
                    'penjualan_tanggal' => $request->penjualan_tanggal,
                ]);

                $details = [];
                $stokIds = $request->stok_id;
                $jumlahs = $request->jumlah;

                for ($i = 0; $i < count($stokIds); $i++) {
                    // Ambil data stok
                    $stok = StokModel::find($stokIds[$i]);
                    if (!$stok || $stok->stok_jumlah < $jumlahs[$i]) {
                        throw new \Exception("Stok tidak mencukupi untuk stok_id: " . $stokIds[$i]);
                    }

                    // Ambil harga dari barang terkait
                    $barang = \App\Models\BarangModel::find($stok->barang_id);
                    if (!$barang) {
                        throw new \Exception("Barang tidak ditemukan untuk stok_id: " . $stokIds[$i]);
                    }

                    $details[] = [
                        'penjualan_id' => $penjualan->penjualan_id,
                        'barang_id' => $stok->barang_id, // Gunakan barang_id dari stok
                        'harga' => $barang->harga_jual*$jumlahs[$i], // Ambil harga_jual dari m_barang
                        'jumlah' => $jumlahs[$i],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Kurangi stok
                    $stok->stok_jumlah -= $jumlahs[$i];
                    $stok->save();
                }

                // Simpan semua detail penjualan
                penjualanDetailModel::insert($details);

                \DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data Penjualan dan Detail berhasil disimpan'
                ]);
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ]);
            }
        }
        return redirect('/');
    }

    public function list(Request $request)
    {
        $Penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'penjualan_kode', 'penjualan_tanggal')->with('user');

        if ($request->has('user_id') && $request->user_id != '') {
            $Penjualans->where('user_id', $request->user_id);
        }
        
        return DataTables::of($Penjualans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()
            ->addColumn('aksi', function ($Penjualans) {  // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $Penjualans->penjualan_id .
                    '/') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $Penjualans->penjualan_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true);
    }

    // Menampilkan detail Penjualan
    public function show_ajax(string $id)
    {
        $Penjualan = PenjualanModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Penjualan', 'Detail']
        ];
        $page =
            (object) [
                'title' => 'Detail Penjualan'
            ];
        $PenjualanDetail = penjualanDetailModel::where('penjualan_id', $id)
            ->with('penjualan')
            ->get();
        $activeMenu = 'penjualan'; // set menu yang sedang aktif
        return view('penjualan.show_ajax', ['breadcrumb' => $breadcrumb, 'page' => $page, 'Penjualan' => $Penjualan, 'PenjualanDetail' => $PenjualanDetail, 'activeMenu' => $activeMenu]);
    }

    public function confirm_ajax(string $id)
    {
        $Penjualan = PenjualanModel::find($id);
        $PenjualanDetail = penjualanDetailModel::where('penjualan_id', $id)
            ->with('penjualan')
            ->get();
        return view('penjualan.confirm_ajax', ['penjualan' => $Penjualan, 'PenjualanDetail' => $PenjualanDetail]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);

            if (!$penjualan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            \DB::beginTransaction();
            try {
                // Hapus semua detail penjualan terkait
                $details = penjualanDetailModel::where('penjualan_id', $id)->get();

                foreach ($details as $detail) {
                    // Kembalikan jumlah stok
                    $stok = StokModel::where('barang_id', $detail->barang_id)->first();
                    if ($stok) {
                        $stok->stok_jumlah += $detail->jumlah;
                        $stok->save();
                    }

                    // Hapus detail penjualan
                    $detail->delete();
                }

                // Hapus penjualan utama
                $penjualan->delete();

                \DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan dan detailnya berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ]);
            }
        }
        return redirect('/');
    }
    public function import()
    {
        return view('Penjualan.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_Penjualan' => 'required|mimes:xls,xlsx|max:1024'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_Penjualan'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'user_id' => $value['A'],
                            'Penjualan_kode' => $value['B'],
                            'punjualan_tanggal' => $value['C'],
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    PenjualanModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil di import'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang di import'
                ]);
            }
        }
        return redirect('/');
    }
    public function export_excel()
    {
        $Penjualan = PenjualanModel::select('user_id', 'Penjualan_kode', 'Penjualan_nama', 'harga_beli', 'harga_jual')
            ->orderBy('user_id')
            ->orderBy('Penjualan_kode')
            ->with('user')
            ->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Nama Pembeli');
        $sheet->setCellValue('D1', 'Tanggal Penjualan');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;
        foreach ($Penjualan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->Penjualan_kode);
            $sheet->setCellValue('C' . $baris, $value->user->username);
            $sheet->setCellValue('D' . $baris, $value->penjualan_tanggal);
            $baris++;
            $no++;
        }

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-offocedocumentsreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d MY H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'penjualan_kode', 'penjualan_tanggal')
            ->orderBy('user_id')
            ->orderBy('penjualan_kode')
            ->with('user')
            ->get();

        // use Barryvdh\DomPDF \Facade\Pdf;
        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();
        return $pdf->stream('Data Penjualan' . date('Y-m-d H:i:s') . '.pdf');
    }
}
