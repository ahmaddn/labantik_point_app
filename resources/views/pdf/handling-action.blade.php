<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Tindakan Pelanggaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0;
            font-size: 10pt;
        }

        .content {
            margin-top: 30px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .violation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .violation-table th,
        .violation-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .violation-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .action-box {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .action-box h3 {
            margin: 0 0 10px 0;
            color: #856404;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature p {
            margin: 5px 0;
        }

        .signature .sign-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 200px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Surat Tindakan Pelanggaran Siswa</h1>
        <p>SMA/SMK NAMA SEKOLAH ANDA</p>
        <p>Alamat Sekolah | Telp: (0xx) xxx-xxxx</p>
    </div>

    <div class="content">
        <p style="text-align: right;">{{ $date }}</p>

        <h3>SURAT PEMBERITAHUAN TINDAKAN</h3>

        <table class="info-table">
            <tr>
                <td>Nama Siswa</td>
                <td>: {{ $student->full_name }}</td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>: {{ $student->student_number }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: {{ $class->academic_level }} {{ $class->name }}</td>
            </tr>
            <tr>
                <td>Total Poin Pelanggaran</td>
                <td>: <strong>{{ $total_points }} Poin</strong></td>
            </tr>
        </table>

        <p>Dengan ini kami beritahukan bahwa siswa yang bersangkutan telah melakukan pelanggaran dengan rincian sebagai
            berikut:</p>

        <table class="violation-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Tanggal</th>
                    <th>Pelanggaran</th>
                    <th>Kategori</th>
                    <th style="width: 60px;">Poin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($violations as $index => $recap)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($recap->created_at)->format('d/m/Y') }}</td>
                        <td>{{ $recap->violation->name }}</td>
                        <td>{{ $recap->violation->category->name ?? '-' }}</td>
                        <td style="text-align: center;">{{ $recap->violation->point ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Total Poin:</td>
                    <td style="text-align: center; font-weight: bold;">{{ $total_points }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="action-box">
            <h3>TINDAKAN YANG DIBERIKAN</h3>
            <table class="info-table">
                <tr>
                    <td>Jenis Tindakan</td>
                    <td>: <strong>{{ $handling->handling_action }}</strong></td>
                </tr>
                <tr>
                    <td>Batas Poin</td>
                    <td>: {{ $handling->handling_point }} Poin</td>
                </tr>
                @if ($description)
                    <tr>
                        <td>Keterangan</td>
                        <td>: {{ $description }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <p>Demikian surat ini dibuat untuk dapat ditindaklanjuti sebagaimana mestinya.</p>

        <div class="signature">
            <p>Hormat kami,</p>
            <p><strong>Kepala Sekolah</strong></p>
            <div class="sign-line"></div>
            <p>(...........................)</p>
        </div>
    </div>
</body>

</html>
