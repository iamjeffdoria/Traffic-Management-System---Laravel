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
            font-size: 16px;
            line-height: 1.65;
            margin: 0;
        }
        .page {
            border: 1px solid #999;
            padding: 0.7in 1in;
            box-sizing: border-box;
            min-height: calc(100vh - 4px);
            position: relative;
            overflow: hidden;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5.6in;
            height: 5.6in;
            transform: translate(-50%, -50%);
            object-fit: contain;
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }
        .page > *:not(.watermark) {
            position: relative;
            z-index: 1;
        }
        .header { position: relative; text-align: center; margin-bottom: 16px; }
        .header img { position: absolute; left: 20px; top: 4px; width: 80px; height: 80px; }
        .header p { margin: 0; font-weight: bold; }
        .header .republic { font-size: 18px; }
        .header .province,
        .header .municipality { font-size: 16px; margin-top: 4px; }
        .header .divider { margin-top: 6px; font-size: 16px; }
        .office { text-align: center; font-weight: bold; font-size: 18px; margin-top: 24px; }
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 19px;
            margin: 6px 0 32px;
        }
        .body-text { text-align: justify; text-indent: 0.5in; margin: 0 0 22px; }
        .u { font-weight: bold; text-decoration: underline; }
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 44px; page-break-inside: avoid; }
        .sig-table td { width: 50%; vertical-align: top; text-align: center; padding: 0 18px; }
        .sig-label { text-align: left; font-weight: bold; margin: 0 0 34px; }
        .sig-name { font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.4; }
        .sig-title { margin: 2px 0 0; font-size: 14px; font-weight: bold; }
        .approved { width: 50%; margin: 44px auto 0; text-align: center; page-break-inside: avoid; }
        .approved .sig-label { margin-bottom: 34px; }
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

    <div class="approved">
        <p class="sig-label" style="text-align:center;">APPROVED:</p>
        <p class="sig-name">{{ $license['approved_by']['name'] }}</p>
        <p class="sig-title">{{ $license['approved_by']['title'] }}</p>
    </div>
</div>
</body>
</html>