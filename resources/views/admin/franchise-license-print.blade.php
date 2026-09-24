@php
    $tricycle = $franchise->tricycle;
    $issued = $franchise->license_issued_date ?? $franchise->date;
    $issuedAt = $franchise->license_issued_at ?: $license['default_issued_at'];

    $renewalLabel = match ($franchise->status) {
        'New' => 'New Franchise',
        'Renewed' => 'Renewal of Franchise',
        default => null,
    };

    // "PST 001-C" -> "PST"
    $todaCode = ($tricycle && $tricycle->toda && preg_match('/^[A-Za-z]+/', $tricycle->toda, $m)) ? $m[0] : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>License to Operate - {{ $franchise->authorized_no }}</title>
    <style>
        @page { margin: 0; }
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 15px;
            line-height: 1.5;
            margin: 0;
        }
        .page {
            border: 1px solid #999;
            padding: 0.5in 0.85in;
            box-sizing: border-box;
            min-height: 100vh;
            position: relative;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5in;
            height: 5in;
            transform: translate(-50%, -50%);
            object-fit: contain;
            opacity: 0.1;
            z-index: 0;
            pointer-events: none;
        }
        .page > *:not(.watermark) {
            position: relative;
            z-index: 1;
        }
        .header { position: relative; text-align: center; margin-bottom: 32px; }
        .header img { position: absolute; left: 0; top: 2px; width: 76px; height: 76px; }
        .header p { margin: 0; font-weight: bold; }
        .header .republic { font-size: 18px; }
        .header .province,
        .header .municipality { font-size: 16px; margin-top: 3px; }
        .header .divider { margin-top: 6px; font-size: 16px; }
        .office { text-align: center; font-weight: bold; font-size: 18px; margin-top: 40px; }
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            margin: 8px 0 44px;
        }
        .body-text { text-align: justify; text-indent: 0.5in; margin: 0 0 28px; font-size: 16px; }
        .u { font-weight: bold; text-decoration: underline; }
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 56px; page-break-inside: avoid; }
        .sig-table td { width: 50%; vertical-align: top; text-align: center; padding: 0 18px; }
        .sig-label { text-align: left; font-weight: bold; margin: 0 0 38px; }
        .sig-name { font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.4; }
        .sig-title { margin: 2px 0 0; font-size: 14px; font-weight: bold; }
        .approval-row { display: flex; align-items: center; justify-content: space-between; margin-top: 64px; page-break-inside: avoid; }
        .approval-row .spacer { width: 130px; flex-shrink: 0; }
        .approved { flex: 1; text-align: center; }
        .approved .sig-label { margin-bottom: 38px; text-align: center; }
        .qr-code {
            flex-shrink: 0;
            text-align: center;
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
    <img class="watermark" src="{{ asset('images/lgu-logo.png') }}" alt="">
    <div class="header">
        <img src="{{ asset('images/lgu-logo.png') }}" alt="Seal">
        <p class="republic">Republic of the Philippines</p>
        <p class="province">Province of Leyte</p>
        <p class="municipality">Municipality of Palompon</p>
        <p class="divider">-oOo-</p>
    </div>

    <p class="office">OFFICE OF THE SANGGUNIANG BAYAN</p>
    <p class="doc-title">LICENSE TO OPERATE MOTORIZED TRICYCLE (FRANCHISE)</p>

    <p class="body-text">
            <strong>THIS</strong> License to Operate Motorized Tricycle is awarded to
            <span class="u">{{ $tricycle->name ?? '—' }}</span>
            @if ($renewalLabel)
                <strong>({{ $renewalLabel }})</strong>
            @endif
            of Barangay <span class="u">{{ $tricycle->address ?? '—' }}</span>, Palompon, Leyte
            plying the route of <span class="u">{{ $franchise->authorized_route }}</span>
            @if ($todaCode)
                TODA (<span class="u">{{ $todaCode }}</span>),
            @endif
            carrying a Body Number <span class="u">{{ $tricycle->body_number ?? '—' }}</span>,
            Engine Number <span class="u">{{ $tricycle->engine_motor_no ?? '—' }}</span>,
            Plate Number <span class="u">{{ $tricycle->plate_no ?? '—' }}</span>,
            and have complied all the necessary documents and paid all the corresponding fee(s) and municipal taxes.
        </p>

        <p class="body-text">
            This award will expire on <strong>{{ $franchise->valid_until->format('F j, Y') }}</strong>.
            This License to Operate Motorized Tricycle shall be suspended, revoked and cancelled for
            NON-Compliance of existing TODA policies, existing traffic rules, regulations and of any
            violation(s) of the Municipal Ordinance Number {{ $license['ordinance_no'] }}.
        </p>

        <p class="body-text">
            Issued this <strong>{{ strtoupper($issued->format('jS')) }}</strong> day of
            <strong>{{ strtoupper($issued->format('F')) }}</strong> <strong>{{ $issued->format('Y') }}</strong>
            at {{ $issuedAt }}.
        </p>

        <table class="sig-table">
            <tr>
                <td>
                    <p class="sig-label">Assessed by:</p>
                    <p class="sig-name">{{ $license['assessed_by']['name'] }}</p>
                    <p class="sig-title">{{ $license['assessed_by']['title'] }}</p>
                </td>
                <td>
                    <p class="sig-label">Recommending Approval:</p>
                    <p class="sig-name">{{ $license['recommending_approval']['name'] }}</p>
                    <p class="sig-title">{{ $license['recommending_approval']['title'] }}</p>
                </td>
            </tr>
        </table>

    <div class="approval-row">
        <div class="spacer"></div>

        <div class="approved">
            <p class="sig-label" style="text-align:center;">APPROVED:</p>
            <p class="sig-name">{{ $license['approved_by']['name'] }}</p>
            <p class="sig-title">{{ $license['approved_by']['title'] }}</p>
        </div>

        @php
            $qrToken = \App\Services\QrToken::encode('franchise_license', $franchise->id);
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=8&ecc=M&data=' . urlencode($qrToken);
        @endphp

        <div class="qr-code">
            <img src="{{ $qrUrl }}" alt="Verification QR Code">
            <p class="qr-label">SCAN TO VERIFY</p>
        </div>
    </div>
</div>
</body>
</html>