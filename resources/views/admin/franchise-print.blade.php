<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Franchise - {{ $franchise->authorized_no }}</title>
    <link rel="stylesheet" href="{{ asset('css/print-letterhead.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=block" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 18px;
            line-height: 1.5;
            margin: 0;
        }
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 0.5px;
            margin: 30px 0 40px;
        }
        .field-row {
            display: flex;
            gap: 40px;
            margin-bottom: 22px;
        }
        .field-row .field { flex: 1; display: flex; min-width: 0; }
        .field-label { font-weight: bold; width: 190px; flex-shrink: 0; }
        .field-value { flex: 1; }
        .signature-block { margin-top: 60px; display: flex; justify-content: flex-end; }
        .signature-block .sig-inner { width: 240px; text-align: center; }
        .signature-block .name {
            font-weight: bold;
            display: block;
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
            margin-bottom: 4px;
        }
        .signature-block .title {
            display: block;
        }
    </style>
</head>
<body>
<div class="letterhead-page">
    <img class="letterhead-bg" src="{{ asset('images/letterhead.png') }}" alt="">
    <p class="doc-title">FRANCHISE CONFIRMATION/ VERIFICATION</p>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Name:</span>
            <span class="field-value">{{ $franchise->tricycle->name ?? '—' }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Denomination:</span>
            <span class="field-value">{{ $franchise->denomination ?? '—' }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Plate No:</span>
            <span class="field-value">{{ $franchise->tricycle->plate_no ?? '—' }}</span>
        </div>
        <div class="field">
            <span class="field-label">Valid Until:</span>
            <span class="field-value">{{ $franchise->valid_until->format('F j, Y') }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Motor No:</span>
            <span class="field-value">{{ $franchise->tricycle->engine_motor_no ?? '—' }}</span>
        </div>
        <div class="field">
            <span class="field-label">Authorized No:</span>
            <span class="field-value">{{ $franchise->authorized_no }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Chassis No:</span>
            <span class="field-value">{{ $franchise->tricycle->chassis_no ?? '—' }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Authorized Route:</span>
            <span class="field-value">{{ $franchise->authorized_route }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Purpose:</span>
            <span class="field-value">{{ $franchise->purpose ?? '—' }}</span>
        </div>
    </div>

    <div class="field-row" style="margin-top: 40px;">
        <div class="field">
            <span class="field-label">Official Receipt No:</span>
            <span class="field-value">{{ $franchise->official_receipt_no }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Date:</span>
            <span class="field-value">{{ $franchise->date->format('F j, Y') }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Amount Paid:</span>
            <span class="field-value">₱{{ number_format($franchise->amount_paid, 2) }}</span>
        </div>
    </div>

    <div class="signature-block">
        <div class="sig-inner">
            <span class="name">{{ strtoupper($franchise->municipal_treasurer) }}</span>
            <span class="title">MUNICIPAL TREASURER</span>
        </div>
    </div>
</div>
</body>
</html>