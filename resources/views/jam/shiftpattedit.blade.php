<form action="/shiftpatt/update" method="POST" id="formShift">
    @csrf
    <input type="hidden" name="id" value="{{$shift->id}}">
    <div class="row">
        <div class="col-12">
            <div class="form-label">Pattern Name</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-search">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20.993 11.646a9 9 0 1 0 -9.318 9.348" />
                        <path d="M12 7v5l1 1" />
                        <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M20.2 20.2l1.8 1.8" />
                    </svg>
                </span>
                <input type="text" value="{{$shift->pattern_name}}" class="form-control" name="pattern_name" id="pattern_name" placeholder="Regular Weekday">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Description</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-signature">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 17c3.333 -3.333 5 -6 5 -8c0 -3 -1 -3 -2 -3s-2.032 1.085 -2 3c.034 2.048 1.658 4.877 2.5 6c1.5 2 2.5 2.5 3.5 1l2 -3c.333 2.667 1.333 4 3 4c.53 0 2.639 -2 3 -2c.517 0 1.517 .667 3 2" />
                    </svg>
                </span>
                <input type="text" value="{{$shift->description}}" class="form-control" name="description" id="description" placeholder="Regular Karyawan">
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
