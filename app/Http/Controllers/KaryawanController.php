<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Company;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\KonfigurasiLokasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    public function index(Request $request): View
    {
        $employees = Karyawan::with(['company', 'cabang', 'department', 'jabatan', 'lokasi'])
            ->orderBy('nama_lengkap')
            ->get();
        $companies = Company::orderBy('short_name')->get();
        $branches = Cabang::orderBy('nama')->get();
        $departments = Department::orderBy('nama')->get();
        $positions = Jabatan::orderBy('nama')->get();
        $locations = KonfigurasiLokasi::orderBy('nama_kantor')->get();

        return view('karyawan.index', compact('employees', 'companies', 'branches', 'departments', 'positions', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nik' => ['required', 'string', 'max:50', 'unique:karyawan,nik'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:karyawan,email'],
            'no_hp' => ['nullable', 'string', 'max:25'],
            'tgl_masuk' => ['nullable', 'date'],
            'company_id' => ['required', 'exists:tb_pt,id'],
            'cabang_id' => ['nullable', 'exists:cabang,id'],
            'department_id' => ['nullable', 'exists:department,id'],
            'jabatan_id' => ['nullable', 'exists:jabatan,id'],
            'lokasi_id' => ['nullable', 'exists:konfigurasi_lokasi,id'],
            'status_kar' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data['password'] = Hash::make($data['password']);

        Karyawan::create($data);

        return Redirect::route('karyawan.index')->with('success', 'Karyawan created.');
    }

    public function update(Request $request, Karyawan $karyawan): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:karyawan,email,' . $karyawan->id],
            'no_hp' => ['nullable', 'string', 'max:25'],
            'tgl_masuk' => ['nullable', 'date'],
            'tgl_resign' => ['nullable', 'date'],
            'company_id' => ['required', 'exists:tb_pt,id'],
            'cabang_id' => ['nullable', 'exists:cabang,id'],
            'department_id' => ['nullable', 'exists:department,id'],
            'jabatan_id' => ['nullable', 'exists:jabatan,id'],
            'lokasi_id' => ['nullable', 'exists:konfigurasi_lokasi,id'],
            'status_kar' => ['required', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return Redirect::route('karyawan.index')
                ->withErrors($validator, 'karyawanUpdate')
                ->withInput(array_merge($request->all(), ['form_action' => 'edit-karyawan']))
                ->with('edit_karyawan_id', $karyawan->id);
        }

        $data = $validator->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $karyawan->update($data);

        return Redirect::route('karyawan.index')->with('success', 'Karyawan updated.');
    }

    public function destroy(Karyawan $karyawan): RedirectResponse
    {
        $karyawan->delete();

        return Redirect::route('karyawan.index')->with('success', 'Karyawan removed.');
    }
}
