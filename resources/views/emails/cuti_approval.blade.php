<!DOCTYPE html>
<html>
<head>
    <title>Email</title>
</head>
<body>
    <p><strong>Approved Leave</strong></p>
    <p><strong>Pengajuan Cuti Karyawan</strong></p>

    <p><strong>Nama:</strong> {{ $leaveApplication->nama_karyawan }}</p>
    <p><strong>NIK:</strong> {{ $leaveApplication->nik }}</p>

    @if($leaveApplication->jenis_cuti == 'Cuti Tahunan')
        <p><strong>Periode Cuti:</strong> {{ $leaveApplication->periode }}</p>
        <p><strong>Sisa Cuti:</strong> {{ $leaveApplication->sisa_cuti }}</p>
    @endif

    <p><strong>Tanggal Cuti:</strong> {{ \App\Helpers\DateHelper::formatIndonesianDate($leaveApplication->tgl_cuti) }}</p>
    <p><strong>Tanggal Cuti Sampai:</strong>
        {{ !empty($leaveApplication->tgl_cuti_sampai) ? \App\Helpers\DateHelper::formatIndonesianDate($leaveApplication->tgl_cuti_sampai) : '' }}
    </p>
    <p><strong>Jumlah Hari:</strong> {{ $leaveApplication->jml_hari }}</p>

    @if($leaveApplication->jenis_cuti == 'Cuti Tahunan')
        <p><strong>Sisa Cuti Setelah:</strong> {{ $leaveApplication->sisa_cuti_setelah }}</p>
        <p><strong>Karyawan Pengganti:</strong> {{ $leaveApplication->kar_ganti }}</p>
    @endif

    <p><strong>Note:</strong> {{ $leaveApplication->note }}</p>

    @if($showApprovalButtons)
        <br>
        <a href="{{ $approveUrl }}" style="padding:10px 20px; background:green; color:white; text-decoration:none;">Accept</a>
        <a href="{{ $denyUrl }}" style="padding:10px 20px; background:red; color:white; text-decoration:none; margin-left:10px;">Deny</a>
        <br><br>
    @else
        <p><strong>Ini hanyalah email informasi. Tidak diperlukan action apa pun</strong></p>
    @endif

    <p>Atau Mohon Cek Di <a href="https://hrms.ciptaharmoni.com/panel">HRMS</a></p>
    <br>
    <p>Terima Kasih</p>
</body>
</html>
