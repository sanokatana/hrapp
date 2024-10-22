<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SK extends Model
{
    use HasFactory;

    protected $table = 'tb_sk';

    // Disable automatic timestamps
    protected $fillable = [
        'nik',
        'nama_karyawan',
        'no_sk',
        'tgl_sk',
        'nama_pt',
        'masa_probation',
        'diketahui',
        'status',
        'file_sk',
        'created_by',
    ];
    public $timestamps = false;
}
