<form action="/konfigurasi/pt/{{ $pt->id }}/update" method="POST" id="formPT">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-label">Nama PT</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-letter-case">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17.5 15.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" />
                        <path d="M3 19v-10.5a3.5 3.5 0 0 1 7 0v10.5" />
                        <path d="M3 13h7" />
                        <path d="M21 12v7" />
                    </svg>
                </span>
                <input type="text" value="{{ $pt->short_name }}" class="form-control" name="short_name" id="short_name" placeholder="Name">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Nama Panjang PT</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-letter-case">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17.5 15.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" />
                        <path d="M3 19v-10.5a3.5 3.5 0 0 1 7 0v10.5" />
                        <path d="M3 13h7" />
                        <path d="M21 12v7" />
                    </svg>
                </span>
                <input type="text" value="{{ $pt->long_name }}" class="form-control" name="long_name" id="long_name" placeholder="Name">
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
