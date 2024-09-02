<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KaryawanExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    /**
     * Return a collection of data to be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Modify the data before exporting
        $karyawans = Karyawan::all();

        return $karyawans->map(function ($karyawan) {
            $karyawan->nik_ktp = "'" . $karyawan->nik_ktp; // Prefix with a single quote to treat as text
            $karyawan->family_card = "'" . $karyawan->family_card; // Prefix with a single quote to treat as text
            return $karyawan;
        });
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
            'EM Alamat'
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
            'AZ' => NumberFormat::FORMAT_NUMBER, // Format 'FD Anak1 NIK' as a number
            'AV' => NumberFormat::FORMAT_NUMBER, // Format 'FD SI' as a number
            'BD' => NumberFormat::FORMAT_NUMBER, // Format 'FD Anak2 NIK' as a number
            'BH' => NumberFormat::FORMAT_NUMBER, // Format 'FD Anak3 NIK' as a number
        ];
    }
}
