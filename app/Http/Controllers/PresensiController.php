<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Pengajuanizin;
use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();

        // Retrieve all location configurations
        $lok_kantor = DB::table('konfigurasi_lokasi')->get();

        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $base = Auth::guard('karyawan')->user()->base_poh;
        $nip = Auth::guard('karyawan')->user()->nip;
        $tgl_presensi = date("Y-m-d");
        $scan_date = date("Y-m-d H:i:s");

        // Retrieve the selected no_mesin value
        $sn = $request->no_mesin;

        // If no_mesin is not selected, use base_poh
        if (!$sn) {
            $lok_kantor = DB::table('konfigurasi_lokasi')
                ->where('nama_kantor', $base)
                ->first();

            if (!$lok_kantor) {
                echo "error|Lokasi kantor tidak ditemukan|in";
                return;
            }

            $sn = $lok_kantor->no_mesin; // Use no_mesin from the base_poh location
        }

        // Check if an attendance record exists for today
        $existingRecord = DB::connection('mysql2')->table('att_log')
            ->whereDate('scan_date', $tgl_presensi)
            ->where('pin', $nip)
            ->exists();

        // Set inoutmode based on whether an entry exists
        $inoutmode = $existingRecord ? 2 : 1;

        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nip . "-" . $tgl_presensi . "-in";
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $data = [
            'sn' => $sn,
            'scan_date' => $scan_date,
            'pin' => $nip,
            'verifymode' => 1,
            'inoutmode' => $inoutmode,
            'reserved' => 0,
            'work_code' => 0,
            'att_id' => '0',
        ];

        $simpan = DB::connection('mysql2')->table('att_log')->insert($data);
        if ($simpan) {
            echo "success|Terima Kasih, Selamat Bekerja|in";
            Storage::put($file, $image_base64);
        } else {
            echo "error|Maaf Gagal Absen Hubungi Tim IT|in";
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

        $nip = Auth::guard('karyawan')->user()->nip;
        $karyawan = DB::table('karyawan')->where('nip', $nip)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function getSisaCutiProfile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;

        // Fetch the active cuti record for the current employee
        $cuti = DB::table('cuti')
            ->where('nik', $nik)
            ->where('status', 1) // Only active records
            ->first();

        if ($cuti) {
            // If cuti exists, return the sisa_cuti and year
            return response()->json([
                'sisa_cuti' => $cuti->sisa_cuti,
                'cutiYear' => $cuti->tahun,
            ]);
        } else {
            // If no active cuti record, return an error message
            return response()->json([
                'error' => 'Anda tidak ada Periode Cuti. Mohon hubungi HRD.',
            ], 404);
        }
    }


    public function updateprofile(Request $request)
    {
        $nip = Auth::guard('karyawan')->user()->nip;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);;
        $karyawan = DB::table('karyawan')->where('nip', $nip)->first();
        if ($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
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

        $update = DB::table('karyawan')->where('nip', $nip)->update($data);
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
        $nip = Auth::guard('karyawan')->user()->nip;

        // Join karyawan and jabatan tables to get nama_jabatan
        $namaUser = DB::table('karyawan')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->where('karyawan.nip', $nip)
            ->select('karyawan.*', 'jabatan.nama_jabatan')
            ->first();

        // Split nama_lengkap into first and last names
        $nameParts = explode(' ', $namaUser->nama_lengkap);
        $firstName = $nameParts[0];
        $lastName = end($nameParts);
        $namaUser->first_name = $firstName;
        $namaUser->last_name = $lastName;

        $presensihariini = DB::table('presensi')->where('nip', $nip)
            ->where('tgl_presensi', $hariini)
            ->first();

        // Fetch approved izin data for the current month
        $izin = DB::table('pengajuan_izin')
            ->select('nip', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
            ->get();

        $historibulanini = DB::connection('mysql2')->table(DB::raw("(SELECT
            DATE(scan_date) as tanggal,
            TIME(MIN(scan_date)) as jam_masuk,
            TIME(MAX(scan_date)) as jam_pulang,
            pin
            FROM att_log
            WHERE pin = ?
            AND MONTH(scan_date) = ?
            AND YEAR(scan_date) = ?
            GROUP BY DATE(scan_date), pin) as sub"))
            ->select('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang')
            ->orderBy('sub.tanggal', 'asc')
            ->setBindings([$nip, $bulanini, $tahunini])
            ->get();

        // Process the presensi data to adjust for izin
        $processedHistoribulanini = $historibulanini->map(function ($item) use ($izin, $nip) {
            $date = Carbon::parse($item->tanggal);
            $isIzin = $this->checkIzin($izin, $nip, $date);

            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            $startShift = Carbon::parse(DB::table('karyawan')
                ->where('nip', $nip)
                ->value('start_shift'));

            // Calculate cycle length from shift_pattern_cycle table
            $cycleLength = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->count();

            $dateString = $date->toDateString();
            $daysFromStart = $date->diffInDays($startShift);
            $dayOfWeek = Carbon::parse($dateString)->dayOfWeekIso;

            if ($shiftPatternId) {

                if ($cycleLength == 7) {
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $dayOfWeek)
                        ->value('shift_id');
                } else {
                    $cyclePosition = $daysFromStart % $cycleLength;
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $cyclePosition + 1)
                        ->value('shift_id');
                }

                if ($shiftId) {
                    // Fetch the early_time and latest_time from the shifts table
                    $shiftTimes = DB::table('shift')
                        ->where('id', $shiftId)
                        ->select('early_time', 'latest_time', 'start_time', 'status')
                        ->first();

                    $morning_start = strtotime($shiftTimes->early_time);
                    $work_start = strtotime($shiftTimes->start_time);
                    $afternoon_start = strtotime($shiftTimes->latest_time);
                } else {
                    // Default values if no shift is found
                    $morning_start = strtotime('05:00:00');
                    $work_start = strtotime('08:00:00');
                    $afternoon_start = strtotime('13:00:00');
                }
            } else {
                // Default values if no shift pattern is found
                $morning_start = strtotime('06:00:00');
                $work_start = strtotime('08:00:00');
                $afternoon_start = strtotime('13:00:00');
            }
            $item->jam_kerja = $work_start;
            $jam_masuk_time = strtotime($item->jam_masuk);
            $jam_pulang_time = strtotime($item->jam_pulang);

            if ($jam_masuk_time < $morning_start) {
                $prev_date = Carbon::parse($item->tanggal)->subDay()->toDateString();
                $item->tanggal = $prev_date; // Adjust the date for early in time
            }

            if ($jam_masuk_time > $afternoon_start) {
                $item->jam_masuk = null; // If jam_pulang is before 1 PM, it should be null
            }

            if ($jam_pulang_time < $afternoon_start) {
                $item->jam_pulang = null; // If jam_pulang is before 1 PM, it should be null
            }

            if ($isIzin) {
                $status = $isIzin->status;
                $pukul = $isIzin->pukul;

                if ($status == 'Tam' && !$item->jam_masuk) {
                    $item->jam_masuk = $pukul;
                }

                if ($status == 'Tap' && !$item->jam_pulang) {
                    $item->jam_pulang = $pukul;
                }
            }

            return $item;
        });

        return view('presensi.gethistori', compact('processedHistoribulanini'));
    }

    private function checkIzin($izin, $nip, $date)
    {
        foreach ($izin as $i) {
            $start = Carbon::parse($i->tgl_izin);
            $end = Carbon::parse($i->tgl_izin_akhir);

            if ($i->nip == $nip && $date->between($start, $end)) {
                return $i; // Return the full object, not just true
            }
        }
        return null; // Return null if no match is found
    }

    public function izin()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $nip = Auth::guard('karyawan')->user()->nip;
        $dataizin = DB::table('pengajuan_izin')->where('nip', $nip)->get();
        return view('izin.izin', compact('dataizin', 'namabulan'));
    }

    public function getizin(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nip = Auth::guard('karyawan')->user()->nip;

        $historiizin = DB::table('pengajuan_izin')
            ->whereRaw('MONTH(tgl_izin)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_izin)="' . $tahun . '"')
            ->where('nip', $nip)
            ->orderBy('tgl_izin')
            ->get();

        return view('izin.getizin', compact('historiizin', 'tahun', 'bulan'));
    }
    public function getizincuti(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nip = Auth::guard('karyawan')->user()->nip;

        $historicuti = DB::table('pengajuan_cuti')
            ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti')
            ->whereRaw('MONTH(pengajuan_cuti.tgl_cuti) = ?', [$bulan])
            ->whereRaw('YEAR(pengajuan_cuti.tgl_cuti) = ?', [$tahun])
            ->where('pengajuan_cuti.nip', $nip)
            ->select('pengajuan_cuti.*', 'tipe_cuti.tipe_cuti')
            ->orderBy('pengajuan_cuti.tgl_cuti')
            ->get();

        return view('izin.getizincuti', compact('historicuti', 'tahun', 'bulan'));
    }

    public function getFolder()
    {
        $user = Auth::guard('karyawan')->user();
        $nip = $user->nip;
        $nama_lengkap = $user->nama_lengkap;
        $folderPath = "public/uploads/karyawan/{$nip}.{$nama_lengkap}/";

        // Check if the folder exists, if not, create it
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        // Get the list of files in the directory
        $files = Storage::files($folderPath);

        // Convert files to URLs
        $fileUrls = array_map(function ($file) {
            return Storage::url($file);
        }, $files);

        // Pass data to the view
        return view('presensi.files', [
            'files' => $fileUrls,
            'folderPath' => $folderPath,
            'nama_lengkap' => $nama_lengkap
        ]);
    }



    public function buatizin()
    {
        return view('izin.buatizin');
    }

    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nip = Auth::guard('karyawan')->user()->nip;
        $nama_lengkap = Auth::guard('karyawan')->user()->nama_lengkap;
        $email_karyawan = Auth::guard('karyawan')->user()->email;
        $tgl_izin = $request->tgl_izin;
        $tgl_izin_akhir = $request->tgl_izin_akhir;
        $jml_hari = $request->jml_hari;
        $status = $request->status;
        $keterangan = $request->keterangan;
        $pukul = $request->pukul;
        $currentDate = Carbon::now();
        $folderPath = "public/uploads/karyawan/{$nip}.{$nama_lengkap}/";

        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        $filePaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = "Surat_" . $nip . "_" . $currentDate->format('d_m_Y') . "_" . uniqid() . "." . $extension;
                $file->storeAs($folderPath, $fileName);
                $filePaths[] = $fileName;
            }
            $foto = implode(',', $filePaths); // Store file names as a comma-separated string
        } else {
            $foto = "No_Document";
        }

        $data = [
            'nik' => $nik,
            'nip' => $nip,
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
            // Fetch the atasan details
            $atasanJabatan = DB::table('jabatan')->where('id', Auth::guard('karyawan')->user()->jabatan)->first();

            if ($atasanJabatan && $atasanJabatan->jabatan_atasan) {
                $atasanJabatanId = $atasanJabatan->jabatan_atasan;
                $atasan = DB::table('karyawan')->where('jabatan', $atasanJabatanId)->first();

                if ($atasan && $atasan->email) {
                    // Prepare the email content
                    $emailContent = "
                        Pengajuan Absensi Karyawan<br><br>
                        Nama : {$nama_lengkap}<br>
                        Tanggal : {$currentDate->toDateString()}<br>
                        Pukul : {$currentDate->format('H:i')}<br>
                        NIK : {$nik}<br>
                        NIP : {$nip}<br>
                        Tanggal Izin : " . DateHelper::formatIndonesianDate($tgl_izin) . "<br>
                        Tanggal Izin Sampai : " . (!empty($tgl_izin_akhir) ? DateHelper::formatIndonesianDate($tgl_izin_akhir) : '') . "<br>
                        Jumlah Hari : {$jml_hari}<br>
                        Status : " . DateHelper::getStatusText($status) . "<br>
                        Pukul : {$pukul}<br>
                        Keterangan : {$keterangan}<br><br>

                        Mohon Cek Di hrms.ciptaharmoni.com/panel<br><br>

                        Terima Kasih
                    ";

                    // Send the email using Mail::html
                    Mail::html($emailContent, function ($message) use ($atasan, $nama_lengkap, $email_karyawan) {
                        $ccList = ['human.resources@ciptaharmoni.com', 'mahardika@ciptaharmoni.com'];

                        // Add $email_karyawan to the CC list if it's not empty
                        // Add $email_karyawan to the CC list if it's not empty
                        if (!empty($email_karyawan) && filter_var($email_karyawan, FILTER_VALIDATE_EMAIL)) {
                            $ccList[] = $email_karyawan;
                        } else {
                            // Log or handle invalid email_karyawan, if needed
                            Log::warning("Invalid or empty email_karyawan: {$email_karyawan}");
                        }

                        $message->to($atasan->email)
                            ->subject("Pengajuan Cuti Baru Dari {$nama_lengkap}")
                            ->cc($ccList)
                            ->priority(1);

                        $message->getHeaders()->addTextHeader('Importance', 'high');
                        $message->getHeaders()->addTextHeader('X-Priority', '1');
                    });
                }
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
                        'jam_masuk' => '',
                        'jam_pulang' => '',
                    ];
                }

                if (empty($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
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
                        'jam_masuk' => '',
                        'jam_pulang' => '',
                    ];
                }

                if ($time >= $morning_start && $time < $afternoon_start) {
                    if (empty($processedPresensi[$key]['jam_masuk']) || $time < strtotime($processedPresensi[$key]['jam_masuk'])) {
                        $processedPresensi[$key]['jam_masuk'] = $record->jam_in;
                    }
                } elseif ($time >= $afternoon_start) {
                    if (empty($processedPresensi[$key]['jam_pulang']) || $time > strtotime($processedPresensi[$key]['jam_pulang'])) {
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
        $nip = Auth::guard('karyawan')->user()->nip;
        $bulanini = date("m");
        $tahunini = date("Y");

        // Get historical presensi data
        $historibulanini = DB::connection('mysql2')->table(DB::raw("(SELECT
            DATE(scan_date) as tanggal,
            TIME(MIN(scan_date)) as jam_masuk,
            TIME(MAX(scan_date)) as jam_pulang,
            pin
            FROM att_log
            WHERE pin = ?
            AND MONTH(scan_date) = ?
            AND YEAR(scan_date) = ?
            GROUP BY DATE(scan_date), pin) as sub"))
            ->select('sub.tanggal', 'sub.jam_masuk', 'sub.jam_pulang')
            ->orderBy('sub.tanggal', 'asc')
            ->setBindings([$nip, $bulanini, $tahunini])
            ->get();

        // Fetch approved izin data for the current month
        $izin = DB::table('pengajuan_izin')
            ->select('nip', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
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
        $endDate = Carbon::now()->subDay();

        while ($currentDate <= $endDate) {
            $dayOfWeek = $currentDate->dayOfWeek;
            if ($dayOfWeek != Carbon::SATURDAY && $dayOfWeek != Carbon::SUNDAY) {
                $dates->push($currentDate->toDateString());
            }
            $currentDate->addDay();
        }

        // Process each date in the current month
        foreach ($dates as $dateString) {
            $date = Carbon::parse($dateString);
            $hasPresensi = $historibulanini->contains('tanggal', $dateString);
            $isIzin = $this->checkIzin($izin, $nip, $date);

            $shiftPatternId = DB::table('karyawan')
                ->where('nip', $nip)
                ->value('shift_pattern_id');

            $startShift = Carbon::parse(DB::table('karyawan')
                ->where('nip', $nip)
                ->value('start_shift'));

            // Calculate cycle length from shift_pattern_cycle table
            $cycleLength = DB::table('shift_pattern_cycle')
                ->where('pattern_id', $shiftPatternId)
                ->count();

            $daysFromStart = $date->diffInDays($startShift);
            $dayOfWeek = $date->dayOfWeekIso;

            if ($shiftPatternId) {

                if ($cycleLength == 7) {
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $dayOfWeek)
                        ->value('shift_id');
                } else {
                    $cyclePosition = $daysFromStart % $cycleLength;
                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $cyclePosition + 1)
                        ->value('shift_id');
                }

                if ($shiftId) {
                    // Fetch the early_time and latest_time from the shifts table
                    $shiftTimes = DB::table('shift')
                        ->where('id', $shiftId)
                        ->select('early_time', 'latest_time', 'start_time', 'status', 'end_time')
                        ->first();

                    $morning_start = strtotime($shiftTimes->early_time);
                    $afternoon_start = strtotime($shiftTimes->latest_time);
                    $work_start = strtotime($shiftTimes->start_time);
                    $end_time = strtotime($shiftTimes->end_time);
                } else {
                    // Default values if no shift is found
                    $morning_start = strtotime('05:00:00');
                    $afternoon_start = strtotime('13:00:00');
                    $work_start = strtotime('08:00:00');
                    $end_time = strtotime('17:00:00');
                }
            } else {
                // Default values if no shift pattern is found
                $morning_start = strtotime('06:00:00');
                $afternoon_start = strtotime('13:00:00');
                $work_start = strtotime('08:00:00');
                $end_time = strtotime('17:00:00');
            }

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
                $presensiData = $historibulanini->firstWhere('tanggal', $dateString);
                $jam_masuk_time = strtotime($presensiData->jam_masuk);
                $jam_pulang_time = strtotime($presensiData->jam_pulang);
                $lateness_threshold = $work_start;

                if ($jam_masuk_time < $morning_start) {
                    $prev_date = Carbon::parse($presensiData->tanggal)->subDay()->toDateString();
                    $presensiData->tanggal = $prev_date; // Adjust the date for early in time
                }

                if ($jam_masuk_time > $afternoon_start) {
                    $presensiData->jam_masuk = null; // If jam_pulang is before 1 PM, it should be null
                    $jam_masuk_time = null;
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

                    if ($presensiData->jam_pulang && $jam_pulang_time < $end_time) {
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


    public function pembatalanizin()
    {
        $nik = Auth::guard('karyawan')->user()->nik;

        $historiizin = DB::table('pengajuan_izin')
            ->where('nik', $nik)
            ->where('status_approved', '!=', '3')
            ->where('status_approved_hrd', '!=', '3')
            ->orderBy('tgl_izin')
            ->get();


        return view('izin.pembatalanizin', compact('historiizin'));
    }

    public function batalIzin(Request $request)
    {
        // Get selected izin IDs from the form
        $izinIds = $request->input('izin_ids');

        // Check if there are selected izin IDs
        if ($izinIds) {
            // Update the selected izin records' status to 3 (batal)
            Pengajuanizin::whereIn('id', $izinIds)
                ->update([
                    'status_approved' => 3,
                    'status_approved_hrd' => 3
                ]);

            // Return success response
            return response()->json(['success' => true]);
        }

        // If no IDs are selected, return error response
        return response()->json(['success' => false]);
    }

    public function pembatalancuti()
    {
        $nik = Auth::guard('karyawan')->user()->nik;

        $historicuti = DB::table('pengajuan_cuti')
            ->where('nik', $nik)
            ->where('status_approved', '!=', '3')
            ->where('status_approved_hrd', '!=', '3')
            ->where('status_management', '!=', '3') // Exclude "returned" leave requests
            ->where('status_approved', '!=', '2')    // Exclude leave already returned
            ->where('status_approved_hrd', '!=', '2') // Exclude leave already returned
            ->where('status_management', '!=', '2')  // Exclude leave already returned
            ->orderBy('tgl_cuti')
            ->get();

        return view('izin.pembatalancuti', compact('historicuti'));
    }


    public function batalCuti(Request $request)
    {
        $cutiIds = $request->input('cuti_ids');

        if ($cutiIds) {
            DB::beginTransaction();

            try {
                $leaveApplications = PengajuanCuti::whereIn('id', $cutiIds)->get();

                PengajuanCuti::whereIn('id', $cutiIds)
                    ->update([
                        'status_approved' => 3,
                        'status_approved_hrd' => 3,
                        'status_management' => 3,
                    ]);

                foreach ($leaveApplications as $leaveApplication) {
                    $cutiRecord = DB::table('cuti')
                        ->where('nik', $leaveApplication->nik)
                        ->where('tahun', $leaveApplication->periode)
                        ->first();

                    if ($cutiRecord) {
                        $newSisaCuti = $cutiRecord->sisa_cuti + $leaveApplication->jml_hari;

                        DB::table('cuti')
                            ->where('nik', $leaveApplication->nik)
                            ->where('tahun', $leaveApplication->periode)
                            ->update(['sisa_cuti' => $newSisaCuti]);
                    }

                    // Email notification logic
                    $emailContent = "
                    Pembatalan Cuti Karyawan<br><br>
                    Nama : {$leaveApplication->nama_lengkap}<br>
                    Tanggal Pembatalan : " . DateHelper::formatIndonesianDate(now()->toDateString()) . "<br>
                    NIK : {$leaveApplication->nik}<br>
                    NIP : {$leaveApplication->nip}<br>
                    Periode : {$leaveApplication->periode}<br>
                    Tanggal Cuti : " . DateHelper::formatIndonesianDate($leaveApplication->tgl_cuti) . "<br>
                    Tanggal Cuti Sampai : " . DateHelper::formatIndonesianDate($leaveApplication->tgl_cuti_sampai) . "<br>
                    Jumlah Hari : {$leaveApplication->jml_hari}<br>
                    Sisa Cuti Setelah Pembatalan : {$newSisaCuti}<br><br>

                    Terima Kasih
                ";

                    Mail::html($emailContent, function ($message) use ($leaveApplication) {
                        $ccList = [
                            'human.resources@ciptaharmoni.com',
                            'al.imron@ciptaharmoni.com',
                            'mahardika@ciptaharmoni.com',
                        ];

                        if (!empty($leaveApplication->email) && filter_var($leaveApplication->email, FILTER_VALIDATE_EMAIL)) {
                            $ccList[] = $leaveApplication->email;
                        } else {
                            Log::warning("Invalid or empty email: {$leaveApplication->email}");
                        }

                        $message->to($leaveApplication->atasan->email)
                            ->subject("Pembatalan Cuti: {$leaveApplication->nama_lengkap}")
                            ->cc($ccList)
                            ->priority(1);

                        $message->getHeaders()->addTextHeader('Importance', 'high');
                        $message->getHeaders()->addTextHeader('X-Priority', '1');
                    });
                }

                DB::commit();

                return response()->json(['success' => true, 'message' => 'Pengajuan cuti berhasil dibatalkan']);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada pengajuan cuti yang dipilih']);
    }
}
