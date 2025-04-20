<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\KategoriModel;
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
        $kategori = KategoriModel::all();
        $activeMenu = 'Penjualan'; // set menu yang sedang aktif
        return view('Penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Penjualan',
            'list' => [
                'Home',
                'Penjualan',
                'Tambah'
            ]
        ];
        $page = (object) [
            'title' => 'Tambah Penjualan baru'
        ];
        $kategori = KategoriModel::all();
        $activeMenu = 'Penjualan'; // set menu yang sedang aktif
        return view('Penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function create_ajax()
    {
        $kategori = KategoriModel::all();
        return view('Penjualan.create_ajax', ['kategori' => $kategori]);
    }

    public function store_ajax(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|integer',
            'Penjualan_kode' => 'required|string|min:3|unique:m_Penjualan,Penjualan_kode',
            'Penjualan_nama' => 'required|string|min:3|unique:m_Penjualan,Penjualan_nama',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer'
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer',
                'Penjualan_kode' => 'required|string|min:3|unique:m_Penjualan,Penjualan_kode',
                'Penjualan_nama' => 'required|string|min:3|unique:m_Penjualan,Penjualan_nama',
                'harga_beli' => 'required|integer',
                'harga_jual' => 'required|integer'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            PenjualanModel::create([
                'kategori_id' => $request->kategori_id,
                'Penjualan_kode' => $request->Penjualan_kode,
                'Penjualan_nama' => $request->Penjualan_nama,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data Penjualan berhasil disimpan'
            ]);
        }
        redirect('/');
    }
    // Menyimpan data Penjualan baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|integer',
            'Penjualan_kode' => 'required|string|min:3|unique:m_Penjualan,Penjualan_kode',
            'Penjualan_nama' => 'required|string|min:3|unique:m_Penjualan,Penjualan_nama',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer'
        ]);
        PenjualanModel::create([
            'kategori_id' => $request->kategori_id,
            'Penjualan_kode' => $request->Penjualan_kode,
            'Penjualan_nama' => $request->Penjualan_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);
        return redirect('/Penjualan')->with('success', 'Data Penjualan berhasil disimpan');
    }

    public function list(Request $request)
    {
        $Penjualans = PenjualanModel::select('Penjualan_id', 'kategori_id', 'Penjualan_nama', 'Penjualan_kode', 'harga_beli', 'harga_jual')->with('kategori');

        return DataTables::of($Penjualans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()
            ->addColumn('aksi', function ($Penjualan) {  // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/Penjualan/' . $Penjualan->Penjualan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/Penjualan/' . $Penjualan->Penjualan_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/Penjualan/' . $Penjualan->Penjualan_id .
                    '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true);
    }

    // Menampilkan detail Penjualan
    public function show(string $id)
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
        $activeMenu = 'Penjualan'; // set menu yang sedang aktif
        return view('Penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'Penjualan' => $Penjualan, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit Penjualan
    public function edit(string $id)
    {
        $Penjualan = PenjualanModel::find($id);
        $breadcrumb = (object) [
            'title' => 'Edit Penjualan',
            'list' => ['Home', 'Penjualan', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit Penjualan'
        ];
        $kategori = KategoriModel::all();
        $activeMenu = 'Penjualan'; // set menu yang sedang aktif
        // Menyimpan perubahan data Penjualan
        return view('Penjualan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'Penjualan' => $Penjualan, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_id' => 'required|integer',
            'Penjualan_kode' => 'required|string|min:3|unique:m_Penjualan,Penjualan_kode,' . $id . ',Penjualan_id',
            'Penjualan_nama' => 'required|string|max:100',
            'harga_jual' => 'required|integer',
            'harga_beli' => 'required|integer'
        ]);

        PenjualanModel::find($id)->update([
            // level_id harus diisi dan berupa angka
            'kategori_id' => $request->kategori_id,
            'Penjualan_kode' => $request->Penjualan_kode,
            'Penjualan_nama' => $request->Penjualan_nama,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli
        ]);
        return redirect('/Penjualan')->with('success', 'Data Penjualan berhasil diubah');
    }

    public function edit_ajax(string $id)
    {
        $Penjualan = PenjualanModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('Penjualan.edit_ajax', ['Penjualan' => $Penjualan, 'kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer',
                'Penjualan_kode' => 'required|string|min:3|unique:m_Penjualan,Penjualan_kode,' . $id . ',Penjualan_id',
                'Penjualan_nama' => 'required|string|max:100',
                'harga_jual' => 'required|integer',
                'harga_beli' => 'required|integer'
            ];

            // use Illuminate\Support\Facades\Validator; 
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,    // respon json, true: berhasil, false: gagal 
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]);
            }
            $check = PenjualanModel::find($id);
            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request 
                    $request->request->remove('password');
                }
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        redirect('/');
    }

    // Menghapus data Penjualan
    public function destroy(string $id)
    {
        $check = PenjualanModel::find($id);
        if (!$check) {
            // untuk mengecek apakah data Penjualan dengan id yang dimaksud ada atau tidak
            return redirect('/Penjualan')->with('error', 'Data Penjualan tidak ditemukan ');
        }
        try {
            PenjualanModel::destroy($id);
            // Hapus data level
            return redirect('/Penjualan')->with('success', 'Data Penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/Penjualan')->with('error', 'Data Penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function confirm_ajax(string $id)
    {
        $Penjualan = PenjualanModel::find($id);
        return view('Penjualan.confirm_ajax', ['Penjualan' => $Penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $Penjualan = PenjualanModel::find($id);
            if ($Penjualan) {
                try {
                    PenjualanModel::destroy($id);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data Penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
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
                            'kategori_id' => $value['A'],
                            'Penjualan_kode' => $value['B'],
                            'Penjualan_nama' => $value['C'],
                            'harga_beli' => $value['D'],
                            'harga_jual' => $value['E'],
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
        $Penjualan = PenjualanModel::select('kategori_id', 'Penjualan_kode', 'Penjualan_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Nama Penjualan');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;
        foreach ($Penjualan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->Penjualan_kode);
            $sheet->setCellValue('C' . $baris, $value->Penjualan_nama);
            $sheet->setCellValue('D' . $baris, $value->harga_beli);
            $sheet->setCellValue('E' . $baris, $value->harga_jual);
            $sheet->setCellValue('F' . $baris, $value->kategori->kategori_nama);
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
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
        $Penjualan = PenjualanModel::select('kategori_id', 'Penjualan_kode', 'Penjualan_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->orderBy('Penjualan_kode')
            ->with('kategori')
            ->get();

        // use Barryvdh\DomPDF \Facade\Pdf;
        $pdf = Pdf::loadView('Penjualan.export_pdf', ['Penjualan' => $Penjualan]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();
        return $pdf->stream('Data Penjualan' . date('Y-m-d H:i:s') . '.pdf');
    }
}
