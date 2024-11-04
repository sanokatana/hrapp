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
            font-size: 11.0pt;
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
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><img width="648" height="276" src="data:image/png;base64,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" alt="image" style="float: left; "><span style="font-size:10.0pt;line-height:107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><span style="font-size: 10.0pt;line-height:107%;">&nbsp;</span></p>
        <p class="MsoNormal" style="margin-top:0in; margin-bottom:0in; margin-left:-.25pt; display: flex; align-items: center;">
            Untuk Jabatan apa Saudara melamar :&nbsp;
            <span style="display: inline-block; width: 400px; border-bottom: 1px dotted; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                {{$candidates->job_opening_name}}
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
                            @elseif($candidates->jenis_kelamin === 'Tidak Menikah')
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
                        <p class="MsoNormal" style="margin:0in;text-align:justify;text-justify:inter-ideograph; text-indent:0in;"><span class="dotted-line">: {{DateHelper::formatIndonesiaDate($candidates->tgl_menikah)}}</span></p>
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
        <p class="MsoNormal" style="margin:0in;text-indent:0in;"><strong>&nbsp;</strong></p>
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
            echo '<p style="color: red;">Image not found.</p>';
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
        <h1 style="text-align: justify; margin-left:10pt;">KETERANGAN PENGHASILAN</h1>
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
