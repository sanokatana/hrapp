<?php

namespace App\Exports;

use App\Models\Cuti;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;
use App\Helpers\DateHelper;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SisaCutiExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    use Exportable;

    private $uniqueYears;

    public function __construct()
    {
        // Fetch distinct years for the dynamic year columns
        $this->uniqueYears = Cuti::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'asc')
            ->pluck('tahun')
            ->toArray();
    }

    public function collection()
    {
        $cutis = Cuti::all();
        $data = [];

        // Loop through the cutis data and build the rows
        foreach ($cutis as $cuti) {
            $karyawan = Karyawan::where('nik', $cuti->nik)->first();
            if (!$karyawan || !$karyawan->nik) { // Skip if there's no valid nik
                continue;
            }

            $tglMasuk = Carbon::parse($karyawan->tgl_masuk);
            $monthsOfService = round(Carbon::now()->diffInDays($tglMasuk) / 365 * 12);
            $tglMasukFormatted = DateHelper::formatIndonesiaDate($karyawan->tgl_masuk);
            $status = strtoupper($karyawan->employee_status);

            $row = [
                'NO' => $cuti->id,
                'NAMA' => $karyawan->nama_lengkap,
                'STATUS' => $status,
                'TGL MASUK' => $tglMasukFormatted,
                'LAMA (BULAN)' => $monthsOfService,
            ];

            // Adding the year columns for each year
            foreach ($this->uniqueYears as $year) {
                $sisaCuti = $cutis->where('tahun', $year)->where('nik', $cuti->nik)->first()->sisa_cuti ?? 0;
                $row[$year] = $sisaCuti;
            }

            $data[] = $row;
        }

        return collect($data);
    }

    public function headings(): array
    {
        // The first row includes column headings (NO, NAMA, STATUS, etc.)
        $headings = [
            'NO',
            'NAMA',
            'STATUS',
            'TGL MASUK',
            'LAMA (BULAN)',
        ];

        // Add a placeholder for the years (these will be added to the second row)
        foreach ($this->uniqueYears as $year) {
            $headings[] = ''; // Placeholder for dynamic year columns
        }

        return $headings;
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '@',
            'D' => 'dd/mm/yyyy',
            'E' => '#,##0',
        ];
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            // Merge the header for "SISA CUTI" across all year columns
            $startColumn = 'F';
            $endColumn = chr(ord($startColumn) + count($this->uniqueYears) - 1);
            $sheet->mergeCells("{$startColumn}1:{$endColumn}1");
            $sheet->setCellValue("{$startColumn}1", "SISA CUTI");

            // Add the years to the second row (starting from column F)
            $column = $startColumn;
            foreach ($this->uniqueYears as $year) {
                $sheet->setCellValue("{$column}2", $year);
                $column++;
            }

            // Apply styles to the header and years row
            $sheet->getStyle("A1:{$endColumn}2")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle("A1:{$endColumn}1")->getFont()->setBold(true); // Bold for the "SISA CUTI" header
            $sheet->getStyle("A2:{$endColumn}2")->getFont()->setBold(true); // Bold for the years

            // Ensure columns A-E are aligned to the center for row 2 (years)
            $sheet->getStyle("A2:E2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Start inserting data from row 3 (not overwriting row 2)
            $startingRow = 3;

            // Insert the data into the sheet starting from row 3
            $rowIndex = $startingRow;
            foreach ($this->collection() as $dataRow) {
                $columnIndex = 'A';
                foreach ($dataRow as $value) {
                    $sheet->setCellValue("{$columnIndex}{$rowIndex}", $value);
                    $columnIndex++;
                }
                $rowIndex++;
            }

            // Apply middle alignment to all the data (start from row 3)
            $sheet->getStyle("A{$startingRow}:{$endColumn}".($rowIndex-1))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        },
    ];
}

}
