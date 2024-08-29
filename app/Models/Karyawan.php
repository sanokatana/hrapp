<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Karyawan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "karyawan";
    protected $primaryKey = "nik";
    protected $fillable = [
        'nik', 'nama_lengkap', 'tgl_masuk', 'email', 'no_hp', 'DOB', 'foto','shift_pattern_id','start_shift',
        'grade', 'employee_status', 'base_poh', 'nama_pt', 'sex', 'marital_status',
        'birthplace', 'religion', 'kode_dept', 'jabatan', 'address', 'address_rt',
        'address_rw', 'address_kel', 'address_kec', 'address_kota', 'address_prov',
        'kode_pos', 'gelar', 'major', 'kampus', 'job_exp', 'nik_ktp', 'blood_type',
        'email_personal', 'family_card', 'no_npwp', 'alamat_npwp', 'bpjstk', 'bpjskes',
        'rek_no', 'bank_name', 'rek_name', 'father_name', 'mother_name', 'fd_si_name',
        'fd_si_nik', 'fd_si_kota', 'fd_si_dob', 'fd_anak1_name', 'fd_anak1_nik',
        'fd_anak1_kota', 'fd_anak1_dob', 'fd_anak2_name', 'fd_anak2_nik',
        'fd_anak2_kota', 'fd_anak2_dob' , 'fd_anak3_name', 'fd_anak3_nik',
        'fd_anak3_kota', 'fd_anak3_dob', 'em_name', 'em_telp', 'em_relation', 'em_alamat'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'nik' => 'string',
    ];

    public $timestamps = false;

}
