<form action="/recruitment/jobs/{{ $job->id }}/update" method="POST" id="formJob">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-label">Title</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-signature">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M3 17c3.333 -3.333 5 -6 5 -8c0 -3 -1 -3 -2 -3s-2.032 1.085 -2 3c.034 2.048 1.658 4.877 2.5 6c1.5 2 2.5 2.5 3.5 1l2 -3c.333 2.667 1.333 4 3 4c.53 0 2.639 -2 3 -2c.517 0 1.517 .667 3 2" />
                    </svg>
                </span>
                <input type="text" value="{{ $job->title }}" class="form-control" name="title" id="title" placeholder="Officer">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Description</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-text-wrap-disabled">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6l10 0" />
                        <path d="M4 18l10 0" />
                        <path d="M4 12h17l-3 -3m0 6l3 -3" />
                    </svg>
                </span>
                <input type="text" value="{{ $job->description }}" class="form-control" name="description" id="description"
                    placeholder="Description">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Recruitment Type</div>
            <div class="input-icon mb-3">
                <select name="recruitment_type_id" id="recruitment_type_id" class="form-select">
                    <option value="">Pilih</option>
                    @foreach ($recruitment_type as $d)
                        <option {{ $job->recruitment_type_id == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Department</div>
            <div class="input-icon mb-3">
                <select name="kode_dept" id="kode_dept" class="form-select">
                    <option value="">Pilih</option>
                    @foreach ($department as $d)
                        <option {{ $job->kode_dept == $d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Status Job Opening</div>
            <div class="input-icon mb-3">
                <select name="status" id="status" class="form-select">
                    <option value="">Pilih Status</option>
                    <option {{ $job->status == 'Open' ? 'selected' : '' }} value="Open">Open</option>
                    <option {{ $job->status == 'Closed' ? 'selected' : '' }} value="Closed">Closed</option>
                </select>
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
