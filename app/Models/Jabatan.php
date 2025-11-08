<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Cabang;
use App\Models\Company;
use App\Models\Department;
use App\Models\Karyawan;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = "jabatan";

    protected $fillable = [
        'department_id',
        'nama',
        'level',
        'company_id',
        'cabang_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
