<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class AttendanceExport implements FromArray, WithHeadings, WithEvents
{
    protected $attendanceData;
    protected $daysInMonth;

    public function __construct($attendanceData)
    {
        $this->attendanceData = $attendanceData;

        // Calculate days in month safely
        $this->daysInMonth = Carbon::now()->daysInMonth;

        // If we have attendance data, try to get the days from the data
        if (!empty($attendanceData)) {
            foreach ($attendanceData as $dept) {
                if (!empty($dept['karyawan'])) {
                    foreach ($dept['karyawan'] as $emp) {
                        if (isset($emp['attendance'])) {
                            $this->daysInMonth = count($emp['attendance']);
                            break 2; // Exit both loops once we find valid data
                        }
                    }
                }
            }
        }
    }

    public function array(): array
    {
        $exportData = [];

        // If no data, return empty array
        if (empty($this->attendanceData)) {
            return [['No data available']];
        }

        foreach ($this->attendanceData as $department) {
            foreach ($department['karyawan'] as $row) {
                $rowData = [
                    $row['nama_lengkap'],
                    $department['department'],
                ];

                $rowData[] = $row['totalT'] ?? 0;
                $rowData[] = isset($row['presentase']) ? $row['presentase'] . '%' : '0%';
                $rowData[] = $department['total_jumlah_telat'] ?? 0; // Telat Department
                $rowData[] = isset($department['total_presentase']) ? $department['total_presentase'] . '%' : '0%'; // Presentase Department
                $rowData[] = $row['menit_telat'] ?? 0;

                // Add attendance days
                if (isset($row['attendance']) && is_array($row['attendance'])) {
                    foreach ($row['attendance'] as $day) {
                        $rowData[] = $day['status'] ?? '';
                    }
                } else {
                    // Handle missing attendance data
                    for ($i = 1; $i <= $this->daysInMonth; $i++) {
                        $rowData[] = '';
                    }
                }

                // Add summary data with null checks
                $rowData[] = $row['totalP'] ?? 0;
                $rowData[] = $row['totalT'] ?? 0;
                $rowData[] = $row['totalOff'] ?? 0;
                $rowData[] = $row['totalSakit'] ?? 0;
                $rowData[] = $row['totalIzin'] ?? 0;
                $rowData[] = $row['totalCuti'] ?? 0;
                $rowData[] = $row['totalDinas'] ?? 0;
                $rowData[] = $row['totalH1'] ?? 0;
                $rowData[] = $row['totalH2'] ?? 0;
                $rowData[] = $row['totalMangkir'] ?? 0;
                $rowData[] = $row['totalBlank'] ?? 0;

                $exportData[] = $rowData;
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        // Use the previously calculated daysInMonth instead of trying to get it from the data
        $headings = ['Nama Karyawan', 'Department'];

        $headings[] = 'Telat';
        $headings[] = '% Telat';
        $headings[] = 'Telat Dept';
        $headings[] = '% Dept';
        $headings[] = 'Menit Telat';

        // Add column headings for each day of the month
        for ($i = 1; $i <= $this->daysInMonth; $i++) {
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
                    'D' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => 'e3c314'], // Gold
                        ],
                    ],
                    'I' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => 'F6CF92'], // Gold
                        ],
                    ],
                    'S' => [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => '43d443'], // Gold
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

                // Calculate the total number of rows and columns
                $totalRows = 1; // Start with header row
                foreach ($this->attendanceData as $department) {
                    $totalRows += count($department['karyawan']);
                }

                $totalColumns = count($this->headings());
                $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);

                // Add borders to all cells including headers
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                // Apply borders to all cells in the range
                $sheet->getStyle('A1:' . $lastColumn . ($totalRows + 1))->applyFromArray($borderStyle);

                // Loop through each department
                foreach ($this->attendanceData as $department) {
                    $departmentStartRow = $startRow + $rowCount; // Track the starting row for the current department
                    $employeeCount = count($department['karyawan']); // Number of employees in the current department

                    // Skip departments with no employees
                    if ($employeeCount == 0) {
                        continue;
                    }

                    foreach ($department['karyawan'] as $employeeIndex => $employee) {
                        $currentRow = $startRow + $rowCount; // Calculate the current row

                        // Check if attendance array exists before looping
                        if (isset($employee['attendance']) && is_array($employee['attendance'])) {
                            // Loop through each day's attendance
                            foreach ($employee['attendance'] as $dayIndex => $attendance) {
                                $cellAddress = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(8 + $dayIndex) . $currentRow;
                                $status = $attendance['status'] ?? 'Blank'; // Get the status for each day

                                if (isset($styleArray[$status])) {
                                    $sheet->getStyle($cellAddress)->applyFromArray($styleArray[$status]);

                                    // Make sure borders are not overridden
                                    $sheet->getStyle($cellAddress)->applyFromArray([
                                        'borders' => [
                                            'allBorders' => [
                                                'borderStyle' => Border::BORDER_THIN,
                                                'color' => ['argb' => '000000'],
                                            ],
                                        ],
                                    ]);
                                }
                            }
                        }

                        $rowCount++; // Increment row count for each employee
                    }

                    // Merge cells for department headings ONLY if there's more than one employee
                    $departmentEndRow = $departmentStartRow + $employeeCount - 1;
                    if ($departmentStartRow < $departmentEndRow) {
                        $sheet->mergeCells("B{$departmentStartRow}:B{$departmentEndRow}");

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
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['argb' => '000000'],
                                ],
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
                }

                // Apply center alignment to all cells for better readability
                $sheet->getStyle('A1:' . $lastColumn . ($totalRows + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Auto-size columns for better readability
                foreach (range('A', $lastColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
