<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class DatabaseSheet implements FromQuery, WithTitle, WithColumnFormatting, WithCustomStartCell, WithStyles
{
    public function startCell(): string
    {
        return 'A5'; // Data starts at row 5
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'AJ'; // Updated to account for 2 additional columns

        // Add filters to first row
        $sheet->setAutoFilter("A1:{$lastColumn}1");

        // Set peach background
        $sheet->getStyle("A2:{$lastColumn}3")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFDAB9');

        // Add main headers and merge EDUCATION columns
        $mainHeaders = $this->getMainHeaders();
        $sheet->fromArray([$mainHeaders], null, 'A2');

        // Merge EDUCATION header cells
        $sheet->mergeCells('AA2:AC2');
        $sheet->setCellValue('AA2', 'EDUCATION');

        // Add subheaders
        $subHeaders = $this->getSubHeaders();
        $sheet->fromArray([$subHeaders], null, 'A3');

        // Rest of styling
        $sheet->getStyle("A2:{$lastColumn}3")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        foreach(range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]]
        ];
    }

    public function query()
    {
        return DB::table('karyawan')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->leftJoin('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->where('karyawan.status_kar', 'Aktif')
            ->select([
                'karyawan.nip',
                DB::raw('ROW_NUMBER() OVER (ORDER BY karyawan.id) as no'),
                'karyawan.nama_pt',
                'karyawan.nik',
                'karyawan.nama_lengkap as employee_name',
                'jabatan.nama_jabatan as job_title',
                'department.nama_dept as department',
                'karyawan.grade',
                'karyawan.employee_status',
                'karyawan.tgl_masuk as joined_date',
                DB::raw('CONCAT(
                    FLOOR(TIMESTAMPDIFF(MONTH, karyawan.tgl_masuk, CURDATE()) / 12),
                    " Tahun ",
                    MOD(TIMESTAMPDIFF(MONTH, karyawan.tgl_masuk, CURDATE()), 12),
                    " Bulan"
                ) as work_period'),
                'karyawan.poh',
                'karyawan.base_poh',
                'karyawan.sex',
                DB::raw("'Indonesia' as nationality"),
                'karyawan.birthplace',
                'karyawan.dob as birthday',
                DB::raw('TIMESTAMPDIFF(YEAR, karyawan.dob, CURDATE()) as age'),
                'karyawan.religion',
                DB::raw("CONCAT(' ', karyawan.nik_ktp) as ktp"),
                DB::raw("CONCAT(karyawan.address, ' RT ', karyawan.address_rt, ' RW ', karyawan.address_rw,
                    ' Kel.', karyawan.address_kel, ' Kec.', karyawan.address_kec,
                    ' ', karyawan.address_kota, ' ', karyawan.address_prov, ' ', karyawan.kode_pos) as address"),
                DB::raw("CONCAT(' ', karyawan.no_npwp) as npwp"),
                'karyawan.alamat_npwp',
                'karyawan.email_personal',
                'karyawan.email',
                'karyawan.blood_type',
                'karyawan.gelar as strata',
                'karyawan.major',
                'karyawan.kampus as school',
                'karyawan.job_exp',
                'karyawan.bpjstk',
                'karyawan.bpjskes',
                'karyawan.rek_no',
                'karyawan.bank_name',
                'karyawan.rek_name',
                'karyawan.status_kar as keterangan'
            ])
            ->orderBy('karyawan.id');
    }

    private function getMainHeaders(): array
    {
        return [
            'NO. MESIN', 'NO', 'NAMA PT', 'NIK', 'EMPLOYEE NAME', 'JOB TITLE',
            'DEPARTMENT', 'GRADE', 'EMPLOYEE STATUS', 'JOINED DATE', 'WORK PERIOD',
            'POH', 'BASE', 'SEX', 'NATIONALITY', 'BIRTHPLACE', 'BIRTHDAY', 'AGE',
            'RELIGION', 'NIK', 'ADDRESS', 'NPWP', 'ALAMAT NPWP', 'ALAMAT EMAIL PRIBADI',
            'ALAMAT EMAIL KANTOR', 'BLOOD TYPE', 'EDUCATION', '', '', // Merged cells for EDUCATION
            'JOB EXPERIENCE', 'BPJSTK', 'BPJSKES', 'NO REK', 'BANK NAME', 'REK. NAME',
            'KETERANGAN'
        ];
    }

    private function getSubHeaders(): array
    {
        $headers = array_fill(0, count($this->getMainHeaders()), ''); // Initialize all with empty strings

        // Set date format subheaders
        $headers[9] = 'DD/MM/YY';  // JOINED DATE
        $headers[16] = 'DD/MM/YY'; // BIRTHDAY

        // Set education subheaders
        $headers[26] = 'STRATA';
        $headers[27] = 'MAJOR';
        $headers[28] = 'SCHOOL/UNIVERSITY';

        return $headers;
    }

    public function columnFormats(): array
    {
        return [
            'A5:A1000' => NumberFormat::FORMAT_NUMBER,        // NO. MESIN
            'D5:D1000' => NumberFormat::FORMAT_TEXT,          // NIK
            'T5:T1000' => NumberFormat::FORMAT_TEXT,          // NIK KTP
            'V5:V1000' => NumberFormat::FORMAT_TEXT,          // NPWP
            'AD5:AD1000' => NumberFormat::FORMAT_TEXT,        // NO REK
            'K5:K1000' => NumberFormat::FORMAT_TEXT,          // WORK PERIOD
            'J5:J1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // JOINED DATE
            'Q5:Q1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // BIRTHDAY
        ];
    }

    public function title(): string
    {
        return 'Database';
    }
}
