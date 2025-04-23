<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\stokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar stok',
            'list' => ['Home', 'stok']
        ];
        $page = (object) [
            'title' => 'Daftar stok yang terdaftar dalam sistem'
        ];
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();
        $activeMenu = 'stok'; // set menu yang sedang aktif
        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'barang' => $barang, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function create_ajax()
    {
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();
        return view('stok.create_ajax', ['supplier' => $supplier, 'barang' => $barang, 'user' => $user]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer|exists:m_user,user_id',
                'barang_id' => 'required|integer|exists:m_barang,barang_id',
                'supplier_id' => 'required|integer|exists:m_supplier,supplier_id',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer|min:1'
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
                // Cek apakah sudah ada stok dengan barang_id dan supplier_id yang sama
                $existingStok = stokModel::where('barang_id', $request->barang_id)
                    ->where('supplier_id', $request->supplier_id)
                    ->first();

                if ($existingStok) {
                    // Jika ada, update stok yang ada
                    $existingStok->update([
                        'user_id' => $request->user_id,
                        'stok_tanggal' => $request->stok_tanggal,
                        'stok_jumlah' => $existingStok->stok_jumlah + $request->stok_jumlah
                    ]);
                } else {
                    // Jika tidak ada, buat stok baru
                    stokModel::create([
                        'user_id' => $request->user_id,
                        'barang_id' => $request->barang_id,
                        'supplier_id' => $request->supplier_id,
                        'stok_tanggal' => $request->stok_tanggal,
                        'stok_jumlah' => $request->stok_jumlah
                    ]);
                }

                \DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil disimpan'
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
    // Menyimpan data stok baru

    public function list(Request $request)
    {
        $stoks = stokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')->with('supplier', 'barang', 'user')
            ->orderBy('stok_id', 'desc');

            if ($request->has('supplier_id') && $request->supplier_id != '') {
                $stoks->where('supplier_id', $request->supplier_id);
            }

        return DataTables::of($stoks)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {  // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true);
    }

    // Menampilkan detail stok
    public function show_ajax(string $id)
    {
        $stok = stokModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Detail stok',
            'list' => ['Home', 'stok', 'Detail']
        ];
        $page =
            (object) [
                'title' => 'Detail stok'
            ];
        $supplier = SupplierModel::all();
        $barang = BarangModel::all();
        $user = UserModel::all();
        $activeMenu = 'stok'; // set menu yang sedang aktif
        return view('stok.show_ajax', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'activeMenu' => $activeMenu, 'supplier' => $supplier, 'barang' => $barang, 'user' => $user]);
    }

    public function edit_ajax(string $id)
    {
        $stok = stokModel::find($id);
        $barang = BarangModel::all();
        $supplier = SupplierModel::all();
        $user = UserModel::all();
        return view('stok.edit_ajax', ['stok' => $stok, 'supplier' => $supplier, 'barang' => $barang, 'user' => $user]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer|exists:m_supplier,supplier_id',
                'barang_id' => 'required|integer|exists:m_barang,barang_id',
                'stok_jumlah' => 'required|integer|min:0'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $stok = stokModel::find($id);
            if ($stok) {
                // Update data
                $stok->update([
                    'supplier_id' => $request->supplier_id,
                    'barang_id' => $request->barang_id,
                    'stok_jumlah' => $request->stok_jumlah
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $stok = stokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = stokModel::find($id);
            if ($stok) {
                try {
                    stokModel::destroy($id);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        redirect('/');
    }
    public function import()
    {
        return view('stok.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_stok' => 'required|mimes:xls,xlsx|max:1024'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_stok'); // ambil file dari request
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
                            'user_id' => $value['A'], // ambil data dari kolom A
                            'barang_id' => $value['B'], // ambil data dari kolom B
                            'supplier_id' => $value['C'], // ambil data dari kolom C
                            'stok_tanggal' => $value['D'], // ambil data dari kolom D
                            'stok_jumlah' => $value['E'], // ambil data dari kolom E
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    stokModel::insertOrIgnore($insert);
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
        $stok = StokModel::select('user_id', 'barang_id', 'supplier_id', 'stok_tanggal', 'stok_jumlah')
            ->orderBy('user_id')
            ->orderBy('barang_id')
            ->with('user', 'barang', 'supplier')
            ->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID User');
        $sheet->setCellValue('C1', 'ID Barang');
        $sheet->setCellValue('D1', 'ID Supplier');
        $sheet->setCellValue('E1', 'Tanggal');
        $sheet->setCellValue('F1', 'Jumlah');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;
        foreach ($stok as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->user_id);
            $sheet->setCellValue('C' . $baris, $value->barang_id);
            $sheet->setCellValue('D' . $baris, $value->supplier_id);
            $sheet->setCellValue('E' . $baris, $value->stok_tanggal);
            $sheet->setCellValue('F' . $baris, $value->stok_jumlah);
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data stok');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data stok ' . date('Y-m-d H:i:s') . '.xlsx';

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
        $stok = StokModel::select('user_id', 'barang_id', 'supplier_id', 'stok_tanggal', 'stok_jumlah')
            ->orderBy('user_id')
            ->orderBy('barang_id')
            ->with('user', 'barang', 'supplier')
            ->get();

        // use Barryvdh\DomPDF \Facade\Pdf;
        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();
        return $pdf->stream('Data stok' . date('Y-m-d H:i:s') . '.pdf');
    }
}
