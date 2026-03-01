<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0.2cm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0.15cm;
            table-layout: fixed;
        }
        td {
            width: 3.3cm;
            height: 2.6cm;
            border: 0.5px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 0.15cm;
            box-sizing: border-box;
        }
        .label-id {
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 2px;
        }
        .label-nama {
            font-size: 7px;
            margin-bottom: 2px;
            word-break: break-word;
            line-height: 1.2;
        }
        .label-harga-text {
            font-size: 6px;
            margin-bottom: 1px;
        }
        .label-harga {
            font-weight: bold;
            font-size: 11px;
            color: #000;
        }
        .empty-cell {
            border: 0.5px dashed #9a9898;
            background: transparent;
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

    <table cellspacing="0" cellpadding="0">
        @for($row = 0; $row < $rows; $row++)
            <tr>
                @for($col = 0; $col < $cols; $col++)
                    @php $currentPosition = ($row * $cols) + $col; @endphp

                    @if($currentPosition < $startPosition)
                        {{-- Cell kosong sebelum posisi mulai --}}
                        <td class="empty-cell"></td>

                    @elseif($barangIndex < count($barang))
                        {{-- Cell berisi data barang --}}
                        <td>
                            <div class="label-id">{{ $barang[$barangIndex]->id_barang }}</div>
                            <div class="label-nama">{{ \Illuminate\Support\Str::limit($barang[$barangIndex]->nama, 25) }}</div>
                            <div class="label-harga-text">Harga</div>
                            <div class="label-harga">Rp {{ number_format($barang[$barangIndex]->harga, 0, ',', '.') }}</div>
                            @php $barangIndex++; @endphp
                        </td>

                    @else
                        {{-- Cell kosong setelah data habis --}}
                        <td class="empty-cell"></td>
                    @endif

                @endfor
            </tr>
        @endfor
    </table>
</body>
</html>