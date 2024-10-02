<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KaryawanExport implements FromQuery, WithHeadings, WithColumnFormatting
{
    /**
     * Return a query for the data to be exported.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return DB::table('karyawan')
            ->leftJoin('jabatan', 'karyawan.jabatan', '=', 'jabatan.id')
            ->select([
                'karyawan.id as ID',
                'karyawan.nik',
                'karyawan.nip',
                'karyawan.nama_lengkap',
                'karyawan.jabatan',
                'karyawan.email',
                'karyawan.no_hp',
                'karyawan.tgl_masuk as Tanggal Masuk',
                'karyawan.tgl_resign as Tanggal Resign',
                'karyawan.dob as DOB',
                'karyawan.kode_dept',
                'karyawan.grade',
                'karyawan.shift_pattern_id',
                'karyawan.start_shift',
                'karyawan.no_kontrak',
                'karyawan.employee_status',
                'karyawan.base_poh',
                'karyawan.nama_pt',
                'karyawan.sex',
                'karyawan.marital_status',
                'karyawan.birthplace',
                'karyawan.religion',
                'karyawan.address',
                'karyawan.address_rt',
                'karyawan.address_rw',
                'karyawan.address_kel',
                'karyawan.address_kec',
                'karyawan.address_kota',
                'karyawan.address_prov',
                'karyawan.kode_pos',
                DB::raw("CONCAT(' ', karyawan.nik_ktp) as nik_ktp"),
                'karyawan.blood_type',
                'karyawan.gelar',
                'karyawan.major',
                'karyawan.kampus',
                'karyawan.job_exp',
                'karyawan.email_personal',
                DB::raw("CONCAT(' ', karyawan.family_card) as family_card"),
                'karyawan.no_npwp',
                'karyawan.alamat_npwp',
                'karyawan.bpjstk',
                'karyawan.bpjskes',
                'karyawan.rek_no',
                'karyawan.bank_name',
                'karyawan.rek_name',
                'karyawan.father_name',
                'karyawan.mother_name',
                'karyawan.fd_si_name',
                DB::raw("CONCAT(' ', karyawan.fd_si_nik) as fd_si_nik"),
                'karyawan.fd_si_kota',
                'karyawan.fd_si_dob',
                'karyawan.fd_anak1_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak1_nik) as fd_anak1_nik"),
                'karyawan.fd_anak1_kota',
                'karyawan.fd_anak1_dob',
                'karyawan.fd_anak2_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak2_nik) as fd_anak2_nik"),
                'karyawan.fd_anak2_kota',
                'karyawan.fd_anak2_dob',
                'karyawan.fd_anak3_name',
                DB::raw("CONCAT(' ', karyawan.fd_anak3_nik) as fd_anak3_nik"),
                'karyawan.fd_anak3_kota',
                'karyawan.fd_anak3_dob',
                'karyawan.em_name',
                'karyawan.em_telp',
                'karyawan.em_relation',
                'karyawan.em_alamat',
                'karyawan.status_kar'
            ])
            ->orderBy('karyawan.id'); // Ensure the query results are ordered

    }

    /**
     * Define the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'NIK',
            'NIP',
            'Nama Lengkap',
            'Jabatan',
            'Email',
            'No HP',
            'Tanggal Masuk',
            'Tanggal Resign',
            'DOB',
            'Kode Dept',
            'Grade',
            'Shift Pattern ID',
            'Start Shift',
            'No Kontrak',
            'Employee Status',
            'Base POH',
            'Nama PT',
            'Sex',
            'Marital Status',
            'Birthplace',
            'Religion',
            'Address',
            'Address RT',
            'Address RW',
            'Address Kel',
            'Address Kec',
            'Address Kota',
            'Address Prov',
            'Kode Pos',
            'NIK KTP',
            'Blood Type',
            'Gelar',
            'Major',
            'Kampus',
            'Job Exp',
            'Email Personal',
            'Family Card',
            'No NPWP',
            'Alamat NPWP',
            'BPJSTK',
            'BPJSKES',
            'Rek No',
            'Bank Name',
            'Rek Name',
            'Father Name',
            'Mother Name',
            'FD SI Name',
            'FD SI NIK',
            'FD SI Kota',
            'FD SI DOB',
            'FD Anak1 Name',
            'FD Anak1 NIK',
            'FD Anak1 Kota',
            'FD Anak1 DOB',
            'FD Anak2 Name',
            'FD Anak2 NIK',
            'FD Anak2 Kota',
            'FD Anak2 DOB',
            'FD Anak3 Name',
            'FD Anak3 NIK',
            'FD Anak3 Kota',
            'FD Anak3 DOB',
            'EM Name',
            'EM Telp',
            'EM Relation',
            'EM Alamat',
            'Status Karyawan'
        ];
    }

    /**
     * Apply column formatting.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER, // Format 'No HP' as a number
            'AD' => NumberFormat::FORMAT_TEXT, // Format 'NIK KTP' as text
            'AP' => NumberFormat::FORMAT_NUMBER, // Format 'Rek No' as a number
            'AK' => NumberFormat::FORMAT_TEXT, // Format 'Family Card' as a number
            'AZ' => NumberFormat::FORMAT_TEXT, // Format 'FD Anak1 NIK' as a number
            'AV' => NumberFormat::FORMAT_TEXT, // Format 'FD SI' as a number
            'BD' => NumberFormat::FORMAT_TEXT, // Format 'FD Anak2 NIK' as a number
            'BH' => NumberFormat::FORMAT_TEXT, // Format 'FD Anak3 NIK' as a number
        ];
    }
}
