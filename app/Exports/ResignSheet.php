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

class ResignSheet implements FromQuery, WithTitle, WithColumnFormatting, WithCustomStartCell, WithStyles
{
    public function query()
    {
        return DB::table('karyawan')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->leftJoin('department', 'karyawan.kode_dept', '=', 'department.kode_dept')
            ->where('karyawan.status_kar', 'Non-Aktif')
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
                'karyawan.tgl_resign as resign_date',
                DB::raw('TIMESTAMPDIFF(YEAR, karyawan.tgl_masuk, CURDATE()) as work_period'),
                'karyawan.poh',
                'karyawan.base_poh',
                'karyawan.sex',
                'karyawan.birthplace',
                'karyawan.dob as birthday',
                DB::raw('TIMESTAMPDIFF(YEAR, karyawan.dob, CURDATE()) as age'),
                'karyawan.religion',
                DB::raw("CONCAT(' ', karyawan.nik_ktp) as nikktp"),
                DB::raw("CONCAT(' ', karyawan.family_card) as no_kk"),
                'karyawan.address_rt as rt',
                'karyawan.address_rw as rw',
                'karyawan.address_kel as kelurahan',
                'karyawan.address_kec as kecamatan',
                'karyawan.address_kota as kota',
                'karyawan.address_prov as provinsi',
                'karyawan.kode_pos',
                'karyawan.father_name',
                'karyawan.mother_name',
                // Spouse details
                'karyawan.fd_si_name as spouse_name',
                DB::raw("CONCAT(' ', karyawan.fd_si_nik) as spouse_nik"),
                'karyawan.fd_si_kota as spouse_birthplace',
                'karyawan.fd_si_dob as spouse_birthdate',
                // Child 1 details
                'karyawan.fd_anak1_name as child1_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak1_nik) as child1_nik"),
                'karyawan.fd_anak1_kota as child1_birthplace',
                'karyawan.fd_anak1_dob as child1_birthdate',
                // Child 2 details
                'karyawan.fd_anak2_name as child2_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak2_nik) as child2_nik"),
                'karyawan.fd_anak2_kota as child2_birthplace',
                'karyawan.fd_anak2_dob as child2_birthdate',
                // Child 3 details
                'karyawan.fd_anak3_name as child3_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak3_nik) as child3_nik"),
                'karyawan.fd_anak3_kota as child3_birthplace',
                'karyawan.fd_anak3_dob as child3_birthdate',
                // Emergency Contact
                'karyawan.em_name',
                'karyawan.em_telp',
                'karyawan.em_relation',
                'karyawan.em_alamat'
            ])
            ->orderBy('karyawan.id', 'asc');
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'AX'; // Adjust based on total columns

        $sheet->setAutoFilter("A1:{$lastColumn}1");

        // Set peach background - updated to cover all columns
        $sheet->getStyle("A2:{$lastColumn}3")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFDAB9');

        // Add main headers and merge cells for grouped columns
        $mainHeaders = [
            'NO. MESIN', 'NO', 'NAMA PT', 'NIK', 'EMPLOYEE NAME', 'JOB TITLE',
            'DEPARTMENT', 'GRADE', 'EMPLOYEE STATUS', 'JOINED DATE', 'RESIGN DATE',
            'WORK PERIOD', 'POH', 'BASE', 'SEX', 'BIRTHPLACE', 'BIRTHDAY', 'AGE',
            'RELIGION', 'NIK KTP', 'NO KK', 'RT', 'RW', 'KELURAHAN', 'KECAMATAN',
            'KOTA', 'PROVINSI', 'KODE POS', 'FATHER NAME', 'MOTHER NAME',
            'SUAMI/ISTRI', '', '', '',  // 4 columns
            'ANAK 1', '', '', '',       // 4 columns
            'ANAK 2', '', '', '',       // 4 columns
            'ANAK 3', '', '', '',       // 4 columns
            'EMERGENCY CONTACT', '', '', ''  // 4 columns
        ];

        $sheet->fromArray([$mainHeaders], null, 'A2');

        // Merge group headers
        $groupHeaders = [
            'SUAMI/ISTRI' => ['start' => 'AE', 'end' => 'AH', 'title' => 'SUAMI/ISTRI'],
            'ANAK 1' => ['start' => 'AI', 'end' => 'AL', 'title' => 'ANAK 1'],
            'ANAK 2' => ['start' => 'AM', 'end' => 'AP', 'title' => 'ANAK 2'],
            'ANAK 3' => ['start' => 'AQ', 'end' => 'AT', 'title' => 'ANAK 3'],
            'EMERGENCY CONTACT' => ['start' => 'AU', 'end' => 'AX', 'title' => 'EMERGENCY CONTACT']
        ];

        foreach ($groupHeaders as $header) {
            $range = $header['start'] . '2:' . $header['end'] . '2';
            $sheet->mergeCells($range);
            $sheet->setCellValue($header['start'] . '2', $header['title']);
        }

        // Add subheaders
        $subHeaders = array_fill(0, 30, ''); // First 30 columns have no subheaders
        $subHeaders = array_merge($subHeaders, [
            // Spouse subheaders
            'NAMA', 'NIK', 'KOTA', 'TANGGAL LAHIR',
            // Child 1 subheaders
            'NAMA', 'NIK', 'KOTA', 'TANGGAL LAHIR',
            // Child 2 subheaders
            'NAMA', 'NIK', 'KOTA', 'TANGGAL LAHIR',
            // Child 3 subheaders
            'NAMA', 'NIK', 'KOTA', 'TANGGAL LAHIR',
            // Emergency contact subheaders
            'NAME', 'PHONE', 'RELATION', 'ADDRESS'
        ]);

        $sheet->fromArray([$subHeaders], null, 'A3');

        // Center align and other formatting
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

    public function columnFormats(): array
    {
        return [
            'A5:A1000' => NumberFormat::FORMAT_NUMBER,        // NO. MESIN
            'D5:D1000' => NumberFormat::FORMAT_TEXT,          // NIK
            'J5:J1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // JOINED DATE
            'K5:K1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // RESIGN DATE
            'Q5:Q1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // BIRTHDAY
            'T5:T1000' => NumberFormat::FORMAT_TEXT,          // NIK KTP
            'U5:U1000' => NumberFormat::FORMAT_TEXT,          // NO KK
            'AE5:AE1000' => NumberFormat::FORMAT_TEXT,        // SPOUSE NIK
            'AI5:AI1000' => NumberFormat::FORMAT_TEXT,        // CHILD 1 NIK
            'AM5:AM1000' => NumberFormat::FORMAT_TEXT,        // CHILD 2 NIK
            'AQ5:AQ1000' => NumberFormat::FORMAT_TEXT,        // CHILD 3 NIK
            'AF5:AF1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // SPOUSE BIRTHDATE
            'AJ5:AJ1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // CHILD 1 BIRTHDATE
            'AN5:AN1000' => NumberFormat::FORMAT_DATE_DDMMYYYY, // CHILD 2 BIRTHDATE
            'AR5:AR1000' => NumberFormat::FORMAT_DATE_DDMMYYYY  // CHILD 3 BIRTHDATE
        ];
    }

    public function title(): string
    {
        return 'Resign';
    }
}
