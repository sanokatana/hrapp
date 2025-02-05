<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceExport implements FromArray, WithHeadings, WithEvents
{
    protected $attendanceData;

    public function __construct($attendanceData)
    {
        $this->attendanceData = $attendanceData;
    }

    public function array(): array
    {
        $exportData = [];
        $daysInMonth = count($this->attendanceData[0]['karyawan'][0]['attendance']);

        foreach ($this->attendanceData as $department) {
            foreach ($department['karyawan'] as $row) {
                $rowData = [
                    $row['nama_lengkap'],
                    $department['department'],
                ];


                $rowData[] = $row['totalT'];
                $rowData[] = $row['presentase'] . '%';
                $rowData[] = $department['total_jumlah_telat']; // Telat Department
                $rowData[] = $department['total_presentase'] . '%'; // Presentase Department
                $rowData[] = $row['menit_telat'];

                // Add attendance days
                foreach ($row['attendance'] as $day) {
                    $rowData[] = $day['status'];
                }

                // Add summary data
                $rowData[] = $row['totalP'];
                $rowData[] = $row['totalT'];
                $rowData[] = $row['totalOff'];
                $rowData[] = $row['totalSakit'];
                $rowData[] = $row['totalIzin'];
                $rowData[] = $row['totalCuti'];
                $rowData[] = $row['totalDinas'];
                $rowData[] = $row['totalH1'];
                $rowData[] = $row['totalH2'];
                $rowData[] = $row['totalMangkir'];
                $rowData[] = $row['totalBlank'];

                $exportData[] = $rowData;
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        $daysInMonth = count($this->attendanceData[0]['karyawan'][0]['attendance']);
        $headings = ['Nama Karyawan', 'Department'];


        $headings[] = 'Telat';
        $headings[] = '% Telat';
        $headings[] = 'Telat Dept';
        $headings[] = '% Dept';
        $headings[] = 'Menit Telat';

        // Add column headings for each day of the month
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $headings[] = $i;
        }

        // Add summary columns
        $headings[] = 'P';
        $headings[] = 'T';
        $headings[] = 'OFF';
        $headings[] = 'S';
        $headings[] = 'I';
        $headings[] = 'C';
        $headings[] = 'D';
        $headings[] = 'H2';
        $headings[] = 'H1';
        $headings[] = 'Mangkir';
        $headings[] = 'Tdk Absen';

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $rowCount = 0; // Initialize row count

                // Styling cells based on status
                $styleArray = [
                    'P' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => 'FFFFFF'], // White
                        ],
                    ],
                    'T' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => 'FFFF00'], // Yellow
                        ],
                    ],
                    'LN' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => 'FFD700'], // Gold
                        ],
                    ],
                    'L' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => '8A2F6F'], // Purple
                        ],
                        'font' => [
                            'color' => ['argb' => 'FFFFFF'], // White text color
                        ],
                    ],

                    'OFF' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => 'FF0000'], // Red
                        ],
                        'font' => [
                            'color' => ['argb' => 'FFFFFF'], // White text color
                        ],
                    ],

                    'C' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => '000000'], // Black
                        ],
                        'font' => [
                            'color' => ['argb' => 'FFFFFF'], // White text color
                        ],
                    ],
                ];

                $startRow = 2; // Start row (assuming the first row is for headings)

                // Loop through each department
                foreach ($this->attendanceData as $department) {
                    $departmentStartRow = $startRow + $rowCount; // Track the starting row for the current department
                    $employeeCount = count($department['karyawan']); // Number of employees in the current department

                    foreach ($department['karyawan'] as $employeeIndex => $employee) {
                        $currentRow = $startRow + $rowCount; // Calculate the current row

                        // Loop through each day's attendance
                        foreach ($employee['attendance'] as $dayIndex => $attendance) {
                            $cellAddress = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(8 + $dayIndex) . $currentRow;
                            $status = $attendance['status'] ?? 'Blank'; // Get the status for each day

                            if (isset($styleArray[$status])) {
                                $sheet->getStyle($cellAddress)->applyFromArray($styleArray[$status]);
                            }
                        }

                        $rowCount++; // Increment row count for each employee
                    }

                    // Merge cells for department headings
                    $departmentEndRow = $departmentStartRow + $employeeCount - 1;
                    if ($departmentStartRow < $departmentEndRow) {
                        $sheet->mergeCells("B{$departmentStartRow}:B{$departmentEndRow}");
                    }

                    // Merge cells for "Telat Department" and "Presentase Department"
                    $telatDepartmentColumnIndex = 5; // "Telat Department" is now the 5th column
                    $presentaseDepartmentColumnIndex = 6; // "Presentase Department" is now the 6th column


                    $sheet->mergeCells(
                        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($telatDepartmentColumnIndex) . $departmentStartRow . ':' .
                            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($telatDepartmentColumnIndex) . $departmentEndRow
                    );

                    $sheet->mergeCells(
                        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($presentaseDepartmentColumnIndex) . $departmentStartRow . ':' .
                            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($presentaseDepartmentColumnIndex) . $departmentEndRow
                    );

                    // Center and middle align the merged cells
                    $alignmentStyle = [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ];

                    $sheet->getStyle("B{$departmentStartRow}:B{$departmentEndRow}")->applyFromArray($alignmentStyle);
                    $sheet->getStyle(
                        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($telatDepartmentColumnIndex) . $departmentStartRow . ':' .
                            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($telatDepartmentColumnIndex) . $departmentEndRow
                    )->applyFromArray($alignmentStyle);
                    $sheet->getStyle(
                        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($presentaseDepartmentColumnIndex) . $departmentStartRow . ':' .
                            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($presentaseDepartmentColumnIndex) . $departmentEndRow
                    )->applyFromArray($alignmentStyle);
                }
            },
        ];
    }
}
