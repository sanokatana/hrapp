<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuanizin extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izin';

    // Define fillable attributes
    protected $fillable = [
        'nik',
        'nip',
        'tgl_izin',
        'tgl_izin_akhir',
        'jml_hari',
        'status',
        'pukul',
        'keterangan',
        'tgl_create',
        'foto',
        'jenis',
    ];

    // Disable timestamps
    public $timestamps = false;
}
