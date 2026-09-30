<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mayor's Permit - {{ $permit->control_no }}</title>
    <link rel="stylesheet" href="{{ asset('css/print-letterhead.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=block" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
        }
        /* Control No. box sits top-right, on the same line as the titles */
        .permit-head { position: relative; }
        .control-box { position: absolute; top: 0; right: 0; text-align: center; width: 150px; }
        .control-box p { margin: 0; }
        .control-box .cn-label { font-size: 11px; letter-spacing: 1.5px; color: #555; }
        .control-box .cn-value {
            border: 1.5px solid #000;
            font-weight: bold;
            font-size: 24px;
            padding: 6px 10px;
            margin-top: 3px;
        }
        .control-box .cn-series { font-size: 10px; letter-spacing: 1px; color: #555; margin-top: 3px; }
        .office-title { text-align: center; font-weight: bold; font-size: 14px; margin: 0; }
        .permit-title { text-align: center; font-weight: bold; font-size: 26px; margin: 4px 0 0; }
        .permit-purpose { text-align: center; font-size: 12px; margin-top: 14px; }
        .permit-purpose .ordinance { font-style: italic; font-size: 10px; margin-top: 3px; }
        .granted { text-align: center; font-weight: bold; font-size: 17px; margin: 14px 0 0; }
        .granted-to { text-align: center; font-size: 12px; margin: 4px 0 0; }
        .fill-line {
            display: block;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            border-bottom: 1px solid #000;
            width: 360px;
            margin: 12px auto 0;
            padding-bottom: 4px;
        }
        .fill-caption { text-align: center; font-style: italic; font-size: 10px; margin: 2px 0 10px; }
        .body-text { margin-top: 14px; text-align: center; }
        .body-text .line { margin-bottom: 8px; font-size: 12px; }
        .underline-inline {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding: 0 6px 2px;
            font-weight: bold;
            text-align: center;
        }
        .quarters { text-align: center; margin: 12px 0 8px; font-size: 12px; }
        .quarters .q-item { display: inline-flex; align-items: center; gap: 5px; margin: 0 8px; font-style: italic; }
        .quarters .q-item sup { vertical-align: super; font-size: 8px; line-height: 1; }
        .quarters .box { display: flex; align-items: center; justify-content: center; width: 14px; height: 14px; border: 1px solid #000; font-weight: bold; font-size: 10px; flex-shrink: 0; }
        .quarters .note { display: block; font-size: 10px; font-style: italic; margin-top: 6px; }
        .issued-line { text-align: center; margin: 16px 0 0; font-size: 12px; }
        .signature-block { margin-top: 44px; text-align: right; }
        .signature-block .sig-inner { display: inline-block; width: 240px; text-align: center; }
        .signature-block .name { font-weight: bold; display: block; border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 3px; font-size: 13px; }
        .signature-block .title { display: block; font-size: 10px; }
        .footer-row { display: flex; align-items: flex-start; justify-content: space-between; margin-top: 36px; }
        .footer-fields { font-size: 12px; }
        .footer-fields .line { margin: 0 0 5px; }
        .footer-fields .label { font-weight: bold; display: inline-block; width: 105px; }
        .qr-code { flex-shrink: 0; text-align: center; margin-right: -0.25in; }
        .qr-code img {
            width: 130px;
            height: 130px;
            display: block;
        }
        .qr-code .qr-label {
            font-size: 9px;
            letter-spacing: 0.5px;
            color: #555;
            margin-top: 4px;
        }
    </style>
</head>
<body>
<div class="letterhead-page">
    <img class="letterhead-bg" src="{{ asset('images/letterhead.png') }}" alt="">
    <div class="permit-head">
        <div class="control-box">
            <p class="cn-label">CONTROL NO.</p>
            <p class="cn-value">{{ $permit->control_no }}</p>
            <p class="cn-series">SERIES OF {{ $permit->issue_date->format('Y') }}</p>
        </div>
        <p class="office-title">OFFICE OF THE MUNICIPAL MAYOR</p>
        <p class="permit-title">MAYOR'S PERMIT</p>
    </div>

    <div class="permit-purpose">
        To Operate, Drive Public Utility Tricycle
        <div class="ordinance">(Pursuant to the Provision of Revised Municipal Tax Ordinance)</div>
    </div>

    <p class="granted">PERMIT IS HEREBY GRANTED</p>
    <p class="granted-to">to</p>

    <span class="fill-line">{{ $permit->tricycle->name ?? '—' }}</span>
    <p class="fill-caption">Name of Operator</p>

    <span class="fill-line">{{ $permit->tricycle->address ?? '—' }}</span>
    <p class="fill-caption">Home Address</p>

    <div class="body-text">
        <div class="line">
            to engage in
            <span class="underline-inline" style="min-width:220px;">{{ $permit->motorized_operation }}</span>
            with
        </div>
        <div class="line">
            business name (if any)
            <span class="underline-inline" style="min-width:200px;">{{ $permit->business_name ?? 'none' }}</span>
            and with
        </div>
        <div class="line">
            business address at
            <span class="underline-inline" style="min-width:260px;">Palompon, Leyte</span>
        </div>
        <div class="line">
            This permit expires on
            <span class="underline-inline" style="min-width:200px;">{{ $permit->expiry_date->format('F j, Y') }}</span>
            and may be earlier revoked for cause.
        </div>
    </div>

    <div class="quarters">
        Applicable Quarter:
        <div style="margin-top: 8px;">
            <span class="q-item"><span class="box">{{ $permit->quarter === 'First Quarter' ? '✓' : '' }}</span> 1<sup>st</sup> Quarter</span>
            <span class="q-item"><span class="box">{{ $permit->quarter === 'Second Quarter' ? '✓' : '' }}</span> 2<sup>nd</sup> Quarter</span>
            <span class="q-item"><span class="box">{{ $permit->quarter === 'Third Quarter' ? '✓' : '' }}</span> 3<sup>rd</sup> Quarter</span>
            <span class="q-item"><span class="box">{{ $permit->quarter === 'Fourth Quarter' ? '✓' : '' }}</span> 4<sup>th</sup> Quarter</span>
        </div>
        <span class="note">(please mark (✓) the appropriate box)</span>
    </div>

    <p class="issued-line">
        Issued this
        <span class="underline-inline" style="min-width:36px;">{{ $permit->issue_date->format('jS') }}</span>
        day of
        <span class="underline-inline" style="min-width:90px;">{{ $permit->issue_date->format('F') }},</span>
        20<span class="underline-inline" style="min-width:28px;">{{ $permit->issue_date->format('y') }}</span>
        at {{ $permit->issued_at }}, Philippines
    </p>

    <div class="signature-block">
        <span class="sig-inner">
            <span class="name">{{ strtoupper($permit->mayor) }}</span>
            <span class="title">MUNICIPAL MAYOR</span>
        </span>
    </div>

    @php
        $qrToken = \App\Services\QrToken::encode('tricycle_mayors_permit', $permit->id);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=8&ecc=M&data=' . urlencode($qrToken);
    @endphp

    <div class="footer-row">
        <div class="footer-fields">
            <p class="line"><span class="label">Amount Paid:</span> ₱{{ number_format($permit->amount_paid, 2) }}</p>
            <p class="line"><span class="label">O.R No:</span> {{ $permit->or_no }}</p>
            <p class="line"><span class="label">Issued On:</span> {{ $permit->issue_date->format('F j, Y') }}</p>
            <p class="line"><span class="label">Issued At:</span> {{ $permit->issued_at }}</p>
        </div>

        <div class="qr-code">
            <img src="{{ $qrUrl }}" alt="Verification QR Code">
            <p class="qr-label">SCAN TO VERIFY</p>
        </div>
    </div>
</div>
</body>
</html>