<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class RecruitmentController extends Controller
{

    //Candidate
    public function candidate()
    {
        $candidate = DB::table('candidates')
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
            ->join('hiring_stages', 'candidates.current_stage_id', '=', 'hiring_stages.id')
            ->select('candidates.*', 'job_openings.title as job_opening_name', 'hiring_stages.name as hiring_stages_name')
            ->get();

        $job = DB::table('job_openings')->get();
        $currentStage = DB::table('hiring_stages')->get();
        return view("recruitment.candidate.index", compact('candidate', 'currentStage', 'job'));
    }

    public function candidate_store(Request $request)
    {
        $nama_candidate = $request->nama_candidate;
        $username = $request->username;
        $email = $request->email;
        $job_opening_id = $request->job_opening_id;
        $current_stage_id = $request->current_stage_id;
        $status = $request->status;
        $password = $request->password;


        $data = [
            'nama_candidate' => $nama_candidate,
            'username' => $username,
            'email' => $email,
            'job_opening_id' => $job_opening_id,
            'current_stage_id' => $current_stage_id,
            'status' => $status,
            'password' => Hash::make($password),  // Hash the password
        ];

        $simpan = DB::table('candidates')
            ->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'Candidate Berhasil Di Simpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Candidate Gagal Di Simpan']);
        }
    }

    public function candidate_edit(Request $request)
    {

        $id = $request->id;
        $candidate = DB::table('candidates')->where('id', $id)->first();
        $job = DB::table('job_openings')->get();
        $currentStage = DB::table('hiring_stages')->get();
        return view("recruitment.candidate.edit", compact('candidate', 'currentStage', 'job'));
    }

    public function candidate_update($id, Request $request)
    {
        $nama_candidate = $request->nama_candidate;
        $username = $request->username;
        $email = $request->email;
        $job_opening_id = $request->job_opening_id;
        $current_stage_id = $request->current_stage_id;
        $status = $request->status;


        $data = [
            'nama_candidate' => $nama_candidate,
            'username' => $username,
            'email' => $email,
            'job_opening_id' => $job_opening_id,
            'current_stage_id' => $current_stage_id,
            'status' => $status,
        ];

        // Only update the password if a new one is provided
        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $update = DB::table('candidates')->where('id', $id)->update($data);

        if ($update) {
            return Redirect::back()->with(['success' => 'Candidate Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Candidate Gagal Di Update']);
        }
    }

    public function candidate_delete($id)
    {
        $delete = DB::table('candidates')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    public function candidate_next($id)
    {
        $candidate = Candidate::findOrFail($id);

        // Increment the current_stage_id to move to the next stage
        $candidate->current_stage_id += 1;

        // Save changes
        $candidate->save();

        // Redirect with a success message
        return redirect()->back()->with('success', 'Candidate has been moved to the next stage.');
    }

    // Reject the candidate
    public function candidate_reject($id, Request $request)
    {
        $candidate = Candidate::findOrFail($id);

        // Set the status to rejected
        $candidate->reject_reason = $request->input('reject_reason');
        $candidate->status = 'rejected';

        // Save changes
        $candidate->save();

        // Redirect with a success message
        return redirect()->back()->with('danger', 'Candidate has been rejected.');
    }


    public function candidate_back($id)
    {
        $candidate = Candidate::findOrFail($id);

        // Increment the current_stage_id to move to the next stage
        $candidate->current_stage_id -= 1;

        // Save changes
        $candidate->save();

        // Redirect with a success message
        return redirect()->back()->with('success', 'Candidate has been moved back a stage.');
    }


    // ------------------------------- Recruitment -------------------------------------------------------------------------------------------------------------------------- //
    public function recruitment()
    {
        $recruitment = DB::table(table: 'recruitment_types')
            ->get();
        return view("recruitment.recruitment.index", compact('recruitment'));
    }

    public function recruitment_edit(Request $request)
    {
        $id = $request->id;
        $recruitment = DB::table('recruitment_types')->where('id', $id)->first();

        return view("recruitment.recruitment.edit", compact('recruitment'));
    }

    public function recruitment_store(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $status = $request->status;
        $data = [
            'name' => $name,
            'description' => $description,
            'status' => $status
        ];

        $simpan = DB::table('recruitment_types')
            ->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'Recruitment Type Berhasil Di Simpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Recruitment Type Gagal Di Simpan']);
        }
    }

    public function recruitment_update($id, Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $status = $request->status;
        $data = [
            'name' => $name,
            'description' => $description,
            'status' => $status
        ];

        $update = DB::table('recruitment_types')->where('id', $id)->update($data);

        if ($update) {
            return Redirect::back()->with(['success' => 'Recruitment Type Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Recruitment Type Gagal Di Update']);
        }
    }

    public function recruitment_delete($id)
    {
        $delete = DB::table('recruitment_types')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    // ------------------------------- Job Openings -------------------------------------------------------------------------------------------------------------------------- //

    public function job_opening()
    {
        // Fetch job openings with recruitment type name
        $job = DB::table('job_openings')
            ->join('recruitment_types', 'job_openings.recruitment_type_id', '=', 'recruitment_types.id')
            ->select('job_openings.*', 'recruitment_types.name as recruitment_type_name')
            ->get();

        $department = DB::table('department')->get();
        $recruitment_type = DB::table('recruitment_types')->get();

        return view("recruitment.job.index", compact('job', 'department', 'recruitment_type'));
    }


    public function job_opening_store(Request $request)
    {
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
            'status' => $status
        ];

        $simpan = DB::table('job_openings')
            ->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'Job Opening Berhasil Di Simpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Job Opening Gagal Di Simpan']);
        }
    }

    public function job_opening_edit(Request $request)
    {
        $id = $request->id;
        $job = DB::table('job_openings')->where('id', $id)->first();
        $department = DB::table('department')->get();
        $recruitment_type = DB::table('recruitment_types')->get();

        return view("recruitment.job.edit", compact('job', 'department', 'recruitment_type'));
    }

    public function job_opening_update($id, Request $request)
    {
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
            'status' => $status
        ];

        $update = DB::table('job_openings')->where('id', $id)->update($data);

        if ($update) {
            return Redirect::back()->with(['success' => 'Job Opening Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Job Opening Gagal Di Update']);
        }
    }

    public function job_opening_delete($id)
    {
        $delete = DB::table('job_openings')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    // ------------------------------- Stages -------------------------------------------------------------------------------------------------------------------------- //

    public function stage()
    {
        // Fetch job openings with recruitment type name
        $stage = DB::table('hiring_stages')
            ->join('recruitment_types', 'hiring_stages.recruitment_type_id', '=', 'recruitment_types.id')
            ->select('hiring_stages.*', 'recruitment_types.name as recruitment_type_name')
            ->get();
        $recruitment_type = DB::table('recruitment_types')->get();

        return view("recruitment.stages.index", compact('stage', 'recruitment_type'));
    }

    public function stage_store(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $recruitment_type_id = $request->recruitment_type_id;
        $sequence = $request->sequence;
        $type = $request->type;
        $data = [
            'name' => $name,
            'description' => $description,
            'recruitment_type_id' => $recruitment_type_id,
            'sequence' => $sequence,
            'type' => $type
        ];

        $simpan = DB::table('hiring_stages')
            ->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'Hiring Stage Berhasil Di Simpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Hiring Stage Gagal Di Simpan']);
        }
    }

    public function stage_edit(Request $request)
    {
        $id = $request->id;
        $stage = DB::table('hiring_stages')->where('id', $id)->first();
        $recruitment_type = DB::table('recruitment_types')->get();

        return view("recruitment.stages.edit", compact('stage', 'recruitment_type'));
    }

    public function stage_update($id, Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $recruitment_type_id = $request->recruitment_type_id;
        $sequence = $request->sequence;
        $type = $request->type;

        $data = [
            'name' => $name,
            'description' => $description,
            'recruitment_type_id' => $recruitment_type_id,
            'sequence' => $sequence,
            'type' => $type
        ];

        $update = DB::table('hiring_stages')->where('id', $id)->update($data);

        if ($update) {
            return Redirect::back()->with(['success' => 'Hiring Stage Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Hiring Stage Gagal Di Update']);
        }
    }

    // ------------------------------- Pipeline -------------------------------------------------------------------------------------------------------------------------- //

    public function pipeline()
    {

        $recruitmentTypes = DB::table('recruitment_types')->get();

        // For each recruitment type, retrieve the associated stages and candidates
        $recruitmentData = [];
        foreach ($recruitmentTypes as $type) {
            $stages = DB::table('hiring_stages')
                ->where('recruitment_type_id', $type->id)
                ->orderBy('sequence', 'asc')
                ->get();

            $stagesWithCandidates = [];
            foreach ($stages as $stage) {
                $candidates = DB::table('candidates')
                    ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
                    ->where('candidates.current_stage_id', $stage->id)
                    ->where('candidates.status', 'In Process')
                    ->select(
                        'candidates.id',
                        'candidates.nama_candidate',
                        'candidates.email',
                        'job_openings.title as job_title',
                        'job_openings.id as job_opening_id'
                    )
                    ->get();

                $stagesWithCandidates[] = [
                    'stage' => $stage,
                    'candidates' => $candidates
                ];
            }

            $recruitmentData[] = [
                'type' => $type,
                'stagesWithCandidates' => $stagesWithCandidates
            ];
        }

        return view("recruitment.pipeline.index", compact('recruitmentData'));
    }


    public function candidate_interview_get(Request $request)
    {
        $id = $request->id;
        $candidate = Candidate::find($id);

        $job_opening = DB::table('job_openings')->where('id', $candidate->job_opening_id)->first();

        $stage = DB::table('hiring_stages')->where('recruitment_type_id', $job_opening->recruitment_type_id)->get();

        // Get the candidate's kode_dept
        $kodeDept = DB::table('job_openings')->where('id', $candidate->job_opening_id)->value('kode_dept');

        // Fetch interviewers only from the same department or from 'Management'
        $interviewer = DB::table('karyawan')->whereIn('kode_dept', [$kodeDept, 'Management'])->get();

        return view("recruitment.pipeline.interview", compact('stage', 'interviewer', 'id'));
    }

    public function candidate_interview(Request $request)
    {
        $id = $request->id;
        $interview_date = $request->interview_date;
        $interview_time = $request->interview_time;
        $notes = $request->notes;
        $interviewer = $request->interviewer;
        $stage_id = $request->stage_id;
        $data = [
            'candidate_id' => $id,
            'interview_date' => $interview_date,
            'interview_time' => $interview_time,
            'notes' => $notes,
            'interviewer' => $interviewer,
            'stage_id' => $stage_id
        ];

        $simpan = DB::table('interviews')
            ->insert($data);
        if ($simpan) {
            DB::table('candidates')->where('id', $id)->update(['current_stage_id' => $stage_id]);
            return Redirect::back()->with(['success' => 'Interview Berhasil Di Simpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Interview Gagal Di Simpan']);
        }
    }

}
