<form action="/recruitment/stages/{{$stage->id}}/update" method="POST" id="formStage">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-label">Name</div>
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
                <input type="text" value="{{$stage->name}}" class="form-control" name="name" id="name" placeholder="HR Interview">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Description</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-letter-case-upper">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 19v-10.5a3.5 3.5 0 0 1 7 0v10.5" />
                        <path d="M3 13h7" />
                        <path d="M14 19v-10.5a3.5 3.5 0 0 1 7 0v10.5" />
                        <path d="M14 13h7" />
                    </svg>
                </span>
                <input type="text" value="{{$stage->description}}" class="form-control" name="description" id="description"
                    placeholder="Description">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Sequence</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-circle-number-0">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M10 10v4a2 2 0 1 0 4 0v-4a2 2 0 1 0 -4 0z" />
                    </svg>
                </span>
                <input type="number" value="{{$stage->sequence}}" class="form-control" name="sequence" id="sequence"
                    placeholder="Sequence">
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
                        <option {{ $stage->recruitment_type_id == $d->id ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Type</div>
            <div class="input-icon mb-3">
                <select name="type" id="type" class="form-select">
                    <option value="">Pilih Type</option>
                    <option {{ $stage->type == 'Initial' ? 'selected' : '' }} value="Initial">Initial</option>
                    <option {{ $stage->type == 'Fill Form' ? 'selected' : '' }} value="Fill Form">Fill Form</option>
                    <option {{ $stage->type == 'Form Filled' ? 'selected' : '' }} value="Form Filled">Form Filled</option>
                    <option {{ $stage->type == 'Test Interview' ? 'selected' : '' }} value="Test Interview">Test Interview</option>
                    <option {{ $stage->type == 'Management Interview' ? 'selected' : '' }} value="Management Interview">Management Interview</option>
                    <option {{ $stage->type == 'Offering Letter' ? 'selected' : '' }} value="Offering Letter">Offering Letter</option>
                    <option {{ $stage->type == 'Contract Interview' ? 'selected' : '' }} value="Contract Interview">Contract Interview</option>
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
