<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PresensiController extends Controller
{
    public function create()
    {
        /** @var Karyawan|null $employee */
        $employee = Auth::guard('karyawan')->user();

        if (!$employee) {
            abort(403, 'Unauthorized');
        }

        $cabang = $employee->cabang;

        $todayAttendance = Presensi::where('karyawan_id', $employee->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        $hasCheckedIn = (bool) optional($todayAttendance)->jam_masuk;
        $hasCheckedOut = (bool) optional($todayAttendance)->jam_keluar;

        return view('presensi.create', [
            'employee'       => $employee,
            'cabang'         => $cabang,
            'hasCheckedIn'   => $hasCheckedIn,
            'hasCheckedOut'  => $hasCheckedOut,
        ]);
    }

    public function history()
    {
        /** @var Karyawan|null $employee */
        $employee = Auth::guard('karyawan')->user();

        if (!$employee instanceof Karyawan) {
            abort(403, 'Unauthorized');
        }

        $now = Carbon::now();
        $currentMonth = (int) $now->format('n');
        $currentYear = (int) $now->format('Y');

        $history = $this->buildHistory($employee, $currentMonth, $currentYear);

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('presensi.histori', [
            'karyawan'        => $employee,
            'namabulan'       => $monthNames,
            'initialHistory'  => view('presensi.partials.history-list', [
                'history' => $history,
            ])->render(),
            'selectedMonth'   => $currentMonth,
            'selectedYear'    => $currentYear,
        ]);
    }

    public function fetchHistory(Request $request)
    {
        /** @var Karyawan|null $employee */
        $employee = Auth::guard('karyawan')->user();

        if (!$employee instanceof Karyawan) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $history = $this->buildHistory(
            $employee,
            (int) $validated['bulan'],
            (int) $validated['tahun']
        );

        return view('presensi.partials.history-list', [
            'history' => $history,
        ]);
    }

    public function editProfile()
    {
        /** @var Karyawan|null $employee */
        $employee = Auth::guard('karyawan')->user();

        if (!$employee instanceof Karyawan) {
            abort(403, 'Unauthorized');
        }

        return view('presensi.editprofile', [
            'karyawan' => $employee,
        ]);
    }

    public function store(Request $request)
    {
        /** @var Karyawan|null $employee */
        $employee = Auth::guard('karyawan')->user();

        if (!$employee) {
            return $this->errorResponse('Autentikasi karyawan tidak valid.');
        }

        $cabang = $employee->cabang;

        if (!$cabang || $cabang->latitude === null || $cabang->longitude === null || $cabang->radius_meter === null) {
            return $this->errorResponse('Pengaturan lokasi cabang belum lengkap. Silakan hubungi administrator.');
        }

        $request->validate([
            'lokasi' => ['required', 'regex:/^-?\d{1,3}(\.\d+)?,-?\d{1,3}(\.\d+)?$/'],
            'selfie' => ['nullable', 'string'],
        ]);

        [$latitude, $longitude] = array_map('floatval', explode(',', $request->input('lokasi')));

        $distance = $this->calculateDistance($latitude, $longitude, (float) $cabang->latitude, (float) $cabang->longitude);

        if ($distance > (float) $cabang->radius_meter) {
            return $this->errorResponse('Anda berada di luar radius presensi. Jarak Anda ' . round($distance) . ' meter dari lokasi cabang.');
        }

        $today = Carbon::today();

        $attendance = Presensi::where('karyawan_id', $employee->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($attendance && $attendance->jam_masuk && $attendance->jam_keluar) {
            return $this->errorResponse('Anda sudah melakukan presensi masuk dan pulang hari ini.');
        }

        $isCheckIn = !$attendance || !$attendance->jam_masuk;
        $photoType = $isCheckIn ? 'masuk' : 'keluar';

        $photoPath = null;
        $selfiePayload = $request->input('selfie');

        if (!empty($selfiePayload)) {
            try {
                $photoPath = $this->storeSelfie($selfiePayload, $employee->nik ?? (string) $employee->id, $today, $photoType);
            } catch (ValidationException $exception) {
                $message = collect($exception->errors())->flatten()->first() ?? 'Foto selfie tidak valid.';
                return $this->errorResponse($message);
            } catch (\Throwable $throwable) {
                Log::error('Failed to store attendance selfie', ['error' => $throwable->getMessage()]);
                return $this->errorResponse('Gagal menyimpan foto selfie. Silakan coba kembali.');
            }
        }

        $now = Carbon::now();
        $mode = $isCheckIn ? 'in' : 'out';

        if (!$attendance) {
            $attendance = new Presensi();
            $attendance->fill([
                'karyawan_id' => $employee->id,
                'lokasi_id'   => $employee->lokasi_id,
                'tanggal'     => $today,
            ]);
        }

        if ($isCheckIn) {
            $attendance->jam_masuk = $now->format('H:i:s');
            $attendance->latitude = $latitude;
            $attendance->longitude = $longitude;
            $attendance->foto_masuk = $photoPath;
            $mode = 'in';
            $message = 'Presensi masuk berhasil disimpan.';
        } else {
            $attendance->jam_keluar = $now->format('H:i:s');
            $attendance->foto_keluar = $photoPath;
            $attendance->latitude = $latitude;
            $attendance->longitude = $longitude;
            $mode = 'out';
            $message = 'Presensi pulang berhasil disimpan.';
        }

        $attendance->save();

        return response('success|' . $message . '|' . $mode);
    }

    private function buildHistory(Karyawan $employee, int $month, int $year)
    {
        return Presensi::with('lokasi')
            ->where('karyawan_id', $employee->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderByDesc('tanggal')
            ->get()
            ->map(function (Presensi $record) {
                $dateValue = $record->tanggal;
                $dateInstance = $dateValue instanceof Carbon ? $dateValue : Carbon::parse($dateValue);

                $jamMasukLabel = $record->jam_masuk
                    ? Carbon::createFromFormat('H:i:s', $record->jam_masuk)->format('H:i')
                    : null;

                $jamKeluarLabel = $record->jam_keluar
                    ? Carbon::createFromFormat('H:i:s', $record->jam_keluar)->format('H:i')
                    : null;

                return (object) [
                    'tanggal' => $dateInstance,
                    'tanggal_label' => DateHelper::formatIndonesianDate($dateInstance->toDateString()),
                    'jam_masuk_label' => $jamMasukLabel,
                    'jam_keluar_label' => $jamKeluarLabel,
                    'has_jam_masuk' => !empty($record->jam_masuk),
                    'has_jam_keluar' => !empty($record->jam_keluar),
                    'lokasi' => optional($record->lokasi)->nama_kantor,
                ];
            })->values();
    }

    private function storeSelfie(string $payload, string $identifier, Carbon $date, string $type): string
    {
        if (!preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $payload, $matches)) {
            throw ValidationException::withMessages([
                'selfie' => 'Format foto tidak valid.',
            ]);
        }

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $data = base64_decode(substr($payload, strpos($payload, ',') + 1));

        if ($data === false) {
            throw ValidationException::withMessages([
                'selfie' => 'Foto selfie tidak dapat dibaca.',
            ]);
        }

        $directory = 'presensi/' . Str::slug($identifier);
        $filename = $date->format('Ymd') . '_' . $type . '_' . Str::random(10) . '.' . $extension;
        $path = $directory . '/' . $filename;

        Storage::disk('public')->put($path, $data);

        return $path;
    }

    private function calculateDistance(float $latFrom, float $lonFrom, float $latTo, float $lonTo): float
    {
        $earthRadius = 6371000; // meters

        $latFromRad = deg2rad($latFrom);
        $lonFromRad = deg2rad($lonFrom);
        $latToRad = deg2rad($latTo);
        $lonToRad = deg2rad($lonTo);

        $latDelta = $latToRad - $latFromRad;
        $lonDelta = $lonToRad - $lonFromRad;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFromRad) * cos($latToRad) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle;
    }

    private function errorResponse(string $message)
    {
        return response('error|' . $message, 200);
    }
}
