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

                            <div class="row mb-3">
                                @if($candidateDataLengkap && $candidateDataLengkap->photo_ktp && $candidateDataLengkap->photo_ktp !== 'No_Document'                                )
                                <!-- If there's a file uploaded, show it as a clickable link -->
                                <div class="col-3">
                                    <h3>File KTP</h3>
                                    <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_ktp) }}" target="_blank" class="btn btn-info btn-block w-100">
                                        Lihat File
                                    </a>
                                </div>
                                @else
                                <!-- If there's no file uploaded, show the upload input -->
                                <div class="col-3">
                                    <h3>KTP</h3>
                                    <input class="form-control" type="file" id="photo_ktp" name="photo_ktp">
                                </div>
                                @endif

                                @if($candidateDataLengkap && $candidateDataLengkap->photo_kk && $candidateDataLengkap->photo_kk !== 'No_Document')
                                <!-- If there's a file uploaded, show it as a clickable link -->
                                <div class="col-3">
                                    <h3>File Kartu Keluarga</h3>
                                    <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_kk) }}" target="_blank" class="btn btn-info btn-block w-100">
                                        Lihat File
                                    </a>
                                </div>
                                @else
                                <!-- If there's no file uploaded, show the upload input -->
                                <div class="col-3">
                                    <h3>File Keluarga</h3>
                                    <input class="form-control" type="file" id="photo_kk" name="photo_kk">
                                </div>
                                @endif

                                @if($candidateDataLengkap && $candidateDataLengkap->photo_sim && $candidateDataLengkap->photo_sim !== 'No_Document')
                                <!-- If there's a file uploaded, show it as a clickable link -->
                                <div class="col-3">
                                    <h3>File SIM</h3>
                                    <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_sim) }}" target="_blank" class="btn btn-info btn-block w-100">
                                        Lihat File
                                    </a>
                                </div>
                                @else
                                <!-- If there's no file uploaded, show the upload input -->
                                <div class="col-3">
                                    <h3>SIM</h3>
                                    <input class="form-control" type="file" id="photo_sim" name="photo_sim">
                                </div>
                                @endif

                                @if($candidateDataLengkap && $candidateDataLengkap->photo_cv && $candidateDataLengkap->photo_cv !== 'No_Document')
                                <!-- If there's a file uploaded, show it as a clickable link -->
                                <div class="col-3">
                                    <h3>File CV</h3>
                                    <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_cv) }}" target="_blank" class="btn btn-info btn-block w-100">
                                        Lihat File
                                    </a>
                                </div>
                                @else
                                <!-- If there's no file uploaded, show the upload input -->
                                <div class="col-3">
                                    <h3>CV</h3>
                                    <input class="form-control" type="file" id="photo_cv" name="photo_cv">
                                </div>
                                @endif
                            </div>
                            <div class="row mb-4">
                                @if($candidateDataLengkap && $candidateDataLengkap->photo_npwp && $candidateDataLengkap->photo_npwp !== 'No_Document')
                                <!-- If there's a file uploaded, show it as a clickable link -->
                                <div class="col-3">
                                    <h3>File NPWP</h3>
                                    <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_npwp) }}" target="_blank" class="btn btn-info btn-block w-100">
                                        Lihat File
                                    </a>
                                </div>
                                @else
                                <!-- If there's no file uploaded, show the upload input -->
                                <div class="col-3">
                                    <h3>NPWP</h3>
                                    <input class="form-control" type="file" id="photo_npwp" name="photo_npwp">
                                </div>
                                @endif

                                @if($candidateDataLengkap && $candidateDataLengkap->photo_ijazah && $candidateDataLengkap->photo_ijazah !== 'No_Document')
                                <!-- If there's a file uploaded, show it as a clickable link -->
                                <div class="col-3">
                                    <h3>File Ijazah</h3>
                                    <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_ijazah) }}" target="_blank" class="btn btn-info btn-block w-100">
                                        Lihat File
                                    </a>
                                </div>
                                @else
                                <!-- If there's no file uploaded, show the upload input -->
                                <div class="col-3">
                                    <h3>Ijazah</h3>
                                    <input class="form-control" type="file" id="photo_ijazah" name="photo_ijazah">
                                </div>
                                @endif

                                @if($candidateDataLengkap && $candidateDataLengkap->photo_cv && $candidateDataLengkap->photo_skck !== 'No_Document')
                                <!-- If there's a file uploaded, show it as a clickable link -->
                                <div class="col-3">
                                    <h3>File SKCK</h3>
                                    <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_skck) }}" target="_blank" class="btn btn-info btn-block w-100">
                                        Lihat File
                                    </a>
                                </div>
                                @else
                                <!-- If there's no file uploaded, show the upload input -->
                                <div class="col-3">
                                    <h3>SKCK</h3>
                                    <input class="form-control" type="file" id="photo_skck" name="photo_skck">
                                </div>
                                @endif

                                @if($candidateDataLengkap && $candidateDataLengkap->photo_anda && $candidateDataLengkap->photo_anda !== 'No_Document')
                                <!-- If there's a file uploaded, show it as a clickable link -->
                                <div class="col-3">
                                    <h3>Photo Anda</h3>
                                    <a href="{{ asset('storage/uploads/candidate/' . $candidate->id . '.' . Str::slug($candidate->nama_candidate) . '/' . $candidateDataLengkap->photo_anda) }}" target="_blank" class="btn btn-info btn-block w-100">
                                        Lihat File
                                    </a>
                                </div>
                                @else
                                <!-- If there's no file uploaded, show the upload input -->
                                <div class="col-3">
                                    <h3>Photo Anda</h3>
                                    <input class="form-control" type="file" id="photo_anda" name="photo_anda">
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="no_kartu_keluarga">No Kartu Keluarga</label>
                                <input type="text" name="no_kartu_keluarga" id="no_kartu_keluarga" class="form-control" value="{{ $candidateDataLengkap->no_kartu_keluarga ?? '' }}">
                            </div>


                            @if($keluargaData->isNotEmpty())
                            <div class="card mt-4">
                                <div class="card-body">
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
                                                    <td>
                                                        <input type="text" name="nik[]" class="form-control" value="{{ $d->nik }}">
                                                        <input type="hidden" name="keluarga_id[]" value="{{ $d->id }}">
                                                    </td>
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
