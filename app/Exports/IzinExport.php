<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;

class IzinExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    /**
     * Retrieve the data for the export.
     */
    public function collection(): Collection
{
    $data = DB::table('pengajuan_izin')
        ->leftJoin('karyawan as karyawan1', 'pengajuan_izin.nik', '=', 'karyawan1.nik') // The employee (karyawan)
        ->leftJoin('jabatan as jabatan1', 'karyawan1.jabatan', '=', 'jabatan1.id') // Employee's jabatan
        ->leftJoin('karyawan as karyawan2', 'jabatan1.jabatan_atasan', '=', 'karyawan2.jabatan') // Supervisor (atasan) of employee
        ->select(
            'pengajuan_izin.nik',
            'karyawan1.nama_lengkap',
            'karyawan2.nama_lengkap as jabatan_atasan', // Getting supervisor's name
            'pengajuan_izin.tgl_izin',
            'pengajuan_izin.tgl_izin_akhir',
            'pengajuan_izin.jml_hari',
            'pengajuan_izin.tgl_create',
            'pengajuan_izin.status',
            'pengajuan_izin.pukul',
            'pengajuan_izin.keterangan',
            'pengajuan_izin.keputusan',
            'pengajuan_izin.tgl_jadwal_off',
            'pengajuan_izin.status_approved',
            'pengajuan_izin.status_approved_hrd'
        )
        ->whereMonth('pengajuan_izin.tgl_izin', $this->bulan)
        ->whereYear('pengajuan_izin.tgl_izin', $this->tahun)
        ->orderBy('karyawan1.nama_lengkap','asc')
        ->get();

    // Map data to include the status text and approval text
    return $data->map(function ($item) {
        $item->status = DateHelper::getStatusText($item->status); // Convert status code to text
        $item->status_approved = $this->getApprovalStatus($item->status_approved); // Map approval status
        $item->status_approved_hrd = $this->getApprovalStatus($item->status_approved_hrd); // Map HRD approval status
        return $item;
    });
}


    /**
     * Convert approval status codes to text.
     */
    private function getApprovalStatus($status): string
    {
        switch ($status) {
            case 1:
                return 'Approved';
            case 0:
                return 'Pending';
            case 2:
                return 'Declined';
            case 3:
                return 'Cancelled';
            default:
                return 'Unknown';
        }
    }

    /**
     * Define the headings for the export.
     */
    public function headings(): array
    {
        return [
            'NIK',
            'Nama Lengkap',
            'Jabatan Atasan', // Added supervisor's name
            'Tanggal Izin',
            'Tanggal Izin Akhir',
            'Jumlah Hari',
            'Tanggal Buat',
            'Status',
            'Pukul',
            'Keterangan',
            'Keputusan',
            'Tanggal Jadwal Off',
            'Status Disetujui',
            'Status Disetujui HRD',
        ];
    }

}

