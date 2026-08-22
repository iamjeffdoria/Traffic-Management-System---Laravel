<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mayor's Permit - {{ $permit->control_no }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            font-size: 16px;
            line-height: 1.4;
            margin: 0;
        }
        .page {
            border: 1px solid #999;
            border-bottom: none;
            padding: 0.4in 0.6in;
            box-sizing: border-box;
        }
        .header { display: flex; align-items: center; justify-content: space-between; }
        .header .logo-slot { width: 140px; display: flex; padding-left: 20px; }
        .header .logo-slot img { width: 95px; height: 95px; }
        .header .title-block { text-align: center; flex: 1; }
        .header .title-block p { margin: 0; font-weight: bold; font-size: 17px; }
        .header .title-block .sub { font-weight: normal; font-size: 15px; margin-top: 3px; }
        .control-box { text-align: center; width: 140px; align-self: center; }
        .control-box .cn-label { font-size: 11px; letter-spacing: 1.5px; color: #555; }
        .control-box .cn-value {
            border: 1px solid #000;
            font-weight: bold;
            font-size: 22px;
            padding: 6px 10px;
            margin-top: 3px;
        }
        .control-box .cn-series { font-size: 11px; letter-spacing: 1px; color: #555; margin-top: 4px; }
        .office-title { text-align: center; font-weight: bold; font-size: 16px; margin-top: 12px; }
        .permit-title { text-align: center; font-weight: bold; font-size: 30px; margin-top: 4px; }
        .permit-purpose { text-align: center; font-size: 14px; margin-top: 14px; }
        .permit-purpose .ordinance { font-style: italic; font-size: 12px; margin-top: 3px; }
        .granted { text-align: center; font-weight: bold; font-size: 20px; margin-top: 16px; }
        .granted-to { text-align: center; font-size: 14px; margin-top: 4px; }
        .fill-line {
            display: block;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            border-bottom: 1px solid #000;
            width: 360px;
            margin: 14px auto 0;
            padding-bottom: 4px;
        }
        .fill-caption { text-align: center; font-style: italic; font-size: 12px; margin-top: 2px; margin-bottom: 12px; }
        .body-text { margin-top: 16px; text-align: center; }
        .body-text .line { margin-bottom: 8px; font-size: 14px; }
        .underline-inline {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding: 0 6px 2px;
            font-weight: bold;
            text-align: center;
        }
        .quarters { text-align: center; margin: 14px 0 10px; font-size: 14px; }
        .quarters .q-item { display: inline-flex; align-items: center; gap: 6px; margin: 0 12px; font-style: italic; }
        .quarters .q-item sup { vertical-align: super; font-size: 9px; line-height: 1; }
        .quarters .box { display: flex; align-items: center; justify-content: center; width: 15px; height: 15px; border: 1px solid #000; font-weight: bold; font-size: 11px; flex-shrink: 0; }
        .quarters .note { display: block; font-size: 11px; font-style: italic; margin-top: 6px; }
        .issued-line { text-align: center; margin-top: 14px; font-size: 14px; }
        .signature-block { margin-top: 24px; text-align: right; }
        .signature-block .sig-inner { display: inline-block; width: 260px; text-align: center; }
        .signature-block .name { font-weight: bold; display: block; border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 3px; font-size: 15px; }
        .signature-block .title { display: block; font-size: 12px; }
        .footer-fields { margin-top: 18px; font-size: 14px; }
        .footer-fields .line { margin-bottom: 6px; }
        .footer-fields .label { font-weight: bold; display: inline-block; width: 120px; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="logo-slot"><img src="{{ asset('images/lgu-logo.png') }}" alt="Seal"></div>
        <div class="title-block">
            <p>Republic of the Philippines</p>
            <p class="sub">Province of Leyte</p>
            <p class="sub">Municipality of Palompon</p>
        </div>
        <div class="control-box">
            <p class="cn-label">CONTROL NO.</p>
            <p class="cn-value">{{ $permit->control_no }}</p>
            <p class="cn-series">SERIES OF {{ $permit->issue_date->format('Y') }}</p>
        </div>
    </div>

    <p class="office-title">OFFICE OF THE MUNICIPAL MAYOR</p>
    <p class="permit-title">MAYOR'S PERMIT</p>

    <div class="permit-purpose">
        To Operate, Drive Potpot / Pedicab
        <div class="ordinance">(Pursuant to the Provision of Revised Municipal Tax Ordinance)</div>
    </div>

    <p class="granted">PERMIT IS HEREBY GRANTED</p>
    <p class="granted-to">to</p>

    <span class="fill-line">{{ $permit->name }}</span>
    <p class="fill-caption">Name of Operator</p>

    <span class="fill-line">{{ $permit->address }}</span>
    <p class="fill-caption">Home Address</p>

    <div class="body-text">
        <div class="line">
            to engage in
            <span class="underline-inline" style="min-width:220px;">{{ $permit->motorized_operation }}</span>
            with
        </div>
        <div class="line">
            business name (if any)
            <span class="underline-inline" style="min-width:200px;">{{ $permit->business_name ?: 'none' }}</span>
            and with
        </div>
        <div class="line">
            business address at
            <span class="underline-inline" style="min-width:260px;">{{ $permit->address }}</span>
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

    <div class="footer-fields">
        <p class="line"><span class="label">Amount Paid:</span> ₱{{ number_format($permit->amount_paid, 2) }}</p>
        <p class="line"><span class="label">O.R No:</span> {{ $permit->or_no }}</p>
        <p class="line"><span class="label">Issued On:</span> {{ $permit->issue_date->format('F j, Y') }}</p>
        <p class="line"><span class="label">Issued At:</span> {{ $permit->issued_at }}</p>
    </div>
</div>
</body>
</html>