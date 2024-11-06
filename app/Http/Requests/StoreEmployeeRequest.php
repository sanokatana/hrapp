<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nik' => 'nullable|string|max:100',
            'nip' => 'nullable|string|max:100',
            'nama_lengkap' => 'required|string|max:255',
            'tgl_masuk' => 'nullable|date',
            'email' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'DOB' => 'nullable|date',
            'grade' => 'nullable|string|max:50',
            'no_kontrak' => 'nullable|string|max:255',
            'employee_status' => 'nullable|string|max:50',
            'poh' => 'nullable|string|max:50',
            'base_poh' => 'nullable|string|max:50',
            'nama_pt' => 'nullable|string|max:50',
            'sex' => 'nullable|string|max:10',
            'tax_status' => 'nullable|string|max:50',
            'birthplace' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:50',
            'kode_dept' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'address_rt' => 'nullable|string|max:10',
            'address_rw' => 'nullable|string|max:10',
            'address_kel' => 'nullable|string|max:100',
            'address_kec' => 'nullable|string|max:100',
            'address_kota' => 'nullable|string|max:100',
            'address_prov' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'gelar' => 'nullable|string|max:50',
            'major' => 'nullable|string|max:100',
            'kampus' => 'nullable|string|max:100',
            'job_exp' => 'nullable|string',
            'nik_ktp' => 'nullable|string|max:16',
            'blood_type' => 'nullable|string|max:5',
            'email_personal' => 'nullable|string|max:255',
            'family_card' => 'nullable|string|max:50',
            'no_npwp' => 'nullable|string|max:50',
            'alamat_npwp' => 'nullable|string|max:255',
            'bpjstk' => 'nullable|string|max:50',
            'bpjskes' => 'nullable|string|max:50',
            'rek_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'rek_name' => 'nullable|string|max:100',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'fd_si_name' => 'nullable|string|max:255',
            'fd_si_nik' => 'nullable|string|max:16',
            'fd_si_kota' => 'nullable|string|max:100',
            'fd_si_dob' => 'nullable|date',
            'fd_anak1_name' => 'nullable|string|max:255',
            'fd_anak1_nik' => 'nullable|string|max:16',
            'fd_anak1_kota' => 'nullable|string|max:100',
            'fd_anak1_dob' => 'nullable|date',
            'fd_anak2_name' => 'nullable|string|max:255',
            'fd_anak2_nik' => 'nullable|string|max:16',
            'fd_anak2_kota' => 'nullable|string|max:100',
            'fd_anak2_dob' => 'nullable|date',
            'fd_anak3_name' => 'nullable|string|max:255',
            'fd_anak3_nik' => 'nullable|string|max:16',
            'fd_anak3_kota' => 'nullable|string|max:100',
            'fd_anak3_dob' => 'nullable|date',
            'em_name' => 'nullable|string|max:255',
            'em_telp' => 'nullable|string|max:255',
            'em_relation' => 'nullable|string|max:100',
            'em_alamat' => 'nullable|string',
            'status_kar' => 'required|in:Aktif,Non-Aktif',

            'status_photo' => 'nullable|boolean',
            'status_ktp' => 'nullable|boolean',
            'status_kk' => 'nullable|boolean',
            'status_npwp' => 'nullable|boolean',
            'status_sim' => 'nullable|boolean',
            'status_ijazah' => 'nullable|boolean',
            'status_skck' => 'nullable|boolean',
            'status_cv' => 'nullable|boolean',
            'status_applicant' => 'nullable|boolean',

            'file_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'file_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_npwp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_sim' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_skck' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_cv' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_applicant' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'created_by' => 'nullable|string|max:100',
            'updated_by' => 'nullable|string|max:100',
            // Add the rest of your rules here...
        ];
    }
}
