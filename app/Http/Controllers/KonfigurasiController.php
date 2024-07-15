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
        $data = [
            'nama_kantor' => $nama_kantor,
            'lokasi_kantor' => $lokasi_kantor,
            'radius'=> $radius
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
        $nama_kantor = $request->nama_kantor;
        $lokasi = DB::table('konfigurasi_lokasi')->where('nama_kantor', $nama_kantor)->first();
        return view('konfigurasi.lokasiedit', compact('lokasi'));
    }

    public function update($nama_kantor, Request $request){
        $nama_kantor = $request->nama_kantor;
        $data = [
            'nama_kantor'=>$nama_kantor
        ];

        $update = DB::table('konfigurasi_lokasi')->where('nama_kantor',$nama_kantor)->update($data);

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

    public function jabatan()
    {
        $jabatan = DB::table('jabatan')
            ->select('jabatan.*', 'department.nama_dept')
            ->join('department', 'jabatan.kode_dept', '=', 'department.kode_dept')
            ->paginate(10); // Add pagination here

        $department = DB::table('department')->get();
        $jabat = DB::table('jabatan')->get();
        $location = DB::table('konfigurasi_lokasi')->get();
        return view("konfigurasi.jabatan", compact('jabatan', 'department', 'jabat','location'));
    }


    public function jabatanstore(Request $request){
        $nama_jabatan = $request->nama_jabatan;
        $kode_dept = $request->kode_dept;
        $atasan_jabatan = $request->atasan_jabatan;
        $site = $request->site;
        $data = [
            'nama_jabatan' => $nama_jabatan,
            'kode_dept' => $kode_dept,
            'jabatan_atasan' => $atasan_jabatan,
            'site' => $site,
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
        $jabatan = DB::table('jabatan')->where('id', $id)->first();
        $jabat = DB::table('jabatan')->get();
        $department = DB::table('department')->get();
        $location = DB::table('konfigurasi_lokasi')->get();
        return view('konfigurasi.jabedit', compact('jabatan','department','jabat','location'));
    }

    public function jabatanupdate(Request $request, $id)
    {
        $nama_jabatan = $request->nama_jabatan;
        $kode_dept = $request->kode_dept;
        $atasan_jabatan = $request->atasan_jabatan;
        $site = $request->site;

        $data = [
            'nama_jabatan' => $nama_jabatan,
            'kode_dept' => $kode_dept,
            'jabatan_atasan' => $atasan_jabatan,
            'site' => $site,
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
}
