<form action="/shift/update" method="POST" id="formShift">
    @csrf
    <input type="hidden" name="id" value="{{$shift->id}}">
    <div class="row">
        <div class="col-12">
            <div class="form-label">Shift Name</div>
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
                <input type="text" value="{{$shift->shift_name}}" class="form-control" name="shift_name" id="shift_name" placeholder="Morning">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">Early Time</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-hour-3">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 12h3.5" />
                        <path d="M12 7v5" />
                    </svg>
                </span>
                <input type="time" value="{{$shift->early_time}}" class="form-control" name="early_time" id="early_time" placeholder="05:00">
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">Start Time</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-hour-3">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 12h3.5" />
                        <path d="M12 7v5" />
                    </svg>
                </span>
                <input type="time" value="{{$shift->start_time}}" class="form-control" name="start_time" id="start_time" placeholder="08:00">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-label">Latest Time</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-hour-3">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 12h3.5" />
                        <path d="M12 7v5" />
                    </svg>
                </span>
                <input type="time" value="{{$shift->latest_time}}" class="form-control" name="latest_time" id="latest_time" placeholder="13:00">
            </div>
        </div>
        <div class="col-6">
            <div class="form-label">End Time</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-hour-3">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 12h3.5" />
                        <path d="M12 7v5" />
                    </svg>
                </span>
                <input type="time" value="{{$shift->end_time}}" class="form-control" name="end_time" id="end_time" placeholder="17:00">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-label">Type</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-writing-sign">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 19c3.333 -2 5 -4 5 -6c0 -3 -1 -3 -2 -3s-2.032 1.085 -2 3c.034 2.048 1.658 2.877 2.5 4c1.5 2 2.5 2.5 3.5 1c.667 -1 1.167 -1.833 1.5 -2.5c1 2.333 2.333 3.5 4 3.5h2.5" />
                        <path d="M20 17v-12c0 -1.121 -.879 -2 -2 -2s-2 .879 -2 2v12l2 2l2 -2z" />
                        <path d="M16 7h4" />
                    </svg>
                </span>
                <input type="text" value="{{$shift->type}}" class="form-control" name="type" id="type" placeholder="Reg">
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
    <div class="row">
        <div class="col-12">
            <div class="form-label">Status</div>
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-sun">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                    </svg>
                </span>
                <input type="text" value="{{$shift->status}}" class="form-control" name="status" id="status" placeholder="P/M">
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
