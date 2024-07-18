<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Karyawan;
use App\Models\Pengajuancuti;
use App\Models\Pengajuanizin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");

        // Fetch the office location configuration
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

        // Access the correct property 'lokasi_kantor'
        $lok = explode(',', $lok_kantor->lokasi_kantor);

        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];

        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }

        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if ($radius > $lok_kantor->radius) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda" . $radius . " meter dari kantor|radius";
        } else {
            if ($cek > 0) {
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi
                ];
                $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                if ($update) {
                    echo "success|Terima Kasih, Hati Hati Di Jalan|out";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal Absen Hubungi Tim IT|out";
                }
            } else {
                $data = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi
                ];
                $simpan = DB::table('presensi')->insert($data);
                if ($simpan) {
                    echo "success|Terima Kasih, Selamat Berkerja|in";
                    Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf Gagal Absen Hubungi Tim IT|in";
                }
            }
        }
    }


    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {

        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function getSisaCutiProfile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $cuti = DB::table('cuti')
            ->where('nik', $nik)
            ->where('status', 1)
            ->first();

        $periode = $cuti ? $cuti->tahun : '';
        $cutiGet = Cuti::where('nik', $nik)
            ->where('tahun', $periode)
            ->first();

        if ($cutiGet) {
            return response()->json(['sisa_cuti' => $cutiGet->sisa_cuti, 'cutiYear' => $periode]);
        } else {
            return response()->json(['sisa_cuti' => 0]);
        }
    }

    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }


    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    public function izin()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
        return view('izin.izin', compact('dataizin', 'namabulan'));
    }

    public function getizin(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $historiizin = DB::table('pengajuan_izin')
            ->whereRaw('MONTH(tgl_izin)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_izin)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_izin')
            ->get();

        return view('izin.getizin', compact('historiizin', 'tahun', 'bulan'));
    }
    public function getizincuti(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $historicuti = DB::table('pengajuan_cuti')
            ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti')
            ->whereRaw('MONTH(pengajuan_cuti.tgl_cuti) = ?', [$bulan])
            ->whereRaw('YEAR(pengajuan_cuti.tgl_cuti) = ?', [$tahun])
            ->where('pengajuan_cuti.nik', $nik)
            ->select('pengajuan_cuti.*', 'tipe_cuti.tipe_cuti')
            ->orderBy('pengajuan_cuti.tgl_cuti')
            ->get();

        return view('izin.getizincuti', compact('historicuti', 'tahun', 'bulan'));
    }


    public function buatizin()
    {
        return view('izin.buatizin');
    }

    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $tgl_izin_akhir = $request->tgl_izin_akhir;
        $jml_hari = $request->jml_hari;
        $status = $request->status;
        $keterangan = $request->keterangan;
        $pukul = $request->pukul;
        $currentDate = Carbon::now();

        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            $foto = "Surat_" . $nik . "_" . $currentDate->format('d_m_Y') . "." . $extension;
        } else {
            $foto = "No_Document";
        }

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'tgl_izin_akhir' => $tgl_izin_akhir,
            'jml_hari' => $jml_hari,
            'status' => $status,
            'pukul' => $pukul,
            'keterangan' => $keterangan,
            'tgl_create' => $currentDate,
            'foto' => $foto
        ];

        $simpan = Pengajuanizin::create($data);

        if ($simpan) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/pengajuan_izin/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Di Simpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Di Simpan']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $query = DB::table('presensi')
            ->selectRaw('DATE(tgl_presensi) as tanggal, presensi.nip, nama_lengkap, nama_dept, MIN(jam_in) as jam_masuk, MAX(jam_in) as jam_pulang')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->groupBy('tanggal', 'presensi.nip', 'nama_lengkap', 'nama_dept')
            ->where('tgl_presensi', $tanggal);

        $query->orderBy('jam_masuk', 'asc');
        $presensi = $query->get(); // Fetch all results without pagination

        return view("presensi.getpresensi", compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)->first();
        return view('presensi.showmap', compact('presensi'));
    }
}
