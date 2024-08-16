@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Master Data
                </div>
                <h2 class="page-title">
                    Shift Pattern
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
                                <a href="#" class="btn btn-primary" id="btnTambahShift">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add Data
                                </a>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>ID</th>
                                            <th>Pattern Name</th>
                                            <th>Description</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                        @foreach($patterns as $d)
                                        <tr>
                                            <td>{{ $d->id}}</td>
                                            <td>{{ $d->pattern_name}}</td>
                                            <td>{{ $d->description}}</td>
                                            <td>
                                                <!-- Each button on a separate row within the same cell -->
                                                <div class="form-group">
                                                    <!-- Edit Button -->
                                                    <div class="mb-1">
                                                        <a href="#" class="edit btn btn-info btn-sm" id="{{ $d->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                <path d="M16 5l3 3" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <!-- Time Button -->
                                                    <div class="mb-1">
                                                        <a href="#" class="btn btn-success btn-sm view-cycles" data-pattern-id="{{ $d->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <!-- Delete Button -->
                                                    <div>
                                                        <form action="/shiftpatt/{{$d->id}}/delete" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm delete-confirm" style="border: none; padding-top: 3px; padding-bottom: 3px; padding-right: 5px; padding-left: 5px;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M4 7l16 0" />
                                                                    <path d="M10 11l0 6" />
                                                                    <path d="M14 11l0 6" />
                                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
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
<div class="modal modal-blur fade" id="modal-inputshift" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Shift Pattern</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/shiftpatt/store" method="POST" id="formShift">
                    @csrf
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
                                <input type="text" value="" class="form-control" name="pattern_name" id="pattern_name" placeholder="Regular Weekday">
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
                                <input type="text" value="" class="form-control" name="description" id="description" placeholder="Regular Karyawan">
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
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal modal-blur fade" id="modal-editshift" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pattern Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditform">

            </div>
        </div>
    </div>
</div>
<!-- Modal for Viewing and Editing Shift Pattern Cycles -->
<div class="modal fade" id="cyclesModal" tabindex="-1" role="dialog" aria-labelledby="cyclesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-width" style="margin-top: 160px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cyclesModalLabel">Shift Pattern Cycles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </button>
            </div>
            <div class="modal-body">
                <form id="cyclesForm">
                    <input type="hidden" id="patternId" name="pattern_id" value="">
                    <table class="table table-striped" id="cyclesTable">
                        <thead>
                            <tr style="text-align: center;">
                                <th>ID</th>
                                <th>Cycle Day</th>
                                <th>Shift</th>
                                <th>Day Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary" id="addCycleBtn">Add Cycle</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="shifts-data" data-shifts="{{ json_encode($shifts) }}"></div>
@endsection

@push('myscript')
<script>
    $(function() {
        var shiftsData = document.getElementById('shifts-data').getAttribute('data-shifts');
        var shifts = JSON.parse(shiftsData);

        $('#btnTambahShift').click(function() {
            $('#modal-inputshift').modal("show");
        });

        $('.edit').click(function() {
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: '/shiftpatt/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(respond) {
                    $('#loadeditform').html(respond);
                }
            });
            $('#modal-editshift').modal("show");
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Apakah Yakin?",
                text: "Data Cabang Akan Ke Delete!",
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

        $('#formShift').submit(function(event) {
            function showWarning(message, selector) {
                Swal.fire({
                    title: 'Warning!',
                    text: message,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $(selector).focus();
                });
            }

            var fields = [{
                    selector: '#pattern_name',
                    message: 'Pattern Name Harus Diisi'
                },
                {
                    selector: '#description',
                    message: 'Descrition Harus Diisi'
                }
            ];

            for (var i = 0; i < fields.length; i++) {
                if ($(fields[i].selector).val() === "") {
                    showWarning(fields[i].message, fields[i].selector);
                    return false;
                }
            }

            // No need to call event.preventDefault() or this.submit() here
            // because if the form is valid, it will submit naturally
        });

        // Handle the "View Cycles" button click
        $('.view-cycles').click(function() {
            var patternId = $(this).data('pattern-id');
            $('#patternId').val(patternId);
            $('#cyclesModal').modal('show');
            loadCycles(patternId);
        });

        // Load cycle data into the modal
        function loadCycles(patternId) {
            $.ajax({
                url: '/shiftpatt/' + patternId + '/cycles',
                method: 'GET',
                success: function(data) {
                    var rows = '';
                    $.each(data.cycles, function(index, cycle) {
                        rows += '<tr>';
                        rows += '<td style="text-align: center;">' + cycle.id + '</td>';
                        rows += '<td><input type="number" class="form-control" name="cycle_day[]" value="' + cycle.cycle_day + '" style="text-align: center;"></td>';
                        rows += '<td>' +
                            '<select class="form-control" name="shift_id[]" style="text-align: center;">';
                        $.each(data.shifts, function(i, shift) {
                            rows += '<option value="' + shift.id + '" ' + (cycle.shift_id == shift.id ? 'selected' : '') + '>' + shift.shift_name + '</option>';
                        });
                        rows += '</select>' +
                            '</td>';
                        rows += '<td><input type="text" class="form-control" name="day_name[]" value="' + cycle.day_name + '" style="text-align: center;"></td>';
                        rows += '<td style="text-align: center;vertical-align: middle;">' +
                            '<form action="/delete-cycle/' + cycle.id + '" method="POST" class="delete-cycle-form">' +
                            '@csrf' +
                            '<button type="submit" class="btn btn-danger btn-sm delete-cycle-btn">Delete</button>' +
                            '</form>' +
                            '</td>';
                        rows += '</tr>';
                    });
                    $('#cyclesTable tbody').html(rows);
                }
            });
        }

        // Add new cycle row
        $('#addCycleBtn').click(function() {
            var newRow = '<tr>';
            newRow += '<td style="text-align: center;">New</td>';
            newRow += '<td><input type="number" class="form-control" name="cycle_day[]" style="text-align: center;"></td>';
            newRow += '<td>' +
                '<select class="form-control" name="shift_id[]" style="text-align: center;">';
            $.each(shifts, function(i, shift) {
                newRow += '<option value="' + shift.id + '">' + shift.shift_name + '</option>';
            });
            newRow += '</select>' +
                '</td>';
            newRow += '<td><input type="text" class="form-control" name="day_name[]" style="text-align: center;"></td>';
            newRow += '<td style="text-align: center;vertical-align: middle;"><button type="button" class="btn btn-danger btn-sm delete-cycle-btn">Delete</button></td>';
            newRow += '</tr>';
            $('#cyclesTable tbody').append(newRow);
        });
        // Save changes

        $('#cyclesForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize(); // Serialize the form data
            formData += '&_token=' + encodeURIComponent("{{ csrf_token() }}");

            $.ajax({
                url: '/shiftpatt/' + $('#patternId').val() + '/cycles',
                method: 'POST',
                data: formData, // Send serialized form data directly
                success: function(response) {
                    $('#cyclesModal').modal('hide');
                    location.reload(); // Refresh the page or update the table as needed
                },
                error: function(xhr) {
                    console.log(xhr.responseText); // Log errors if any
                }
            });
        });

    });
</script>

@endpush
