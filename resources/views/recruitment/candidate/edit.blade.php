<form action="/recruitment/candidate/{{ $candidate->id }}/update" method="POST" id="formCandidate">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-label">Nama Candidate</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-minus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.348 0 .686 .045 1.009 .128" />
                        <path d="M16 19h6" />
                    </svg>
                </span>
                <input type="text" value="{{ $candidate->nama_candidate }}" class="form-control" name="nama_candidate" id="nama_candidate"
                    placeholder="John">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Username</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </span>
                <input type="text" value="{{ $candidate->username }}" class="form-control" name="username" id="username" placeholder="Username">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Email</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                        <path d="M3 7l9 6l9 -6" />
                    </svg>
                </span>
                <input type="text" value="{{ $candidate->email }}" class="form-control" name="email" id="email" placeholder="@gmail.com">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Job Opening</div>
            <div class="input-icon mb-3">
                <select name="job_opening_id" id="job_opening_id" class="form-select">
                    <option value="">Pilih</option>
                    @foreach ($job as $d)
                        <option {{ $candidate->job_opening_id == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Current Stage</div>
            <div class="input-icon mb-3">
                <select name="current_stage_id" id="current_stage_id" class="form-select">
                    <option value="">Pilih</option>
                    @foreach ($currentStage as $d)
                        <option {{ $candidate->current_stage_id == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Status</div>
            <div class="input-icon mb-3">
                <select name="status" id="status" class="form-select">
                    <option value="">Pilih Status</option>
                    <option {{ $candidate->status == 'In Process' ? 'selected' : '' }} value="In Process">In Process</option>
                    <option {{ $candidate->status == 'Hired' ? 'selected' : '' }} value="Hired">Hired</option>
                    <option {{ $candidate->status == 'Rejected' ? 'selected' : '' }} value="Rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Password</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-password">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 10v4" />
                        <path d="M10 13l4 -2" />
                        <path d="M10 11l4 2" />
                        <path d="M5 10v4" />
                        <path d="M3 13l4 -2" />
                        <path d="M3 11l4 2" />
                        <path d="M19 10v4" />
                        <path d="M17 13l4 -2" />
                        <path d="M17 11l4 2" />
                    </svg>
                </span>
                <input type="text" value="" class="form-control" name="password" id="password" placeholder="password">
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                    </svg>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
