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
            'penjelasan_pendidikan' => 'nullable|string|max:100',
            'alasan_pekerjaan_terakhir' => 'nullable|string|max:255',
            'uraian_pekerjaan_terakhir' => 'nullable|string|max:255',
            'engineering_no' => 'nullable|int|max:100',
            'accounting_no' => 'nullable|int|max:100',
            'geologist_no' => 'nullable|int|max:100',
            'administration_no' => 'nullable|int|max:100',
            'agronomist_no' => 'nullable|int|max:100',
            'ga_no' => 'nullable|int|max:100',
            'consultant_no' => 'nullable|int|max:100',
            'personnel_no' => 'nullable|int|max:100',
            'cashier_no' => 'nullable|int|max:100',
            'finance_no' => 'nullable|int|max:100',
            'humas_no' => 'nullable|int|max:100',
            'driver_no' => 'nullable|int|max:100',
            'saudara_pekerjaan' => 'nullable|string|max:255',
            'organisasi' => 'nullable|string|max:255',
            'em_nama' => 'nullable|string|max:255',
            'em_alamat' => 'nullable|string|max:255',
            'em_telp' => 'nullable|string|max:255',
            'em_status' => 'nullable|string|max:255',
            'nama_referensi1' => 'nullable|string|max:255',
            'nama_referensi2' => 'nullable|string|max:255',
            'sakit_lama' => 'nullable|string|max:255',
            'gambaran_posisi' => 'nullable|file|mimes:jpg,png,pdf|max:2048', // Adjust as needed
            'masa_percobaan' => 'required|string|max:100',
            'proses_bi' => 'required|string|max:100',
            'mulai_kerja' => 'nullable|date',
            'slip1' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'slip2' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'slip3' => 'nullable|file|mimes:jpg,png,pdf|max:2048',

            // Salary and financial details
            'gaji_pokok' => 'nullable|numeric|min:0|max:9999999999999.99', // For decimal(15,2)
            'tunjangan1' => 'nullable|string|max:255',
            'nilai_tunjangan1' => 'nullable|numeric|min:0|max:9999999999999.99',
            'tunjangan2' => 'nullable|string|max:255',
            'nilai_tunjangan2' => 'nullable|numeric|min:0|max:9999999999999.99',
            'tunjangan3' => 'nullable|string|max:255',
            'nilai_tunjangan3' => 'nullable|numeric|min:0|max:9999999999999.99',
            'tunjangan4' => 'nullable|string|max:255',
            'nilai_tunjangan4' => 'nullable|numeric|min:0|max:9999999999999.99',
            'tunjangan5' => 'nullable|string|max:255',
            'nilai_tunjangan5' => 'nullable|numeric|min:0|max:9999999999999.99',
            'nilai_insentif' => 'nullable|numeric|min:0|max:9999999999999.99',
            'nilai_lain_lain' => 'nullable|numeric|min:0|max:9999999999999.99',
            'take_home_bulan' => 'nullable|numeric|min:0|max:9999999999999.99',
            'take_home_tahun' => 'nullable|numeric|min:0|max:9999999999999.99',
            'bulan_gaji' => 'required|string|max:100',

            // Expected Income
            'harap_take_home_bulan' => 'nullable|numeric|min:0|max:9999999999999.99',
            'harap_take_home_tahun' => 'nullable|numeric|min:0|max:9999999999999.99',

        ];
    }
}
