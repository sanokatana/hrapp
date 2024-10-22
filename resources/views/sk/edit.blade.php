<form action="/sk/{{ $sk->id }}/update" method="POST" id="formSK">
    @csrf
    <div class="row">
        <div class="col-6">
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
                <input type="text" value="{{$sk->nik}}" class="form-control" name="nik" id="nik" placeholder="0xx-xxx" autocomplete="off" disabled>
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">Nama Karyawan</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                        <path d="M15 19l2 2l4 -4" />
                    </svg>
                </span>
                <input type="text" value="{{$sk->nama_karyawan}}" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Type or select employee name" autocomplete="off" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">No SK</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    </svg>
                </span>
                <input type="text" value="{{$sk->no_sk}}" class="form-control" name="no_sk" id="no_sk" placeholder="No Kontrak">
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">Tgl SK</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l0 4" />
                        <path d="M8 3l0 4" />
                        <path d="M4 11l16 0" />
                        <path d="M8 15h2v2h-2z" />
                    </svg>
                </span>
                <input type="date" value="{{$sk->tgl_sk}}" class="form-control" name="tgl_sk" id="tgl_sk" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Masa Probation</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    </svg>
                </span>
                <input type="text" value="{{$sk->masa_probation}}" class="form-control" name="masa_probation" id="masa_probation" placeholder="Masa Probation">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">Nama PT</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-versions">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 5m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                        <path d="M7 7l0 10" />
                        <path d="M4 8l0 8" />
                    </svg>
                </span>
                <input type="text" value="{{$sk->nama_pt}}" class="form-control" name="nama_pt" id="nama_pt" placeholder="Nama PT">
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">Diketahui</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cash-banknote">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M3 6m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                        <path d="M18 12l.01 0" />
                        <path d="M6 12l.01 0" />
                    </svg>
                </span>
                <input type="text" value="{{$sk->diketahui}}" class="form-control" name="diketahui" id="diketahui" placeholder="Nama">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">Status</div>
            <select name="status" id="status" class="form-select">
                <option {{ $sk->status == '' ? 'selected' : '' }} value="">Choose</option>
                <option {{ $sk->status == 'Active' ? 'selected' : '' }} value="Active">Active</option>
                <option {{ $sk->status == 'Terminated' ? 'selected' : '' }} value="Terminated">Terminated</option>
                <option {{ $sk->status == 'Non-Active' ? 'selected' : '' }} value="Non-Active">Non-Active</option>
            </select>
        </div>
        <div class="col-6">
            <div class="form-label">SK File</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-info">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <path d="M11 14h1v4h1" />
                        <path d="M12 11h.01" />
                    </svg>
                </span>
                <input type="text" value="" class="form-control" name="file_sk" id="file_sk" placeholder="File">
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
                    Simpan
                </button>
            </div>
        </div>
    </div>
</form>
