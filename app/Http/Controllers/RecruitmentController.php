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

        // Get job opening associated with the candidate
        $job_opening = DB::table('job_openings')->where('id', $candidate->job_opening_id)->first();

        // Fetch stages based on recruitment type
        $stage = DB::table('hiring_stages')->where('recruitment_type_id', $job_opening->recruitment_type_id)->get();

        // Fetch interviewers with specific jabatan (Section Head, Head of Department, Management)
        $interviewer = DB::table('karyawan')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with the jabatan table
            ->where('karyawan.status_kar', 'Aktif') // Only active employees
            ->whereIn('jabatan.jabatan', ['Section Head', 'Head of Department', 'Management']) // Filter by job titles
            ->select('karyawan.*', 'jabatan.nama_jabatan as nama_jabatan') // Select all karyawan fields
            ->get();

        return view("recruitment.pipeline.interview", compact('stage', 'interviewer', 'id'));
    }




    public function candidate_interview(Request $request)
    {
        $id = $request->id;
        $interview_date = $request->interview_date;
        $interview_time = $request->interview_time;
        $notes = $request->notes;
        $interviewer = $request->interviewer;
        $interviewer2 = $request->interviewer2;
        $stage_id = $request->stage_id;
        $data = [
            'candidate_id' => $id,
            'interview_date' => $interview_date,
            'interview_time' => $interview_time,
            'notes' => $notes,
            'interviewer' => $interviewer,
            'interviewer2' => $interviewer2,
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

    public function candidate_interview_data(Request $request)
    {
        // Fetch interview data with candidate name and stage name
        $interview = DB::table('interviews')
            ->join('candidates', 'interviews.candidate_id', '=', 'candidates.id') // Join candidates table
            ->join('hiring_stages', 'interviews.stage_id', '=', 'hiring_stages.id') // Join stages table
            ->select(
                'interviews.*',
                'candidates.nama_candidate as candidate_name', // Retrieve candidate name
                'hiring_stages.name as stage_name' // Retrieve stage name
            )
            ->get();

        return view("recruitment.interview.index", compact('interview'));
    }

    public function candidate_interview_edit(Request $request)
    {
        $id = $request->id;

        $interview = DB::table('interviews')->where('id', $id)->first();

        return view("recruitment.interview.edit", compact('interview'));
    }

    public function candidate_interview_update($id, Request $request)
    {
        $interview_date = $request->interview_date;
        $interview_time = $request->interview_time;
        $notes = $request->notes;
        $interviewer = $request->interviewer;
        $interviewer2 = $request->interviewer2;
        $status = $request->status;

        $data = [
            'interview_date' => $interview_date,
            'interview_time' => $interview_time,
            'notes' => $notes,
            'interviewer' => $interviewer,
            'interviewer2' => $interviewer2,
            'status' => $status
        ];

        $update = DB::table('interviews')->where('id', $id)->update($data);

        if ($update) {
            return Redirect::back()->with(['success' => 'Interview Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Interview Gagal Di Update']);
        }
    }

    public function candidate_data(Request $request)
    {
        // Fetch interview data with candidate name, job opening title, and current stage name
        $data = DB::table('candidate_data')
            ->join('candidates', 'candidate_data.candidate_id', '=', 'candidates.id') // Join candidates table
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id') // Join job_openings table to get job title
            ->join('hiring_stages', 'candidates.current_stage_id', '=', 'hiring_stages.id') // Join stages table to get the current stage
            ->select(
                'candidate_data.*',
                'candidates.nama_candidate as candidate_name', // Retrieve candidate name
                'candidates.id as candidate_id', // Retrieve candidate name
                'candidates.status as status_candidate', // Retrieve candidate name
                'job_openings.title as job_title',             // Retrieve job opening title
                'hiring_stages.name as stage_name'                    // Retrieve current stage name
            )
            ->get();

        return view("recruitment.candidate.datatable", compact('data'));
    }

    public function candidate_data_view(Request $request)
    {
        // Get the currently authenticated candidate
        $candidateId = $request->candidate_id;

        // Check if candidate data exists
        $candidateData = DB::table('candidate_data')->where('candidate_id', $candidateId)->first();

        // Get all records from candidate_data_keluarga related to the candidateData's id
        $candidateFamilyData = DB::table('candidate_data_keluarga')
            ->where('candidate_data_id', $candidateData->id)
            ->get();

        $candidateFamilyDataSendiri = DB::table('candidate_data_keluarga_sendiri')
            ->where('candidate_data_id', $candidateData->id)
            ->get();

        $candidatePendidikan = DB::table('candidate_data_pendidikan')
            ->where('candidate_data_id', $candidateData->id)
            ->get();

        $candidateKursus = DB::table('candidate_data_kursus')
            ->where('candidate_data_id', $candidateData->id)
            ->get();

        $candidateBahasa = DB::table('candidate_data_bahasa')
            ->where('candidate_data_id', $candidateData->id)
            ->get();

        $candidatePekerjaan = DB::table('candidate_data_pekerjaan')
            ->where('candidate_data_id', $candidateData->id)
            ->get();

        // Otherwise, return 'recruitment.form.index' view
        return view('recruitment.candidate.data', compact('candidateData', 'candidateId', 'candidateFamilyData', 'candidateKursus', 'candidateBahasa', 'candidatePekerjaan', 'candidateFamilyDataSendiri', 'candidatePendidikan'));
    }

    public function candidate_data_approve(Request $request)
    {
        $candidateId = $request->input('id');
        $newStatus = $request->input('status_form');

        // Update candidate's status in the candidate_data table
        DB::table('candidate_data')
            ->where('id', $candidateId)
            ->update(['status_form' => $newStatus]);

        // Get the candidate_id from the updated candidate_data table
        $candidate = DB::table('candidate_data')
            ->where('id', $candidateId)
            ->first(); // Assuming 'candidate_id' exists in this table

        if ($candidate) {
            $candidateRealId = $candidate->candidate_id; // Adjust this to match your actual field name

            // Update verify_offer in the candidates table based on newStatus
            if ($newStatus === 'Verified') {
                DB::table('candidates')
                    ->where('id', $candidateRealId) // Using the candidate_id from candidate_data
                    ->update(['verify_offer' => 1]);
            } elseif ($newStatus === 'Declined') {
                DB::table('candidates')
                    ->where('id', $candidateRealId) // Using the candidate_id from candidate_data
                    ->update(['verify_offer' => 0]);
            }
        }

        return redirect()->back()->with('success', 'Candidate status updated successfully!');
    }



    public function dashboard()
    {

        // Total job openings
        $totalJobOpenings = DB::table('job_openings')->count();

        // Count of job openings that are open
        $openJobOpenings = DB::table('job_openings')
            ->where('status', 'Open') // Assuming 'open' is the status you're tracking
            ->count();

        // Total candidates in progress (where status is 'in progress')
        $totalCandidatesInProgress = DB::table('candidates')
            ->where('status', 'In Progress') // Assuming 'in progress' is the status
            ->count();

        // Total hired candidates (where status is 'hired')
        $totalHiredCandidates = DB::table('candidates')
            ->where('status', 'Hired') // Assuming 'hired' is the status
            ->count();

        return view("recruitment.index", compact('totalHiredCandidates', 'totalCandidatesInProgress', 'openJobOpenings', 'totalJobOpenings'));
    }
}
