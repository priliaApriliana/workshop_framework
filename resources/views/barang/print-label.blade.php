```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga</title>
    <style>
        @page {
        size: A4 portrait;
        margin: 5mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; }
        table {           
            width: 200mm;
            border-collapse: collapse;
            table-layout: fixed;
        }
        td {
            width: 38mm;
            height: 18.5mm;
            text-align: center;
            vertical-align: middle;
            /* top right bottom left */
            padding: 2.7mm 1mm 0 11mm;
            font-weight: bold;
            color: #000;
        }
        .label-id {
            font-size: 6pt;
            margin-bottom: 1mm;
        }
        .label-nama {
            font-size: 6pt;
            margin-bottom: 1mm;
            word-break: break-word;
            line-height: 1.2;
        }
        .label-harga-text {
            font-size: 5.5pt;
            margin-bottom: 0.5mm;
        }
        .label-harga {
            font-size: 8pt;
        }
        .label-barcode svg {
            width: 30mm;
            height: 8mm;
            margin-bottom: 0.5mm;
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
                            <div class="label-barcode">{!! $barcodes[$barang[$barangIndex]->id_barang] !!}</div>
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