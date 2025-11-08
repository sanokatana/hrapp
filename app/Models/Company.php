<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'tb_pt';

    protected $fillable = [
        'short_name',
        'long_name',
    ];

    public function cabang()
    {
        return $this->hasMany(Cabang::class);
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

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')->withTimestamps();
    }
}
