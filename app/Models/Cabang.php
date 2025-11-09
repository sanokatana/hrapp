<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    public function scopeForCompany($query, $companyId)
    {
        return $query->where($this->getTable() . '.company_id', $companyId);
    }

    public function scopeForSelected(Request $request, $query)
    {
        if ($companyId = $request->session()->get('selected_company_id')) {
            $query->where($this->getTable() . '.company_id', $companyId);
        }
        if ($cabangId = $request->session()->get('selected_cabang_id')) {
            $query->where($this->getTable() . '.id', $cabangId);
        }
        return $query;
    }
}
