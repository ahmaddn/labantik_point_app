<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Perjanjian</title>
    <style>
        .print-btn-container {
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }
        .print-btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        .print-btn:hover {
            background-color: #2563eb;
        }
        @media print {
            .print-btn-container {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px auto;
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">Cetak Surat</button>
    </div>
<table cellspacing="0" cellpadding="0" style="width: 559.95pt; border-collapse: collapse">
    <tbody>
        <tr style="height: 1pt">
            <td style="width: 72pt; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top;">
                <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 8pt;">
                    <img src="{{ asset('assets/images/pemprov.png') }}" width="96" height="114"
                        alt="Logo Pemprov Jabar.png" />
                </p>
            </td>
            <td style="width: 466.35pt; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top;">
                <p
                    style="margin-top: 0pt; margin-left: 7.1pt; margin-bottom: 0pt; text-align: center; font-size: 14pt;">
                    <strong><span style="font-family: Arial">PEMERINTAH DAERAH PROVINSI JAWA BARAT</span></strong>
                </p>
                <p
                    style="margin-top: 0pt; margin-left: 7.1pt; margin-bottom: 0pt; text-align: center; font-size: 18pt;">
                    <strong><span style="font-family: Arial">CABANG DINAS PENDIDIKAN WILAYAH IX</span></strong>
                </p>
                <p
                    style="margin-top: 0pt; margin-left: 7.1pt; margin-bottom: 0pt; text-align: center; font-size: 14pt;">
                    <strong><span style="font-family: Arial">SEKOLAH MENENGAH KEJURUAN NEGERI 1 TALAGA</span></strong>
                </p>
                <p style="margin-top: 0pt; margin-left: 7.1pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                    <span style="font-family: Tahoma">Bidang Keahlian: Teknologi dan Rekayasa, Teknologi Informasi komunikasi, Bisnis dan Manajemen</span>
                </p>
                <p style="margin-top: 0pt; margin-left: 7.1pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                    <span style="font-family: Tahoma">Kampus 1: Jalan Sekolah Nomor 20 Desa Talagakulon Kecamatan Talaga Kabupaten Majalengka</span>
                </p>
                <p style="margin-top: 0pt; margin-left: 7.1pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                    <span style="font-family: Tahoma">Kampus 2: Jalan Talaga-Bantarujeg Desa Mekarrahaja Kecamatan Talaga Kabupaten Majalengka</span>
                </p>
                <p style="margin-top: 0pt; margin-left: 7.1pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                    <span style="font-family: Tahoma">Telpon <span style="font-family: Wingdings"></span> (0233) 319238 FAX <span style="font-family: Wingdings"></span> (0233) 319238 POS <span style="font-family: Wingdings"></span> 45463 NPSN: 20213872</span>
                </p>
                <p
                    style="margin-top: 0pt; margin-left: 7.1pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                    <span style="font-family: Tahoma">Website <span style="font-family: Wingdings"></span> </span><a href="http://www.smkn1talaga.sch.id"
                        style="text-decoration: none"><u><span
                                style="font-family: Tahoma; color: #0000ff;">www.smkn1talaga.sch.id</span></u></a><span
                        style="font-family: Tahoma"> – Email <span style="font-family: Wingdings"></span> </span><a href="mailto:admin@smkn1talaga.sch.id"
                        style="text-decoration: none"><u><span
                                style="font-family: Tahoma; color: #0000ff;">admin@smkn1talaga.sch.id</span></u></a>
                </p>
            </td>
        </tr>
    </tbody>
</table>
<div style="border-top: 3px solid #000; border-bottom: 1px solid #000; height: 2px; margin-top: 5px; margin-bottom: 15px; width: 100%;"></div>
<p style="margin-top: 0pt; margin-bottom: 0pt">&nbsp;</p>
<p
    style="
        margin-top: 0pt;
        margin-bottom: 0pt;
        text-align: center;
        font-size: 14pt;
    ">
    <strong><u><span style="font-family: Arial">SURAT PERJANJIAN</span></u></strong>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <span style="font-family: Arial">Nomor :</span> <span style="font-family: Arial">{{ $no_surat }}</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: 150%">
    <span style="font-family: Arial">Yang bertanda tangan dibawah</span><span
        style="font-family: Arial">&nbsp;&nbsp;</span><span style="font-family: Arial">ini :</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: 150%">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p
    style="
        margin-top: 0pt;
        margin-bottom: 0pt;
        text-indent: 36pt;
        line-height: 150%;
    ">
    <span style="font-family: Arial">Nama</span><span
        style="
            width: 3.99pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="
            width: 36pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="
            width: 36pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="font-family: Arial">:</span><span style="font-family: Arial">&nbsp;&nbsp;</span><span
        style="font-family: Arial">___________________________________________________</span>
</p>
<p
    style="
        margin-top: 0pt;
        margin-bottom: 0pt;
        text-indent: 36pt;
        line-height: 150%;
    ">
    <span style="font-family: Arial">Kelas</span><span
        style="
            width: 5.98pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="
            width: 36pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="
            width: 36pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="font-family: Arial">:</span><span style="font-family: Arial">&nbsp;&nbsp;</span><span
        style="font-family: Arial">___________________________________________________</span>
</p>
<p
    style="
        margin-top: 0pt;
        margin-bottom: 0pt;
        text-indent: 36pt;
        line-height: 150%;
    ">
    <span style="font-family: Arial">NIS</span><span
        style="
            width: 16pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="
            width: 36pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="
            width: 36pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="font-family: Arial">:</span><span style="font-family: Arial">&nbsp;&nbsp;</span><span
        style="font-family: Arial">___________________________________________________</span>
</p>
<p
    style="
        margin-top: 0pt;
        margin-bottom: 0pt;
        text-indent: 36pt;
        line-height: 150%;
    ">
    <span style="font-family: Arial">NISN</span><span
        style="
            width: 7.33pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="
            width: 36pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="
            width: 36pt;
            text-indent: 0pt;
            font-family: Arial;
            display: inline-block;
        ">&nbsp;</span><span
        style="font-family: Arial">:</span><span style="font-family: Arial">&nbsp;&nbsp;</span><span
        style="font-family: Arial">___________________________________________________</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; line-height: 150%">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p
    style="
        margin-top: 0pt;
        margin-bottom: 0pt;
        text-align: justify;
        line-height: 150%;
    ">
    <span style="font-family: Arial">Berjanji untuk tidak melakukan perbuatan/pelanggaran terhadap peraturan
        sekolah:&nbsp;</span>
</p>
<ol type="1" style="margin: 0pt; padding-left: 0pt">
    <li
        style="
            margin-left: 50.46pt;
            margin-bottom: 10pt;
            line-height: 150%;
            padding-left: 6.24pt;
            font-family: Arial;
        ">
        __________________________________________________________________
    </li>
    <li
        style="
            margin-left: 50.46pt;
            margin-bottom: 10pt;
            line-height: 150%;
            padding-left: 6.24pt;
            font-family: Arial;
        ">
        __________________________________________________________________
    </li>
    <li
        style="
            margin-left: 50.46pt;
            margin-bottom: 10pt;
            line-height: 150%;
            padding-left: 6.24pt;
            font-family: Arial;
        ">
        __________________________________________________________________
    </li>
</ol>
<p
    style="
        margin-top: 0pt;
        margin-bottom: 0pt;
        text-align: justify;
        line-height: 150%;
    ">
    <span style="font-family: Arial">Apabila saya hari ini tanggal _______________________ sampai dengan
        seterusnya saya terbukti</span><span style="font-family: Arial">&nbsp;&nbsp;</span><span
        style="font-family: Arial">mengulangi perbuatan tersebut saya bersedia untuk dikeluarkan dari
        sekolah ini.</span>
</p>
<p
    style="
        margin-top: 0pt;
        margin-bottom: 0pt;
        text-align: justify;
        line-height: 150%;
    ">
    <span style="font-family: Arial">Demikian Surat Perjanjian ini saya buat dalam keadaan sehat</span><span
        style="font-family: Arial">&nbsp;&nbsp;</span><span style="font-family: Arial">jasmani dan tanpa paksaan dari
        pihak manapun.</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<table cellspacing="0" cellpadding="0" style="width: 506.9pt; border-collapse: collapse">
    <tbody>
        <tr>
            <td
                style="
                    width: 278.1pt;
                    padding-right: 5.4pt;
                    padding-left: 5.4pt;
                    vertical-align: top;
                ">
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">Orang Tua/Wali/</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">Kuasa Orang Tua</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <strong><span style="font-family: Arial">_______________________</span></strong>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">Wali Kelas</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <strong><span style="font-family: Arial">_______________________</span></strong>
                </p>
            </td>
            <td
                style="
                    width: 207.2pt;
                    padding-right: 5.4pt;
                    padding-left: 5.4pt;
                    vertical-align: top;
                ">
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">Talaga, __________________</span><span
                        style="font-family: Arial">&nbsp;&nbsp;</span><span style="font-family: Arial">20 ___</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">Yang Membuat Pernyataan,</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 8pt">
                    <span style="font-family: Arial; color: #808080">Materai 6000</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <strong><span style="font-family: Arial">&nbsp;</span></strong>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <strong><span style="font-family: Arial">_______________________</span></strong>
                </p>
                <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt">
                    <span style="font-family: Arial">&nbsp;</span>
                </p>
            </td>
        </tr>
    </tbody>
</table>
<p style="margin-top: 0pt; margin-bottom: 0pt">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <span style="font-family: Arial">Mengetahui,</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <span style="font-family: Arial">Kepala SMKN 1 Talaga</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <span style="font-family: Arial">&nbsp;</span>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <strong><u><span style="font-family: Arial">&nbsp;</span></u></strong><strong><u><span
                style="font-family: Arial">{{ $kepalaSekolah->employee->full_name ?? ($kepalaSekolah->name ?? '') }}&nbsp;</span></u></strong>
</p>
<p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center">
    <span style="font-family: Arial">NIP.
        {{ optional($kepalaSekolah)->employee?->nip ?? (optional($kepalaSekolah)->employee?->nuptk ?? '') }}
</p>
</body>
</html>
