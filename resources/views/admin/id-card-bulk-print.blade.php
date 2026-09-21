<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Cards - Bulk Print</title>
    <style>
        @page { size: letter portrait; margin: 0.4in; }
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        html, body {
            height: 100%;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sheet {
            display: grid;
            grid-template-columns: repeat(2, 3.4in);
            grid-template-rows: repeat(2, 4.75in);
            gap: 0.3in;
            justify-content: center;
            align-content: center;
        }
        .card {
            position: relative;
            width: 3.4in;
            height: 4.75in;
            background: url('{{ asset('images/idcard.png') }}') no-repeat top left;
            background-size: 100% 100%;
            page-break-inside: avoid;
        }
        .card.is-empty {
            background: none;
            border: 1px dashed #d4d4d4;
            border-radius: 8px;
        }
        .photo {
            position: absolute;
            top: 21%;
            left: 29%;
            width: 42.5%;
            height: 27%;
            object-fit: cover;
        }
        .id-number {
            position: absolute;
            top: 48.5%;
            left: 43%;
            font-size: 13px;
            font-weight: bold;
            color: #fff;
        }
        .full-name {
            position: absolute;
            top: 55%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
        }
        .value {
            position: absolute;
            font-weight: bold;
            font-size: 13px;
            color: #111;
        }
        .value.address    { top: 61%; left: 16%; width: 78%; }
        .value.dob         { top: 67%;   left: 26%; }
        .value.gender      { top: 67%;   left: 77%; }
        .value.height      { top: 70.5%;   left: 26%; }
        .value.weight      { top: 70.5%;   left: 77%; }
        .value.issued      { top: 75.6%;   left: 26%; }
        .value.or-no       { top: 75.6%;   left: 77%; }
        .value.expiration  { top: 80%;   left: 26%; }
        .mayor-name {
            position: absolute;
            top: 87.2%;
            left: 0;
            width: 100%;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="sheet">
    @foreach ($idCards as $idCard)
        <div class="card">
            @if ($idCard->photo_path)
                <img class="photo" src="{{ asset('storage/' . $idCard->photo_path) }}" alt="{{ $idCard->full_name }}">
            @endif

            <div class="id-number">{{ $idCard->id_number }}</div>
            <div class="full-name">{{ $idCard->full_name }}</div>

            <div class="value address">{{ $idCard->address }}</div>
            <div class="value dob">{{ $idCard->date_of_birth->format('M-d-Y') }}</div>
            <div class="value gender">{{ $idCard->gender }}</div>
            <div class="value height">{{ number_format($idCard->height, 2) }}</div>
            <div class="value weight">{{ number_format($idCard->weight, 2) }}</div>
            <div class="value issued">{{ $idCard->date_issued->format('M-d-Y') }}</div>
            <div class="value or-no">{{ $idCard->or_number }}</div>
            <div class="value expiration">{{ $idCard->expiry_date->format('M-d-Y') }}</div>

            <div class="mayor-name">MARY DOMINIQUE OÑATE</div>
        </div>
    @endforeach

    @for ($i = $idCards->count(); $i < 4; $i++)
        <div class="card is-empty"></div>
    @endfor
</div>
</body>
</html>