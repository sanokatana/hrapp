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
use Illuminate\Support\Facades\Storage;

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


        if (!empty($request->candidate_name)) {
            $candidates->where('candidates.nama_candidate', 'like', '%' . $request->candidate_name . '%');
        }

        if (!empty($request->title_job)) {
            $candidates->where('job_openings.title', $request->title_job);
        }

        if ($request->has('status_candidate')) {
            if ($request->status_candidate === 'In Process' || $request->status_candidate === 'Hired' || $request->status_candidate === 'Rejected') {
                $candidates->where('candidates.status', $request->status_candidate);
            }
        } else {
            // Default to '0' (Pending) if no status_approved_hrd is provided
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
        $job_opening = DB::table('job_openings')
            ->select('title', 'recruitment_type_id')
            ->where('id', $job_opening_id)
            ->first();

        // Fetch the first stage ID for this recruitment type
        $firstStage = DB::table('hiring_stages')
            ->where('recruitment_type_id', $job_opening->recruitment_type_id)
            ->orderBy('sequence', 'asc')  // Order by sequence to get the first stage
            ->first();

        // Set the current stage to the first stage of this recruitment type
        $current_stage_id = $firstStage ? $firstStage->id : 1; // Fallback to 1 if no stages defined
        $status = 'In Process';
        $email_user = Auth::guard('user')->user()->email;

        // Generate username from nama_candidate
        $username = $this->generateUsername($nama_candidate);

        // Generate a simple random password
        $password = Str::random(6); // 6-character alphanumeric password

        // Fetch the job opening title using the job_opening_id
        $job_opening = DB::table('job_openings')
            ->select('title', 'recruitment_type_id')
            ->where('id', $job_opening_id)
            ->first();

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
            $position = $job_opening->title;
            $formattedInterviewDate = DateHelper::formatIndonesianDate($interview_date);

            // Base email content
            $emailContent = <<<EOD
            Kepada Yth.<br>
            Bpk/Ibu/Sdr/i {$nama_candidate}<br>
            Di Tempat<br><br>
            Dengan hormat,<br>
            EOD;

            // Add different process description based on recruitment type
            if ($job_opening->recruitment_type_id == 2) {
                // Internship - without psychotest
                $emailContent .= <<<EOD
                Berdasarkan Aplikasi saudara, dengan ini kami mengundang  anda untuk mengikuti proses <b><i>Recruitment</i> di PT Cipta Harmoni Lestari Group</b> melalui 2 tahapan berikut:<br><br>

                1. Silahkan <b>mengisi data pribadi</b> saudara melalui link website kami <a href="http://hrms.ciptaharmoni.com/candidate">hrms.ciptaharmoni.com/candidate</a> dengan kode akses:
                <ul>
                    <li>Username &nbsp;&nbsp;: {$username}</li>
                    <li>Password &nbsp;&nbsp;&nbsp;: {$password}</li>
                    <li>Jika anda mengalami kesulitan dalam pengisiannya bisa di lihat dalam link Video tutorial berikut, https://drive.google.com/file/d/1bFizRnN5JR454qeRknmaGdTtmGMnZMnH/view?usp=drive_link</li>
                </ul>

                2. Informasi <b>jadwal interview</b> yang akan dilaksanakan pada,<br>
                EOD;
            } else {
                // Regular recruitment - with psychotest
                $emailContent .= <<<EOD
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
                EOD;
            }

            // Common email content continuation
            $emailContent .= <<<EOD
                <ul>
                    <li>Hari & Tanggal    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$formattedInterviewDate}</li>
                    <li>Waktu             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$interview_time} - Selesai</li>
                    <li>Posisi di lamar   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$position}</li>
                    <li>Interviewer       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$interviewer}</li>
                    <li>Alamat            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: CHL Group Marketing Lounge,<br>Ruko Sorrento Place No. 18-19 PJQJ+R8G, Jl. Ir.Sukarno, Curug Sangereng, Kec.Klp. Dua, Kabupaten Tangerang, Banten 15810. https://goo.gl/maps/Ko81dv9gxMHmMC7p9 </li>
                </ul>

                <b>CATATAN:</b><br>
                <ul>
            EOD;

            // Add different notes based on recruitment type
            if ($job_opening->recruitment_type_id == 2) {
                $emailContent .= <<<EOD
                    <li>Setelah mengisis <b>data pribadi</b> agar menginformasikan kepada kami via <i>WhatsApp</i> di nomor: 0813 8500 0789</li>
                    <li><b>Data pribadi</b> agar di isi dihari yang sama pada saat terima email ini</li>
                    <li>Harap hadir 10 menit sebelum jadwal pelaksanaan <b>interview</b>.</li>
                EOD;
            } else {
                $emailContent .= <<<EOD
                    <li>Setelah mengisis <b>data pribadi</b> dan mengerjakan <b>psikotest online</b> agar menginformasikan kepada kami via <i>WhatsApp</i> di nomor: 0813 8500 0789</li>
                    <li><b>Data pribadi</b> dan <b>psikotest online</b> agar di isi dihari yang sama pada saat terima email ini</li>
                    <li>Harap hadir 10 menit sebelum jadwal pelaksanaan <b>interview</b>.</li>
                    <li>Kandidat <b>Markom</b> dan <b>Architect</b> agar menyiapkan bahan <b>presentasi</b> portopolio dengan membawa <b>laptop</b> pribadi.</li>
                EOD;
            }

            // Common email footer
            $emailContent .= <<<EOD
                </ul>

                <br><br>
                Best regards,<br>
                Zicki Darmawan<br>
                HR CHL Group<br>
                <a href="https://www.ciptaharmoni.com/">www.ciptaharmoni.com</a><br>
            EOD;

            // Send email
            try {
                $subject = $job_opening->recruitment_type_id == 2
                    ? "{$nama_candidate}, Selamat Datang di Program Magang CHL Group untuk posisi {$position}"
                    : "{$nama_candidate}, Selamat Datang di Proses Rekrutmen CHL Group untuk posisi {$position}";

                Mail::html($emailContent, function ($message) use ($email, $email_user, $subject) {
                    $message->to($email)
                        ->subject($subject)
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
            ->orderBy('job_openings.id', 'DESC')
            ->get();

        $department = DB::table('department')->get();
        $recruitment_type = DB::table('recruitment_types')->get();
        $jabatan = DB::table('jabatan')->orderBy('nama_jabatan', 'ASC')->get();

        return view("recruitment.job.index", compact('job', 'department', 'recruitment_type', 'jabatan'));
    }


    public function job_opening_store(Request $request)
    {
        $data = [
            'jabatan_id' => $request->jabatan_id,
            'title' => $request->title,
            'description' => $request->description,
            'recruitment_type_id' => $request->recruitment_type_id,
            'kode_dept' => $request->kode_dept,
            'status' => 'Open',
            'site' => $request->site
        ];

        try {
            DB::table('job_openings')->insert($data);
            return Redirect::back()->with(['success' => 'Job Opening Successfully Saved']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Failed to Save Job Opening: ' . $e->getMessage()]);
        }
    }

    public function job_opening_edit(Request $request)
    {
        $id = $request->id;
        $job = DB::table('job_openings')->where('id', $id)->first();
        $department = DB::table('department')->get();
        $recruitment_type = DB::table('recruitment_types')->get();
        $jabatan = DB::table('jabatan')->orderBy('nama_jabatan', 'ASC')->get();

        return view("recruitment.job.edit", compact('job', 'department', 'recruitment_type', 'jabatan'));
    }

    public function job_opening_update($id, Request $request)
    {
        $data = [
            'jabatan_id' => $request->jabatan_id,
            'title' => $request->title,
            'description' => $request->description,
            'recruitment_type_id' => $request->recruitment_type_id,
            'kode_dept' => $request->kode_dept,
            'status' => $request->status,
            'site' => $request->site
        ];

        try {
            DB::table('job_openings')->where('id', $id)->update($data);
            return Redirect::back()->with(['success' => 'Job Opening Successfully Updated']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Failed to Update Job Opening: ' . $e->getMessage()]);
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
        $recruitmentData = [];

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
                    ->leftJoin('candidate_data', 'candidates.id', '=', 'candidate_data.candidate_id') // Changed to leftJoin
                    ->where('candidates.current_stage_id', $stage->id)
                    ->where('candidates.status', 'In Process')
                    ->select(
                        'candidates.id',
                        'candidates.nama_candidate',
                        'candidates.email',
                        'candidates.status',
                        DB::raw('COALESCE(candidate_data.status_form, "Pending") as status_form'), // Default value if null
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

        $jabatan = DB::table('jabatan')
            ->orderBy('nama_jabatan', 'ASC')
            ->get();
        $uniqueBase = Karyawan::whereNotNull('base_poh')->distinct()->pluck('base_poh')->filter();
        $job = DB::table('job_openings')->get();


        return view("recruitment.pipeline.index", compact('recruitmentData', 'jabatan', 'uniqueBase', 'job'));
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

            Sebagai proses lanjutan dari proses rekrutmen di CHL Group, saya undang untuk datang ke kantor sesi Final Interview pada,<br>
            <ul>
                <li>Hari & Tanggal    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$formattedInterviewDate}</li>
                <li>Waktu             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$interview_time} - Selesai</li>
                <li>Posisi di lamar   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$nama_posisi}</li>
                <li>Tahap Interview   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$stage_interview_name}</li>
                <li>Interviewer       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
            EOD;

            // Conditionally format the interviewer text
            if (empty($interviewer2)) {
                $emailContent .= "{$interviewer}";
            } elseif ($interviewer == $interviewer2) {
                $emailContent .= "{$interviewer}";
            } else {
                $emailContent .= "{$interviewer} & {$interviewer2}";
            }

            $emailContent .= <<<EOD
            </li>
                <li>Alamat            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: CHL Group Marketing Lounge,<br>
                Ruko Sorrento Place No. 18-19 PJQJ+R8G, Jl. Ir.Sukarno, Curug Sangereng, Kec.Klp. Dua, Kabupaten Tangerang, Banten 15810.
                <a href='https://goo.gl/maps/Ko81dv9gxMHmMC7p9'>Google Maps</a><br>
                Harap hadir 15 menit sebelum waktu yang telah ditentukan.</li>
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
                    ->subject("{$nama_candidate} - Jadwal Final Interview Anda di CHL Group")
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
        }

        if ($request->has('status_candidate')) {
            if ($request->status_candidate === 'In Process' || $request->status_candidate === 'Hired' || $request->status_candidate === 'Rejected') {
                $datas->where('candidates.status', $request->status_candidate);
            }
        } else {
            // Default to '0' (Pending) if no status_approved_hrd is provided
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
            'grade' => 'nullable|string|max:255',
            'nama_pt' => 'nullable|string|max:255',
            'rek_no' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'rek_name' => 'nullable|string|max:255',
        ]);
        try {

            // Get candidate data
            $candidateDataId = $request->dataCandidate;
            $candidateData = DB::table('candidate_data')->where('id', $candidateDataId)->first();

            if (!$candidateData) {
                throw new \Exception("Candidate data not found");
            }

            $candidateDetails = DB::table('candidates')->where('id', $candidateData->candidate_id)->first();

            // Get job opening and jabatan details
            $jobOpening = DB::table('job_openings')
                ->join('jabatan', 'job_openings.jabatan_id', '=', 'jabatan.id')
                ->where('job_openings.id', $candidateDetails->job_opening_id)
                ->select('jabatan.*', 'job_openings.kode_dept')
                ->first();

            $recruitmentTypeId = DB::table('job_openings')
                ->where('id', $candidateDetails->job_opening_id)
                ->value('recruitment_type_id');

            $employeeStatus = ($recruitmentTypeId == 2) ? 'Internship' : 'Kontrak';

            if (!$jobOpening) {
                throw new \Exception("Job opening details not found");
            }

            // Get tanggal_masuk from candidate_perlengkapan
            $perlengkapan = DB::table('candidate_data_perlengkapan')
                ->where('candidate_data_id', $candidateDataId)
                ->first();

            $tglMasuk = $perlengkapan->tanggal_masuk ?? now()->format('Y-m-d');

            // Handle NIK generation/assignment
            if (empty($request->nik)) {
                // Get recruitment_type_id from job opening
                $recruitmentTypeId = DB::table('job_openings')
                    ->where('id', $candidateDetails->job_opening_id)
                    ->value('recruitment_type_id');

                // Automatic NIK generation
                $lastSequence = DB::table('karyawan')
                    ->whereNotNull('nik')
                    ->whereRaw("nik REGEXP '^[0-9]{4}-[0-9]{8}$'")
                    ->where("status_kar", "Aktif")
                    ->selectRaw('CAST(SUBSTRING(nik, 1, 4) AS UNSIGNED) as sequence')
                    ->orderByDesc('sequence')
                    ->value('sequence');

                $newSequence = str_pad(($lastSequence ?? 0) + 1, 4, '0', STR_PAD_LEFT);
                $tglMasukFormatted = date('Ymd', strtotime($tglMasuk));

                // Add 'M' suffix for internship (recruitment_type_id = 2)
                if ($recruitmentTypeId == 2) {
                    $nik = $newSequence . '-' . $tglMasukFormatted . 'M';
                } else {
                    $nik = $newSequence . '-' . $tglMasukFormatted;
                }
            } else {
                $nik = $request->nik;
            }

            // Handle NIP generation/assignment
            if (empty($request->nip)) {
                // Get the 10 most recent active employees
                $recentNips = DB::table('karyawan')
                    ->where('status_kar', 'Aktif')
                    ->whereNotNull('nip')
                    ->orderBy('id', 'DESC')
                    ->limit(10)
                    ->pluck('nip')
                    ->toArray();

                if (empty($recentNips)) {
                    // If no NIPs exist, start from 1
                    $nip = '0001';
                } else {
                    // Convert NIPs to integers and find the highest
                    $numericNips = array_map('intval', $recentNips);
                    $highestNip = max($numericNips);

                    // Increment the highest NIP
                    $nip = str_pad($highestNip + 1, 4, '0', STR_PAD_LEFT);
                }
            } else {
                $nip = $request->nip;
            }

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

            $karyawanData = [
                'nik' => $nik,
                'nip' => $nip,
                'nama_lengkap' => $candidateData->nama_lengkap,
                'jabatan' => $jobOpening->id,
                'email' => $candidateData->alamat_email,
                'no_hp' => $candidateData->telp_rumah_hp,
                'tgl_masuk' => $tglMasuk,
                'DOB' => $candidateData->tgl_lahir,
                'kode_dept' => $kodeDept,
                'grade' => $request->grade,
                'shift_pattern_id' => '1',
                'start_shift' => $request->tgl_masuk,
                'employee_status' => $employeeStatus,
                'base_poh' => $jobOpening->site,
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
                'status_applicant' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // THEN do the file handling
            $candidateId = $candidateData->candidate_id;
            $nama_candidate = Str::slug($candidateData->nama_lengkap);
            $sourceFolder = storage_path("public/uploads/candidate/{$candidateId}.{$nama_candidate}");
            $destinationFolder = storage_path("public/uploads/karyawan/{$nik}.{$candidateData->nama_lengkap}/files");

            // Create destination directory if it doesn't exist
            if (!file_exists($destinationFolder)) {
                mkdir($destinationFolder, 0755, true);
            }

            // Define the fields to copy and their corresponding database fields
            $fileFields = [
                'photo_ktp' => 'file_ktp',
                'photo_kk' => 'file_kk',
                'photo_sim' => 'file_sim',
                'photo_npwp' => 'file_npwp',
                'photo_ijazah' => 'file_ijazah',
                'photo_cv' => 'file_cv',
                'photo_skck' => 'file_skck',
                'photo_anda' => 'file_photo'
            ];

            // Get documents if they exist
            $documents = DB::table('candidate_data_perlengkapan')
                ->where('candidate_data_id', $request->dataCandidate)
                ->first();

            // Handle file fields whether documents exist or not
            foreach ($fileFields as $sourceField => $destField) {
                if ($documents && $documents->$sourceField && $documents->$sourceField !== 'No_Document') {
                    $sourceFile = $sourceFolder . '/' . $documents->$sourceField;

                    if (file_exists($sourceFile)) {
                        try {
                            // Use the original filename for both copying and database storage
                            $originalFileName = $documents->$sourceField;
                            $destinationFile = $destinationFolder . '/' . $originalFileName;

                            // Copy the file
                            if (copy($sourceFile, $destinationFile)) {
                                // Store the original filename in the database
                                $karyawanData[$destField] = $originalFileName;
                                $karyawanData["status_" . substr($destField, 5)] = 1;
                            } else {
                                throw new \Exception("Failed to copy file");
                            }
                        } catch (\Exception $e) {
                            Log::error("Failed to copy file {$sourceField}", [
                                'source' => $sourceFile,
                                'destination' => $destinationFile,
                                'error' => $e->getMessage()
                            ]);
                            $karyawanData[$destField] = 'No_Document';
                            $karyawanData["status_" . substr($destField, 5)] = 0;
                        }
                    } else {
                        $karyawanData[$destField] = 'No_Document';
                        $karyawanData["status_" . substr($destField, 5)] = 0;
                    }
                } else {
                    // Set default values when no documents exist
                    $karyawanData[$destField] = 'No_Document';
                    $karyawanData["status_" . substr($destField, 5)] = 0;
                }
            }

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
        $candidateId = $request->candidate_id;

        // Get candidate with recruitment_type_id
        $candidate = DB::table('candidates')
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
            ->where('candidates.id', $candidateId)
            ->select('candidates.*', 'job_openings.recruitment_type_id')
            ->first();

        // Check if candidate data exists
        $candidateData = DB::table('candidate_data')->where('candidate_id', $candidateId)->first();

        $candidateDataLengkap = DB::table('candidate_data_perlengkapan')
            ->where('candidate_data_id', $candidateData->id)
            ->first();

        if ($candidateData) {
            $keluargaData = DB::table('candidate_data_keluarga')
                ->where('candidate_data_id', $candidateData->id)
                ->get();
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

        $data = compact(
            'candidateData',
            'candidateDataLengkap',
            'keluargaData',
            'candidate',
            'candidateId',
            'candidateFamilyData',
            'candidateKursus',
            'candidateBahasa',
            'candidatePekerjaan',
            'candidateFamilyDataSendiri',
            'candidatePendidikan'
        );

        // Choose view based on recruitment_type_id
        if ($candidate->recruitment_type_id == 2) {
            return view('recruitment.candidate.dataIntern', $data);
        }

        return view('recruitment.candidate.data', $data);
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

                    $candidateRecord = DB::table('candidates')
                        ->where('id', $candidateRealId)
                        ->first();

                    $temporaryPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

                    DB::table('candidates')
                        ->where('id', $candidateRealId)
                        ->update([
                            'verify_offer' => 1,
                            'temp_pass' => Hash::make($temporaryPassword)
                        ]);

                    // Fetch candidate email and position name
                    $jobOpening = DB::table('job_openings')
                        ->where('id', $candidateRecord->job_opening_id)
                        ->first();

                    $email = $candidateRecord->email;
                    $nama_candidate = $candidateRecord->nama_candidate;
                    $nama_posisi = $jobOpening->title;
                    $username = $candidateRecord->username;

                    // Email content
                    $emailContent = "
                        Yth. {$nama_candidate},<br><br>

                        Selamat! Kami dengan senang hati menginformasikan bahwa Anda telah berhasil diterima untuk posisi <b>{$nama_posisi}</b> di <b>PT Cipta Harmoni Lestari.</b> Proses seleksi yang Anda jalani menunjukkan komitmen, kemampuan, dan kecocokan yang luar biasa dengan nilai dan tujuan perusahaan kami.<br><br>

                        Berikut adalah kredensial login Anda untuk mengakses sistem:<br>
                        Username: <b>{$username}</b><br>
                        Password Sementara: <b>{$temporaryPassword}</b><br><br>

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
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data = [
            // Total counts
            'totalRecruits' => DB::table('candidates')->count(),
            'recruitsInProcess' => DB::table('candidates')
                ->where('status', 'In Process')
                ->count(),
            'totalHired' => DB::table('candidates')
                ->where('status', 'Hired')
                ->count(),
            'totalDeclined' => DB::table('candidates')
                ->where('status', 'Rejected')
                ->count(),

            // This month's stats
            'recruitsThisMonth' => DB::table('candidates')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            'hiredThisMonth' => DB::table('candidates')
                ->where('status', 'Hired')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),

            // Job openings
            'totalJobOpenings' => DB::table('job_openings')->count(),
            'openJobOpenings' => DB::table('job_openings')
                ->where('status', 'Open')
                ->count(),
        ];

        // Add monthly trends data
        $monthlyTrends = $this->getMonthlyTrends();

        // Add department statistics
        $departmentStats = $this->getDepartmentStats();

        // Add recent applications
        // In your dashboard method, update the recent applications query
        $recentApplications = DB::table('candidates')
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
            ->join('department', 'job_openings.kode_dept', '=', 'department.kode_dept')
            ->join('jabatan', 'job_openings.jabatan_id', '=', 'jabatan.id')  // Add this join
            ->select(
                'candidates.nama_candidate',  // Changed from nama_candidate
                'jabatan.nama_jabatan',     // Get position name from jabatan
                'department.nama_dept',
                'candidates.created_at',
                'candidates.status'
            )
            ->orderBy('candidates.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('recruitment.index', array_merge($data, [
            'monthlyTrends' => $monthlyTrends,
            'departmentStats' => $departmentStats,
            'recentApplications' => $recentApplications
        ]));
    }

    private function getMonthlyTrends()
    {
        $months = [];
        $applications = [];
        $hired = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $applications[] = DB::table('candidates')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $hired[] = DB::table('candidates')
                ->where('status', 'Hired')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return [
            'months' => $months,
            'applications' => $applications,
            'hired' => $hired
        ];
    }

    private function getDepartmentStats()
    {
        $stats = DB::table('candidates')
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
            ->join('department', 'job_openings.kode_dept', '=', 'department.kode_dept')
            ->select('department.nama_dept', DB::raw('count(*) as count'))
            ->groupBy('department.nama_dept')
            ->get();

        return [
            'departments' => $stats->pluck('nama_dept'),
            'counts' => $stats->pluck('count')
        ];
    }

    public function printCandidateData($id)
    {
        // Fetch candidate data along with job opening, hiring stage
        $candidates = DB::table('candidate_data')
            ->join('candidates', 'candidate_data.candidate_id', '=', 'candidates.id')
            ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
            ->join('hiring_stages', 'candidates.current_stage_id', '=', 'hiring_stages.id')
            ->select(
                'candidate_data.*',
                'job_openings.title as job_opening_name',
                'job_openings.recruitment_type_id', // Add this line
                'hiring_stages.name as hiring_stage_name',
                'candidates.id as candidate_id',
                'candidates.nama_candidate as nama_candidate'
            )
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


        // Replace the static familyMembers1 initialization with this dynamic version
        $familyData1 = DB::table('candidate_data_keluarga_sendiri')
            ->where('candidate_data_id', $candidates->id)
            ->get();

        // Start with parents (these are always included)
        $familyMembers1 = [
            ['uraian' => 'Ayah', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
            ['uraian' => 'Ibu', 'nama_lengkap' => '', 'jenis' => '', 'tgl_lahir' => '', 'pendidikan' => '', 'pekerjaan' => '', 'keterangan' => ''],
        ];

        // Count how many "Anak" entries exist in the database
        $anakCount = DB::table('candidate_data_keluarga_sendiri')
            ->where('candidate_data_id', $candidates->id)
            ->where('uraian', 'LIKE', 'Anak ke%')
            ->count();

        // Add array elements for each child that exists
        for ($i = 1; $i <= max(1, $anakCount); $i++) { // At least 1 child slot, or more if they exist
            $familyMembers1[] = [
                'uraian' => 'Anak ke ' . $i,
                'nama_lengkap' => '',
                'jenis' => '',
                'tgl_lahir' => '',
                'pendidikan' => '',
                'pekerjaan' => '',
                'keterangan' => ''
            ];
        }

        // Fill the family members with actual data
        foreach ($familyData1 as $member1) {
            // Find the index in our array that matches this uraian
            $index = array_search($member1->uraian, array_column($familyMembers1, 'uraian'));

            if ($index !== false) {
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
                $dari_sampai = '';

                // Only format and show dates if both from and to dates exist
                if (!empty($pendidikan->dari) && !empty($pendidikan->sampai)) {
                    $dari_sampai = date('d M y', strtotime($pendidikan->dari)) . ' - ' . date('d M y', strtotime($pendidikan->sampai));
                }

                $pendidikanList[$index] = [
                    'tingkat_besar' => $pendidikan->tingkat_besar,
                    'nama_sekolah' => $pendidikan->nama_sekolah,
                    'tempat_sekolah' => $pendidikan->tempat_sekolah,
                    'jurusan_studi' => $pendidikan->jurusan_studi,
                    'berijazah' => $pendidikan->berijazah,
                    'dari_sampai' => $dari_sampai, // Use the conditionally formatted string
                    'keterangan' => $pendidikan->keterangan,
                ];
            }
        }

        $kursusData = DB::table('candidate_data_kursus')
            ->where('candidate_data_id', $candidates->id)
            ->get();

        // Count how many kursus entries exist in the database
        $kursusCount = DB::table('candidate_data_kursus')
            ->where('candidate_data_id', $candidates->id)
            ->count();

        // Initialize array with minimum 3 empty entries, or more if needed
        $kursusList = [];
        $minEntries = 3;
        $totalEntries = max($minEntries, $kursusCount);

        // Create the array with empty values
        for ($i = 0; $i < $totalEntries; $i++) {
            $kursusList[] = [
                'nama' => '',
                'diadakan_oleh' => '',
                'tempat' => '',
                'lama' => '',
                'tahun' => '',
                'dibiayai_oleh' => '',
                'keterangan' => ''
            ];
        }

        // Fill with actual data where it exists
        foreach ($kursusData as $index => $kursus) {
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

        // Count how many pekerjaan entries exist in the database
        $pekerjaanCount = DB::table('candidate_data_pekerjaan')
            ->where('candidate_data_id', $candidates->id)
            ->count();

        // Initialize array with minimum 3 empty entries, or more if needed
        $pekerjaanList = [];
        $minEntries = 3;
        $totalEntries = max($minEntries, $pekerjaanCount);

        // Create the array with empty values
        for ($i = 0; $i < $totalEntries; $i++) {
            $pekerjaanList[] = [
                'perusahaan' => '',
                'alamat' => '',
                'jabatan' => '',
                'dari' => '',
                'sampai' => '',
                'keterangan' => '',
                'alasan' => ''
            ];
        }

        // Fill with actual data where it exists
        foreach ($pekerjaanData as $index => $pekerjaan) {
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

        $data = compact(
            'candidates',
            'penjelasan3',
            'penjelasan4',
            'alasan1',
            'alasan2',
            'alasan3',
            'alasan4',
            'alasan5',
            'alasan6',
            'pekerjaanList',
            'bahasaList',
            'kursusList',
            'penjelasan1',
            'penjelasan2',
            'addressLine1',
            'pendidikanList',
            'addressLine2',
            'addressLine3',
            'addressLine4',
            'addressLine5',
            'familyMembers',
            'familyMembers1'
        );

        // Choose view based on recruitment_type_id
        if ($candidates->recruitment_type_id == 2) {
            return view('recruitment.candidate.printintern', $data);
        }

        return view('recruitment.candidate.print', $data);
    }

    public function getCandidateDataId(Request $request)
    {
        $candidateId = $request->candidate_id;

        $candidateData = DB::table('candidate_data')
            ->where('candidate_id', $candidateId)
            ->select('id')
            ->first();

        if ($candidateData) {
            return response()->json([
                'success' => true,
                'data_id' => $candidateData->id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Candidate data not found'
        ]);
    }
}
