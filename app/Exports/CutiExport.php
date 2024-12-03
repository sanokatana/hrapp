<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
class CutiExport implements FromCollection, WithHeadings
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
        $data = DB::table('pengajuan_cuti')
            ->leftJoin('karyawan', 'pengajuan_cuti.nik', '=', 'karyawan.nik')
            ->leftJoin('tipe_cuti', 'pengajuan_cuti.tipe', '=', 'tipe_cuti.id_tipe_cuti') // Join with tipe_cuti table
            ->select(
                'pengajuan_cuti.nik',
                'karyawan.nama_lengkap',
                'pengajuan_cuti.periode',
                'pengajuan_cuti.sisa_cuti',
                'pengajuan_cuti.tgl_cuti',
                'pengajuan_cuti.tgl_cuti_sampai',
                'pengajuan_cuti.jml_hari',
                'pengajuan_cuti.sisa_cuti_setelah',
                'pengajuan_cuti.kar_ganti',
                'pengajuan_cuti.note',
                'pengajuan_cuti.jenis',
                'tipe_cuti.tipe_cuti as tipe_name', // Get tipe_cuti name
                'pengajuan_cuti.status_approved',
                'pengajuan_cuti.status_approved_hrd',
                'pengajuan_cuti.status_management'
            )
            ->whereMonth('pengajuan_cuti.tgl_cuti', $this->bulan)
            ->orderBy('pengajuan_cuti.tgl_cuti', 'ASC')
            ->whereYear('pengajuan_cuti.tgl_cuti_sampai', $this->tahun)
            ->get();

        // Map data to include the status text and approval text
        return $data->map(function ($item) {
            $item->status_approved = $this->getApprovalStatus($item->status_approved); // Map approval status
            $item->status_approved_hrd = $this->getApprovalStatus($item->status_approved_hrd); // Map HRD approval status
            $item->status_management = $this->getApprovalStatus($item->status_management); // Map Management approval status
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
            'Periode Cuti',
            'Sisa Cuti',
            'Tanggal Cuti',
            'Tanggal Cuti Sampai',
            'Jumlah Hari',
            'Sisa Cuti Setelah',
            'Karyawan Pengganti',
            'Note',
            'Jenis',
            'Tipe Cuti',
            'Status Disetujui',
            'Status Disetujui HRD',
            'Status Disetujui Management',
        ];
    }
}
