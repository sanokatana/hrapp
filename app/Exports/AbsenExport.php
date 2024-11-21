<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsenExport implements WithMultipleSheets, ShouldAutoSize
{
    protected $karyawanPresensi;
    protected $filterMonth;
    protected $filterYear;

    public function __construct($karyawanPresensi, $filterMonth, $filterYear)
    {
        $this->karyawanPresensi = $karyawanPresensi;
        $this->filterMonth = $filterMonth;
        $this->filterYear = $filterYear;
    }

    public function sheets(): array
    {
        $sheets = [];
        // Create a sheet for each karyawan (employee)
        foreach ($this->karyawanPresensi as $employeeData) {
            // Pass the employee data and their presensi (attendance) to the AttendanceSheet class
            $sheets[] = new AttendanceSheet($employeeData['employee'], $employeeData['presensi']);
        }

        return $sheets;
    }
}

class AttendanceSheet implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $employee;
    protected $presensi;

    public function __construct($employee, $presensi)
    {
        $this->employee = $employee;
        $this->presensi = $presensi;
    }

    public function collection()
    {
        // Flatten the presensi grouped by scan_date
        $flatPresensi = $this->presensi->flatMap(function ($dates) {
            return $dates; // Each $dates is a collection of attendance records for a specific scan_date
        });

        // Prepare data for export
        $data = [];
        foreach ($flatPresensi as $att) {
            $data[] = [
                'Tanggal' => $att->scan_date,
                'Jam Absen Masuk' => $att->earliest_scan_time,
                'Jam Absen Pulang' => $att->latest_scan_time, // Add the latest scan time here
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam Absen Masuk',
            'Jam Absen Pulang', // Adjusted to include both times
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Example: making the header row bold
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set the sheet title to the employee's name
                $event->sheet->setTitle($this->employee->nama_lengkap);
            },
        ];
    }
}


