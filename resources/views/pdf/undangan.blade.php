<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan</title>
    <style>
        @page {
            margin: 2cm 2.5cm;
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .header-kementerian {
            font-size: 11pt;
            font-weight: bold;
        }
        .header-univ {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-fakultas {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a5276;
        }
        .header-address {
            font-size: 10pt;
            margin-top: 5px;
        }
        .surat-info {
            margin-bottom: 20px;
        }
        .surat-info table {
            width: 100%;
        }
        .surat-info td {
            vertical-align: top;
            padding: 2px 0;
        }
        .surat-info .label {
            width: 80px;
        }
        .kepada {
            margin-bottom: 20px;
        }
        .isi-surat {
            text-align: justify;
            margin-bottom: 20px;
        }
        .detail-acara {
            margin: 20px 0;
            margin-left: 30px;
        }
        .detail-acara table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .detail-acara .label {
            width: 80px;
        }
        .penutup {
            text-align: justify;
            margin-bottom: 40px;
        }
        .ttd {
            float: right;
            text-align: center;
            width: 250px;
        }
        .ttd-line {
            margin-top: 80px;
            border-bottom: 1px solid #333;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }
        .ttd-name {
            font-weight: bold;
            margin-top: 5px;
        }
        .ttd-nip {
            font-size: 10pt;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <!-- HEADER SURAT -->
    <div class="header">
        <div class="header-kementerian">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI</div>
        <div class="header-univ">UNIVERSITAS CONTOH</div>
        <div class="header-fakultas">FAKULTAS ILMU KOMPUTER</div>
        <div class="header-address">
            Jl. Contoh No. 123, Kota Contoh 12345<br>
            Telp: (021) 123-4567 | Email: fik@universitascontoh.ac.id
        </div>
    </div>

    <!-- INFO SURAT - EDIT DI SINI -->
    <div class="surat-info">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td>: UN/FIK/2026/001</td>
            </tr>
            <tr>
                <td class="label">Perihal</td>
                <td>: <strong>Undangan Rapat Koordinasi</strong></td>
            </tr>
        </table>
    </div>

    <!-- KEPADA - EDIT DI SINI -->
    <div class="kepada">
        <p>Kepada Yth.<br>
        <strong>Bapak/Ibu Dosen Fakultas Ilmu Komputer</strong><br>
        di Tempat</p>
    </div>

    <!-- ISI SURAT - EDIT DI SINI -->
    <div class="isi-surat">
        <p>Dengan hormat, bersama ini kami mengundang Bapak/Ibu untuk menghadiri acara yang akan dilaksanakan pada:</p>
    </div>

    <!-- DETAIL ACARA - EDIT DI SINI -->
    <div class="detail-acara">
        <table>
            <tr>
                <td class="label">Hari/Tanggal</td>
                <td>: Rabu, 19 February 2026</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td>: 09:00 WIB - Selesai</td>
            </tr>
            <tr>
                <td class="label">Tempat</td>
                <td>: Ruang Rapat Lt. 3 Gedung FIK</td>
            </tr>
        </table>
    </div>

    <!-- PENUTUP -->
    <div class="penutup">
        <p>Demikian undangan ini kami sampaikan. Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.</p>
    </div>

    <!-- TANDA TANGAN - EDIT DI SINI -->
    <div class="clearfix">
        <div class="ttd">
            <p>Jakarta, 19 February 2026</p>
            <p>Dekan,</p>
            <div class="ttd-line"></div>
            <p class="ttd-name">Dr. John Doe, M.Kom</p>
            <p class="ttd-nip">NIP. 198501012010011001</p>
        </div>
    </div>
</body>
</html>