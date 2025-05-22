<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">No. Parklaring</th>
                    <td>{{ $parklaring->no_parklaring }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $parklaring->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td>{{ $parklaring->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $parklaring->jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td>{{ $parklaring->departemen ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Masuk</th>
                    <td>{{ $parklaring->tgl_masuk ? date('d F Y', strtotime($parklaring->tgl_masuk)) : '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Terakhir Kerja</th>
                    <td>{{ date('d F Y', strtotime($parklaring->tgl_terakhir)) }}</td>
                </tr>
                <tr>
                    <th>Tanggal Dibuat</th>
                    <td>{{ date('d F Y H:i', strtotime($parklaring->created_at)) }}</td>
                </tr>
                <tr>
                    <th>Masa Kerja</th>
                    <td>
                    @php
                        $tgl_masuk = \Carbon\Carbon::parse($parklaring->tgl_masuk);
                        $tgl_terakhir = \Carbon\Carbon::parse($parklaring->tgl_terakhir);
                        $diff = $tgl_masuk->diff($tgl_terakhir);

                        $masa_kerja = '';
                        if ($diff->y > 0) {
                            $masa_kerja .= $diff->y . ' tahun ';
                        }
                        if ($diff->m > 0) {
                            $masa_kerja .= $diff->m . ' bulan ';
                        }
                        if ($diff->d > 0) {
                            $masa_kerja .= $diff->d . ' hari';
                        }
                        echo $masa_kerja;
                    @endphp
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
