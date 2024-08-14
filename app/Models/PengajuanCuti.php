<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_cuti';

    // Define fillable attributes
    protected $fillable = [
        'nik',
        'periode',
        'sisa_cuti',
        'tgl_cuti',
        'tgl_cuti_sampai',
        'jml_hari',
        'sisa_cuti_setelah',
        'kar_ganti',
        'note',
        'jenis',
        'tipe',
    ];

    // Disable timestamps
    public $timestamps = false;
}
