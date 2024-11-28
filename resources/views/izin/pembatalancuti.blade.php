@extends('layouts.presensi')

@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Pembatalan Cuti</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

<style>
    .listview {
        border-radius: 10px;
    }
</style>

@section('content')
@php
use App\Helpers\DateHelper;
@endphp

<form action="/presensi/pembatalancutiYes" method="POST" id="batalForm">
    @csrf
    <div class="row" style="margin-top:70px">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-danger btn-block" type="submit" id="batalBtn">
                    <ion-icon name="search-outline"></ion-icon>Batalkan
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col" style="margin-bottom:80px">
            @foreach ($historicuti as $d)
            @php
            // Format the date for each izin entry
            $izinFormattedDate = DateHelper::formatIndonesianDate($d->tgl_cuti);
            $izinFormattedDateAkhir = DateHelper::formatIndonesianDate($d->tgl_cuti_sampai);
            @endphp
            <ul class="listview image-listview rounded-custom">
                <li>
                    <div class="item">
                        <div>
                            <input type="checkbox" name="cuti_ids[]" value="{{ $d->id }}" class="form-check-input m-0 align-middle" aria-label="Select task">
                        </div>
                        <div class="in">
                            <div style="padding-left: 40px;">
                                <b>{{ $izinFormattedDate }}</b><br>
                                <b class="text-muted">Sampai</b><br>
                                @if ($d->tgl_cuti_sampai)
                                <b>{{ $izinFormattedDateAkhir }}</b><br>
                                @endif
                                <b style="color: red;">{{ $d->jenis }}</b><br>
                                @if ($d->tipe)
                                <b>{{ $d->tipe }}</b><br>
                                @endif
                                Note - <b class="text-info">{{ $d->note }}</b>
                                Keputusan - <b class="text-info">{{ $d->keputusan }}</b>
                            </div>

                            <div class="status-row" style="text-align: right">
                                <div class="mb-1">
                                    @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">Waiting Approval</span>
                                    @elseif ($d->status_approved == 1)
                                    <span class="badge bg-success">Form Approved</span>
                                    @elseif ($d->status_approved == 1)
                                    <span class="badge bg-danger">Form Declined</span>
                                    @else
                                    <span class="badge bg-danger">Form Cancelled</span>
                                    @endif
                                </div>
                                <div class="mb-1">
                                    @if ($d->status_approved_hrd == 0)
                                    <span class="badge bg-warning">Waiting Approval</span>
                                    @elseif ($d->status_approved_hrd == 1)
                                    <span class="badge bg-success">Form Approved</span>
                                    @elseif ($d->status_approved_hrd == 2)
                                    <span class="badge bg-danger">Form Declined</span>
                                    @else
                                    <span class="badge bg-danger">Form Cancelled</span>
                                    @endif
                                </div>
                                <div>
                                    @if ($d->status_management == 0)
                                    <span class="badge bg-warning">Waiting Approval</span>
                                    @elseif ($d->status_management == 1)
                                    <span class="badge bg-success">Form Approved</span>
                                    @elseif ($d->status_management == 2)
                                    <span class="badge bg-danger">Form Declined</span>
                                    @else
                                    <span class="badge bg-danger">Form Cancelled</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            @endforeach
        </div>
    </div>
</form>

@endsection

@push('myscript')
<script>
    // Optionally, you can use AJAX here to submit the form without refreshing the page
    $('#batalForm').on('submit', function(e) {
        e.preventDefault();

        let selectedIds = $("input[name='cuti_ids[]']:checked").map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length > 0) {
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    cuti_ids: selectedIds
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', 'Cuti has been successfully canceled!', 'success');
                    } else {
                        Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                    }
                }
            });
        } else {
            Swal.fire('No selection', 'Please select at least one Cuti to cancel.', 'warning');
        }
    });
</script>
@endpush
