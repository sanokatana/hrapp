<form action="/konfigurasi/tipecuti/{{ $tipecuti->id_tipe_cuti }}/update" method="POST" id="formCuti">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-label">ID Tipe Cuti</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-community">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 9l5 5v7h-5v-4m0 4h-5v-7l5 -5m1 1v-6a1 1 0 0 1 1 -1h10a1 1 0 0 1 1 1v17h-8" />
                        <path d="M13 7l0 .01" />
                        <path d="M17 7l0 .01" />
                        <path d="M17 11l0 .01" />
                        <path d="M17 15l0 .01" />
                    </svg>
                </span>
                <input type="text" value="{{ $tipecuti->id_tipe_cuti}}" class="form-control" name="id_tipe_cuti" id="id_tipe_cuti" placeholder="0" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Lokasi Kantor</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                    </svg>
                </span>
                <input type="text" value="{{ $tipecuti->tipe_cuti}}" class="form-control" name="tipe_cuti" id="tipe_cuti" placeholder="Pernikahan">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Jumlah Hari</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                    </svg>
                </span>
                <input type="text" value="{{ $tipecuti->jumlah_hari}}" class="form-control" name="jumlah_hari" id="jumlah_hari" placeholder="0">
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
