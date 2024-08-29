<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TimeExport implements FromArray, WithHeadings, WithEvents
{
    protected $attendanceData;

    public function __construct($attendanceData)
    {
        $this->attendanceData = $attendanceData;
    }

    public function array(): array
    {
        $exportData = [];

        foreach ($this->attendanceData as $department) {
            foreach ($department['karyawan'] as $row) {
                $rowData = [
                    $row['nama_lengkap'],
                    $department['department'],
                ];

                // Add attendance days
                foreach ($row['attendance'] as $day) {
                    $rowData[] = $day['hours'];
                }

                // Add summary attendance data
                $rowData[] = $row['total_jam_kerja'];
                $rowData[] = $department['total_hours']; // Total department hours

                $exportData[] = $rowData;
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        $daysInMonth = count($this->attendanceData[0]['karyawan'][0]['attendance']);
        $headings = ['Nama Karyawan', 'Department'];

        // Add column headings for each day of the month
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $headings[] = $i;
        }

        // Add summary columns
        $headings[] = 'Total Jam Kerja';
        $headings[] = 'Total Jam Kerja Department';

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $rowCount = 0; // Initialize row count
                $startRow = 2; // Start row (assuming the first row is for headings)

                // Loop through each department
                foreach ($this->attendanceData as $department) {
                    $departmentStartRow = $startRow + $rowCount; // Track the starting row for the current department
                    $employeeCount = count($department['karyawan']); // Number of employees in the current department

                    // Merge cells for department headings
                    $departmentEndRow = $departmentStartRow + $employeeCount - 1;
                    if ($employeeCount > 1) { // Merge only if there are multiple rows
                        $sheet->mergeCells("B{$departmentStartRow}:B{$departmentEndRow}");

                        // Merge cells for total department hours
                        $telatDepartmentColumnIndex = count($department['karyawan'][0]['attendance']) + 4; // Adjusted index
                        $sheet->mergeCells(
                            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($telatDepartmentColumnIndex) . $departmentStartRow . ':' .
                            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($telatDepartmentColumnIndex) . $departmentEndRow
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
                    }

                    $rowCount += $employeeCount; // Update row count for the next department
                }
            },
        ];
    }
}
