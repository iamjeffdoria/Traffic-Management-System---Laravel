@php
    $months = ['Enero', 'Pebrero', 'Marso', 'Abril', 'Mayo', 'Hunyo', 'Hulyo', 'Agosto', 'Setyembre', 'Oktubre', 'Nobyembre', 'Disyembre'];
    $days = ['Domingo', 'Lunes', 'Martes', 'Miyerkules', 'Huwebes', 'Biyernes', 'Sabado'];

    $signedDate = $lostItem->date;
    $dateText = $months[$signedDate->month - 1] . ' ' . $signedDate->day . ', ' . $signedDate->year
        . ' (' . $days[$signedDate->dayOfWeek] . ')';

    $signedTime = \Carbon\Carbon::parse($lostItem->time);
    $hour = (int) $signedTime->format('G');
    $period = $hour < 12 ? 'sa buntag' : ($hour === 12 ? 'sa udto' : ($hour < 18 ? 'sa hapon' : 'sa gabii'));
    $timeText = $signedTime->format('g:i') . ' ' . $period;

    $amountValue = (float) $lostItem->amount;
    $amountText = fmod($amountValue, 1) == 0 ? number_format($amountValue) : number_format($amountValue, 2);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kasabutan - {{ $lostItem->body_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/print-letterhead.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=block" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 14px;
            line-height: 1.8;
            margin: 0;
        }
        /* Faint black-and-white PSO logo behind the text. Fixed = centered on every printed page. */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 7.5in;
            height: 7.5in;
            transform: translate(-50%, -50%);
            object-fit: contain;
            opacity: 0.08;
            filter: grayscale(100%);
            z-index: -1;
            pointer-events: none;
        }
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 1px;
            text-decoration: underline;
            margin: 30px 0 30px;
        }
        .body-text { text-align: justify; text-indent: 0.5in; margin: 0 0 16px; }
        .u { font-weight: bold; text-decoration: underline; }

        .sig-table { width: 100%; border-collapse: collapse; margin-top: 40px; page-break-inside: avoid; }
        .sig-table td { width: 50%; vertical-align: top; text-align: center; padding: 0 24px; }
        .sig-line { border-bottom: 1px solid #000; min-height: 30px; display: flex; align-items: flex-end; justify-content: center; padding-bottom: 2px; }
        .sig-name { font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.4; }
        .sig-label { margin: 4px 0 0; font-size: 12px; }

        .officials-table { width: 100%; border-collapse: collapse; margin-top: 40px; page-break-inside: avoid; }
        .officials-table td { width: 50%; vertical-align: top; text-align: center; padding: 0 24px; }
        .official-heading { text-align: left; font-weight: bold; font-size: 12px; letter-spacing: 0.5px; margin: 0 0 30px; }
        .official-title { margin: 2px 0 0; font-size: 12px; }
    </style>
</head>
<body>
<div class="letterhead-page">
    <img class="letterhead-bg" src="{{ asset('images/letterhead.png') }}" alt="">
    <img class="watermark" src="{{ asset('images/csulogo2.png') }}" alt="">

    <p class="doc-title">KASABUTAN</p>

    <p class="body-text">
        Ako si <span class="u">{{ $lostItem->driver }}</span>, driver sa
        <span class="u">Potpot Body # {{ $lostItem->body_number }}</span>, edad
        <span class="u">{{ $lostItem->driver_age }} anyos</span>, residente sa
        <span class="u">{{ $lostItem->driver_address }}</span>, muilis sa PINTAL nga nagkantidad ug
        <span class="u">{{ $amountText }} php</span> (my resibo) ngadto kang
        <span class="u">{{ $lostItem->passenger }}</span>, pasahero sa potpot, edad
        <span class="u">{{ $lostItem->passenger_age }} anyos</span>, residente sa
        <span class="u">{{ $lostItem->passenger_address }}</span>.
    </p>

    <p class="body-text">
        Kini nga kasabutan pirmahan ug pamatud-an karong
        <span class="u">{{ $dateText }}, {{ $timeText }}</span>, sa
        <span class="u">{{ $lostItem->issued_at }}</span>.
    </p>

    <table class="sig-table">
        <tr>
            <td>
                <div class="sig-line"><span class="sig-name">{{ $lostItem->driver }} ({{ $lostItem->body_number }})</span></div>
                <p class="sig-label">Ngalan ug Pirma sa Potpot Driver</p>
            </td>
            <td>
                <div class="sig-line"><span class="sig-name">{{ $lostItem->passenger }}</span></div>
                <p class="sig-label">Ngalan ug Pirma sa Pasahero</p>
            </td>
        </tr>
    </table>

    <table class="sig-table" style="margin-top: 32px;">
        <tr>
            <td>
                <div class="sig-line"><span class="sig-name">{{ $lostItem->witness_1 }}</span></div>
                <p class="sig-label">Witness (Traffic Enforcer)</p>
            </td>
            <td>
                <div class="sig-line"><span class="sig-name">{{ $lostItem->witness_2 }}</span></div>
                <p class="sig-label">Witness (Traffic Enforcer)</p>
            </td>
        </tr>
    </table>

    <table class="officials-table">
        <tr>
            <td>
                <p class="official-heading">VERIFIED BY:</p>
                <div class="sig-line"><span class="sig-name">{{ $lostItem->it_section }}</span></div>
                <p class="official-title">IT Section</p>
            </td>
            <td>
                <p class="official-heading">NOTED BY:</p>
                <div class="sig-line"><span class="sig-name">{{ $lostItem->security_officer }}</span></div>
                <p class="official-title">Security Officer II</p>
                <p class="official-title">Public Safety Office</p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>