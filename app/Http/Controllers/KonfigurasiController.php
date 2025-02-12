<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function index(){
        $lokasi = DB::table('konfigurasi_lokasi')
        ->get();
        return view("konfigurasi.lokasikantor", compact('lokasi'));
    }

    public function store(Request $request){
        $nama_kantor = $request->nama_kantor;
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;
        $no_mesin = $request->no_mesin;
        $data = [
            'nama_kantor' => $nama_kantor,
            'lokasi_kantor' => $lokasi_kantor,
            'radius'=> $radius,
            'no_mesin' => $no_mesin
        ];

        $simpan = DB::table('konfigurasi_lokasi')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Lokasi Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Lokasi Gagal Di Simpan']);
        }
    }
    public function edit(Request $request){
        $id = $request->id;
        $lokasi = DB::table('konfigurasi_lokasi')->where('id', $id)->first();
        return view('konfigurasi.lokasiedit', compact('lokasi'));
    }

    public function update($id, Request $request){
        $nama_kantor = $request->nama_kantor;
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;
        $no_mesin = $request->no_mesin;
        $data = [
            'nama_kantor'=>$nama_kantor,
            'lokasi_kantor' => $lokasi_kantor,
            'radius'=> $radius,
            'no_mesin' => $no_mesin
        ];

        $update = DB::table('konfigurasi_lokasi')->where('id',$id)->update($data);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Di Update']);
        }else {
            return Redirect::back()->with(['warning'=>'Data Gagal Di Update']);
        }
    }

    public function tipecuti(){
        $tipecuti = DB::table('tipe_cuti')
        ->get();
        return view("konfigurasi.tipecuti", compact('tipecuti'));
    }

    public function tipecutistore(Request $request){
        $tipe_cuti = $request->tipe_cuti;
        $jumlah_hari = $request->jumlah_hari;
        $data = [
            'tipe_cuti' => $tipe_cuti,
            'jumlah_hari' => $jumlah_hari,
        ];

        $simpan = DB::table('tipe_cuti')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Tipe Cuti Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Lokasi Gagal Di Simpan']);
        }
    }
    public function tipecutiedit(Request $request){
        $id_tipe_cuti = $request->id_tipe_cuti;
        $tipecuti = DB::table('tipe_cuti')->where('id_tipe_cuti', $id_tipe_cuti)->first();
        return view('konfigurasi.tipecutiedit', compact('tipecuti'));
    }

    public function tipecutiupdate($id_tipe_cuti, Request $request){
        $id_tipe_cuti = $request->id_tipe_cuti;
        $tipe_cuti = $request->tipe_cuti;
        $jumlah_hari = $request->jumlah_hari;
        $data = [
            'id_tipe_cuti'=>$id_tipe_cuti,
            'tipe_cuti' => $tipe_cuti,
            'jumlah_hari' => $jumlah_hari,
        ];

        $update = DB::table('tipe_cuti')->where('id_tipe_cuti',$id_tipe_cuti)->update($data);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Di Update']);
        }else {
            return Redirect::back()->with(['warning'=>'Data Gagal Di Update']);
        }
    }

    public function tipecutidelete($id_tipe_cuti)
    {
        $delete = DB::table('tipe_cuti')->where('id_tipe_cuti', $id_tipe_cuti)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    // Jabatan

    public function jabatan(Request $request)
    {
        $query = DB::table('jabatan as j1')
            ->select('j1.*', 'department.nama_dept', 'j2.nama_jabatan as nama_jabatan_atasan')
            ->join('department', 'j1.kode_dept', '=', 'department.kode_dept')
            ->leftJoin('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id');

        // Apply filters
        if ($request->filled('jabatan_nama')) {
            $query->where('j1.nama_jabatan', 'like', '%' . $request->jabatan_nama . '%');
        }
        if ($request->filled('dept_kode')) {
            $query->where('j1.kode_dept', $request->dept_kode);
        }
        if ($request->filled('nama_kantor')) {
            $query->where('j1.site', $request->nama_kantor);
        }

        $jabatan = $query->paginate(25)->appends($request->all());

        $department = DB::table('department')->get();
        $jabat = DB::table('jabatan')->orderBy('nama_jabatan', 'asc')->get();
        $location = DB::table('konfigurasi_lokasi')->get();

        return view("konfigurasi.jabatan", compact('jabatan', 'department', 'jabat', 'location'));
    }


    public function jabatanstore(Request $request){
        $nama_jabatan = $request->nama_jabatan;
        $kode_dept = $request->kode_dept;
        $atasan_jabatan = $request->atasan_jabatan;
        $site = $request->site;
        $jabatan_posisi =$request->jabatan_posisi;
        $data = [
            'nama_jabatan' => $nama_jabatan,
            'kode_dept' => $kode_dept,
            'jabatan_atasan' => $atasan_jabatan,
            'site' => $site,
            'jabatan' => $jabatan_posisi,
        ];

        $simpan = DB::table('jabatan')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Jabatan Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Jabatan Gagal Di Simpan']);
        }
    }
    public function jabatanedit(Request $request){
        $id = $request->id;
        $jabatan = DB::table('jabatan as j1')
            ->select('j1.*', 'j2.nama_jabatan as nama_jabatan_atasan')
            ->leftJoin('jabatan as j2', 'j1.jabatan_atasan', '=', 'j2.id')
            ->where('j1.id', $id)
            ->first();
        $jabat = DB::table('jabatan')->orderBy('nama_jabatan', 'asc')->get();
        $department = DB::table('department')->get();
        $location = DB::table('konfigurasi_lokasi')->get();
        return view('konfigurasi.jabedit', compact('jabatan','department','jabat','location'));
    }


    public function jabatanupdate(Request $request, $id)
    {
        $nama_jabatan = $request->nama_jabatan;
        $kode_dept = $request->kode_dept;
        $jabatan_atasan = $request->jabatan_atasan;
        $site = $request->site;
        $jabatan_posisi =$request->jabatan_posisi;

        $data = [
            'nama_jabatan' => $nama_jabatan,
            'kode_dept' => $kode_dept,
            'jabatan_atasan' => $jabatan_atasan,
            'site' => $site,
            'jabatan' => $jabatan_posisi,
        ];

        $update = DB::table('jabatan')->where('id', $id)->update($data);

        if ($update) {
            return redirect()->back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return redirect()->back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function jabatandelete($id)
    {
        $delete = DB::table('jabatan')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    //Libur Nasional

    public function libur(){
        $libur = DB::table('libur_nasional')
            ->get();
        return view("konfigurasi.libur", compact('libur'));
    }

    public function liburstore(Request $request){
        $tgl_libur = $request->tgl_libur;
        $nama_libur = $request->nama_libur;
        $data = [
            'tgl_libur' => $tgl_libur,
            'nama_libur' => $nama_libur,
        ];

        $simpan = DB::table('libur_nasional')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Libur Nasional Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Libur Nasional Gagal Di Simpan']);
        }
    }
    public function liburedit(Request $request){
        $tgl_libur = $request->tgl_libur;
        $libur = DB::table('libur_nasional')->where('tgl_libur', $tgl_libur)->first();
        return view('konfigurasi.liburedit', compact('libur'));
    }

    public function liburupdate($tgl_libur, Request $request){
        $tgl_libur = $request->tgl_libur;
        $nama_libur = $request->nama_libur;
        $data = [
            'tgl_libur'=>$tgl_libur,
            'nama_libur' => $nama_libur,
        ];

        $update = DB::table('libur_nasional')->where('tgl_libur',$tgl_libur)->update($data);

        if($update){
            return Redirect::back()->with(['success'=>'Libur Nasional Berhasil Di Update']);
        }else {
            return Redirect::back()->with(['warning'=>'Libur Nasional Gagal Di Update']);
        }
    }

    public function liburdelete($tgl_libur)
    {
        $delete = DB::table('libur_nasional')->where('tgl_libur', $tgl_libur)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Libur Nasional Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Libur Nasional Gagal Di Hapus']);
        }
    }

    public function liburkar()
    {
        $liburkars = DB::table('libur_kar')
            ->leftJoin('karyawan', function ($join) {
                $join->on('libur_kar.nip', '=', 'karyawan.nip')
                    ->orOn('libur_kar.nik', '=', 'karyawan.nik');
            })
            ->select('libur_kar.*', 'karyawan.nama_lengkap')
            ->get();

        return view('konfigurasi.liburkar', compact('liburkars'));
    }

    public function liburkarstore(Request $request)
    {
        $nik = $request->nik;
        $nip = DB::table('karyawan')->where('nik', $nik)->value('nip');
        $bulan = $request->bulan;

        try {
            $data = [
                'nik' => $nik,
                'nip' => $nip,
                'month' => $bulan,
            ];
            DB::table('libur_kar')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
        } catch (\Throwable $th) {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Simpan']);
        }
    }

    public function shiftupdate(Request $request)
    {
        $id = $request->id; // Retrieve the id from the request
        $nik = $request->nik;
        $nip = DB::table('karyawan')->where('nik', $nik)->value('nip');
        $bulan = $request->bulan;

        try {
            $data = [
                'nik' => $nik,
                'nip' => $nip,
                'month' => $bulan,
            ];
            DB::table('shift')
                ->where('id', $id)
                ->update($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } catch (\Throwable $th) {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function liburkaredit(Request $request)
    {
        $id = $request->id;
        $liburkar = DB::table('libur_kar')->where('id', $id)->first();
        return view('konfigurasi.liburkaredit', compact('liburkar'));
    }

    public function getDays($id)
    {
        $days = DB::table('libur_kar_day')
            ->where('libur_id', $id)
            ->get();

        return response()->json(['days' => $days]);
    }

    public function saveOrUpdateDays(Request $request, $liburId)
    {
        // Validate the incoming request
        $request->validate([
            'tanggal.*' => 'required|date',
        ]);

        $tanggal = $request->input('tanggal', []);

        // Check if there is existing data for the current libur_kar ID
        $existingDays = DB::table('libur_kar_day')->where('libur_id', $liburId)->get();

        if ($existingDays->isEmpty()) {
            // Insert new days if no existing data
            foreach ($tanggal as $date) {
                DB::table('libur_kar_day')->insert([
                    'libur_id' => $liburId,
                    'tanggal' => $date,
                ]);
            }
        } else {
            // Update existing days
            foreach ($tanggal as $date) {
                $dateExists = $existingDays->where('tanggal', $date)->first();

                if (!$dateExists) {
                    // Add new date if it does not exist
                    DB::table('libur_kar_day')->insert([
                        'libur_id' => $liburId,
                        'tanggal' => $date,
                    ]);
                }
            }

            // Delete days that are no longer present
            $existingDates = $existingDays->pluck('tanggal');
            $datesToDelete = $existingDates->diff($tanggal);

            foreach ($datesToDelete as $date) {
                DB::table('libur_kar_day')->where('libur_id', $liburId)->where('tanggal', $date)->delete();
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function liburkardelete($id)
    {
        $delete = DB::table('libur_kar')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    // Method to delete a specific day
    public function deleteDay($id)
    {
        $delete = DB::table('libur_kar_day')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

}
