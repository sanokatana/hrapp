<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KontrakExport implements FromQuery, WithHeadings
{
    /**
     * Return a query for the data to be exported.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return DB::table('kontrak')
            ->leftJoin('karyawan', 'kontrak.nik', '=', 'karyawan.nik')
            ->select([
                'kontrak.id as ID',
                'kontrak.nik',
                'karyawan.nama_lengkap',
                'kontrak.start_date',
                'kontrak.end_date',
                'kontrak.contract_type',
                'kontrak.position',
                'kontrak.salary',
                'kontrak.status',
            ])
            ->orderBy('kontrak.start_date'); // Ensure the query results are ordered

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
            'Nama Karyawan',
            'Tanggal Mulai',
            'Tanggal Akhir',
            'Tipe Kontrak',
            'Posisi Jabatan',
            'Gaji',
            'Status Kontrak',
        ];
    }

    /**
     * Apply column formatting.
     *
     * @return array
     */

}
