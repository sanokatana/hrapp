@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Recruitment
                </div>
                <h2 class="page-title">
                    Job Openings
                </h2>
                <br>
            </div>
        </div>
    </div>
</div>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    });
</script>
@elseif(session('danger'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Danger!',
            text: "{{ session('danger') }}",
            icon: 'danger',
            confirmButtonText: 'Ok'
        });
    });
</script>
@endif
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btnAddJob">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Job Opening
                                </a>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Recruitment Type</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($job as $d)
                                        <tr>
                                            <td>{{ $d->id}}</td>
                                            <td>{{ $d->title}}</td>
                                            <td>{{ $d->description}}</td>
                                            <td>{{ $d->recruitment_type_name}}</td>
                                            <td>{{ $d->kode_dept}}</td>
                                            <td>{{ $d->status}}</td>
                                            <td>
                                                <div class="form-group">
                                                    <a href="#" class="edit btn btn-info btn-sm" id="{{ $d->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                    </a>
                                                    <form action="/recruitment/jobs/{{$d->id}}/delete" method="POST">
                                                        @csrf
                                                        <a class="btn btn-danger btn-sm delete-confirm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M4 7l16 0" />
                                                                <path d="M10 11l0 6" />
                                                                <path d="M14 11l0 6" />
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            </svg>
                                                        </a>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-inputJob" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- In resources/views/recruitment/job/index.blade.php -->
            <!-- Update the modal form -->
            <div class="modal-body">
                <form action="/recruitment/jobs/store" method="POST" id="formJob">
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
                                           ($j->jabatan === 'Officer' ? 1 : null)) }}">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-text-wrap-disabled">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 6l10 0" />
                                        <path d="M4 18l10 0" />
                                        <path d="M4 12h17l-3 -3m0 6l3 -3" />
                                    </svg>
                                </span>
                                <input type="text" class="form-control" name="description" id="description" placeholder="Description">
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="title" id="title">
                    <input type="hidden" name="recruitment_type_id" id="recruitment_type_id">
                    <input type="hidden" name="kode_dept" id="kode_dept">
                    <input type="hidden" name="status" id="status" value="Open">
                    <input type="hidden" name="site" id="site">

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
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal modal-blur fade" id="modal-editJob" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadedJob">

            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        $('#btnAddJob').click(function() {
            $('#modal-inputJob').modal("show");
        });

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

        $('.edit').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: '/recruitment/jobs/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token();}}",
                    id: id
                },
                success: function(respond) {
                    $('#loadedJob').html(respond);
                }
            });
            $('#modal-editJob').modal("show");
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Job Opening Akan Ke Delete!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
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
@endpush
