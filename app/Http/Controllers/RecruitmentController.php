<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Candidate;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class RecruitmentController extends Controller
{

    //Candidate
    public function candidate(Request $request)
    {
        $candidates = DB::table('candidates')
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
            ->join('hiring_stages', 'candidates.current_stage_id', '=', 'hiring_stages.id')
            ->orderBy('id', 'asc')
            ->select('candidates.*', 'job_openings.title as job_opening_name', 'hiring_stages.name as hiring_stages_name');


        if (!empty($request->nama_candidate)) {
            $candidates->where('candidates.nama_candidate', 'like', '%' . $request->nama_candidate . '%');
        }

        if (!empty($request->title_job)) {
            $candidates->where('job_openings.title', $request->title_job);
        }

        if (!empty($request->status_candidate)) {
            $candidates->where('candidates.status', $request->status_candidate);
        } else {
            $candidates->where('candidates.status', 'In Process');
        }

        $candidate = $candidates->get(); // Get results after applying filters

        $interviewer = DB::table('karyawan')
            ->join('jabatan', 'karyawan.jabatan', '=', 'jabatan.id') // Join with the jabatan table
            ->where('karyawan.status_kar', 'Aktif') // Only active employees
            ->where(function ($query) {
                $query->whereIn('jabatan.jabatan', ['Section Head', 'Head of Department', 'Management']) // Filter by job titles
                    ->orWhere('jabatan.id', 12); // Include jabatan.id = 12
            })
            ->select('karyawan.*', 'jabatan.nama_jabatan as nama_jabatan') // Select all karyawan fields
            ->get();

        $job = DB::table('job_openings')->get();
        $currentStage = DB::table('hiring_stages')->get();
        return view("recruitment.candidate.index", compact('candidate', 'currentStage', 'job', 'interviewer'));
    }

    public function candidate_store(Request $request)
    {

        $request->validate([
            'nama_candidate' => 'required|string|max:255',
            'email' => 'required|email', // Ensure email is valid
            'job_opening_id' => 'required|integer',
            'interview_date' => 'required|date',
            'interview_time' => 'required|string',
            'notes' => 'nullable|string',
            'interviewer' => 'required|string|max:255',
            'interviewer2' => 'nullable|string|max:255',
        ]);

        $nama_candidate = $request->nama_candidate;
        $email = $request->email;
        $job_opening_id = $request->job_opening_id;
        $current_stage_id = 1;
        $status = 'In Process';
        $email_user = Auth::guard('user')->user()->email;

        // Generate username from nama_candidate
        $username = $this->generateUsername($nama_candidate);

        // Generate a simple random password
        $password = Str::random(6); // 6-character alphanumeric password

        // Fetch the job opening title using the job_opening_id
        $job_opening = DB::table('job_openings')
            ->where('id', $job_opening_id)
            ->value('title');

        // Save candidate data
        // Save candidate data and retrieve the ID
        $candidateData = [
            'nama_candidate' => $nama_candidate,
            'username' => $username,
            'email' => $email,
            'job_opening_id' => $job_opening_id,
            'current_stage_id' => $current_stage_id,
            'status' => $status,
            'password' => Hash::make($password), // Secure the password
        ];

        $candidateID = DB::table('candidates')->insertGetId($candidateData);

        // Save interview data using the retrieved candidate ID
        $interviewData = [
            'candidate_id' => $candidateID,
            'stage_id' => '3',
            'interview_date' => $request->interview_date,
            'interview_time' => $request->interview_time,
            'notes' => $request->notes,
            'interviewer' => $request->interviewer,
            'interviewer2' => $request->interviewer2,
        ];

        DB::table('interviews')->insert($interviewData);


        // Send email to the candidate
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $interview_date = $request->interview_date;
            $interview_time = $request->interview_time;
            $interviewer = $request->interviewer2;
            $position = $job_opening;
            $formattedInterviewDate = DateHelper::formatIndonesianDate($interview_date);

            $emailContent = <<<EOD
            Kepada Yth.<br>
            Bpk/Ibu/Sdr/i {$nama_candidate}<br>
            Di Tempat<br><br>
            Dengan hormat,<br>
            Berdasarkan Aplikasi saudara, dengan ini kami mengundang  anda untuk mengikuti proses <b><i>Recruitment</i> di PT Cipta Harmoni Lestari Group</b> melalui 3 tahapan berikut:<br><br>

            1. Silahkan <b>mengisi data pribadi</b> saudara melalui link website kami <a href="http://hrms.ciptaharmoni.com/candidate">hrms.ciptaharmoni.com/candidate</a> dengan kode akses:
            <ul>
                <li>Username &nbsp;&nbsp;: {$username}</li>
                <li>Password &nbsp;&nbsp;&nbsp;: {$password}</li>
                <li>Jika anda mengalami kesulitan dalam pengisiannya bisa di lihat dalam link Video tutorial berikut, https://drive.google.com/file/d/1bFizRnN5JR454qeRknmaGdTtmGMnZMnH/view?usp=drive_link</li>
            </ul>

            2. Mengikuti <b>psikotest online</b> dengan cara akses website portal kami di https://extendedforms.io/form/9a116bdb-1df4-4fba-84e7-b2aa91526cd9/login dengan langkah dan ketentuan dibawah ini:
            <ul>
                <li>Login menggunakan PC/ Handphone</li>
                <li>Durasi waktu pengerjaan selama 30 menit</li>
                <li>Dijawab dengan jujur, bahkan jika anda tidak menyukai jawabannya</li>
            </ul>

            3. Informasi <b>jadwal interview</b> yang akan dilaksanakan pada,<br>
            <ul>
                <li>Hari & Tanggal    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$formattedInterviewDate}</li>
                <li>Waktu             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$interview_time} - Selesai</li>
                <li>Posisi di lamar   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$position}</li>
                <li>Interviewer       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$interviewer}</li>
                <li>Alamat            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: CHL Group Marketing Lounge,<br>Ruko Sorrento Place No. 18-19 PJQJ+R8G, Jl. Ir.Sukarno, Curug Sangereng, Kec.Klp. Dua, Kabupaten Tangerang, Banten 15810. https://goo.gl/maps/Ko81dv9gxMHmMC7p9 </li>
            </ul>

            <b>CATATAN:</b><br>
            <ul>
                <li>Setelah mengisis <b>data pribadi</b> dan mengerjakan <b>psikotest online</b> agar menginformasikan kepada kami via <i>WhatsApp</i> di nomor: 0813 8500 0789</li>
                <li><b>Data pribadi</b> dan <b>psikotest online</b> agar di isi dihari yang sama pada saat terima email ini</li>
                <li>Harap hadir 10 menit sebelum jadwal pelaksanaan <b>interview</b>.</li>
                <li>Kandidat <b>Markom</b> dan <b>Architect</b> agar menyiapkan bahan <b>presentasi</b> portopolio dengan membawa <b>laptop</b> pribadi.</li>
            </ul>

            <br><br><br>
            Best regards,<br>
            Zicki Darmawan<br>
            HR CHL Group<br>
            <a href="https://www.ciptaharmoni.com/">www.ciptaharmoni.com</a><br>
        EOD;

            // Send email
            try {
                Mail::html($emailContent, function ($message) use ($email, $nama_candidate, $email_user) {
                    $message->to($email)
                        ->subject("CHL Job Candidacy Invitation for {$nama_candidate}")
                        ->cc(['human.resources@ciptaharmoni.com', $email_user])
                        ->priority(1);

                    // Add importance headers
                    $message->getHeaders()->addTextHeader('Importance', 'high');
                    $message->getHeaders()->addTextHeader('X-Priority', '1');
                });
            } catch (\Exception $e) {
                return Redirect::back()->with(['warning' => 'Failed to send email: ' . $e->getMessage()]);
            }
        } else {
            return Redirect::back()->with(['warning' => 'Invalid email address.']);
        }

        if ($candidateID) {
            return Redirect::back()->with(['success' => 'Candidate Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Candidate Gagal Disimpan']);
        }
    }


    private function generateUsername($nama_candidate)
    {
        $nameParts = explode(' ', strtolower($nama_candidate));
        $baseUsername = $nameParts[0] . (isset($nameParts[1]) ? '_' . substr($nameParts[1], 0, 1) : '');

        $username = $baseUsername;
        $iteration = 1;

        while (DB::table('candidates')->where('username', $username)->exists()) {
            $username = $baseUsername . ($iteration > 1 ? $iteration : '');
            $iteration++;
        }

        return $username;
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
        $candidate->status = 'Rejected';
        $candidate->tgl_reject = Carbon::now();

        // Save changes
        $candidate->save();

        // Return JSON response for AJAX
        return response()->json(['status' => 'success', 'message' => 'Candidate has been rejected.']);
    }

    public function candidate_hire($id)
    {
        $candidate = Candidate::findOrFail($id);

        // Set the status to hired
        $candidate->status = 'Hired';
        $candidate->tgl_hire = Carbon::now();

        // Save changes
        $candidate->save();

        // Return JSON response for AJAX
        return response()->json(['status' => 'success', 'message' => 'Candidate has been hired.']);
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

        DB::beginTransaction();

        try {
            // Mark previous interviews as completed
            DB::table('interviews')
                ->where('candidate_id', $id)
                ->where('status', 'Scheduled')
                ->update(['status' => 'Completed']);

            // Insert new interview
            $data = [
                'candidate_id' => $id,
                'interview_date' => $interview_date,
                'interview_time' => $interview_time,
                'notes' => $notes,
                'interviewer' => $interviewer,
                'interviewer2' => $interviewer2,
                'stage_id' => $stage_id,
                'status' => 'Scheduled', // New interview is marked as Scheduled
            ];
            DB::table('interviews')->insert($data);

            // Update candidate's current stage
            DB::table('candidates')->where('id', $id)->update(['current_stage_id' => $stage_id]);

            // Fetch candidate, job opening, and stage details
            $candidate = DB::table('candidates')->where('id', $id)->first();
            $jobOpening = DB::table('job_openings')->where('id', $candidate->job_opening_id)->first();
            $stage = DB::table('hiring_stages')->where('id', $stage_id)->first();

            // Extract details
            $email = $candidate->email;
            $nama_candidate = $candidate->nama_candidate;
            $nama_posisi = $jobOpening->title;
            $stage_interview_name = $stage->name;

            $email_user = Auth::guard('user')->user()->email;

            // Format interview date
            $formattedInterviewDate = DateHelper::formatIndonesianDate($interview_date);

            // Email content
            $emailContent = <<<EOD
            Kepada Yth.<br>
            Bpk/Ibu/Sdr/i <b>{$nama_candidate}</b><br><br>

            Informasi <b>jadwal interview</b> yang akan dilaksanakan pada,<br>
            <ul>
                <li>Hari & Tanggal    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$formattedInterviewDate}</li>
                <li>Waktu             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$interview_time} - Selesai</li>
                <li>Posisi di lamar   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$nama_posisi}</li>
                <li>Tahap Interview   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$stage_interview_name}</li>
                <li>Interviewer       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$interviewer}</li>
                <li>Alamat            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: CHL Group Marketing Lounge,<br>
                Ruko Sorrento Place No. 18-19 PJQJ+R8G, Jl. Ir.Sukarno, Curug Sangereng, Kec.Klp. Dua, Kabupaten Tangerang, Banten 15810.
                <a href='https://goo.gl/maps/Ko81dv9gxMHmMC7p9'>Google Maps</a></li>
            </ul>

            <br>
            Best regards,<br>
            Zicki Darmawan<br>
            HR CHL Group<br>
            <a href="https://www.ciptaharmoni.com/">www.ciptaharmoni.com</a><br><br><br><br>
        EOD;

            // Send email
            Mail::html($emailContent, function ($message) use ($email, $nama_candidate, $email_user, $stage_interview_name) {
                $message->to($email)
                    ->subject("CHL Job Candidacy {$stage_interview_name} Invitation for {$nama_candidate}")
                    ->cc([$email_user, 'human.resources@ciptaharmoni.com'])
                    ->priority(1);

                // Add importance headers
                $message->getHeaders()->addTextHeader('Importance', 'high');
                $message->getHeaders()->addTextHeader('X-Priority', '1');
            });

            DB::commit();
            return redirect()->back()->with(['success' => 'Interview berhasil disimpan dan email dikirim.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['warning' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }



    public function candidate_interview_data(Request $request)
    {
        // Fetch interview data with candidate name and stage name
        $interviews = DB::table('interviews')
            ->join('candidates', 'interviews.candidate_id', '=', 'candidates.id') // Join candidates table
            ->join('hiring_stages', 'interviews.stage_id', '=', 'hiring_stages.id') // Join stages table
            ->select(
                'interviews.*',
                'candidates.nama_candidate as candidate_name', // Retrieve candidate name
                'hiring_stages.name as stage_name' // Retrieve stage name
            );

        if (!empty($request->nama_candidate)) {
            $interviews->where('candidates.nama_candidate', 'like', '%' . $request->nama_candidate . '%');
        }

        $interview = $interviews->get();

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
        $datas = DB::table('candidate_data')
            ->join('candidates', 'candidate_data.candidate_id', '=', 'candidates.id') // Join candidates table
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id') // Join job_openings table to get job title
            ->join('hiring_stages', 'candidates.current_stage_id', '=', 'hiring_stages.id') // Join stages table to get the current stage
            ->orderBy('id', 'asc')
            ->select(
                'candidate_data.*',
                'candidates.nama_candidate as candidate_name', // Retrieve candidate name
                'candidates.id as candidate_id', // Retrieve candidate name
                'candidates.status as status_candidate', // Retrieve candidate name
                'job_openings.title as job_title',             // Retrieve job opening title
                'hiring_stages.name as stage_name'                    // Retrieve current stage name
            );


        if (!empty($request->nama_candidate)) {
            $datas->where('candidates.nama_candidate', 'like', '%' . $request->nama_candidate . '%');
        }

        if (!empty($request->title_job)) {
            $datas->where('job_openings.title', $request->title_job);
        }

        if (!empty($request->status_candidate)) {
            $datas->where('candidates.status', $request->status_candidate);
        } else {
            $datas->where('candidates.status', 'In Process');
        }

        $data = $datas->get(); // Get results after applying filters
        $jabatan = DB::table('jabatan')
            ->orderBy('nama_jabatan', 'ASC')
            ->get();
        $uniqueBase = Karyawan::whereNotNull('base_poh')->distinct()->pluck('base_poh')->filter();
        $job = DB::table('job_openings')->get();

        return view("recruitment.candidate.datatable", compact('data', 'job', 'jabatan', 'uniqueBase'));
    }

    public function candidate_peningkatan(Request $request)
    {
        // Validation logic remains unchanged
        $request->validate([
            'dataCandidate' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:255',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'required',
            'tgl_masuk' => 'required|date',
            'employee_status' => 'required|string',
            'grade' => 'nullable|string|max:255',
            'base' => 'nullable|string',
            'nama_pt' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'rek_no' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'rek_name' => 'nullable|string|max:255',
        ]);
        try {
            if (!$request->filled('nik')) {
                // Retrieve the last numeric sequence portion of NIK by filtering valid patterns
                $lastSequence = DB::table('karyawan')
                    ->whereNotNull('nik')
                    ->whereRaw("nik REGEXP '^[0-9]{4}-[0-9]{8}$'") // Ensures NIK matches the pattern NNNN-YYYYMMDD
                    ->where("status_kar", "Aktif")
                    ->selectRaw('CAST(SUBSTRING(nik, 1, 4) AS UNSIGNED) as sequence')
                    ->orderByDesc('sequence')
                    ->value('sequence'); // Gets the highest sequence as an integer

                // Increment the sequence or start from 1 if none found, ensuring 4-digit formatting
                $newSequence = str_pad(($lastSequence ?? 0) + 1, 4, '0', STR_PAD_LEFT);

                // Format the date part of the NIK using the input `tgl_masuk`
                $tglMasukFormatted = date('Ymd', strtotime($request->tgl_masuk));

                // Combine the new sequence and date to create the new NIK
                $nik = $newSequence . '-' . $tglMasukFormatted;
            } else {
                $nik = $request->nik;
            }


            $candidateDataId = $request->dataCandidate;
            $candidateData = DB::table('candidate_data')->where('id', $candidateDataId)->first();
            $candidateDetails = DB::table('candidates')->where('id', $candidateData->candidate_id)->first();
            $candidateId = $candidateDetails->id;
            $candidateDataKeluarga = DB::table('candidate_data_keluarga')->where('candidate_data_id', $candidateData->id)->get(); // Get all family data
            $jobOpeningId = DB::table('candidates')->where('id', $candidateId)->value('job_opening_id');
            $kodeDept = DB::table('job_openings')->where('id', $jobOpeningId)->value('kode_dept');
            $sex = ($candidateData->jenis === 'Laki-laki') ? 'M' : 'F';

            $taxStatusMap = [
                'TK' => 'TK',
                'TK1' => 'TK/1',
                'TK2' => 'TK/2',
                'TK3' => 'TK/3',
                'K' => 'K',
                'K1' => 'K/1',
                'K2' => 'K/2',
                'K3' => 'K/3',
            ];
            $taxStatus = $taxStatusMap[$candidateData->status_pajak] ?? $candidateData->status_pajak;

            $latestEducation = DB::table('candidate_data_pendidikan')
                ->where('candidate_data_id', $candidateData->id)
                ->orderBy('id', 'desc') // Assuming 'id' is the primary key and auto-incremented
                ->first();

            // Determine gelar based on tingkat_besar
            $gelarMap = [
                'Dasar' => 'Dasar',
                'SLTP' => 'SLTP',
                'SLTA' => 'SLTA',
                'Diploma' => 'D3',
                'Strata I' => 'S1',
                'Strata II' => 'S2',
            ];

            $gelar = $latestEducation ? ($gelarMap[$latestEducation->tingkat_besar] ?? null) : null;

            // Get the last 3 job experiences
            $jobExperiences = DB::table('candidate_data_pekerjaan')
                ->where('candidate_data_id', $candidateData->id)
                ->orderBy('id', 'desc') // Assuming 'id' is the primary key and auto-incremented
                ->take(3)
                ->get();

            // Format job experiences into a long text
            $jobExpText = $jobExperiences->map(function ($job) {
                return "{$job->dari} - {$job->sampai}: {$job->jabatan} di {$job->perusahaan}";
            })->implode(', '); // Join with a comma

            // Fetch father and mother names
            $fatherData = DB::table('candidate_data_keluarga_sendiri')
                ->where('candidate_data_id', $candidateData->id)
                ->where('uraian', 'Ayah')
                ->first();
            $father_name = $fatherData ? $fatherData->nama_lengkap : null;

            $motherData = DB::table('candidate_data_keluarga_sendiri')
                ->where('candidate_data_id', $candidateData->id)
                ->where('uraian', 'Ibu')
                ->first();
            $mother_name = $motherData ? $motherData->nama_lengkap : null;

            // Initialize additional fields
            $fd_si_name = $fd_si_nik = $fd_si_kota = $fd_si_dob = null;
            $fd_anak1_name = $fd_anak1_nik = $fd_anak1_kota = $fd_anak1_dob = null;
            $fd_anak2_name = $fd_anak2_nik = $fd_anak2_kota = $fd_anak2_dob = null;
            $fd_anak3_name = $fd_anak3_nik = $fd_anak3_kota = $fd_anak3_dob = null;

            // Process family data for spouse and children
            foreach ($candidateDataKeluarga as $familyMember) {
                switch ($familyMember->uraian) {
                    case 'Istri/Suami':
                        $fd_si_name = $familyMember->nama_lengkap ?? null;
                        $fd_si_nik = $familyMember->nik ?? null;
                        $fd_si_kota = $familyMember->tempat_lahir ?? null;
                        $fd_si_dob = $familyMember->tgl_lahir ?? null;
                        break;
                    case 'Anak ke 1':
                        $fd_anak1_name = $familyMember->nama_lengkap ?? null;
                        $fd_anak1_nik = $familyMember->nik ?? null;
                        $fd_anak1_kota = $familyMember->tempat_lahir ?? null;
                        $fd_anak1_dob = $familyMember->tgl_lahir ?? null;
                        break;
                    case 'Anak ke 2':
                        $fd_anak2_name = $familyMember->nama_lengkap ?? null;
                        $fd_anak2_nik = $familyMember->nik ?? null;
                        $fd_anak2_kota = $familyMember->tempat_lahir ?? null;
                        $fd_anak2_dob = $familyMember->tgl_lahir ?? null;
                        break;
                    case 'Anak ke 3':
                        $fd_anak3_name = $familyMember->nama_lengkap ?? null;
                        $fd_anak3_nik = $familyMember->nik ?? null;
                        $fd_anak3_kota = $familyMember->tempat_lahir ?? null;
                        $fd_anak3_dob = $familyMember->tgl_lahir ?? null;
                        break;
                }
            }

            $documents = DB::table('candidate_data_perlengkapan')
                ->where('candidate_data_id', $request->dataCandidate)
                ->first();

            // Check each document status and set the status and file fields accordingly
            $statusPhoto = ($documents->photo_anda ?? 'No_Document') !== 'No_Document' ? 1 : 0;
            $statusKtp = ($documents->photo_ktp ?? 'No_Document') !== 'No_Document' ? 1 : 0;
            $statusKk = ($documents->photo_kk ?? 'No_Document') !== 'No_Document' ? 1 : 0;
            $statusNpwp = ($documents->photo_npwp ?? 'No_Document') !== 'No_Document' ? 1 : 0;
            $statusIjazah = ($documents->photo_ijazah ?? 'No_Document') !== 'No_Document' ? 1 : 0;
            $statusSim = ($documents->photo_sim ?? 'No_Document') !== 'No_Document' ? 1 : 0;
            $statusSkck = ($documents->photo_skck ?? 'No_Document') !== 'No_Document' ? 1 : 0;
            $statusCv = ($documents->photo_cv ?? 'No_Document') !== 'No_Document' ? 1 : 0;

            $karyawanData = [
                'nik' => $nik,
                'nip' => $request->nip,
                'nama_lengkap' => $candidateData->nama_lengkap,
                'jabatan' => $request->jabatan,
                'email' => $candidateData->alamat_email,
                'no_hp' => $candidateData->telp_rumah_hp,
                'tgl_masuk' => $request->tgl_masuk,
                'DOB' => $candidateData->tgl_lahir,
                'kode_dept' => $kodeDept,
                'grade' => $request->grade,
                'shift_pattern_id' => '1',
                'start_shift' => $request->tgl_masuk,
                'employee_status' => $request->employee_status,
                'base_poh' => $request->base,
                'nama_pt' => $request->nama_pt,
                'sex' => $sex,
                'tax_status' => $taxStatus,
                'birthplace' => $candidateData->tempat_lahir,
                'religion' => $request->religion,
                'address' => $candidateData->alamat_rumah,
                'address_rt' => $candidateData->alamat_rt,
                'address_rw' => $candidateData->alamat_rw,
                'address_kel' => $candidateData->alamat_kel,
                'address_kec' => $candidateData->alamat_kec,
                'address_kota' => $candidateData->alamat_kota,
                'address_prov' => $candidateData->alamat_prov,
                'kode_pos' => $candidateData->alamat_pos,
                'nik_ktp' => $candidateData->no_ktp_sim,
                'blood_type' => $candidateData->gol_darah,
                'gelar' => $gelar,
                'major' => $latestEducation ? $latestEducation->jurusan_studi : null,
                'kampus' => $latestEducation ? $latestEducation->tempat_sekolah : null,
                'job_exp' => $jobExpText,
                'email_personal' => $candidateData->alamat_email,
                'family_card' => $documents->no_kartu_keluarga ?? '',
                'no_npwp' => $candidateData->no_npwp,
                'alamat_npwp' => $candidateData->alamat_npwp,
                'father_name' => $father_name,
                'mother_name' => $mother_name,
                'fd_si_name' => $fd_si_name,
                'fd_si_nik' => $fd_si_nik,
                'fd_si_kota' => $fd_si_kota,
                'fd_si_dob' => $fd_si_dob,
                'fd_anak1_name' => $fd_anak1_name,
                'fd_anak1_nik' => $fd_anak1_nik,
                'fd_anak1_kota' => $fd_anak1_kota,
                'fd_anak1_dob' => $fd_anak1_dob,
                'fd_anak2_name' => $fd_anak2_name,
                'fd_anak2_nik' => $fd_anak2_nik,
                'rek_no' => $request->rek_no,
                'rek_name' => $request->rek_name,
                'bank_name' => $request->bank_name,
                'fd_anak2_kota' => $fd_anak2_kota,
                'fd_anak2_dob' => $fd_anak2_dob,
                'fd_anak3_name' => $fd_anak3_name,
                'fd_anak3_nik' => $fd_anak3_nik,
                'fd_anak3_kota' => $fd_anak3_kota,
                'fd_anak3_dob' => $fd_anak3_dob,
                'em_name' => $candidateData->em_nama,
                'em_telp' => $candidateData->em_telp,
                'em_relation' => $candidateData->em_status,
                'em_alamat' => $candidateData->em_alamat,
                'status_kar' => 'Aktif',
                'status_photo' => $statusPhoto,
                'status_ktp' => $statusKtp,
                'status_kk' => $statusKk,
                'status_npwp' => $statusNpwp,
                'status_ijazah' => $statusIjazah,
                'status_sim' => $statusSim,
                'status_skck' => $statusSkck,
                'status_cv' => $statusCv,
                'status_applicant' => '1',
                'file_photo' => $statusPhoto ? $documents->photo_anda : null,
                'file_ktp' => $statusKtp ? $documents->photo_ktp : null,
                'file_kk' => $statusKk ? $documents->photo_kk : null,
                'file_npwp' => $statusNpwp ? $documents->photo_npwp : null,
                'file_ijazah' => $statusIjazah ? $documents->photo_ijazah : null,
                'file_sim' => $statusSim ? $documents->photo_sim : null,
                'file_skck' => $statusSkck ? $documents->photo_skck : null,
                'file_cv' => $statusCv ? $documents->photo_cv : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('karyawan')->insert($karyawanData);

            return redirect()->back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            // Capture additional context for debugging
            $errorDetails = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(), // Full stack trace
                'input' => request()->all(), // Log input data (sensitive data might need filtering)
                'candidateData' => $candidateData,
            ];

            // Log the error with comprehensive details
            Log::error('Error inserting candidate data', $errorDetails);

            // Return the error message to the user
            return redirect()->back()->with('error', 'Failed to convert candidate to karyawan. ' . $e->getMessage());
        }
    }




    public function candidate_data_view(Request $request)
    {
        // Get the currently authenticated candidate

        $candidateId = $request->candidate_id;

        $candidate = DB::table('candidates')->where('id', $candidateId)->first();

        // Check if candidate data exists
        $candidateData = DB::table('candidate_data')->where('candidate_id', $candidateId)->first();


        $candidateDataLengkap = DB::table('candidate_data_perlengkapan')->where('candidate_data_id', $candidateData->id)->first();

        if ($candidateData) {
            $keluargaData = DB::table('candidate_data_keluarga')->where('candidate_data_id', $candidateData->id)->get();
        } else {
            $keluargaData = collect(); // Empty collection if no candidate data
        }

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
        return view('recruitment.candidate.data', compact('candidateData', 'candidateDataLengkap', 'keluargaData', 'candidate', 'candidateId', 'candidateFamilyData', 'candidateKursus', 'candidateBahasa', 'candidatePekerjaan', 'candidateFamilyDataSendiri', 'candidatePendidikan'));
    }

    public function candidate_data_approve(Request $request)
    {
        try {
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

                    // Fetch candidate email and position name
                    $candidateData = DB::table('candidates')
                        ->where('id', $candidateRealId)
                        ->first();

                    $jobOpening = DB::table('job_openings')
                        ->where('id', $candidateData->job_opening_id)
                        ->first();

                    $email = $candidateData->email;
                    $nama_candidate = $candidateData->nama_candidate;
                    $nama_posisi = $jobOpening->title;

                    // Email content
                    $emailContent = "
                        Yth. {$nama_candidate},<br><br>

                        Selamat! Kami dengan senang hati menginformasikan bahwa Anda telah berhasil diterima untuk posisi <b>{$nama_posisi}</b> di <b>PT Cipta Harmoni Lestari.</b> Proses seleksi yang Anda jalani menunjukkan komitmen, kemampuan, dan kecocokan yang luar biasa dengan nilai dan tujuan perusahaan kami.<br><br>

                        Kami sangat antusias untuk menyambut Anda di tim kami dan berharap Anda dapat memberikan kontribusi terbaik bagi kesuksesan bersama. Silahkan lengkapi data administrasi dengan klik link https://hrms.ciptaharmoni.com/candidate dan tunggu info selanjutnya terkait penandatanganan kontrak dan informasi lainnya.<br><br>

                        Sekali lagi, selamat atas pencapaian ini. Kami sangat menantikan untuk bekerja bersama Anda.<br><br>

                        Hormat kami,<br>
                        HR Dept.
                    ";

                    // Send the email
                    try {
                        Mail::html($emailContent, function ($message) use ($email, $nama_candidate, $nama_posisi) {
                            $message->to($email)
                                ->subject("Selamat {$nama_candidate} Anda telah berhasil diterima untuk posisi {$nama_posisi}")
                                ->cc(['human.resources@ciptaharmoni.com', auth()->user()->email])
                                ->priority(1);

                            // Add importance headers
                            $message->getHeaders()->addTextHeader('Importance', 'high');
                            $message->getHeaders()->addTextHeader('X-Priority', '1');
                        });
                    } catch (\Exception $e) {
                        return Redirect::back()->with(['warning' => 'Failed to send email: ' . $e->getMessage()]);
                    }
                } elseif ($newStatus === 'Declined') {
                    DB::table('candidates')
                        ->where('id', $candidateRealId) // Using the candidate_id from candidate_data
                        ->update(['verify_offer' => 0]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Candidate status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ]);
        }
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

    public function printCandidateData($id)
    {
        // Fetch candidate data along with job opening, hiring stage
        $candidates = DB::table('candidate_data')
            ->join('candidates', 'candidate_data.candidate_id', '=', 'candidates.id')
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
            ->join('hiring_stages', 'candidates.current_stage_id', '=', 'hiring_stages.id')
            ->select('candidate_data.*', 'job_openings.title as job_opening_name', 'hiring_stages.name as hiring_stage_name', 'candidates.id as candidate_id', 'candidates.nama_candidate as nama_candidate')
            ->where('candidate_data.id', $id)
            ->first();

        // Fetch family data separately
        $familyData = DB::table('candidate_data_keluarga')
            ->where('candidate_data_id', $candidates->id)
            ->get();

        // Initialize the family members array with empty values
        $familyMembers = [
            ['uraian' => '', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => '', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => '', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => '', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
        ];

        // Fill the family members with actual data
        foreach ($familyData as $index => $member) {
            if ($index < 4) { // Only fill up to 4 rows
                $familyMembers[$index] = [
                    'uraian' => $member->uraian,
                    'nama_lengkap' => $member->nama_lengkap,
                    'jenis' => $member->jenis,
                    'tgl_lahir' => DateHelper::formatIndonesiaDate($member->tgl_lahir),
                    'pendidikan' => $member->pendidikan,
                    'pekerjaan' => $member->pekerjaan,
                    'keterangan' => $member->keterangan,
                ];
            }
        }


        $familyData1 = DB::table('candidate_data_keluarga_sendiri')
            ->where('candidate_data_id', $candidates->id)
            ->get();

        // Initialize the family members array with empty values
        $familyMembers1 = [
            ['uraian' => 'Ayah', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => 'Ibu', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => 'Anak ke 1', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => 'Anak ke 2', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => 'Anak ke 3', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => 'Anak ke 4', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => 'Anak ke 5', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => 'Anak ke 6', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
        ];

        // Fill the family members with actual data
        foreach ($familyData1 as $index => $member1) {
            if ($index < 8) { // Only fill up to 4 rows
                $familyMembers1[$index] = [
                    'uraian' => $member1->uraian,
                    'nama_lengkap' => $member1->nama_lengkap,
                    'jenis' => $member1->jenis,
                    'tgl_lahir' => DateHelper::formatIndonesiaDate($member1->tgl_lahir),
                    'pendidikan' => $member1->pendidikan,
                    'pekerjaan' => $member1->pekerjaan,
                    'keterangan' => $member1->keterangan,
                ];
            }
        }


        $pendidikanData = DB::table('candidate_data_pendidikan')
            ->where('candidate_data_id', $candidates->id)
            ->get();

        // Initialize the family members array with empty values
        $pendidikanList = [
            ['tingkat_besar' => 'Dasar', 'nama_sekolah' => '', 'tempat_sekolah' => '', 'jurusan_studi' => '', 'berijazah' => '', 'dari_sampai' => '', 'keterangan' => ''],
            ['tingkat_besar' => 'SLTP', 'nama_sekolah' => '', 'tempat_sekolah' => '', 'jurusan_studi' => '', 'berijazah' => '', 'dari_sampai' => '', 'keterangan' => ''],
            ['tingkat_besar' => 'SLTA', 'nama_sekolah' => '', 'tempat_sekolah' => '', 'jurusan_studi' => '', 'berijazah' => '', 'dari_sampai' => '', 'keterangan' => ''],
            ['tingkat_besar' => 'Diploma', 'nama_sekolah' => '', 'tempat_sekolah' => '', 'jurusan_studi' => '', 'berijazah' => '', 'dari_sampai' => '', 'keterangan' => ''],
            ['tingkat_besar' => 'Strata I', 'nama_sekolah' => '', 'tempat_sekolah' => '', 'jurusan_studi' => '', 'berijazah' => '', 'dari_sampai' => '', 'keterangan' => ''],
            ['tingkat_besar' => 'Strata II', 'nama_sekolah' => '', 'tempat_sekolah' => '', 'jurusan_studi' => '', 'berijazah' => '', 'dari_sampai' => '', 'keterangan' => ''],
            ['tingkat_besar' => 'Lain-ain', 'nama_sekolah' => '', 'tempat_sekolah' => '', 'jurusan_studi' => '', 'berijazah' => '', 'dari_sampai' => '', 'keterangan' => ''],
        ];

        // Fill the family members with actual data
        foreach ($pendidikanData as $index => $pendidikan) {
            if ($index < 8) { // Only fill up to 4 rows
                $pendidikanList[$index] = [
                    'tingkat_besar' => $pendidikan->tingkat_besar,
                    'nama_sekolah' => $pendidikan->nama_sekolah,
                    'tempat_sekolah' => $pendidikan->tempat_sekolah,
                    'jurusan_studi' => $pendidikan->jurusan_studi,
                    'berijazah' => $pendidikan->berijazah,
                    'dari_sampai' => date('d M y', strtotime($pendidikan->dari)) . ' - ' . date('d M y', strtotime($pendidikan->sampai)),
                    'keterangan' => $pendidikan->keterangan,
                ];
            }
        }

        $kursusData = DB::table('candidate_data_kursus')
            ->where('candidate_data_id', $candidates->id)
            ->get();

        // Initialize the family members array with empty values
        $kursusList = [
            ['nama' => '', 'diadakan_oleh' => '', 'tempat' => '', 'lama' => '', 'tahun' => '', 'dibiayai_oleh' => '', 'keterangan' => ''],
            ['nama' => '', 'diadakan_oleh' => '', 'tempat' => '', 'lama' => '', 'tahun' => '', 'dibiayai_oleh' => '', 'keterangan' => ''],
            ['nama' => '', 'diadakan_oleh' => '', 'tempat' => '', 'lama' => '', 'tahun' => '', 'dibiayai_oleh' => '', 'keterangan' => ''],
            ['nama' => '', 'diadakan_oleh' => '', 'tempat' => '', 'lama' => '', 'tahun' => '', 'dibiayai_oleh' => '', 'keterangan' => ''],
            ['nama' => '', 'diadakan_oleh' => '', 'tempat' => '', 'lama' => '', 'tahun' => '', 'dibiayai_oleh' => '', 'keterangan' => ''],
            ['nama' => '', 'diadakan_oleh' => '', 'tempat' => '', 'lama' => '', 'tahun' => '', 'dibiayai_oleh' => '', 'keterangan' => ''],
            ['nama' => '', 'diadakan_oleh' => '', 'tempat' => '', 'lama' => '', 'tahun' => '', 'dibiayai_oleh' => '', 'keterangan' => ''],
        ];

        // Fill the family members with actual data
        foreach ($kursusData as $index => $kursus) {
            if ($index < 7) { // Only fill up to 4 rows
                $kursusList[$index] = [
                    'nama' => $kursus->nama,
                    'diadakan_oleh' => $kursus->diadakan_oleh,
                    'tempat' => $kursus->tempat,
                    'lama' => $kursus->lama,
                    'tahun' => $kursus->tahun,
                    'dibiayai_oleh' => $kursus->dibiayai_oleh,
                    'keterangan' => $kursus->keterangan,
                ];
            }
        }

        $bahasaData = DB::table('candidate_data_bahasa')
            ->where('candidate_data_id', $candidates->id)
            ->get();

        // Initialize the family members array with empty values
        $bahasaList = [
            ['bahasa' => '', 'bicara' => '', 'baca' => '', 'tulis' => '', 'steno' => ''],
            ['bahasa' => '', 'bicara' => '', 'baca' => '', 'tulis' => '', 'steno' => ''],
            ['bahasa' => '', 'bicara' => '', 'baca' => '', 'tulis' => '', 'steno' => ''],
        ];

        // Fill the family members with actual data
        foreach ($bahasaData as $index => $bahasa) {
            if ($index < 7) { // Only fill up to 4 rows
                $bahasaList[$index] = [
                    'bahasa' => $bahasa->bahasa,
                    'bicara' => $bahasa->bicara,
                    'baca' => $bahasa->baca,
                    'tulis' => $bahasa->tulis,
                    'steno' => $bahasa->steno,
                ];
            }
        }

        $pekerjaanData = DB::table('candidate_data_pekerjaan')
            ->where('candidate_data_id', $candidates->id)
            ->get();

        $pekerjaanList = [
            ['perusahaan' => '', 'alamat' => '', 'jabatan' => '', 'dari' => '', 'sampai' => '', 'keterangan' => '', 'alasan' => ''],
            ['perusahaan' => '', 'alamat' => '', 'jabatan' => '', 'dari' => '', 'sampai' => '', 'keterangan' => '', 'alasan' => ''],
            ['perusahaan' => '', 'alamat' => '', 'jabatan' => '', 'dari' => '', 'sampai' => '', 'keterangan' => '', 'alasan' => ''],
            ['perusahaan' => '', 'alamat' => '', 'jabatan' => '', 'dari' => '', 'sampai' => '', 'keterangan' => '', 'alasan' => ''],
            ['perusahaan' => '', 'alamat' => '', 'jabatan' => '', 'dari' => '', 'sampai' => '', 'keterangan' => '', 'alasan' => ''],
            ['perusahaan' => '', 'alamat' => '', 'jabatan' => '', 'dari' => '', 'sampai' => '', 'keterangan' => '', 'alasan' => ''],
            ['perusahaan' => '', 'alamat' => '', 'jabatan' => '', 'dari' => '', 'sampai' => '', 'keterangan' => '', 'alasan' => ''],
            ['perusahaan' => '', 'alamat' => '', 'jabatan' => '', 'dari' => '', 'sampai' => '', 'keterangan' => '', 'alasan' => ''],
        ];

        // Fill the family members with actual data
        foreach ($pekerjaanData as $index => $pekerjaan) {
            if ($index < 8) { // Only fill up to 4 rows
                $pekerjaanList[$index] = [
                    'perusahaan' => $pekerjaan->perusahaan,
                    'alamat' => $pekerjaan->alamat,
                    'jabatan' => $pekerjaan->jabatan,
                    'dari' => $pekerjaan->dari,
                    'sampai' => $pekerjaan->sampai,
                    'keterangan' => $pekerjaan->keterangan,
                    'alasan' => $pekerjaan->alasan,
                ];
            }
        }

        // Address formatting logic (same as before)
        $wrappedAddress = wordwrap($candidates->alamat_rumah, 60, "\n", true);
        $addressParts = explode("\n", $wrappedAddress);
        $addressLine1 = $addressParts[0] ?? '';
        $addressLine2 = $addressParts[1] ?? '';
        $addressLine3 = $addressParts[2] ?? '';

        $wrappedAddress1 = wordwrap($candidates->alamat_perusahaan, 60, "\n", true);
        $addressParts1 = explode("\n", $wrappedAddress1);
        $addressLine4 = $addressParts1[0] ?? '';
        $addressLine5 = $addressParts1[1] ?? '';

        $penjelasan1 = substr($candidates->penjelasan_pendidikan, 0, 30);
        $penjelasan2 = substr($candidates->penjelasan_pendidikan, 30);

        $wrappedAddress2 = wordwrap($candidates->alasan_pekerjaan_terakhir, 100, "\n", true);
        $addressParts2 = explode("\n", $wrappedAddress2);
        $alasan1 = $addressParts2[0] ?? '';
        $alasan2 = $addressParts2[1] ?? '';
        $alasan3 = $addressParts2[2] ?? '';

        $wrappedAddress3 = wordwrap($candidates->uraian_pekerjaan_terakhir, 100, "\n", true);
        $addressParts3 = explode("\n", $wrappedAddress3);
        $alasan4 = $addressParts3[0] ?? '';
        $alasan5 = $addressParts3[1] ?? '';
        $alasan6 = $addressParts3[2] ?? '';


        $combinedEm = $candidates->em_nama . ' , ' . $candidates->em_alamat . ' , ' . $candidates->em_telp . ' , ' . $candidates->em_status;

        // Find the last space within the first 45 characters
        $limit = 50;
        $breakpoint = strrpos(substr($combinedEm, 0, $limit), ' ');

        if ($breakpoint !== false) {
            // Split the text at the last space before the limit
            $penjelasan3 = substr($combinedEm, 0, $breakpoint);
            $penjelasan4 = substr($combinedEm, $breakpoint + 1); // +1 to skip the space
        } else {
            // If there's no space within the limit, break at the limit
            $penjelasan3 = substr($combinedEm, 0, $limit);
            $penjelasan4 = substr($combinedEm, $limit);
        }

        return view('recruitment.candidate.print', compact('candidates', 'penjelasan3', 'penjelasan4', 'alasan1', 'alasan2', 'alasan3', 'alasan4', 'alasan5', 'alasan6', 'pekerjaanList', 'bahasaList', 'kursusList', 'penjelasan1', 'penjelasan2', 'addressLine1', 'pendidikanList', 'addressLine2', 'addressLine3', 'addressLine4', 'addressLine5', 'familyMembers', 'familyMembers1'));
    }
}
