<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;
use App\Helpers\DateHelper;

class ParklaringExport implements FromCollection, WithHeadings, WithEvents
{
    protected $parklar;

    public function __construct($parklar)
    {
        $this->parklar = $parklar;
    }

    public function collection()
    {
        $counter = 0;

        return collect($this->parklar)->map(function ($row) use (&$counter) {
            $counter++;

            // Format dates in Indonesian format
            $tgl_masuk_indo = DateHelper::formatIndonesiaDate($row->tgl_masuk);
            $tgl_terakhir_indo = DateHelper::formatIndonesiaDate($row->tgl_terakhir);

            // Format masa kerja as range of dates in Indonesian
            $masa_kerja = $tgl_masuk_indo . ' - ' . $tgl_terakhir_indo;

            // Format company names with PT prefix
            $namaPT_caps = 'PT ' . strtoupper($row->nama_pt ?? '');
            $namaPT_title = 'PT ' . ucwords(strtolower($row->nama_pt ?? ''));

            return [
                'no' => $counter,
                'nama_pt_caps' => $namaPT_caps,
                'no_parklaring' => $row->no_parklaring,
                'nama_lengkap' => $row->nama_lengkap,
                'nik' => $row->nik,
                'nama_pt_title' => $namaPT_title,
                'jabatan' => $row->jabatan,
                'masa_kerja' => $masa_kerja,
                'waktu' => $tgl_terakhir_indo
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama PT',
            'Nomor Surat',
            'Nama',
            'No KTP',
            'Nama PT Font Kecil',
            'Jabatan',
            'Masa Kerja',
            'Waktu'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;

                // Define the worksheet range
                $lastColumn = 'I';
                $lastRow = count($this->parklar) + 1; // +1 for header row

                // Style the header row
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'FF4F81BD'], // Blue header
                    ]
                ]);

                // Add borders to all cells
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Set row height
                $sheet->getRowDimension(1)->setRowHeight(20);

                // Auto-size columns for better readability
                foreach (range('A', $lastColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Set alignment
                $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Set number column to center alignment
                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I2:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Apply zebra striping
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A'.$row.':'.$lastColumn.$row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'color' => ['argb' => 'FFEDF1F5'],
                            ],
                        ]);
                    }
                }
            }
        ];
    }
}
