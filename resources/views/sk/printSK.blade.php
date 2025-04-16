<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>9dad3cfa-7b10-4909-b305-cb5dde1d4ed3</title>
    <meta name="author" content="CHL" />
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        h1 {
            color: black;
            font-family: "Times New Roman", serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 18pt;
        }

        .s1 {
            color: black;
            font-family: Calibri, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        h2 {
            color: black;
            font-family: "Palatino Linotype", serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: underline;
            font-size: 15pt;
        }

        .s2 {
            color: black;
            font-family: "Palatino Linotype", serif;
            font-style: italic;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        h3 {
            color: black;
            font-family: "Palatino Linotype", serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
        }

        .p,
        p {
            color: black;
            font-family: "Palatino Linotype", serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
            margin: 0pt;
        }

        .s3 {
            color: black;
            font-family: "Palatino Linotype", serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: underline;
            font-size: 12pt;
        }

        .centered {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 10px;
            margin-left: -70px;
        }

        .centered img {
            height: 70px;
            /* Adjust this value as needed */
        }
    </style>
    <script>
        // Trigger print dialog on page load
        window.onload = function() {
            window.print();
        };
    </script>
</head>

<body style="
    padding-left: 30px;
    padding-right: 30px;
">
    <div class="centered">
        <img src="{{ asset('assets/img/logoDoc.png') }}" alt="image" />
        <div>
            <h1>PT {{ strtoupper($ptDetails->long_name ?? 'BANGUN INDAH HARMONI') }}</h1>
            <p class="s1">Alamat : Pisa Grande Blok C 35-38 Jl. Boulevard Raya Gading Serpong </p>
            <p class="s1">Klp. Dua, Kec. Klp. Dua,
                Tangerang, Banten 15810</p>
            <p class="s1">Telp +62 21 2222 0080 Fax. 021 2222 0081</p>
        </div>
    </div>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="padding-left: 5pt;text-indent: 0pt;line-height: 1pt;text-align: left;" />
    <hr style="border: none; border-top: 1px solid black; margin: 10px auto; width: 600px;" />
    <p style="padding-top: 2pt;text-indent: 0pt;text-align: left;"><br /></p>
    <h2 style="text-indent: 0pt;line-height: 20pt;text-align: center;">SURAT KEPUTUSAN</h2>
    <p class="s2" style="text-indent: 0pt;line-height: 16pt;text-align: center;">No. :
        {{$sk->no_sk}}
    </p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <h3 style="text-indent: 0pt;text-align: center;">Tentang</h3>
    <h3 style="padding-top: 8pt;text-indent: 0pt;text-align: center;">PENGANGKATAN SEBAGAI KARYAWAN
        TETAP</h3>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <h3 style="padding-left: 40pt;text-indent: 0pt;text-align: left;">Menimbang &nbsp;&nbsp;&nbsp;&nbsp; : <span class="p">Hasil evaluasi kerja
            dari perusahaan.</span></h3>
    <h3 style="padding-top: 16pt;padding-left: 40pt;text-indent: 0pt;text-align: left;">Mengingat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <span
            class="p">Kebutuhan karyawan sesuai perencanaan organisasi Perusahaan.</span></h3>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <h3 style="padding-left: 40pt;text-indent: 0pt;text-align: left;">Menetapkan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span class="p">Terhitung mulai
            tanggal surat ini ditetapkan, Direksi memutuskan</span></h3>
    <span class="p" style="padding-left: 134pt">untuk mengangkat:</span>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <h3 style="padding-left: 134pt;text-indent: 0pt;text-align: left;">Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$sk->nama_karyawan}}</h3>
    <h3 style="padding-left: 134pt;text-indent: 0pt;text-align: left;">NIK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$sk->nik}}</h3>
    <h3 style="padding-left: 134pt;text-indent: 0pt;text-align: left;">Job Title &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$sk->nama_jabatan}}</h3>
    <h3 style="padding-left: 134pt;text-indent: 0pt;text-align: left;">Golongan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$sk->grade}}</h3>
    <h3 style="padding-left: 134pt;text-indent: 0pt;line-height: 16pt;text-align: left;">Group Dept. &nbsp;: {{$sk->kode_dept}}</h3>
    <h3 style="padding-left: 134pt;text-indent: 0pt;line-height: 16pt;text-align: left;">Department &nbsp;&nbsp;: {{$sk->kode_dept}}</h3>
    <p style="padding-top: 5pt;text-indent: 0pt;text-align: left;"><br /></p>
    <p style="padding-left: 134pt;text-indent: 0pt;text-align: left;">dari <b>karyawan kontrak </b>menjadi <b>karyawan
            tetap</b>.</p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="padding-left: 134pt;text-indent: 0pt;text-align: left;">Demikian surat ini dibuat, ketentuan lainnya
        sesuai dengan Peraturan Perusahaan yang berlaku.</p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="line-height: 137%;text-align: center;">Ditetapkan di Serpong</p>
    <p style="line-height: 137%;text-align: center;">Pada
        tanggal {{$dateNow}}</p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p style="text-indent: 0pt;text-align: left;"><br /></p>
    <p class="s3" style="text-indent: 0pt;text-align: center;">Al Imron</p>
    <p style="text-indent: 0pt;text-align: center;">Associate Director</p>
</body>

</html>
