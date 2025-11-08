<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Cabang;
use App\Models\Company;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\KonfigurasiLokasi;
use App\Models\Presensi;

class Karyawan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'karyawan';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'email',
        'no_hp',
        'tgl_masuk',
        'tgl_resign',
        'department_id',
        'jabatan_id',
        'pt_id',
        'company_id',
        'cabang_id',
        'lokasi_id',
        'status_kar',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_resign' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(KonfigurasiLokasi::class, 'lokasi_id');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }
}
