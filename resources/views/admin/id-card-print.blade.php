<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Card - {{ $idCard->id_number }}</title>
    <style>
        @page { margin: 0; }
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            margin: 0;
        }
        .card {
            position: relative;
            width: 3.5in;
            height: 5in;
            margin: 0.3in auto;
            background: url('{{ asset('images/idcard.png') }}') no-repeat top left;
            background-size: 100% 100%;
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
        .value.address    { top: 70.7%; left: 14%; width: 78%; }
        .value.dob         { top: 78%;   left: 24%; }
        .value.gender      { top: 78%;   left: 63%; }
        .value.height      { top: 83%;   left: 21%; }
        .value.weight      { top: 83%;   left: 63%; }
        .value.issued      { top: 88%;   left: 24%; }
        .value.or-no       { top: 88%;   left: 60%; }
        .value.expiration  { top: 93%;   left: 24%; }
        .mayor-name {
            position: absolute;
            top: 96.3%;
            left: 0;
            width: 100%;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>
<body>
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
</body>
</html>