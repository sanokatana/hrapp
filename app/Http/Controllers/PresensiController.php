<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
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
        $hariini = date("Y-m-d");
        $bulanini = $request->bulan;
        $tahunini = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        // Join karyawan and jabatan tables to get nama_jabatan
        $namaUser = DB::table('karyawan')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->where('karyawan.nik', $nik)
            ->select('karyawan.*', 'jabatan.nama_jabatan')
            ->first();

        // Split nama_lengkap into first and last names
        $nameParts = explode(' ', $namaUser->nama_lengkap);
        $firstName = $nameParts[0];
        $lastName = end($nameParts);
        $namaUser->first_name = $firstName;
        $namaUser->last_name = $lastName;

        $presensihariini = DB::table('presensi')->where('nik', $nik)
            ->where('tgl_presensi', $hariini)
            ->first();

        // Fetch approved izin data for the current month
        $izin = DB::table('pengajuan_izin')
            ->select('nik', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
            ->get();

        $historibulanini = DB::table(DB::raw("(SELECT
    DATE(tgl_presensi) as tanggal,
    MIN(jam_in) as jam_masuk,
    MAX(jam_in) as jam_pulang,
    nik
    FROM presensi
    WHERE nik = ?
        AND MONTH(tgl_presensi) = ?
        AND YEAR(tgl_presensi) = ?
    GROUP BY DATE(tgl_presensi), nik) as sub"))
            ->leftJoin('presensi as p', function ($join) {
                $join->on('sub.tanggal', '=', DB::raw('DATE(p.tgl_presensi)'))
                    ->on('sub.nik', '=', 'p.nik')
                    ->whereRaw('p.jam_in = sub.jam_masuk OR p.jam_in = sub.jam_pulang');
            })
            ->select('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang', DB::raw('MAX(p.foto_in) as foto_in'), DB::raw('MAX(p.foto_out) as foto_out'))
            ->groupBy('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang')
            ->orderBy('sub.tanggal', 'asc')
            ->setBindings([$nik, $bulanini, $tahunini])
            ->get();

        // Process the presensi data to adjust for izin
        $processedHistoribulanini = $historibulanini->map(function ($item) use ($izin, $nik) {
            $date = Carbon::parse($item->tanggal);
            $isIzin = $this->checkIzin($izin, $nik, $date);

            $morning_start = strtotime('06:00:00');
            $afternoon_start = strtotime('13:00:00');
            $jam_masuk_time = strtotime($item->jam_masuk);
            $jam_pulang_time = strtotime($item->jam_pulang);

            if ($jam_masuk_time < $morning_start) {
                $prev_date = Carbon::parse($item->tanggal)->subDay()->toDateString();
                $item->tanggal = $prev_date; // Adjust the date for early in time
            }

            if ($jam_pulang_time > strtotime('18:00:00')) {
                $item->jam_pulang = '18:00:00'; // Cap the jam_pulang if it's after 6 PM
            } elseif ($jam_pulang_time < $afternoon_start) {
                $item->jam_pulang = null; // If jam_pulang is before 1 PM, it should be null
            }

            if ($isIzin) {
                $status = $isIzin->status;
                $keputusan = $isIzin->keputusan;

                if ($status == 'Dt' && $keputusan == 'Terlambat') {
                    $item->jam_masuk = '08:00:00';
                }

                if ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                    $item->jam_pulang = '17:00:00';
                }

                if ($status == 'Tam' && !$item->jam_masuk) {
                    $item->jam_masuk = '08:00:00';
                }

                if ($status == 'Tap' && !$item->jam_pulang) {
                    $item->jam_pulang = '17:00:00';
                }
            }

            return $item;
        });

        return view('presensi.gethistori', compact('processedHistoribulanini'));
    }

    private function checkIzin($izin, $nik, $date)
    {
        foreach ($izin as $i) {
            $start = Carbon::parse($i->tgl_izin);
            $end = Carbon::parse($i->tgl_izin_akhir);

            if ($i->nik == $nik && $date->between($start, $end)) {
                return $i; // Return the full object, not just true
            }
        }
        return null; // Return null if no match is found
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
        $nama_lengkap = $request->nama_karyawan;

        $query = DB::table('presensi')
            ->selectRaw('DATE(tgl_presensi) as tanggal, presensi.nip, nama_lengkap, nama_dept, jam_in')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');

        if ($tanggal) {
            $query->whereDate('tgl_presensi', $tanggal);
        }

        if ($nama_lengkap) {
            $query->where('nama_lengkap', 'like', '%' . $nama_lengkap . '%');
        }

        $query->orderBy('nama_dept', 'asc');
        $presensi = $query->get();

        // Process records to find min and max jam_in
        $processedPresensi = [];
        foreach ($presensi as $record) {
            $tanggal = $record->tanggal;
            $nip = $record->nip;
            $time = strtotime($record->jam_in);
            $morning_start = strtotime('06:00:00');
            $afternoon_start = strtotime('13:00:00');
            $next_day_morning_end = strtotime('06:00:00') + 24 * 60 * 60;

            // Check if jam_in is before 6 AM and associate it with the previous day's jam_pulang
            if ($time < $morning_start) {
                $prev_tanggal = Carbon::parse($tanggal)->subDay()->toDateString();
                $key = $prev_tanggal . '_' . $nip;

                if (!isset($processedPresensi[$key])) {
                    $processedPresensi[$key] = [
                        'tanggal' => $prev_tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ];
                }

                if (is_null($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                    $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                }
            } else {
                $key = $tanggal . '_' . $nip;

                if (!isset($processedPresensi[$key])) {
                    $processedPresensi[$key] = [
                        'tanggal' => $tanggal,
                        'nip' => $nip,
                        'nama_lengkap' => $record->nama_lengkap,
                        'nama_dept' => $record->nama_dept,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ];
                }

                if ($time >= $morning_start && $time < $afternoon_start) {
                    if (is_null($processedPresensi[$key]['jam_masuk']) || $time < strtotime($processedPresensi[$key]['jam_masuk'])) {
                        $processedPresensi[$key]['jam_masuk'] = $record->jam_in;
                    }
                } elseif ($time >= $afternoon_start) {
                    if (is_null($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
                        $processedPresensi[$key]['jam_pulang'] = $record->jam_in;
                    }
                }
            }
        }

        // Convert the processed data to a collection for the view
        $presensi = collect($processedPresensi);

        return view("presensi.getpresensi", compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)->first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function notifikasi(Request $request)
{
    $nik = Auth::guard('karyawan')->user()->nik;
    $bulanini = date("m");
    $tahunini = date("Y");

    // Get historical presensi data
    $historibulanini = DB::table(DB::raw("(SELECT
    DATE(tgl_presensi) as tanggal,
    MIN(jam_in) as jam_masuk,
    MAX(jam_in) as jam_pulang,
    nik
    FROM presensi
    WHERE nik = ?
        AND MONTH(tgl_presensi) = ?
        AND YEAR(tgl_presensi) = ?
    GROUP BY DATE(tgl_presensi), nik) as sub"))
        ->leftJoin('presensi as p', function ($join) {
            $join->on('sub.tanggal', '=', DB::raw('DATE(p.tgl_presensi)'))
                ->on('sub.nik', '=', 'p.nik')
                ->whereRaw('p.jam_in = sub.jam_masuk OR p.jam_in = sub.jam_pulang');
        })
        ->select('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang', DB::raw('MAX(p.foto_in) as foto_in'), DB::raw('MAX(p.foto_out) as foto_out'))
        ->groupBy('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang')
        ->orderBy('sub.tanggal', 'asc')
        ->setBindings([$nik, $bulanini, $tahunini])
        ->get();

    // Fetch approved izin data for the current month
    $izin = DB::table('pengajuan_izin')
        ->select('nik', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
        ->where('status_approved', 1)
        ->where('status_approved_hrd', 1)
        ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
        ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
        ->get();

    // Initialize notifications array
    $notifications = [];

    // Generate all possible dates for the current month excluding weekends
    $dates = collect();
    $currentDate = Carbon::createFromFormat('Y-m-d', "{$tahunini}-{$bulanini}-01");
    $endDate = Carbon::now();

    while ($currentDate <= $endDate) {
        $dayOfWeek = $currentDate->dayOfWeek;
        if ($dayOfWeek != Carbon::SATURDAY && $dayOfWeek != Carbon::SUNDAY) {
            $dates->push($currentDate->toDateString());
        }
        $currentDate->addDay();
    }

    // Process each date in the current month
    foreach ($dates as $date) {
        $hasPresensi = $historibulanini->contains('tanggal', $date);
        $isIzin = $this->checkIzin($izin, $nik, Carbon::parse($date));

        $details = [];

        if (!$hasPresensi && !$isIzin) {
            // No presensi and no izin for this date
            $notifications[] = [
                'tanggal' => $date,
                'details' => [
                    [
                        'status' => 'Tidak Masuk Kerja',
                        'status_class' => 'text-warning',
                        'jam_masuk' => 'No Data',
                        'jam_pulang' => 'No Data'
                    ]
                ]
            ];
        } else if ($hasPresensi) {
            // Find presensi data for the date
            $presensiData = $historibulanini->firstWhere('tanggal', $date);

            $morning_start = strtotime('06:00:00');
            $afternoon_start = strtotime('13:00:00');
            $jam_masuk_time = strtotime($presensiData->jam_masuk);
            $jam_pulang_time = strtotime($presensiData->jam_pulang);
            $lateness_threshold = strtotime("08:01:00");

            if ($jam_masuk_time < $morning_start) {
                $prev_date = Carbon::parse($presensiData->tanggal)->subDay()->toDateString();
                $presensiData->tanggal = $prev_date; // Adjust the date for early in time
            }

            if ($jam_pulang_time < $afternoon_start) {
                $presensiData->jam_pulang = null; // If jam_pulang is before 1 PM, it should be null
            }

            if ($isIzin) {
                $status = $isIzin->status;
                $keputusan = $isIzin->keputusan;

                if ($status == 'Dt' && $keputusan == 'Terlambat') {
                    // Skip lateness notification if there's a valid Dt Terlambat izin
                    continue;
                }

                if ($status == 'Pa' && $keputusan == 'Pulang Awal') {
                    // Skip early leave notification if there's a valid Pa Pulang Awal izin
                    continue;
                }

                if ($status == 'Tam' && is_null($presensiData->jam_masuk)) {
                    // Skip no scan in notification if there's a valid Tam izin
                    $details[] = [
                        'status' => "Izin Tidak Absen Masuk",
                        'status_class' => "text-info",
                        'jam_masuk' => 'No Data',
                        'jam_pulang' => $presensiData->jam_pulang ? $presensiData->jam_pulang : "No Data"
                    ];
                    continue;
                }

                if ($status == 'Tap' && is_null($presensiData->jam_pulang)) {
                    // Skip no scan out notification if there's a valid Tap izin
                    $details[] = [
                        'status' => "Izin Tidak Absen Pulang",
                        'status_class' => "text-info",
                        'jam_masuk' => $presensiData->jam_masuk ? $presensiData->jam_masuk : 'No Data',
                        'jam_pulang' => 'No Data'
                    ];
                    continue;
                }
            } else {
                if ($jam_masuk_time >= $lateness_threshold) {
                    $details[] = [
                        'status' => "Terlambat",
                        'status_class' => "text-danger",
                        'jam_masuk' => $presensiData->jam_masuk,
                        'jam_pulang' => $presensiData->jam_pulang ? $presensiData->jam_pulang : "No Data"
                    ];
                }

                if (is_null($presensiData->jam_masuk)) {
                    $details[] = [
                        'status' => 'Tidak Absen Masuk',
                        'status_class' => 'text-warning',
                        'jam_masuk' => 'No Data',
                        'jam_pulang' => $presensiData->jam_pulang
                    ];
                }

                if (is_null($presensiData->jam_pulang)) {
                    $details[] = [
                        'status' => 'Tidak Absen Pulang',
                        'status_class' => 'text-warning',
                        'jam_masuk' => $presensiData->jam_masuk ? $presensiData->jam_masuk : 'No Data',
                        'jam_pulang' => 'No Data'
                    ];
                }

                if ($presensiData->jam_pulang && $jam_pulang_time < strtotime('17:00:00')) {
                    $details[] = [
                        'status' => 'Pulang Awal',
                        'status_class' => 'text-warning',
                        'jam_masuk' => $presensiData->jam_masuk ? $presensiData->jam_masuk : 'No Data',
                        'jam_pulang' => $presensiData->jam_pulang
                    ];
                }
            }

            if (count($details) > 0) {
                $notifications[] = [
                    'tanggal' => $presensiData->tanggal,
                    'details' => $details
                ];
            }
        } else if ($isIzin) {
            // Handle cases where there is no presensi data but there is izin
            $status = $isIzin->status;
            $keputusan = $isIzin->keputusan;

            if ($status == 'Tam') {
                // Tam izin with no presensi data
                $notifications[] = [
                    'tanggal' => $date,
                    'details' => [
                        [
                            'status' => 'Tidak Absen Pulang',
                            'status_class' => 'text-warning',
                            'jam_masuk' => 'No Data',
                            'jam_pulang' => 'No Data'
                        ]
                    ]
                ];
            } elseif ($status == 'Tap') {
                // Tap izin with no presensi data
                $notifications[] = [
                    'tanggal' => $date,
                    'details' => [
                        [
                            'status' => 'Tidak Absen Masuk',
                            'status_class' => 'text-warning',
                            'jam_masuk' => 'No Data',
                            'jam_pulang' => 'No Data'
                        ]
                    ]
                ];
            }
        }
    }

    return view('presensi.notif', ['notif' => $notifications]);
}

}
