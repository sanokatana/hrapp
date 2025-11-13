@extends('layouts.admin.tabler')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Organisasi</div>
        <h2 class="page-title">Cabang</h2>
      </div>
    </div>
  </div>
</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', () => {
  Swal.fire({ title:'Berhasil!', text:"{{ session('success') }}", icon:'success', confirmButtonText:'Ok' });
});
</script>
@elseif(session('danger'))
<script>
document.addEventListener('DOMContentLoaded', () => {
  Swal.fire({ title:'Gagal!', text:"{{ session('danger') }}", icon:'error', confirmButtonText:'Ok' });
});
</script>
@endif

<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-12">
                <a href="#" class="btn btn-primary" id="btnTambahCabang">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                       class="icon icon-tabler icon-tabler-plus">
                    <path d="M12 5v14M5 12h14"/>
                  </svg>
                  Add Cabang
                </a>
              </div>
            </div>

            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table table-vcenter card-table table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kode</th>
                      <th>Nama Cabang</th>
                      <th>Perusahaan</th>
                      <th>Kota</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse ($cabangs as $i => $c)
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{{ $c->kode }}</td>
                      <td>{{ $c->nama }}</td>
                      <td>{{ $c->company?->short_name ?? '—' }}</td>
                      <td>{{ $c->kota ?? '—' }}</td>
                      <td>
                        <div class="d-flex gap-2">
                          <a href="#" class="btn btn-info btn-sm edit" data-id="{{ $c->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="icon icon-tabler icon-tabler-edit">
                              <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                              <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z"/>
                              <path d="M16 5l3 3"/>
                            </svg>
                          </a>

                          <form action="/cabang/{{ $c->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm delete-confirm">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                   stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                   class="icon icon-tabler icon-tabler-trash">
                                <path d="M4 7h16M10 11v6M14 11v6"/>
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                <path d="M9 7V4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                              </svg>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-secondary">Belum ada cabang.</td></tr>
                  @endforelse
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

{{-- Modal Create --}}
<div class="modal modal-blur fade" id="modal-inputcabang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Cabang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/cabang" method="POST" id="formCabangCreate">
          @csrf

          <div class="mb-3">
            <label class="form-label">Company</label>
            <select name="company_id" class="form-select" required>
              <option value="">Pilih Company</option>
              @foreach($companies as $company)
              <option value="{{ $company->id }}" {{ ($selectedCompanyId ?? null) == $company->id ? 'selected' : '' }}>
                {{ $company->short_name }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Kode Cabang</label>
            <div class="input-icon">
              <span class="input-icon-addon">#</span>
              <input type="text" name="kode" id="kode_create" class="form-control" placeholder="SOR" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Nama Cabang</label>
            <div class="input-icon">
              <span class="input-icon-addon">@</span>
              <input type="text" name="nama" id="nama_create" class="form-control" placeholder="Sorrento" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" placeholder="Alamat">
          </div>

          <div class="mb-3">
            <label class="form-label">Kota</label>
            <input type="text" name="kota" class="form-control" placeholder="Kota">
          </div>

          <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="number" step="0.000001" name="latitude" id="latitude_create" class="form-control" placeholder="-6.200000" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="number" step="0.000001" name="longitude" id="longitude_create" class="form-control" placeholder="106.816666" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Radius (meter)</label>
            <input type="number" name="radius_meter" id="radius_create" class="form-control" placeholder="100" min="10" max="5000" required>
          </div>

          <button class="btn btn-primary w-100">
            Simpan
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal modal-blur fade" id="modal-editcabang" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Cabang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="loadedcabang">
        {{-- filled by AJAX /cabang/edit --}}
      </div>
    </div>
  </div>
</div>
@endsection

@push('myscript')
<script>
$(function () {
  // Open create modal
  $('#btnTambahCabang').on('click', function () {
    $('#modal-inputcabang').modal('show');
  });

  // Validate create
  $('#formCabangCreate').on('submit', function () {
    if (!$('#kode_create').val()) {
      Swal.fire({ title:'Warning!', text:'Kode cabang wajib diisi', icon:'warning' });
      return false;
    }
    if (!$('#nama_create').val()) {
      Swal.fire({ title:'Warning!', text:'Nama cabang wajib diisi', icon:'warning' });
      return false;
    }
    if (!$('#latitude_create').val()) {
      Swal.fire({ title:'Warning!', text:'Latitude wajib diisi', icon:'warning' });
      return false;
    }
    if (!$('#longitude_create').val()) {
      Swal.fire({ title:'Warning!', text:'Longitude wajib diisi', icon:'warning' });
      return false;
    }
    if (!$('#radius_create').val()) {
      Swal.fire({ title:'Warning!', text:'Radius wajib diisi', icon:'warning' });
      return false;
    }
  });

  // Edit modal load
  $('.edit').on('click', function () {
    const id = $(this).data('id');
    $.ajax({
      type: 'POST',
      url: '/cabang/edit',
      data: { _token: "{{ csrf_token() }}", id },
      success: function (html) {
        $('#loadedcabang').html(html);
        $('#modal-editcabang').modal('show');
      }
    });
  });

  // Delete confirm
  $(document).on('click', '.delete-confirm', function (e) {
    e.preventDefault();
    const form = $(this).closest('form');
    Swal.fire({
      title: "Apakah Yakin?",
      text: "Data Cabang Akan Di Hapus!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Delete"
    }).then((r) => { if (r.isConfirmed) form.submit(); });
  });
});
</script>
@endpush
