<form action="/companies/{{ $company->id }}" method="POST" id="formCompany">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12">
            <div class="form-label">Nama Pendek</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 21l18 0"/>
                        <path d="M9 8l1 0"/>
                        <path d="M9 12l1 0"/>
                        <path d="M9 16l1 0"/>
                        <path d="M14 8l1 0"/>
                        <path d="M14 12l1 0"/>
                        <path d="M14 16l1 0"/>
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10 a2 2 0 0 1 2 2v16"/>
                    </svg>
                </span>
                <input type="text" value="{{ $company->short_name }}" class="form-control" name="short_name" id="short_name" placeholder="PT Demo" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Nama Lengkap</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 21l18 0"/>
                        <path d="M9 8l1 0"/>
                        <path d="M9 12l1 0"/>
                        <path d="M9 16l1 0"/>
                        <path d="M14 8l1 0"/>
                        <path d="M14 12l1 0"/>
                        <path d="M14 16l1 0"/>
                        <path d="M5 21v-16a2 2 0 0 1 2 -2h10 a2 2 0 0 1 2 2v16"/>
                    </svg>
                </span>
                <input type="text" value="{{ $company->long_name }}" class="form-control" name="long_name" id="long_name" placeholder="PT Demo Sejahtera" required>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/>
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                        <path d="M14 4l0 4l-6 0l0 -4"/>
                    </svg>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
