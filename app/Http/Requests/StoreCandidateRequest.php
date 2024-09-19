<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'candidate_id' => 'required|int|max:255', // remove this line
            'nama_lengkap' => 'nullable|string|max:255',
            'nama_panggilan' => 'nullable|string|max:255',
            'jenis' => 'nullable|string',
            'gol_darah' => 'nullable|string|max:2',
            'tgl_lahir' => 'nullable|date',
            'warga_negara' => 'nullable|string|max:255',
            'alamat_rumah' => 'nullable|string|max:500',
            'telp_rumah_hp' => 'nullable|string|max:20',
            'no_ktp_sim' => 'nullable|string|max:20',
            'tgl_ktp_sim' => 'nullable|date',
            'no_npwp' => 'nullable|string|max:20',
            'alamat_npwp' => 'nullable|string|max:500',
            'status_keluarga' => 'required|string|max:10',
            'tgl_menikah' => 'nullable|date',
            'jabatan' => 'nullable|string|max:255',
            'nama_perusahaan' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string|max:500',
            'alamat_email' => 'nullable|email|max:255',
            'tanggung_jawab' => 'nullable|string|max:50',
            'siapa_tanggungan' => 'nullable|string|max:255',
            'nilai_tanggungan' => 'nullable|int|max:100',
            'rumah_status' => 'nullable|string|max:100',
            'melanjut_pendidikan' => 'nullable|string|max:100',
            'penjelasan_pendidikan' => 'nullable|string|max:100'

        ];
    }
}
