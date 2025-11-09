{{-- resources/views/cabang/_edit.blade.php --}}
<form action="/cabang/{{ $cabang->id }}" method="POST" id="formCabang">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Company</label>
        <select name="company_id" class="form-select" required>
            <option value="">Pilih Company</option>
            @foreach($companies as $company)
            <option value="{{ $company->id }}" {{ $cabang->company_id == $company->id ? 'selected' : '' }}>
                {{ $company->short_name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Kode Cabang</label>
        <div class="input-icon">
            <span class="input-icon-addon">#</span>
            <input type="text" value="{{ $cabang->kode }}" class="form-control" name="kode" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Cabang</label>
        <div class="input-icon">
            <span class="input-icon-addon">@</span>
            <input type="text" value="{{ $cabang->nama }}" class="form-control" name="nama" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Alamat</label>
        <input type="text" value="{{ $cabang->alamat }}" class="form-control" name="alamat">
    </div>

    <div class="mb-3">
        <label class="form-label">Kota</label>
        <input type="text" value="{{ $cabang->kota }}" class="form-control" name="kota">
    </div>

    <button class="btn btn-primary w-100">Update</button>
</form>