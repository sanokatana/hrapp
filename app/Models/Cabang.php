<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $fillable = [
        'company_id',
        'kode',
        'nama',
        'alamat',
        'kota',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function jabatans()
    {
        return $this->hasMany(Jabatan::class);
    }

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function locations()
    {
        return $this->hasMany(KonfigurasiLokasi::class, 'cabang_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cabang_user')->withTimestamps();
    }
}
