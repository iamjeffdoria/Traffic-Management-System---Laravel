<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kasabutan - {{ $selling->body_number }}</title>
    <link rel="stylesheet" href="{{ asset('css/print-letterhead.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=block" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, Helvetica, sans-serif;
            color: #111;
            font-size: 14px;
            line-height: 1.9;
            margin: 0;
        }
        /* Faint PSO logo behind the text. Fixed = centered on every printed page. */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 5in;
            height: 5in;
            transform: translate(-50%, -50%);
            object-fit: contain;
            opacity: 0.1;
            z-index: -1;
            pointer-events: none;
        }
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 1px;
            text-decoration: underline;
            margin: 30px 0 34px;
        }
        .greeting { margin: 0 0 18px; }
        .body-text { text-align: justify; text-indent: 0.5in; margin: 0 0 18px; }
        .u { font-weight: bold; text-decoration: underline; }

        .sig-table { width: 100%; border-collapse: collapse; margin-top: 56px; page-break-inside: avoid; }
        .sig-table td { width: 50%; vertical-align: top; text-align: center; padding: 0 24px; }
        .sig-space { height: 36px; border-bottom: 1px solid #000; }
        .sig-name { font-weight: bold; text-transform: uppercase; margin: 4px 0 0; line-height: 1.4; }
        .sig-label { margin: 0; font-size: 13px; }

        .witness-block { width: 50%; margin: 48px auto 0; text-align: center; page-break-inside: avoid; }
        .date-line { margin-top: 36px; }
    </style>
</head>
<body>
<div class="letterhead-page">
    <img class="letterhead-bg" src="{{ asset('images/letterhead.png') }}" alt="">
    <img class="watermark" src="{{ asset('images/csulogo2.png') }}" alt="">

    <p class="doc-title">KASABUTAN</p>

    <p class="greeting">Alang sa Kasayuran sa tanan,</p>

    <p class="body-text">
        Ako si <span class="u">{{ $selling->owner }}</span> nga nagpuyo ning barangay
        <span class="u">{{ $selling->barangay }}</span> lungsod sa
        <span class="u">{{ $selling->municipality }}</span> nanag-iya ug pot-pot numero
        <span class="u">{{ $selling->body_number }}</span>, Ako kining gibaligya ngadto kang
        <span class="u">{{ $selling->buyer }}</span>, taga barangay
        <span class="u">{{ $selling->buyer_address }}</span> sa kantidad nga
        Php <span class="u">{{ number_format($selling->amount, 2) }}</span>.
    </p>

    <p class="body-text">
        Aron sa pagmatuod ako kining pagapirmahan sa ubos, kauban sa testigos.
    </p>

    <table class="sig-table">
        <tr>
            <td>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $selling->owner }}</p>
                <p class="sig-label">[ Nagbaligya ]</p>
            </td>
            <td>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $selling->buyer }}</p>
                <p class="sig-label">[ Nagpalit ]</p>
            </td>
        </tr>
    </table>

    <div class="witness-block">
        <div class="sig-space"></div>
        <p class="sig-name">{{ $selling->witness }}</p>
        <p class="sig-label">[ Testigos ]</p>
    </div>

    <p class="date-line">Petsa: <span class="u">{{ $selling->date->format('F j, Y') }}</span></p>
</div>
</body>
</html>