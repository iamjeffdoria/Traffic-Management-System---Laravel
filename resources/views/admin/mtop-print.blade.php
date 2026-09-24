<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MTOP - {{ $mtop->case_no }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 15px;
            line-height: 1.45;
            margin: 0;
        }
        .page {
            border: 1px solid #999;
            padding: 0.45in 0.6in;
            box-sizing: border-box;
            min-height: 100vh;
        }
        .header { position: relative; text-align: center; margin-bottom: 8px; padding-bottom: 6px; }
        .header img { position: absolute; left: 20px; top: 4px; width: 80px; height: 80px; }
        .header .title-block { display: inline-block; }
        .header h3, .header h4 { margin: 0; font-weight: bold; }
        .header h3 { font-size: 14px; }
        .header h4 { font-size: 15px; margin-top: 8px; }
        .number-line { text-align: center; margin: 10px 0; }
        .permit-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 18px 0 2px;
        }
        .permit-subtitle { text-align: center; font-size: 13px; margin-bottom: 20px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 6px; }
        .row .label { font-weight: normal; }
        .row .value { font-weight: bold; }
        hr { border: none; border-top: 1px solid #000; margin: 8px 0; }
        .vehicle-table { width: 100%; border-collapse: separate; border-spacing: 12px 0; margin: 12px -12px; }
        .vehicle-table td { text-align: center; padding-bottom: 2px; }
        .vehicle-table .value-row td { border-bottom: 1px solid #000; padding-bottom: 4px; font-weight: bold; }
        .vehicle-table .label-row td { font-size: 12px; padding-top: 4px; }
        ol { padding-left: 20px; margin: 8px 0; }
        ol li { margin-bottom: 4px; }
        .so-ordered { margin-top: 14px; font-weight: bold; }
        .signature-row { display: flex; align-items: flex-end; gap: 8px; margin: 0; }
        .signature-spacer { visibility: hidden; }
        .signature-blank { flex: 0 0 220px; border-bottom: 1px solid #000; }
        .signature-date { flex: 0 0 220px; text-align: center; }
        .approval-section { margin-top: 12px; }
        .approval-columns { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-top: 6px; margin-bottom: 8px; }
        .approval-columns .signatures { display: flex; justify-content: flex-start; gap: 40px; flex: 1; }
        .approval-columns .signatures div { text-align: center; }
        .approval-columns .name { font-weight: bold; }
        .qr-code {
            text-align: center;
            flex-shrink: 0;
        }
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
<div class="page">
    <div class="header">
        <img src="{{ asset('images/lgu-logo.png') }}" alt="Seal">
        <h3>Republic of the Philippines</h3>
        <h3>PROVINCE OF LEYTE</h3>
        <h4>MUNICIPALITY OF PALOMPON</h4>
    </div>

    <hr>

    <p class="permit-title">MOTORIZED TRICYCLE OPERATION'S PERMIT (MTOP)</p>
    <p class="permit-subtitle">(Legalization)</p>

    <div class="row">
        <span class="label">Name of Operator: <span class="value">{{ $mtop->tricycle->name ?? '—' }}</span></span>
        <span class="label">Case No: <span class="value">{{ $mtop->case_no }}</span></span>
    </div>
    <div class="row">
        <span class="label">Address: <span class="value">{{ $mtop->tricycle->address ?? '—' }}</span></span>
        <span class="label">No. of Units: <span class="value">{{ $mtop->no_of_units }}</span></span>
    </div>
    <div class="row" style="display:block;">
        <span class="label">Route of Operation: <span class="value">{{ $mtop->route_operation }}</span></span>
        @if ($mtop->tricycle)
            <br><span class="label" style="display: inline-block; margin-top: 6px;">WITH BODY NUMBER {{ $mtop->tricycle->body_number }}</span>
        @endif
    </div>

    @if ($mtop->tricycle)
        <p class="number-line">Number: {{ $mtop->tricycle->body_number }}</p>
    @endif

    <hr>

    <table class="vehicle-table">
        <tr class="value-row">
            <td>{{ $mtop->tricycle->make_kind ?? '—' }}</td>
            <td>{{ $mtop->tricycle->engine_motor_no ?? '—' }}</td>
            <td>{{ $mtop->tricycle->chassis_no ?? '—' }}</td>
            <td>{{ $mtop->tricycle->plate_no ?? '—' }}</td>
        </tr>
        <tr class="label-row">
            <td>MAKE</td>
            <td>MOTOR NO.</td>
            <td>CHASSIS NO.</td>
            <td>PLATE NO.</td>
        </tr>
    </table>

    <p><strong>Subject to the following:</strong></p>
    <ol>
        <li>Applicant/Operator shall comply with the rules/regulations described by the Board; Failure to comply therefore and any of the conditions herein set forth shall be sufficient cause for the suspension or cancellation of the authority herein granted.</li>
        <li>The unit/s shall be registered as for hire with the Land Transportation Office; Agency of Palompon, Leyte within THIRTY (30) days from date hereof.</li>
        <li>This special authority shall be valid for TWO (2) YEARS from date thereof and shall constitute as franchise certificate giving the operator the privilege to operate the unit/s herein prescribed for hire and/or compensation.</li>
        <li>Without previous authority from the Board, Operator shall not increase/decrease, transfer, drop, and/or substitute unit/s; otherwise, the granted authority shall be declared forfeited or cancelled.</li>
        <li>Protect this document; its loss or destruction may affect your legal rights to operate the service. In addition, any alteration or deletion not otherwise authorized invalidates this document.</li>
    </ol>

    <p class="so-ordered">SO ORDERED.</p>

    <div class="signature-row">
        <span>Palompon, Leyte Philippines</span>
        <span class="signature-blank">&nbsp;</span>
    </div>
    <div class="signature-row" style="margin-top: 2px;">
        <span class="signature-spacer">Palompon, Leyte Philippines</span>
        <span class="signature-date">{{ $mtop->date->format('F j, Y') }}</span>
    </div>

    <div class="approval-section">
        <p><strong>Recommending approval:</strong></p>
        <div class="approval-columns">
            <div class="signatures">
                <div>
                    <p class="name" style="margin-bottom: 2px;">{{ $mtop->municipal_treasurer }}</p>
                    <p style="margin-top: 0;">ICO-Municipal Treasurer</p>
                </div>
                <div>
                    <p class="name" style="margin-bottom: 2px;">{{ $mtop->officer_in_charge }}</p>
                    <p style="margin-top: 0;">OFFICER-IN-CHARGE</p>
                </div>
            </div>

            @php
                $qrToken = \App\Services\QrToken::encode('mtop', $mtop->id);
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=8&ecc=M&data=' . urlencode($qrToken);
            @endphp

            <div class="qr-code">
                <img src="{{ $qrUrl }}" alt="Verification QR Code">
                <p class="qr-label">SCAN TO VERIFY</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>