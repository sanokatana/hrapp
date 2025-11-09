<form action="/data/user/{{ $user->id }}/update" method="POST" id="formUser" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12">
            <div class="form-label">NIK</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M15 8l2 0" />
                        <path d="M15 12l2 0" />
                        <path d="M7 16l10 0" />
                    </svg>
                </span>
                <input type="text" value="{{ $user->nik }}" class="form-control" name="nik" id="nik" placeholder="10101" readonly>
            </div>
        </div>
        <div class="col-12">
            <div class="form-label">Nama User</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </span>
                <input type="text" value="{{ $user->name }}" class="form-control" name="nama" id="nama" placeholder="John Doe" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Email</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                        <path d="M3 7l9 6l9 -6" />
                    </svg>
                </span>
                <input type="text" value="{{ $user->email }}" class="form-control" name="email" id="email" placeholder="@ciptaharmoni.com" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label">Level</label>
            <div class="form-group mb-3">
                <select name="level" id="level" class="form-select">
                    <option value="pilih">Pilih Level</option>
                    <option {{ $user->level == 'Management' ? 'selected' : '' }} value="Management">Management</option>
                    <option {{ $user->level == 'Admin' ? 'selected' : '' }} value="Admin">Admin</option>
                    <option {{ $user->level == 'HRD' ? 'selected' : '' }} value="HRD">HRD</option>
                    <option {{ $user->level == 'Superadmin' ? 'selected' : '' }} value="Superadmin">Superadmin</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label">Password</label>
            <div class="input-group input-group-flat">
                <input type="password" class="form-control" name="new_password" id="new_password" autocomplete="off" placeholder="New password">
            </div>
        </div>
        <div class="col-12">
            <label class="form-label"></label>
            <div class="input-group input-group-flat">
                <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" autocomplete="off" placeholder="Confirm new password">
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <label class="form-label">Assign Companies (Superadmin Only)</label>
            <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                @foreach ($companies as $company)
                <label class="form-selectgroup-item flex-fill">
                    <input type="checkbox" name="companies[]" value="{{ $company->id }}" class="form-selectgroup-input" {{ $user->companies->contains($company->id) ? 'checked' : '' }}>
                    <div class="form-selectgroup-label d-flex align-items-center p-3">
                        <div class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </div>
                        <div>
                            <strong>{{ $company->short_name }}</strong><br>
                            <small class="text-muted">{{ $company->long_name }}</small>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
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
