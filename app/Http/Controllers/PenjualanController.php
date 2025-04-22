<?php

namespace App\Http\Controllers;

use App\Models\penjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\KategoriModel;
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
        return view('penjualan.create_ajax', ['user' => $user]);
    }

    public function store_ajax(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'Penjualan_kode' => 'required|string|min:3|unique:m_Penjualan,Penjualan_kode',
            'Penjualan_tanggal' => 'required|date',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'Penjualan_kode' => 'required|string|min:3|unique:m_Penjualan,Penjualan_kode',
                'Penjualan_tanggal' => 'required|date',
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
                'user_id' => $request->user_id,
                'Penjualan_kode' => $request->Penjualan_kode,
                'Penjualan_tanggal' => $request->Penjualan_tanggal,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data Penjualan berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function list(Request $request)
    {
        $Penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'penjualan_kode', 'penjualan_tanggal')->with('user');

        return DataTables::of($Penjualans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()
            ->addColumn('aksi', function ($Penjualans) {  // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $Penjualans->penjualan_id .
                    '/') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $Penjualans->penjualan_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
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

    public function edit_ajax(string $id)
    {
        $Penjualan = PenjualanModel::find($id);
        $user = UserModel::all();
        return view('penjualan.edit_ajax', ['Penjualan' => $Penjualan, 'user' => $user]);
    }

    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'Penjualan_kode' => 'required|string|min:3|unique:m_Penjualan,Penjualan_kode,' . $id . ',Penjualan_id',
                'Penjualan_tanggal' => 'required|date',
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
        $Penjualan = PenjualanModel::select('user_id', 'Penjualan_kode', 'Penjualan_nama', 'harga_beli', 'harga_jual')
            ->orderBy('user_id')
            ->orderBy('Penjualan_kode')
            ->with('user')
            ->get();

        // use Barryvdh\DomPDF \Facade\Pdf;
        $pdf = Pdf::loadView('Penjualan.export_pdf', ['Penjualan' => $Penjualan]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();
        return $pdf->stream('Data Penjualan' . date('Y-m-d H:i:s') . '.pdf');
    }
}
