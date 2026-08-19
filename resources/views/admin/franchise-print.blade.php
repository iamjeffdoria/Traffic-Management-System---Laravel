<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Franchise - {{ $franchise->authorized_no }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 18px;
            line-height: 1.5;
            margin: 0;
        }
        .page {
            border: 1px solid #999;
            padding: 0.6in;
            box-sizing: border-box;
            min-height: 100vh;
        }
        .header { position: relative; text-align: center; margin-bottom: 20px; padding-bottom: 10px; }
        .header img { position: absolute; left: 20px; top: 4px; width: 80px; height: 80px; }
        .header p { margin: 0; font-weight: bold; }
        .header .republic { font-size: 18px; }
        .header .province,
        .header .municipality { font-size: 16px; font-weight: normal; margin-top: 4px; }
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 0.5px;
            margin: 30px 0 40px;
        }
        .field-row {
            display: flex;
            margin-bottom: 22px;
        }
        .field-row .field { flex: 1; display: flex; }
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
<div class="page">
    <div class="header">
        <img src="{{ asset('images/lgu-logo.png') }}" alt="Seal">
        <p class="republic">Republic of the Philippines</p>
        <p class="province">Province of Leyte</p>
        <p class="municipality">Municipality of Palompon</p>
    </div>

    <p class="doc-title">FRANCHISE CONFIRMATION/ VERIFICATION</p>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Name:</span>
            <span class="field-value">{{ $franchise->name }}</span>
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
            <span class="field-value">{{ $franchise->plate_no }}</span>
        </div>
        <div class="field">
            <span class="field-label">Valid Until:</span>
            <span class="field-value">{{ $franchise->valid_until->format('F j, Y') }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Motor No:</span>
            <span class="field-value">{{ $franchise->motor_no }}</span>
        </div>
        <div class="field">
            <span class="field-label">Authorized No:</span>
            <span class="field-value">{{ $franchise->authorized_no }}</span>
        </div>
    </div>

    <div class="field-row">
        <div class="field">
            <span class="field-label">Chassis No:</span>
            <span class="field-value">{{ $franchise->chassis_no }}</span>
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