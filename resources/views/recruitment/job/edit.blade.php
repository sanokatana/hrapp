<form action="/recruitment/jobs/{{ $job->id }}/update" method="POST" id="formJob">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="form-label">Position</div>
            <div class="input-icon mb-3">
                <select name="jabatan_id" id="jabatan_id" class="form-select">
                    <option value="">Select Position</option>
                    @foreach ($jabatan as $j)
                    <option value="{{ $j->id }}"
                        data-title="{{ $j->nama_jabatan }}"
                        data-dept="{{ $j->kode_dept }}"
                        data-type="{{ $j->jabatan === 'Head of Department' || $j->jabatan === 'Section Head' ? 3 :
                           ($j->jabatan === 'Internship' ? 2 :
                           ($j->jabatan === 'Officer' ? 1 : null)) }}"
                        {{ $job->jabatan_id == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_jabatan }} - {{ $j->site }}
                    </option>
                    @endforeach
                </select>
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

    <!-- Hidden inputs -->
    <input type="hidden" name="title" id="title" value="{{ $job->title }}">
    <input type="hidden" name="recruitment_type_id" id="recruitment_type_id" value="{{ $job->recruitment_type_id }}">
    <input type="hidden" name="kode_dept" id="kode_dept" value="{{ $job->kode_dept }}">
    <input type="hidden" name="status" id="status" value="{{ $job->status }}">
    <input type="hidden" name="site" id="site" value="{{ $job->site }}">

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

<script>
$(function() {
    // Handle jabatan selection
    $('#jabatan_id').change(function() {
        var selected = $(this).find('option:selected');
        var title = selected.data('title');
        var dept = selected.data('dept');
        var type = selected.data('type');
        var site = selected.text().split(' - ')[1];

        $('#title').val(title);
        $('#recruitment_type_id').val(type);
        $('#kode_dept').val(dept);
        $('#site').val(site);
    });

    $('#formJob').submit(function() {
        var jabatan_id = $('#jabatan_id').val();
        var description = $('#description').val();

        if (jabatan_id == "") {
            Swal.fire({
                title: 'Warning!',
                text: 'Position must be selected',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => {
                $('#jabatan_id').focus();
            });
            return false;
        } else if (description == "") {
            Swal.fire({
                title: 'Warning!',
                text: 'Description must be filled',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then(() => {
                $('#description').focus();
            });
            return false;
        }
    });
});
</script>