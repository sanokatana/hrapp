@extends('layouts.candidate.tabler')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Dashboard
                </div>
                <h2 class="page-title">
                    Perlengkapan Data
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
            title: 'Sudah!',
            text: "{{ session('danger') }}",
            icon: 'warning',
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
                        <form action='/candidate/data/store/perlengkapan' method="POST" id="formCandidate" enctype="multipart/form-data">
                            @csrf
                            <!-- File Upload Section -->
                            <div class="row mb-4">
                                <div class="col-4">
                                    <h3>KTP</h3>
                                    <input class="form-control" type="file" id="photo_ktp" name="photo_ktp">
                                </div>
                                <div class="col-4">
                                    <h3>Kartu Keluarga</h3>
                                    <input class="form-control" type="file" id="photo_kk" name="photo_kk">
                                </div>
                                <div class="col-4">
                                    <h3>SIM</h3>
                                    <input class="form-control" type="file" id="photo_sim" name="photo_sim">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-4">
                                    <h3>NPWP</h3>
                                    <input class="form-control" type="file" id="photo_npwp" name="photo_npwp">
                                </div>
                                <div class="col-4">
                                    <h3>Ijazah</h3>
                                    <input class="form-control" type="file" id="photo_ijazah" name="photo_ijazah">
                                </div>
                                <div class="col-4">
                                    <h3>Photo Anda</h3>
                                    <input class="form-control" type="file" id="photo_candidate" name="photo_candidate">
                                </div>
                            </div>

                            @if($keluargaData->isNotEmpty())
                            <!-- Only show this section if there is data in keluargaData -->
                            <div class="card mt-4">
                                <div class="card-body">
                                    @csrf
                                    <div class="col-12 table-responsive">
                                        <table class="table table-vcenter card-table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Uraian</th>
                                                    <th>Nama</th>
                                                    <th>Jenis</th>
                                                    <th>NIK</th>
                                                    <th>Tempat Lahir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($keluargaData as $d)
                                                <tr>
                                                    <td>{{ $d->uraian }}</td>
                                                    <td>{{ $d->nama_lengkap }}</td>
                                                    <td>{{ $d->jenis }}</td>
                                                    <td><input type="text" name="nik[]" class="form-control" value="{{ $d->nik }}"></td>
                                                    <td><input type="text" name="tempat_lahir[]" class="form-control" value="{{ $d->tempat_lahir }}"></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
@endpush
