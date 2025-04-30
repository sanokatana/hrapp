<!DOCTYPE html>
<html>
@php
use App\Helpers\DateHelper;
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Form Aplikasi CHL</title>
    <style>
        /* Font Definitions */
        @font-face {
            font-family: "Cambria Math";
            panose-1: 2 4 5 3 5 4 6 3 2 4;
        }

        @font-face {
            font-family: Calibri;
            panose-1: 2 15 5 2 2 2 4 3 2 4;
        }

        @font-face {
            font-family: "Century Gothic";
            panose-1: 2 11 5 2 2 2 2 2 2 4;
        }

        /* Style Definitions */
        p.MsoNormal,
        li.MsoNormal,
        div.MsoNormal {
            margin-top: 0in;
            margin-right: 0in;
            margin-bottom: 5.2pt;
            margin-left: .5pt;
            text-indent: -.5pt;
            line-height: 107%;
            font-size: 14px;
            font-family: "Times New Roman", serif;
            color: black;
        }

        h1 {
            mso-style-link: "Heading 1 Char";
            margin-top: 0in;
            margin-right: 0in;
            margin-bottom: 0in;
            margin-left: .5pt;
            text-indent: -.5pt;
            line-height: 107%;
            page-break-after: avoid;
            font-size: 11.0pt;
            font-family: "Times New Roman", serif;
            color: black;
        }

        span.Heading1Char {
            mso-style-name: "Heading 1 Char";
            mso-style-link: "Heading 1";
            font-family: "Times New Roman", serif;
            color: black;
            font-weight: bold;
        }

        .MsoChpDefault {
            font-size: 12.0pt;
        }

        .MsoPapDefault {
            margin-bottom: 8.0pt;
            line-height: 115%;
        }

        /* Page Definitions */
        @page WordSection1 {
            size: 595.3pt 841.9pt;
            margin: 50pt 57pt 60pt 57pt;
        }

        div.WordSection1 {
            page: WordSection1;
        }

        /* List Definitions */
        ol {
            margin-bottom: 0in;
        }

        ul {
            margin-bottom: 0in;
        }

        @media (max-width: 900px) {
            img {
                max-width: 100%;
                height: auto;
            }

            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th {
                padding: 8px;
                text-align: left;
                border: 1px solid #ddd;
            }
        }

        @page {
            size: A4;
        }

        .dotted-line {
            display: inline-block;
            width: 100%;
            border-bottom: 1px dotted;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .circled {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid black;
            /* Circle border color */
            border-radius: 50%;
            /* Make it a circle */
            text-align: center;
            text-indent: 0pt;
            background-color: white;
            /* Optional: set background color */
        }


        .circle-text {
            margin-left: 5px;
            /* Optional: add some spacing */
        }

        .centered {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 50px;
            margin-left: -70px;
        }

        .centered img {
            height: 70px;
            /* Adjust this value as needed */
        }

        .s1 {
            font-family: 'Times New Roman', Times, serif;
            font-style: normal;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: none;
            font-size: 20pt;

            /* Gradient color effect with a middle stop */
            background: linear-gradient(to right, #DD8D4B, #98551D 50%, #DD8D4B);
            -webkit-background-clip: text;
            color: transparent;
            background-clip: text;
        }

        .s2 {
            color: black;
            font-family: 'Times New Roman', Times, serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
        }

        .s3 {
            color: black;
            font-family: 'Times New Roman', Times, serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 18pt;
        }

        body {
            overflow-wrap: break-word;
            position: relative;
            color: #333;
            /* Text color for readability */
        }

        /* Watermark background */
        /* Watermark background */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: 900px;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.2;
            z-index: -1;
            pointer-events: none;
            background-image: url("{{ asset('assets/img/logoPic.png') }}");
        }


        /* Flex container for Catatan and Photo */
        .flex-container {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-top: 20px;
        }

        .catatan {
            flex: 1;
            padding-right: 70px;
        }

        .photo-box {
            width: 120px;
            /* Adjust width as needed */
            border: 0.8pt solid #000000;
            text-align: center;
            min-height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        li {
            display: block;
        }

        #l1 {
            padding-left: 0pt;
            counter-reset: c1 1;
        }
        #l1>li>*:first-child:before {
            counter-increment: c1;
            content: counter(c1, lower-latin)". ";
            color: black;
            font-family: "Times New Roman", serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
        }

        #l1>li:first-child>*:first-child:before {
            counter-increment: c1 0;
        }
    </style>
    <script>
        // Trigger print dialog on page load
        window.onload = function() {
            window.print();
        };
    </script>
</head>

