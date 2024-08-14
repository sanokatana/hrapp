<form action="/cabang/update" method="POST" id="formCabangEdit">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-label">Kode Cabang</div>
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
                <input type="text" value="{{ $cabang->kode_cabang }}" class="form-control" name="kode_cabang" id="kode_cabang" placeholder="SOR" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Nama Cabang</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </span>
                <input type="text" value="{{ $cabang->nama_cabang }}" class="form-control" name="nama_cabang" id="nama_cabang" placeholder="Sorrento">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Lokasi</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" />
                        <path d="M9 4v13" />
                        <path d="M15 7v13" />
                    </svg>
                </span>
                <input type="text" value="{{ $cabang->lokasi_cabang }}" class="form-control" name="lokasi_cabang" id="lokasi_cabang" placeholder="Lokasi">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Radius</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-dotted">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7.5 4.21l0 .01" />
                        <path d="M4.21 7.5l0 .01" />
                        <path d="M3 12l0 .01" />
                        <path d="M4.21 16.5l0 .01" />
                        <path d="M7.5 19.79l0 .01" />
                        <path d="M12 21l0 .01" />
                        <path d="M16.5 19.79l0 .01" />
                        <path d="M19.79 16.5l0 .01" />
                        <path d="M21 12l0 .01" />
                        <path d="M19.79 7.5l0 .01" />
                        <path d="M16.5 4.21l0 .01" />
                        <path d="M12 3l0 .01" />
                    </svg>
                </span>
                <input type="text" value="{{ $cabang->radius }}" class="form-control" name="radius" id="radius" placeholder="50">
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
