<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Pengajuanizin;
use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Karyawan;
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
        $nip = Auth::guard('karyawan')->user()->nip;
        $cek = DB::connection('mysql2')->table('att_log')
            ->whereDate('scan_date', $hariini)
            ->where('pin', $nip)
            ->count();

        // Retrieve all location configurations
        $lok_kantor = DB::table('konfigurasi_lokasi')->get();

        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request)
    {
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

        // $image = $request->image;
        // $folderPath = "public/uploads/absensi/";
        // $formatName = $nip . "-" . $tgl_presensi . "-in";
        // $image_parts = explode(";base64", $image);
        // $image_base64 = base64_decode($image_parts[1]);
        // $fileName = $formatName . ".png";
        // $file = $folderPath . $fileName;

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
            // Storage::put($file, $image_base64);
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

        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
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
                'awal' => DateHelper::formatIndonesiaDate($cuti->periode_awal),
                'akhir' => DateHelper::formatIndonesiaDate($cuti->periode_akhir),
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
        $karyawan = DB::table('karyawan')->where('nip', $nip)->first();

        // Validate the request
        $request->validate([
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'password' => 'nullable|min:6',
            'nama_lengkap' => 'required',
            'no_hp' => 'required'
        ]);

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();

            // Delete old photo if exists
            if (!empty($karyawan->foto)) {
                Storage::delete('public/uploads/karyawan/' . $karyawan->foto);
            }

            // Store new photo - remove one 'public' from the path
            $request->file('foto')->storeAs('uploads/karyawan', $foto); // Changed from 'public/uploads/karyawan'
        } else {
            $foto = $karyawan->foto;
        }

        // Prepare data for update
        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp' => $request->no_hp,
            'foto' => $foto
        ];

        // Add password to data if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        try {
            DB::table('karyawan')
                ->where('nip', $nip)
                ->update($data);

            return Redirect::back()->with([
                'success' => 'Profile berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            return Redirect::back()->with([
                'error' => 'Gagal mengupdate profile. Silakan coba lagi.'
            ]);
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
        $date = Carbon::parse();

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
                // Fetch the shift times and type
                $shiftTimes = DB::table('shift')
                    ->where('id', $shiftId)
                    ->select('early_time', 'latest_time', 'start_time', 'status')
                    ->first();

                if ($shiftTimes) {
                    if ($shiftTimes->early_time !== NULL && $shiftTimes->latest_time !== NULL) {
                        // If shift has defined early and latest times, use them directly
                        $shift_start = strtotime($shiftTimes->start_time);
                        $window_start = strtotime($shiftTimes->early_time);
                        $window_end = strtotime($shiftTimes->latest_time);
                    } else {
                        // If no specific times defined, calculate window based on start time
                        $shift_start = strtotime($shiftTimes->start_time);
                        $window_start = strtotime('-1 hours', $shift_start);
                        $window_end = strtotime('+1 hours', $shift_start);
                    }
                }
            }
        }

        // Process the presensi data to adjust for izin
        $presensi = DB::connection('mysql2')
            ->table('att_log')
            ->where('pin', $nip)
            ->whereMonth('scan_date', $bulanini)
            ->whereYear('scan_date', $tahunini)
            ->orderBy('scan_date', 'asc')
            ->get();

        // Get approved izin data
        $izin = DB::table('pengajuan_izin')
            ->select('nip', 'tgl_izin', 'tgl_izin_akhir', 'status', 'keputusan', 'pukul')
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahunini])
            ->get();

        // Process attendance records
        $processedPresensi = [];
        foreach ($presensi as $record) {
            $tanggal = date('Y-m-d', strtotime($record->scan_date));
            $jam = date('H:i:s', strtotime($record->scan_date));
            $time = strtotime($jam);

            // Get shift pattern info for this date
            $date = Carbon::parse($tanggal);
            $daysFromStart = $date->diffInDays($startShift);
            $dayOfWeek = $date->dayOfWeekIso;

            if ($shiftPatternId) {
                // Determine cycle day
                $patternStartDate = DB::table('karyawan')
                    ->where('nip', $nip)
                    ->value('start_shift');

                if ($patternStartDate) {
                    // Get pattern length
                    $patternLength = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->count();

                    // Calculate days since pattern start
                    $daysSinceStart = Carbon::parse($patternStartDate)
                        ->diffInDays(Carbon::parse($tanggal));

                    if ($patternLength == 7) {
                        // Get the current day of week (1 = Monday, 7 = Sunday)
                        $currentDayOfWeek = Carbon::parse($tanggal)->dayOfWeekIso;

                        // For 7-day cycles, simply use the current day of week
                        $currentCycleDay = $currentDayOfWeek;
                        $prevDayCycleDay = $currentDayOfWeek == 1 ? 7 : $currentDayOfWeek - 1;
                    } else {
                        // For non-weekly patterns, use straight count from start date
                        $currentCycleDay = ($daysSinceStart % $patternLength) + 1;
                        $prevDayCycleDay = (($daysSinceStart - 1) % $patternLength) + 1;
                    }

                    $shiftId = DB::table('shift_pattern_cycle')
                        ->where('pattern_id', $shiftPatternId)
                        ->where('cycle_day', $currentCycleDay)
                        ->value('shift_id');

                    if ($shiftId) {
                        $shiftTimes = DB::table('shift')
                            ->where('id', $shiftId)
                            ->select('early_time', 'latest_time', 'start_time', 'status', 'description')
                            ->first();

                        if ($shiftTimes) {
                            $key = $tanggal . '_' . $nip;
                            if (!isset($processedPresensi[$key])) {
                                $processedPresensi[$key] = [
                                    'tanggal' => $tanggal,
                                    'jam_masuk' => '',
                                    'jam_pulang' => '',
                                    'shift_start_time' => $shiftTimes->start_time,
                                    'shift_type' => $shiftTimes->status,
                                    'shift_name' => $shiftTimes->description
                                ];
                            }

                            // Set window times
                            if ($shiftTimes->early_time !== NULL && $shiftTimes->latest_time !== NULL) {
                                $window_start = strtotime($shiftTimes->early_time);
                                $window_end = strtotime($shiftTimes->latest_time);
                            } else {
                                $shift_start = strtotime($shiftTimes->start_time);
                                $window_start = strtotime('-1 hours', $shift_start);
                                $window_end = strtotime('+1 hours', $shift_start);
                            }

                            $prevDayDate = Carbon::parse($tanggal)->subDay();
                            $prevDayCycleDay = (($daysSinceStart - 1) % $patternLength) + 1;

                            $prevDayShift = DB::table('shift_pattern_cycle')
                                ->where('pattern_id', $shiftPatternId)
                                ->where('cycle_day', $prevDayCycleDay)
                                ->join('shift', 'shift.id', '=', 'shift_pattern_cycle.shift_id')
                                ->value('shift.status');

                            if ($shiftTimes->status === 'M') {
                                // Night shift logic
                                if ($time >= $window_start && $time <= $window_end) {
                                    // Evening check-in
                                    if (
                                        empty($processedPresensi[$key]['jam_masuk']) ||
                                        $time < strtotime($processedPresensi[$key]['jam_masuk'])
                                    ) {
                                        $processedPresensi[$key]['jam_masuk'] = $jam;
                                    }
                                } elseif ($time <= strtotime('12:00:00')) {
                                    // Morning check-out (should be assigned to previous day)
                                    $prevDate = Carbon::parse($tanggal)->subDay()->format('Y-m-d');
                                    $prevKey = $prevDate . '_' . $nip;

                                    // Only assign to previous day if it was a night shift
                                    if (
                                        isset($processedPresensi[$prevKey]) &&
                                        isset($processedPresensi[$prevKey]['shift_type']) &&
                                        $processedPresensi[$prevKey]['shift_type'] === 'M'
                                    ) {
                                        $processedPresensi[$prevKey]['jam_pulang'] = $jam;
                                    }
                                }
                            } else {
                                if ($time <= strtotime('12:00:00') && $prevDayShift === 'M') {
                                    // If early morning scan and previous day was night shift
                                    $prevKey = Carbon::parse($tanggal)->subDay()->format('Y-m-d') . '_' . $nip;
                                    if (isset($processedPresensi[$prevKey])) {
                                        $processedPresensi[$prevKey]['jam_pulang'] = $jam;
                                    }
                                } else {
                                    // Day shift logic (unchanged)
                                    if ($time >= $window_start && $time <= $window_end) {
                                        if (
                                            empty($processedPresensi[$key]['jam_masuk']) ||
                                            $time < strtotime($processedPresensi[$key]['jam_masuk'])
                                        ) {
                                            $processedPresensi[$key]['jam_masuk'] = $jam;
                                        }
                                    } elseif ($time >= strtotime($shiftTimes->latest_time)) {
                                        $processedPresensi[$key]['jam_pulang'] = $jam;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Convert to collection for view
        $processedHistoribulanini = collect($processedPresensi)->map(function ($item) {
            return (object) [
                'tanggal' => $item['tanggal'],
                'jam_masuk' => $item['jam_masuk'],
                'jam_pulang' => $item['jam_pulang'],
                'shift_start_time' => $item['shift_start_time'],
                'jam_kerja' => $item['shift_start_time'],
                'status' => $item['shift_type'],
                'shift_name' => $item['shift_name']
            ];
        })->filter(function ($item) {
            // Keep records that have either jam_masuk OR jam_pulang
            return !empty($item->jam_masuk) || !empty($item->jam_pulang);
        })->values();

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

    private function checkCuti($cuti, $nip, $date)
    {
        foreach ($cuti as $i) {
            if ($i->nip === $nip && $date->between(Carbon::parse($i->tgl_cuti), Carbon::parse($i->tgl_cuti_sampai))) {
                return $i;
            }
        }
        return false;
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

        if (empty($tgl_izin_akhir)) {
            $tgl_izin_akhir = $tgl_izin;
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
                        Waktu Bikin: {$currentDate->format('H:i')}<br>
                        NIK : {$nik}<br>
                        NIP : {$nip}<br>
                        Tanggal Izin : " . DateHelper::formatIndonesianDate($tgl_izin) . "<br>
                        Tanggal Izin Sampai : " . (!empty($tgl_izin_akhir) ? DateHelper::formatIndonesianDate($tgl_izin_akhir) : '') . "<br>
                        Jumlah Hari : {$jml_hari}<br>
                        Status : " . DateHelper::getStatusText($status) . "<br>
                        Waktu Izin: {$pukul}<br>
                        Keterangan : {$keterangan}<br><br>

                        Mohon Cek Di hrms.ciptaharmoni.com/panel<br><br>

                        Terima Kasih
                    ";

                    // Send the email using Mail::html
                    Mail::html($emailContent, function ($message) use ($atasan, $nama_lengkap, $email_karyawan, $currentDate) {
                        $ccList = ['mahardika@ciptaharmoni.com'];

                        // Add $email_karyawan to the CC list if it's not empty
                        // Add $email_karyawan to the CC list if it's not empty
                        if (!empty($email_karyawan) && filter_var($email_karyawan, FILTER_VALIDATE_EMAIL)) {
                            $ccList[] = $email_karyawan;
                        } else {
                            // Log or handle invalid email_karyawan, if needed
                            Log::warning("Invalid or empty email_karyawan: {$email_karyawan}");
                        }

                        $message->to('human.resources@ciptaharmoni.com')
                            ->subject("Pengajuan Izin Baru Dari {$nama_lengkap} - {$currentDate->format('Y-m-d H:i:s')}")
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

        $cuti = DB::table('pengajuan_cuti')
            ->select('nip', 'tgl_cuti', 'tgl_cuti_sampai')
            ->where('nip', $nip)
            ->where('status_approved', 1)
            ->where('status_approved_hrd', 1)
            ->where('status_management', 1)
            ->whereRaw('MONTH(tgl_cuti) = ? OR MONTH(tgl_cuti_sampai) = ?', [$bulanini, $bulanini])
            ->whereRaw('YEAR(tgl_cuti) = ? OR YEAR(tgl_cuti_sampai) = ?', [$tahunini, $tahunini])
            ->get();

        $holidays = DB::table('libur_nasional')
            ->whereRaw('MONTH(tgl_libur) = ?', [$bulanini])
            ->whereRaw('YEAR(tgl_libur) = ?', [$tahunini])
            ->pluck('tgl_libur')->toArray();
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
            $isCuti = $this->checkCuti($cuti, $nip, $date);

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

            $isHoliday = in_array($dateString, $holidays);

            if ($isHoliday) {
                // Employee is on holiday
                $notifications[] = [
                    'tanggal' => $date,
                    'details' => [
                        [
                            'status' => 'Libur Nasional',
                            'status_class' => 'text-success',
                            'jam_masuk' => 'No Data',
                            'jam_pulang' => 'No Data'
                        ]
                    ]
                ];
            } else if ($isCuti) {
                $notifications[] = [
                    'tanggal' => $date,
                    'details' => [
                        [
                            'status' => 'Cuti Tahunan',
                            'status_class' => 'text-primary',
                            'jam_masuk' => 'No Data',
                            'jam_pulang' => 'No Data'
                        ]
                    ]
                ];

            }else if (!$hasPresensi && !$isIzin) {
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
            ->where('tgl_izin_akhir', '>', now())
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
            ->where('tgl_cuti_sampai', '>', now())
            ->orderBy('tgl_cuti')
            ->get();

        return view('izin.pembatalancuti', compact('historicuti'));
    }


    public function batalCuti(Request $request)
    {
        $cutiIds = $request->input('cuti_ids');
        $email_karyawan = Auth::guard('karyawan')->user()->email;

        if ($cutiIds) {
            DB::beginTransaction();

            try {
                // Check if any status is already 3
                $existingStatus = PengajuanCuti::whereIn('id', $cutiIds)
                    ->where(function ($query) {
                        $query->where('status_approved', 3)
                            ->orWhere('status_approved_hrd', 3)
                            ->orWhere('status_management', 3);
                    })
                    ->exists();

                if ($existingStatus) {
                    return response()->json(['success' => false, 'message' => 'Cuti ini sudah di batalkan']);
                }

                // Fetch leave applications
                $leaveApplications = PengajuanCuti::whereIn('id', $cutiIds)->get();

                // Update statuses to 3
                PengajuanCuti::whereIn('id', $cutiIds)
                    ->update([
                        'status_approved' => 3,
                        'status_approved_hrd' => 3,
                        'status_management' => 3,
                    ]);

                foreach ($leaveApplications as $leaveApplication) {
                    $employee = DB::table('karyawan')->where('nik', $leaveApplication->nik)->first();
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
                        Nama : {$employee->nama_lengkap}<br>
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

                    Mail::html($emailContent, function ($message) use ($leaveApplication, $email_karyawan) {
                        $ccList = [
                            'human.resources@ciptaharmoni.com',
                            'al.imron@ciptaharmoni.com',
                            'mahardika@ciptaharmoni.com',
                        ];

                        if (!empty($email_karyawan) && filter_var($email_karyawan, FILTER_VALIDATE_EMAIL)) {
                            $ccList[] = $email_karyawan;
                        } else {
                            Log::warning("Invalid or empty email_karyawan: {$email_karyawan}");
                        }

                        $atasanEmail = null;
                        if ($leaveApplication->nik) {
                            $employee = DB::table('karyawan')->where('nik', $leaveApplication->nik)->first();
                            if ($employee && $employee->jabatan) {
                                $jabatanAtasan = DB::table('jabatan')->where('id', $employee->jabatan)->value('jabatan_atasan');
                                if ($jabatanAtasan) {
                                    $atasan = DB::table('karyawan')->where('jabatan', $jabatanAtasan)->first();
                                    if ($atasan && $atasan->email) {
                                        $atasanEmail = $atasan->email;
                                    }
                                }
                            }
                        }

                        if ($atasanEmail) {
                            $message->to($atasanEmail)
                                ->subject("Pembatalan Cuti: {$employee->nama_lengkap}")
                                ->cc($ccList)
                                ->priority(1);

                            $message->getHeaders()->addTextHeader('Importance', 'high');
                            $message->getHeaders()->addTextHeader('X-Priority', '1');
                        } else {
                            Log::warning("Atasan email not found for employee NIK: {$leaveApplication->nik}");
                        }
                    });
                }

                DB::commit();

                return response()->json(['success' => true, 'message' => 'Pengajuan cuti berhasil dibatalkan']);
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Error in batalCuti method', [
                    'error' => $e->getMessage(),
                    'cuti_ids' => $cutiIds,
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada pengajuan cuti yang dipilih']);
    }

}
