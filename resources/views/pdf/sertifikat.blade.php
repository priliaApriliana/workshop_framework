<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', serif;
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #fff;
        }
        
        /* Background Image */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        
        /* Container untuk semua konten dinamis */
        .content-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10;
        }
        
        /* Nama Peserta */
        .recipient-name {
            position: absolute;
            top: 330px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 48px;
            font-family: 'Georgia', serif;
            font-style: italic;
            color: #1a3a5c;
            letter-spacing: 2px;
        }
        
        /* Deskripsi Kegiatan */
        .description {
            position: absolute;
            top: 430px;
            left: 120px;
            right: 120px;
            text-align: center;
            font-size: 16px;
            color: #333;
            line-height: 1.8;
        }
        
        .event-name {
            color: #1a3a5c;
            font-weight: bold;
            font-style: italic;
        }

        /* Certificate Number */
        .cert-number {
            position: absolute;
            bottom: 40px;
            left: 80px;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Background Image -->
    <img src="{{ public_path('assets/images/sertifikat_sobat.png') }}" class="bg-image">
    
    <div class="content-wrapper">
        <!-- Nama Peserta - EDIT DI SINI -->
        <div class="recipient-name">Putri Apriliana</div>
        
        <!-- Deskripsi Kegiatan - EDIT DI SINI -->
        <div class="description">
            "Telah menjadi Peserta dalam kegiatan '<span class="event-name">Workshop Laravel Framework</span>' yang dilaksanakan pada 19 February 2026."
        </div>
        
        <!-- Certificate Number - EDIT DI SINI -->
        <div class="cert-number">No: SERT/2026/0001</div>
    </div>
</body>
</html>