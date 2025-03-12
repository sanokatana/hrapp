<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KontrakExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['123456', 'CONT/2024/001', '22', '21 Februari 2024', '21 Februari 2025', 'PKWT', 'Staff'],
            ['123457', 'CONT/2024/002', '22', '21/02/2024', '21/02/2025', 'PKWT', 'Officer']
        ];
    }

    public function headings(): array
    {
        return ['nik', 'no_kontrak', 'hari_kerja', 'start_date', 'end_date', 'contract_type', 'position'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'D:E' => ['numberFormat' => ['formatCode' => 'dd mmmm yyyy']],
        ];
    }
}