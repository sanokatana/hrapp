<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RecruitmentController extends Controller
{

    //Candidate
    public function candidate(){
        $candidate = DB::table('candidates')
        ->get();
        return view("recruitment.candidate.index", compact('candidate'));
    }



    //Recruitment
    public function recruitment(){
        $recruitment = DB::table(table: 'recruitment_types')
        ->get();
        return view("recruitment.recruitment.index", compact('recruitment'));
    }

    public function recruitment_store(Request $request){
        $name = $request->name;
        $description = $request->description;
        $status = $request->status;
        $data = [
            'name' => $name,
            'description' => $description,
            'status'=> $status
        ];

        $simpan = DB::table('recruitment_types')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Recruitment Type Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Recruitment Type Gagal Di Simpan']);
        }
    }

    //Job Openings

    public function job_opening() {
        // Fetch job openings with recruitment type name
        $job = DB::table('job_openings')
            ->join('recruitment_types', 'job_openings.recruitment_type_id', '=', 'recruitment_types.id')
            ->select('job_openings.*', 'recruitment_types.name as recruitment_type_name')
            ->get();

        $department = DB::table('department')->get();
        $recruitment_type = DB::table('recruitment_types')->get();

        return view("recruitment.job.index", compact('job', 'department', 'recruitment_type'));
    }


    public function job_opening_store(Request $request){
        $title = $request->title;
        $description = $request->description;
        $recruitment_type_id = $request->recruitment_type_id;
        $kode_dept = $request->kode_dept;
        $status = $request->status;
        $data = [
            'title' => $title,
            'description' => $description,
            'recruitment_type_id' => $recruitment_type_id,
            'kode_dept' => $kode_dept,
            'status'=> $status
        ];

        $simpan = DB::table('job_openings')
        ->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Job Opening Berhasil Di Simpan']);
        }else {
            return Redirect::back()->with(['warning'=>'Job Opening Gagal Di Simpan']);
        }
    }
}
