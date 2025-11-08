<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Cabang;
use App\Models\Company;

class KonfigurasiLokasi extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_lokasi';

    protected $fillable = [
        'nama_kantor',
        'latitude',
        'longitude',
        'radius',
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
}