<body lang="EN-US" style="overflow-wrap: break-word;">
    <div class="WordSection1">
        <div class="centered">
            <img src="{{ asset('assets/img/logoDoc.png') }}" alt="image" style="width: 80px; height: 80px" />
            <div>
                <h1 class="s1">CIPTA HARMONI LESTARI GROUP</h1>
                <h1 class="s2">Alamat : Ruko Mendrisio III Blok B No. 11- Paramount</h1>
                <h1 class="s2">Gading Serpong, Tangerang, Banten 15312</h1>
                <h1 class="s2">Telp. 021 2222 0080 Fax. 021 2222 0081</h1>
            </div>
        </div>
        <hr style="border: none; border-top: 0.5px solid black; margin: 10px auto; width: 100%; margin-top: 30px" />
        <p class="s3" style="padding-top: 3pt;text-indent: 0pt;text-align: center;">FORMULIR LAMARAN KERJA</p>

        <!-- Flex container -->
        <div class="flex-container MsoNormal" style="margin-bottom: 20px;">
            <div class="catatan MsoNormal">
                <h1 style="padding-top: 11pt;text-indent: 0pt;line-height: 10pt;text-align: left;">Catatan :</h1>
                <ol id="l1">
                    <li data-list-text="a.">
                        <p style="padding-left: 17pt;text-indent: -17pt;line-height: 10pt;text-align: left;">&nbsp;&nbsp;Harap ditulis dengan huruf cetak</p>
                    </li>
                    <li data-list-text="b.">
                        <p style="padding-left: 18pt;text-indent: -18pt;text-align: left; margin-top: -15px">&nbsp;&nbsp;Bila keterangan yang diberikan ini ada yang tidak sesuai dengan kenyataan, maka Perusahaan berhak memutuskan hubungan kerja</p>
                    </li>
                </ol>
            </div>

            <div class="photo-box">
                <p>Photo</p>
            </div>
        </div>
        <p class="MsoNormal" style="margin-top:0in; margin-bottom:0in; margin-left:-.25pt; display: flex; align-items: center;">
            Jabatan anda melamar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
            <span style="display: inline-block; width: 440px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
            &nbsp;{{$candidates->job_opening_name}}
            </span>
        </p>

        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
        <h1 style="margin-left:-.25pt;">A. IDENTITAS</h1>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="643" style="width:482.6pt;border-collapse:collapse;">
            <tbody>
                <tr style="height:15.6pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 15.6pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">1.&nbsp;&nbsp; Nama Lengkap &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 15.6pt;">
                        <p class="MsoNormal" style="margin:0in; text-align:justify; text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">
                                : {{$candidates->nama_lengkap}}
                            </span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">2.&nbsp;&nbsp; Nama Kecil/Panggilan</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->nama_panggilan}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">3.&nbsp;&nbsp; Jenis Kelamin &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">:&nbsp;{{$candidates->jenis}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Gol. Darah :&nbsp; {{$candidates->gol_darah}}&nbsp;</p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">4.&nbsp;&nbsp; Tempat/Tgl Lahir &nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->tempat_lahir}} / {{DateHelper::formatIndonesiaDate($candidates->tgl_lahir)}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">5.&nbsp;&nbsp; Warga Negara &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->warga_negara}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">6.&nbsp;&nbsp; Alamat Rumah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$addressLine1}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">&nbsp; {{$addressLine2}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">&nbsp; {{$addressLine3}}</span></p>
                    </td>
                </tr>
                <tr style="height:19.0pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 19pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">7.&nbsp;&nbsp; Telpon Rumah/HP&nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 19pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->telp_rumah_hp}}</span></p>
                    </td>
                </tr>
                <tr style="height:19.0pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 19pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">8.&nbsp;&nbsp; No. KTP / SIM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 19pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->no_ktp_sim}}</p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">9.&nbsp;&nbsp; Tgl. Berlaku KTP/SIM</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{DateHelper::formatIndonesiaDate($candidates->tgl_ktp_sim)}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">10. No. NPWP&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->no_npwp}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">11. Status Keluarga &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">: @if($candidates->marriage_status === 'Menikah')
                            Menikah
                            @elseif($candidates->marriage_status === 'Tidak Menikah')
                            Tidak Menikah
                            @endif
                        </p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">12. Tanggal Menikah &nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{ $candidates->tgl_menikah ? DateHelper::formatIndonesiaDate($candidates->tgl_menikah) : '' }}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">13. Jabatan saat ini &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->jabatan}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">14. Nama Perusahaan &nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->nama_perusahaan}}</span></p>
                    </td>
                </tr>
                <tr style="height:18.95pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">15. Alamat Perusahaan&nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 18.95pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$addressLine4}}</span></p>
                    </td>
                </tr>
                <tr style="height:19.0pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 19pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 19pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">&nbsp;{{$addressLine5}}</span></p>
                    </td>
                </tr>
                <tr style="height:15.65pt;">
                    <td valign="top" style="width: 192px; padding: 0in; height: 15.65pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">16. Alamat Email&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
                    </td>
                    <td valign="top" style="width: 451px; padding: 0in; height: 15.65pt;">
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{$candidates->alamat_email}}</span></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:17.15pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <h1 style="margin-top:0in;margin-right:0in;margin-bottom:5.2pt;margin-left: -.25pt;">B. KELUARGA &amp; LINGKUNGAN</h1>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:11.05pt;text-indent:-11.05pt;"><span style="line-height:107%;">1.<span style='font:7.0pt "Times New Roman";'>&nbsp;</span></span> Susunan Keluarga (Suami/Istri dan anak &ndash; anak)</p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="642" style="width:481.55pt;margin-left:.25pt;border-collapse:collapse;">
            <tbody>
                <tr style="height:19.55pt;">
                    <td valign="top" style="width: 93px; border: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt;">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:2.25pt; margin-bottom:0in;margin-left:0in;text-align:center;text-indent:0in; text-align: center; vertical-align: middle">Uraian</p>
                    </td>
                    <td valign="top" style="width: 115px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:3.95pt;text-indent:0in;">Nama Lengkap</p>
                    </td>
                    <td valign="top" style="width: 65px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:2.25pt; margin-bottom:0in;margin-left:0in;text-align:center;text-indent:0in;">L /P</p>
                    </td>
                    <td valign="top" style="width: 99px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.35pt;text-indent:0in;">Tanggal Lahir</p>
                    </td>
                    <td valign="top" style="width: 85px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:2.05pt;text-indent:0in;">Pendidikan</p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:2.4pt; margin-bottom:0in;margin-left:0in;text-align:center;text-indent:0in;">Pekerjaan</p>
                    </td>
                    <td valign="top" style="width: 93px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:4.2pt;text-indent:0in;">Keterangan</p>
                    </td>
                </tr>
                @foreach ($familyMembers as $member)
                <tr style="height: 19.45pt;">
                    <td valign="top" style="width: 93px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['uraian'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['nama_lengkap'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['jenis'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['tgl_lahir'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['pendidikan'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['pekerjaan'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['keterangan'] }}
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="font-size: 10.0pt;line-height:107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="font-size: 10.0pt;line-height:107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:11.05pt;text-indent:-11.05pt;"><span style="line-height:107%;">2.<span style='font:7.0pt "Times New Roman";'>&nbsp;</span></span> Susunan Keluarga (Ayah, Ibu dan Saudara Kandung termasuk Saudara)</p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="642" style="width:481.55pt;margin-left:.25pt;border-collapse:collapse;">
            <tbody>
                <tr style="height:19.6pt;">
                    <td valign="top" style="width: 91px; border: 1pt solid black; padding: 0.7pt 3.15pt 0in 5.4pt; height: 19.6pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:1.9pt; margin-bottom:0in;margin-left:0in;text-align:center;text-indent:0in;">&nbsp;Uraian</p>
                    </td>
                    <td valign="top" style="width: 115px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 3.15pt 0in 5.4pt; height: 19.6pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:4.1pt;text-indent:0in;">Nama Lengkap</p>
                    </td>
                    <td valign="top" style="width: 65px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 3.15pt 0in 5.4pt; height: 19.6pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:2.15pt; margin-bottom:0in;margin-left:0in;text-align:center;text-indent:0in;">L /P</p>
                    </td>
                    <td valign="top" style="width: 99px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 3.15pt 0in 5.4pt; height: 19.6pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.35pt;text-indent:0in;">Tanggal Lahir</p>
                    </td>
                    <td valign="top" style="width: 86px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 3.15pt 0in 5.4pt; height: 19.6pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:1.9pt;text-indent:0in;">Pendidikan</p>
                    </td>
                    <td valign="top" style="width: 93px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 3.15pt 0in 5.4pt; height: 19.6pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:2.3pt; margin-bottom:0in;margin-left:0in;text-align:center;text-indent:0in;">Pekerjaan</p>
                    </td>
                    <td valign="top" style="width: 93px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 3.15pt 0in 5.4pt; height: 19.6pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:4.2pt;text-indent:0in;">Keterangan</p>
                    </td>
                </tr>
                @foreach ($familyMembers1 as $member)
                <tr style="height: 19.45pt;">
                    <td valign="top" style="width: 93px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['uraian'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['nama_lengkap'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['jenis'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['tgl_lahir'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['pendidikan'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['pekerjaan'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $member['keterangan'] }}
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:.05pt; margin-left:0in;text-indent:0in;"><span style="font-size:10.0pt;line-height: 107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin-left:11.05pt;text-indent:-11.05pt;">
            <span style="line-height:107%;">3.<span style='font:7.0pt "Times New Roman";'>&nbsp;</span></span>
            Apakah Saudara mempunyai tanggung jawab lain selain anak &amp; istri?
            <span class="circle-text">
                @if($candidates->tanggung_jawab == 'Ya')
                <span class="circled">Ya</span>
                @else
                Ya
                @endif
            </span>
            /
            <span class="circle-text">
                @if($candidates->tanggung_jawab == 'Tidak')
                <span class="circled">Tidak</span>
                @else
                Tidak
                @endif
            </span>
        </p>
        <p class="MsoNormal" style="margin-left:-.25pt;">&nbsp;&nbsp;&nbsp; Siapa dan berapa besar tanggungan ? {{$candidates->siapa_tanggungan}} / Rp. {{ number_format($candidates->nilai_tanggungan, thousands_separator: '.') }} / bulan</p>
        <p class="MsoNormal" style="margin-left:11.05pt;text-indent:-11.05pt;">
            <span style="line-height:107%;">4.<span style='font:7.0pt "Times New Roman";'>&nbsp;</span></span>
            Apakah rumah status yang Saudara tempati saat ini:
            <span class="{{ $candidates->rumah_status == 'Rumah Pribadi' ? 'circled' : '' }}">Rumah Pribadi</span> /
            <span class="{{ $candidates->rumah_status == 'Orang Tua' ? 'circled' : '' }}">Orang Tua</span> /
            <span class="{{ $candidates->rumah_status == 'Kontrak' ? 'circled' : '' }}">Kontrak</span> /
            <span class="{{ $candidates->rumah_status == 'Lain-lain' ? 'circled' : '' }}">Lain-lain</span>.
        </p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
        <h1 style="margin-left:-.25pt;">C. PENDIDIKAN</h1>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="642" style="width:481.55pt;margin-left:.25pt;border-collapse:collapse;">
            <tbody>
                <tr style="height:38.4pt;">
                    <td valign="top" style="width: 92px; border: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 38.4pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.7pt;text-align:center;text-indent:0in; text-align: center; vertical-align: middle">Tingkat</p>
                    </td>
                    <td valign="top" style="width: 91px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 38.4pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:5.15pt;margin-left:.45pt;text-align:center;text-indent:0in;">Nama</p>
                        <p class="MsoNormal" align="center" style="margin-bottom:0in;text-align:center; text-indent:0in;">Sekolah</p>
                    </td>
                    <td valign="top" style="width: 91px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 38.4pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin:0in;text-align:center; text-indent:0in;">Tempat Sekolah</p>
                    </td>
                    <td valign="top" style="width: 91px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 38.4pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:5.15pt;margin-left:.55pt;text-align:center;text-indent:0in;">Jurusan</p>
                        <p class="MsoNormal" align="center" style="margin-bottom:0in;text-align:center; text-indent:0in;">Studi</p>
                    </td>
                    <td valign="top" style="width: 91px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 38.4pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:2.3pt;text-align:center;text-indent:0in;">Dari Sampai</p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 38.4pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:5.15pt;margin-left:.65pt;text-align:center;text-indent:0in;">Berijazah</p>
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.65pt;text-align:center;text-indent:0in;">(Thn)</p>
                    </td>
                    <td valign="top" style="width: 93px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 38.4pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:4.3pt;text-indent:0in;">Keterangan</p>
                    </td>
                </tr>
                @foreach ($pendidikanList as $pendidikan)
                <tr style="height: 19.45pt;">
                    <td valign="top" style="width: 93px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $pendidikan['tingkat_besar'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $pendidikan['nama_sekolah'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $pendidikan['tempat_sekolah'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $pendidikan['jurusan_studi'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $pendidikan['dari_sampai'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $pendidikan['berijazah'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $pendidikan['keterangan'] }}
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="font-size: 10.0pt;line-height:107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin-left:-.25pt;">1. Apakah Saudara masih melanjutkan pendidikan ?
            <span class="circle-text">
                @if($candidates->melanjut_pendidikan == 'Ya')
                <span class="circled">Ya</span>
                @else
                Ya
                @endif
            </span>
            /
            <span class="circle-text">
                @if($candidates->melanjut_pendidikan == 'Tidak')
                <span class="circled">Tidak</span>
                @else
                Tidak
                @endif
            </span>
        </p>
        <p class="MsoNormal" style="margin-left:-.25pt;">&nbsp;&nbsp;&nbsp; Jika Ya, sebutkan pendidikan apa dan kapan waktunya ( hari / jam ) {{$penjelasan1}}</p>
        <p class="MsoNormal" style="margin-left:-.25pt;line-height:148%;">&nbsp;&nbsp;&nbsp; {{$penjelasan2}} <strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.25pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <h1 style="margin-left:-.25pt;">D. KURSUS / TRAINING (isikan dari urutan yang terbaru)</h1>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="645" style="width:484.05pt;margin-left:.25pt;border-collapse:collapse;">
            <tbody>
                <tr style="height:25.8pt;">
                    <td valign="top" style="width: 91px; border: 1pt solid black; padding: 0.6pt 5.75pt 0in 5.4pt; height: 25.8pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.6pt;text-align:center;text-indent:0in;">Nama</p>
                    </td>
                    <td valign="top" style="width: 93px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 25.8pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin:0in;text-align:center; text-indent:0in;">Diadakan&nbsp; Oleh</p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 25.8pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.55pt;text-align:center;text-indent:0in;">Tempat</p>
                    </td>
                    <td valign="top" style="width: 91px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 25.8pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.45pt;text-align:center;text-indent:0in;">Lama</p>
                    </td>
                    <td valign="top" style="width: 91px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 25.8pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.35pt;text-align:center;text-indent:0in;">Tahun</p>
                    </td>
                    <td valign="top" style="width: 93px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 25.8pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin:0in;text-align:center; text-indent:0in;">Dibiayai Oleh</p>
                    </td>
                    <td valign="top" style="width: 94px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 25.8pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.6pt;text-align:center;text-indent:0in;">Keterangan</p>
                    </td>
                </tr>
                @foreach ($kursusList as $kursus)
                <tr style="height: 19.45pt;">
                    <td valign="top" style="width: 93px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $kursus['nama'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $kursus['diadakan_oleh'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $kursus['tempat'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $kursus['lama'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $kursus['tahun'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $kursus['dibiayai_oleh'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $kursus['keterangan'] }}
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="font-size: 10.0pt;line-height:107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="font-size: 10.0pt;line-height:107%;">&nbsp;</span></p>
        <h1 style="margin-left:-.25pt;">E. PENGUASAAN BAHASA DAN KETERAMPILAN</h1>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:-.25pt;"><span style="font-size:10.0pt;line-height:107%;">Pilih Baik, Cukup, Kurang</span></p>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="648" style="width:6.75in;margin-left:.25pt;border-collapse:collapse;">
            <tbody>
                <tr style="height:15.5pt;">
                    <td valign="top" style="width: 110px; border: 1pt solid black; padding: 0.6pt 5.75pt 0in 5.4pt; height: 15.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.55pt;text-align:center;text-indent:0in;">Bahasa</p>
                    </td>
                    <td valign="top" style="width: 110px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 15.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.4pt;text-align:center;text-indent:0in;">Bicara</p>
                    </td>
                    <td valign="top" style="width: 110px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 15.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.35pt;text-align:center;text-indent:0in;">Baca</p>
                    </td>
                    <td valign="top" style="width: 110px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 15.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-bottom:0in;text-align:center; text-indent:0in;">Tulis</p>
                    </td>
                    <td valign="top" style="width: 209px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 5.75pt 0in 5.4pt; height: 15.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.25pt;text-align:center;text-indent:0in;">Mengetik Steno WPM</p>
                    </td>
                </tr>
                @foreach ($bahasaList as $bahasa)
                <tr style="height: 19.45pt;">
                    <td valign="top" style="width: 93px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $bahasa['bahasa'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $bahasa['bicara'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $bahasa['baca'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $bahasa['tulis'] }}
                        </p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 2.9pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">
                            {{ $bahasa['steno'] }}
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:-.25pt;"><strong>F. RIWAYAT PEKERJAAN</strong> <span style="font-size:10.0pt; line-height:107%;">(Isikan urutan dari pekerjaan saat ini)</span></p>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="647" style="width:485.4pt;margin-left:.25pt;border-collapse:collapse;">
            <tbody>
                @foreach ($pekerjaanList as $pekerjaan)
                <tr style="height:13.2pt;">
                    <td valign="top" style="width: 107px; border: 1pt solid black; padding: 0.6pt 3.1pt 0in 5.4pt; height: 13.2pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">Perusahaan</p>
                    </td>
                    <td valign="top" style="width: 116px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 3.1pt 0in 5.4pt; height: 13.2pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Alamat</p>
                    </td>
                    <td valign="top" style="width: 108px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 3.1pt 0in 5.4pt; height: 13.2pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Jabatan</p>
                    </td>
                    <td valign="top" style="width: 100px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 3.1pt 0in 5.4pt; height: 13.2pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">Dari</p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 3.1pt 0in 5.4pt; height: 13.2pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Sampai</p>
                    </td>
                    <td valign="top" style="width: 124px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.6pt 3.1pt 0in 5.4pt; height: 13.2pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Keterangan</p>
                    </td>
                </tr>
                <tr style="height:14.5pt;">
                    <td valign="top" style="width: 107px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.6pt 3.1pt 0in 5.4pt; height: 14.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$pekerjaan['perusahaan']}}</p>
                    </td>
                    <td valign="top" style="width: 116px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.6pt 3.1pt 0in 5.4pt; height: 14.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">{{$pekerjaan['alamat']}}</p>
                    </td>
                    <td valign="top" style="width: 108px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.6pt 3.1pt 0in 5.4pt; height: 14.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">{{$pekerjaan['jabatan']}}</p>
                    </td>
                    <td valign="top" style="width: 100px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.6pt 3.1pt 0in 5.4pt; height: 14.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$pekerjaan['dari']}}</p>
                    </td>
                    <td valign="top" style="width: 92px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.6pt 3.1pt 0in 5.4pt; height: 14.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">{{$pekerjaan['sampai']}}</p>
                    </td>
                    <td valign="top" style="width: 124px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.6pt 3.1pt 0in 5.4pt; height: 14.5pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">{{$pekerjaan['keterangan']}}</p>
                    </td>
                </tr>
                <tr style="height:25.8pt;">
                    <td valign="top" colspan="6" style="border-left: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; padding: 0.6pt 3.1pt 0in 5.4pt; text-align: left;">
                        <p class="MsoNormal" style="margin-top:0in; margin-right:0in; margin-bottom:0in; margin-left:.1pt; text-indent:0in;">
                            Alasan Keluar: {{ $pekerjaan['alasan'] ?? '' }}
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:4.8pt; margin-left:0in;text-indent:0in;"><span style="font-size:10.0pt;line-height: 107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.8pt; margin-left:0in;text-indent:0in;"><span style="font-size:10.0pt;line-height: 107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin-left:-.25pt;">Selain untuk meningkatkan karir dan pendapatan, sebutkan alasan saudara meninggalkan pekerjaan terakhir:</p>
        <p class="MsoNormal" style="margin-left:-.25pt;"><span class="dotted-line">{{$alasan1}}</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:-.25pt;line-height:148%;"><span class="dotted-line">{{$alasan2}}</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;"><span class="dotted-line">{{$alasan3}}</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-left:-.25pt;">1. Berilah uraian pekerjaan dari jabatan terakhir: <span class="dotted-line">{{$alasan4}}</span></p>
        <p class="MsoNormal" style="margin-left:-.25pt;"><span class="dotted-line">{{$alasan5}}</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:-.25pt;line-height:148%;"><span class="dotted-line">{{$alasan6}}</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.65pt; margin-left:0in;text-indent:0in;"><span style="font-size:10.0pt;line-height: 107%;">&nbsp;</span></p>
        <h1 style="margin-left:-.25pt;">G. MINAT DAN KONSEP PRIBADI</h1>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="font-size: 10.0pt;line-height:107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:-.25pt;">HAL-HAL LAIN YANG BERHUBUNGAN DENGAN LAMARAN SAUDARA.</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:-.25pt;">Berikan nomor secara berurutan bagian/jenis macam pekerjaan yang anda senangi :</p>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="642" style="width:481.55pt;margin-left:.25pt;border-collapse:collapse;">
            <tbody>
                <tr style="height:19.45pt;">
                    <td valign="top" style="width: 163px; border: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.7pt;text-align:center;text-indent:0in;">Jenis Pekerjaan</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.7pt;text-align:center;text-indent:0in;">Nomor</p>
                    </td>
                    <td valign="top" style="width: 162px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.45pt;text-align:center;text-indent:0in;">Jenis Pekerjaan</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: 1pt solid black; border-right: 1pt solid black; border-bottom: 1pt solid black; border-image: initial; border-left: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:.65pt;text-align:center;text-indent:0in;">Nomor</p>
                    </td>
                </tr>
                <tr style="height:19.45pt;">
                    <td valign="top" style="width: 163px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">Engineering</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->engineering_no}}</p>
                    </td>
                    <td valign="top" style="width: 162px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Accounting</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->accounting_no}}</p>
                    </td>
                </tr>
                <tr style="height:19.55pt;">
                    <td valign="top" style="width: 163px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">Geologist</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->geologist_no}}</p>
                    </td>
                    <td valign="top" style="width: 162px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Administration</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->administration_no}}</p>
                    </td>
                </tr>
                <tr style="height:19.45pt;">
                    <td valign="top" style="width: 163px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">Agronomist</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->agronomist_no}}</p>
                    </td>
                    <td valign="top" style="width: 162px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">General Affair</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->ga_no}}</p>
                    </td>
                </tr>
                <tr style="height:19.45pt;">
                    <td valign="top" style="width: 163px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">Consultant/Riset</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->consultant_no}}</p>
                    </td>
                    <td valign="top" style="width: 162px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Personnel</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->personnel_no}}</p>
                    </td>
                </tr>
                <tr style="height:19.45pt;">
                    <td valign="top" style="width: 163px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">Cashier</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->cashier_no}}</p>
                    </td>
                    <td valign="top" style="width: 162px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Finance</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.45pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->finance_no}}</p>
                    </td>
                </tr>
                <tr style="height:19.55pt;">
                    <td valign="top" style="width: 163px; border-right: 1pt solid black; border-bottom: 1pt solid black; border-left: 1pt solid black; border-image: initial; border-top: none; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">Humas</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->humas_no}}</p>
                    </td>
                    <td valign="top" style="width: 162px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">Driver</p>
                    </td>
                    <td valign="top" style="width: 159px; border-top: none; border-left: none; border-bottom: 1pt solid black; border-right: 1pt solid black; padding: 0.7pt 5.75pt 0in 5.4pt; height: 19.55pt; text-align: center; vertical-align: middle">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.1pt;text-indent:0in;">{{$candidates->driver_no}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.3pt; margin-left:.25in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-left:.25in;text-indent:-.25in;"><span style="line-height:107%;">1.<span style='font:7.0pt "Times New Roman";'>&nbsp;&nbsp;&nbsp;&nbsp;</span></span> Pernahkah Saudara melamar pekerjaan di Perusahaan kami ? :</p>
        <p class="MsoNormal" style="margin-left:18.5pt;"><span class="dotted-line">&nbsp;{{$candidates->saudara_pekerjaan}}</span></p>
        <p class="MsoNormal" style="margin-left:.25in;text-indent:-.25in;"><span style="line-height:107%;">2.<span style='font:7.0pt "Times New Roman";'>&nbsp;&nbsp;&nbsp;&nbsp;</span></span> Organisasi apakah yang pernah Saudara masuki ?&nbsp; Sebutkan jabatan &ndash; jabatan yang pernah Anda pegang</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:.15pt; margin-left:18.5pt;line-height:148%;"><span class="dotted-line">{{$candidates->organisasi}}</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:.25in;text-indent:-.25in;line-height:149%;"><span style="line-height: 149%;">3.<span style='font:7.0pt "Times New Roman";'>&nbsp;&nbsp;&nbsp;&nbsp;</span></span> Dalam keadaan darurat, siapakah yang dapat dihubungi ? Sebutkan nama, alamat, telpon serta apa hubungannya Saudara dengan nama tersebut ? <span style="display: inline; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{$penjelasan3}}</span></p>
        <p class="MsoNormal" style="margin-left:18.5pt;"><span class="dotted-line">&nbsp;{{$penjelasan4}}</span></p>
        <p class="MsoNormal" style="margin-left:.25in;text-indent:-.25in;"><span style="line-height:107%;">4.<span style='font:7.0pt "Times New Roman";'>&nbsp;&nbsp;&nbsp;&nbsp;</span></span> Sebutkan dua nama sebagai referensi Saudara dalam hal ini (yang mengetahui tentang Anda)</p>
        <p class="MsoNormal" style="margin-left:18.5pt;"><span class="dotted-line">{{$candidates->nama_referensi1}}</span></p>
        <p class="MsoNormal" style="margin-left:18.5pt;"><span class="dotted-line">{{$candidates->nama_referensi2}}</span></p>
        <p class="MsoNormal" style="margin-left:.25in;text-indent:-.25in;"><span style="line-height:107%;">5.<span style='font:7.0pt "Times New Roman";'>&nbsp;&nbsp;&nbsp;&nbsp;</span></span> Apakah Saudara pernah menderita sakit yang lama ?</p>
        <p class="MsoNormal" style="margin-left:18.5pt;"><span class="dotted-line">{{$candidates->sakit_lama}}</span></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.3pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.25pt; margin-left:-.25pt;">
            <strong>H. GAMBARAN POSISI SAAT INI&nbsp;</strong>
        </p>
        <p class="MsoNormal" style="margin-left:-.25pt;">&nbsp;&nbsp;&nbsp;&nbsp; Gambarkan Posisi Anda saat ini dalam Struktur Organisasi&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:4.2pt; margin-left:0in;text-indent:0in;">&nbsp;</p>

        <?php

        use Illuminate\Support\Str;

        // Define the folder path
        $folderPath = public_path('storage/uploads/candidate/' . $candidates->candidate_id . '.' . Str::slug($candidates->nama_candidate) . '/');

        // Create the full file path for the gambaran_posisi image
        $imagePath = $folderPath . $candidates->gambaran_posisi;

        // Display the image if it exists
        if (file_exists($imagePath)) {
            echo '<img src="' . asset('storage/uploads/candidate/' . $candidates->candidate_id . '.' . Str::slug($candidates->nama_candidate) . '/' . $candidates->gambaran_posisi) . '" alt="Gambaran Posisi" style="max-width: 100%; height: auto; margin-top: 10px;">';
        } else {
            echo '<p style="color: red;"></p>';
        }
        ?>

        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:4.85pt; margin-left:0in;text-indent:0in;"><span style="font-size:10.0pt;line-height: 107%;">&nbsp;</span></p>

        <h1 style="margin-top:0in;margin-right:0in;margin-bottom:5.4pt;margin-left: -.25pt;">I. LAIN &ndash; LAIN</h1>
        <p class="MsoNormal" style="margin-left:36.05pt;text-indent:-21.85pt;"><span style="line-height:107%;">1.<span style='font:7.0pt "Times New Roman";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span> Apakah Saudara bersedia menjalani masa percobaan ? <span class="circle-text">
                @if($candidates->masa_percobaan == 'Ya')
                <span class="circled">Ya</span>
                @else
                Ya
                @endif
            </span>
            /
            <span class="circle-text">
                @if($candidates->masa_percobaan == 'Tidak')
                <span class="circled">Tidak</span>
                @else
                Tidak
                @endif
            </span>
        </p>
        <p class="MsoNormal" style="margin-left:36.05pt;text-indent:-21.85pt;"><span style="line-height:107%;">2.<span style='font:7.0pt "Times New Roman";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span> Bersediakan saudara untuk mengikuti proses BI Checking bersama CHL Group? <span class="circle-text">
                @if($candidates->proses_bi == 'Ya')
                <span class="circled">Ya</span>
                @else
                Ya
                @endif
            </span>
            /
            <span class="circle-text">
                @if($candidates->proses_bi == 'Tidak')
                <span class="circled">Tidak</span>
                @else
                Tidak
                @endif
            </span>
        </p>
        <p class="MsoNormal" style="margin-left:36.05pt;text-indent:-21.85pt;"><span style="line-height:107%;">3.<span style='font:7.0pt "Times New Roman";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span> Kapankah Saudara dapat mulai bekerja di perusahaan kami ? {{DateHelper::formatIndonesianDate($candidates->mulai_kerja)}}</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:0in; margin-left:-.25pt;line-height:148%;">Demikianlah hal tersebut di atas saya uraikan dengan sebenarnya dan saya berani mempertanggung jawabkan isi formulir di lamaran kerja ini.</p>
        <p class="MsoNormal" style="margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-left:-.25pt;">Serpong,........&nbsp; ..................................&nbsp; 20........</p>
        <p class="MsoNormal" style="margin-left:-.25pt;">Hormat saya,</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-left:-.25pt;">(........................................................)</p>
        <h1 style="text-align: justify; margin-left:10pt; page-break-before: always;">KETERANGAN PENGHASILAN</h1>
        <h1 style="text-align: justify; margin-left:10pt;">(HARAP LENGKAPI DENGAN SLIP GAJI 3</h1>
        <h1 style="text-align: justify; margin-left:10pt;">BULAN TERAKHIR)</h1>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.25pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.25pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:9.4pt;text-align:center;text-indent:0in;"><strong>PENDAPATAN TERAKHIR</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.75pt; margin-left:10.1pt;text-indent:0in;">&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style='font-family: "Calibri",sans-serif;'>&nbsp;</span> Gaji pokok &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                Rp &nbsp;{{ number_format($candidates->gaji_pokok, thousands_separator: '.') }}
            </span></p>
        <table class="TableGrid" border="0" cellspacing="0" cellpadding="0" width="583" style="width:437.5pt;margin-left:4.7pt;border-collapse:collapse;">
            <tbody>
                <tr style="height:34.9pt;">
                    <td valign="top" style="width: 324px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt dashed white; padding: 5.15pt 5.75pt 0in 0in; height: 34.9pt;">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.65pt; margin-left:0in;text-indent:0in;">Tunjangan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</p>
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 280px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{$candidates->tunjangan1}}</span></p>
                    </td>
                    <td valign="top" style="width: 199px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt dashed white; padding: 5.15pt 5.75pt 0in 0in; height: 34.9pt;">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.65pt; margin-left:0in;text-indent:0in;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</p>
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                Rp &nbsp;{{ number_format($candidates->nilai_tunjangan1, thousands_separator: '.') }}
                            </span></p>
                    </td>
                    <td valign="top" style="width: 61px; border-top: 1pt solid white; border-left: none; border-bottom: 1pt solid white; border-right: none; padding: 5.15pt 5.75pt 0in 0in; height: 34.9pt;">
                        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.35pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
                    </td>
                </tr>
                <tr style="height:17.65pt;">
                    <td valign="top" style="width: 324px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.65pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 280px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{$candidates->tunjangan2}}</span></p>
                    </td>
                    <td valign="top" style="width: 199px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.65pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                Rp &nbsp;{{ number_format($candidates->nilai_tunjangan2, thousands_separator: '.') }}
                            </span></p>
                    </td>
                    <td valign="top" style="width: 61px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.65pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
                    </td>
                </tr>
                <tr style="height:17.75pt;">
                    <td valign="top" style="width: 324px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.75pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 280px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{$candidates->tunjangan3}}</span></p>
                    </td>
                    <td valign="top" style="width: 199px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.75pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                Rp &nbsp;{{ number_format($candidates->nilai_tunjangan3, thousands_separator: '.') }}
                            </span></p>
                    </td>
                    <td valign="top" style="width: 61px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.75pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
                    </td>
                </tr>
                <tr style="height:17.65pt;">
                    <td valign="top" style="width: 324px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.65pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><u><span style="display: inline-block; width: 280px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{$candidates->tunjangan4}}</span></p>
                    </td>
                    <td valign="top" style="width: 199px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.65pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                Rp &nbsp;{{ number_format($candidates->nilai_tunjangan4, thousands_separator: '.') }}
                            </span></p>
                    </td>
                    <td valign="top" style="width: 61px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.65pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
                    </td>
                </tr>
                <tr style="height:17.75pt;">
                    <td valign="top" style="width: 324px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.75pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 280px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{$candidates->tunjangan5}}</span></p>
                    </td>
                    <td valign="top" style="width: 199px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.75pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                Rp &nbsp;{{ number_format($candidates->nilai_tunjangan5, thousands_separator: '.') }}
                            </span></p>
                    </td>
                    <td valign="top" style="width: 61px; border-top: none; border-right: none; border-left: none; border-image: initial; border-bottom: 1pt solid white; padding: 5.15pt 5.75pt 0in 0in; height: 17.75pt;">
                        <p class="MsoNormal" style="margin:0in;text-indent:0in;">&nbsp;</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style='font-family: "Calibri",sans-serif;'>&nbsp;&nbsp;</span>Insentif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                Rp &nbsp;{{ number_format($candidates->nilai_insentif, thousands_separator: '.') }}
            </span>
        </p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.25pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style='font-family: "Calibri",sans-serif;'>&nbsp;&nbsp;</span>Lain - lain&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                Rp &nbsp;{{ number_format($candidates->nilai_lain_lain, thousands_separator: '.') }}
            </span>
            &nbsp; +
        </p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.25pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.75pt; margin-left:0in;text-indent:0in;"><span style='font-family:"Calibri",sans-serif;'>&nbsp;</span> <strong>TOTAL TAKE HOME PAY</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.75pt; margin-left:0in;text-indent:0in;"><span style='font-family:"Calibri",sans-serif;'>&nbsp;</span> <strong>/BULAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong> <span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                Rp &nbsp;{{ number_format($candidates->take_home_bulan, thousands_separator: '.') }}
            </span> (bersih/kotor)*</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.65pt; margin-left:10.1pt;text-indent:0in;">&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style='font-family: "Calibri",sans-serif;'>&nbsp;</span> Pendapatan per tahun &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                Rp &nbsp;{{ number_format($candidates->take_home_tahun, thousands_separator: '.') }}
            </span> (bersih/kotor)*</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.25pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style='font-family: "Calibri",sans-serif;'>&nbsp;</span> (Termasuk THR) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ({{$candidates->bulan_gaji}} bulan gaji)</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.25pt; margin-left:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
        <p class="MsoNormal" align="center" style="margin-top:0in;margin-right:0in; margin-bottom:0in;margin-left:9.4pt;text-align:center;text-indent:0in;"><strong>PENDAPATAN YANG DIHARAPKAN</strong></p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:3.65pt; margin-left:10.1pt;text-indent:0in;">&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style='font-family: "Calibri",sans-serif;'>&nbsp;</span> Take Home Pay /Bulan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                Rp &nbsp;{{ number_format($candidates->harap_take_home_bulan, thousands_separator: '.') }}
            </span> (bersih/kotor)*</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.75pt; margin-left:10.1pt;text-indent:0in;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style='font-family: "Calibri",sans-serif;'>&nbsp;</span> Pendapatan per tahun &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="display: inline-block; width: 180px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                Rp &nbsp;{{ number_format($candidates->harap_take_home_tahun, thousands_separator: '.') }}
            </span> (bersih/kotor)*</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:1.5pt; margin-left:10.1pt;text-indent:0in;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-left:-.25pt;">Serpong,........&nbsp; ..................................&nbsp; 20........</p>
        <p class="MsoNormal" style="margin-left:-.25pt;">Hormat saya,</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.25pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.15pt; margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-left:0in;text-indent:0in;">&nbsp;</p>
        <p class="MsoNormal" style="margin-top:0in;margin-right:0in;margin-bottom:5.45pt; margin-left:0in;text-indent:0in;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</p>
        <p class="MsoNormal" style="margin-left:-.25pt;">(........................................................)</p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style='font-family: "Century Gothic",sans-serif;'>&nbsp;</span></p>
    </div>
</body>

</html>
