<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 8mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; }

        table {
            width: 194mm;
            border-collapse: separate;
            border-spacing: 1mm;
            table-layout: fixed;
        }
        td {
            width: 38mm;
            height: 18mm;
            text-align: center;
            vertical-align: top;
            padding: 2.7mm 1mm 0 1mm;
            font-weight: bold;
            color: #000;
            border: 0.5px dashed #999;
            overflow: hidden;
        }
        .label-barcode img {
            width: 24mm;
            height: 7mm;
            display: block;
            margin-bottom: 0.5mm;
        }
        .label-id {
            font-size: 5pt;
            line-height: 1;
            margin-bottom: 0.3mm;
        }
        .label-nama {
            font-size: 5.5pt;
            word-break: break-word;
            line-height: 1.2;
            margin-bottom: 0.3mm;
        }
        .label-harga-text {
            font-size: 5pt;
            line-height: 1;
        }
        .label-harga {
            font-size: 7.5pt;
            line-height: 1;
        }
    </style>
</head>
<body>
    @php
        $cols          = 5;
        $rows          = 8;
        $startPosition = (($startY - 1) * $cols) + ($startX - 1);
        $barangIndex   = 0;
    @endphp

    <table>
        @for($row = 0; $row < $rows; $row++)
            <tr>
                @for($col = 0; $col < $cols; $col++)
                    @php $currentPosition = ($row * $cols) + $col; @endphp

                    @if($currentPosition < $startPosition)
                        <td></td>

                    @elseif($barangIndex < count($barang))
                        <td>
                            <div class="label-barcode">
                                <img src="data:image/png;base64,{{ $barcodes[$barang[$barangIndex]->id_barang] }}"
                                alt="barcode">
                            </div>
                            <div class="label-id">{{ $barang[$barangIndex]->id_barang }}</div>
                            <div class="label-nama">{{ \Illuminate\Support\Str::limit($barang[$barangIndex]->nama, 25) }}</div>
                            <div class="label-harga-text">Harga</div>
                            <div class="label-harga">Rp {{ number_format($barang[$barangIndex]->harga, 0, ',', '.') }}</div>
                            @php $barangIndex++; @endphp
                        </td>

                    @else
                        <td></td>
                    @endif

                @endfor
            </tr>
        @endfor
    </table>
</body>
</html>