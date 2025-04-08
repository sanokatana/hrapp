<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FamilySheet implements FromQuery, WithTitle, WithColumnFormatting, WithCustomStartCell, WithStyles
{
    public function query()
    {
        return DB::table('karyawan')
            ->where('karyawan.status_kar', 'Aktif')
            ->select([
                DB::raw('ROW_NUMBER() OVER (ORDER BY karyawan.id) as no'),
                'karyawan.nama_lengkap as employee_name',
                DB::raw("CONCAT(' ', karyawan.family_card) as kk"),
                'karyawan.address_rt',
                'karyawan.address_rw',
                'karyawan.address_kel',
                'karyawan.address_kec',
                'karyawan.address_kota',
                'karyawan.address_prov',
                'karyawan.kode_pos',
                'karyawan.father_name',
                'karyawan.mother_name',
                'karyawan.fd_si_name',
                DB::raw("CONCAT(' ', karyawan.fd_si_nik) as nik_si"),
                'karyawan.fd_si_kota',
                'karyawan.fd_si_dob',
                'karyawan.fd_anak1_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak1_nik) as nik_anak1"),
                'karyawan.fd_anak1_kota',
                'karyawan.fd_anak1_dob',
                'karyawan.fd_anak2_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak2_nik) as nik_anak2"),
                'karyawan.fd_anak2_kota',
                'karyawan.fd_anak2_dob',
                'karyawan.fd_anak3_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak3_nik) as nik_anak3"),
                'karyawan.fd_anak3_kota',
                'karyawan.fd_anak3_dob',
                'karyawan.em_name',
                'karyawan.em_telp',
                'karyawan.em_relation',
                'karyawan.em_alamat',
            ])
            ->orderBy('karyawan.id');
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function styles(Worksheet $sheet)
    {
        // Count total columns (32 columns A to AF)
        $lastColumn = 'AF';

        // Add filters to first row
        $sheet->setAutoFilter("A1:{$lastColumn}1");

        // Set peach background for title and date format rows
        $sheet->getStyle("A2:{$lastColumn}3")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFDAB9');

        // Merge cells for EMERGENCY CONTACT
        $sheet->mergeCells('AC2:AF2');
        $sheet->setCellValue('AC2', 'EMERGENCY CONTACT');

        // Add main headers in row 2
        $mainHeaders = [
            'NO', 'EMPLOYEE NAME', 'FAMILY CARD NUMBER', 'RT', 'RW',
            'KELURAHAN', 'KECAMATAN', 'KOTA/KABUPATEN', 'PROVINSI', 'KODE POS',
            'FATHERS NAME', 'MOTHERS NAME',
            'SUAMI/ISTRI', '', '', '',  // 4 columns
            'ANAK 1', '', '', '',       // 4 columns
            'ANAK 2', '', '', '',       // 4 columns
            'ANAK 3', '', '', '',       // 4 columns
            'EMERGENCY CONTACT'         // Already merged above
        ];
        $sheet->fromArray([$mainHeaders], null, 'A2');

        // Add sub headers in row 3
        $subHeaders = [
            '', '', '', '', '', '', '', '', '', '', '', '',
            'NAMA', 'NIK', 'KOTA', 'TANGGAL LAHIR',
            'NAMA', 'NIK', 'KOTA', 'TANGGAL LAHIR',
            'NAMA', 'NIK', 'KOTA', 'TANGGAL LAHIR',
            'NAMA', 'NIK', 'KOTA', 'TANGGAL LAHIR',
            'NAME', 'PHONE', 'RELATION', 'ADDRESS'
        ];
        $sheet->fromArray([$subHeaders], null, 'A3');

        // Merge cells for static headers (columns that don't have sub-headers)
        for ($i = 'A'; $i <= 'L'; $i++) {
            $sheet->mergeCells($i.'2:'.$i.'3');
        }

        // Center align all headers
        $sheet->getStyle("A2:{$lastColumn}3")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Auto-size columns
        foreach(range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Bold formatting for headers
        $sheet->getStyle("A2:{$lastColumn}3")->getFont()->setBold(true);

        // Add borders
        $sheet->getStyle("A2:{$lastColumn}3")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]]
        ];
    }

    private function getHeadings(): array
    {
        return [
            'NO',
            'EMPLOYEE NAME',
            'FAMILY CARD NUMBER',
            'RT',
            'RW',
            'KELURAHAN',
            'KECAMATAN',
            'KOTA/KABUPATEN',
            'PROVINSI',
            'KODE POS',
            'FATHERS NAME',
            'MOTHERS NAME',
            'SUAMI/ISTRI',
            'NIK',
            'KOTA',
            'TANGGAL LAHIR',
            'ANAK 1',
            'NIK',
            'KOTA',
            'TANGGAL LAHIR',
            'ANAK 2',
            'NIK',
            'KOTA',
            'TANGGAL LAHIR',
            'ANAK 3',
            'NIK',
            'KOTA',
            'TANGGAL LAHIR',
            'EMERGENCY NAME',
            'EMERGENCY PHONE',
            'EMERGENCY RELATION',
            'EMERGENCY ADDRESS',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C5:C1000' => NumberFormat::FORMAT_TEXT,          // KK
            'N5:N1000' => NumberFormat::FORMAT_TEXT,          // NIK SUAMI/ISTRI
            'P5:P1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // TGL LAHIR SUAMI/ISTRI
            'R5:R1000' => NumberFormat::FORMAT_TEXT,          // NIK ANAK 1
            'T5:T1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // TGL LAHIR ANAK 1
            'V5:V1000' => NumberFormat::FORMAT_TEXT,          // NIK ANAK 2
            'X5:X1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // TGL LAHIR ANAK 2
            'Z5:Z1000' => NumberFormat::FORMAT_TEXT,          // NIK ANAK 3
            'AB5:AB1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // TGL LAHIR ANAK 3
            'AD5:AD1000' => NumberFormat::FORMAT_TEXT,        // EMERGENCY PHONE
        ];
    }

    public function title(): string
    {
        return 'Family';
    }
}
