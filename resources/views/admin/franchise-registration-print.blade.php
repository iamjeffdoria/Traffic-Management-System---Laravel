@php
    $driverName = $franchise->tricycle->driver ?? '';
    $slipDate = $franchise->date ? $franchise->date->format('F j, Y') : '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Franchise Registration - {{ $franchise->authorized_no }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=block" rel="stylesheet">
    <style>
        @page { size: A4 portrait; margin: 0; }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        html, body { margin: 0; padding: 0; }
        body { font-family: 'Montserrat', Arial, Helvetica, sans-serif; color: #000; }

        /* 4 identical slips: 2 across x 2 down. Cut along the dashed lines. */
        .sheet {
            width: 210mm;
            height: 296mm;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            overflow: hidden;
        }
        .slip {
            --hdr: 27mm; /* height of the letterhead strip. Raise/lower if the header is cut off or too tall. */
            position: relative;
            overflow: hidden;
            font-size: 9.5px;
            line-height: 1.35;
        }
        .slip:nth-child(odd) { border-right: 1px dashed #888; }
        .slip:nth-child(-n+2) { border-bottom: 1px dashed #888; }

        /* Official letterhead: only the header portion of the image */
        .letterhead {
            height: var(--hdr);
            background: url('{{ asset('images/letterhead.png') }}') no-repeat top center;
            background-size: 100% auto;
        }

        .body { padding: 0 8mm; }

        .titles { text-align: center; margin-top: 2mm; }
        .office { font-weight: 700; font-size: 9.5px; margin: 0; }
        .title { display: block; margin-top: 1.5mm; font-weight: 700; font-size: 10px; }

        .name-row { display: flex; gap: 6mm; margin-top: 8mm; }
        .name-col { flex: 1; min-width: 0; }
        .date-col { width: 30mm; flex-shrink: 0; }
        .line {
            min-height: 4.5mm;
            border-bottom: 1px solid #000;
            text-align: center;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
        }
        .line-label { margin-top: 0.8mm; font-weight: 700; }
        .date-col .line-label { text-align: center; }

        .fees { margin-top: 6mm; }
        .fee { display: flex; font-weight: 700; line-height: 1.6; }
        .fee .l { flex: 1; }
        .fee .a { width: 22mm; text-align: center; flex-shrink: 0; }

        .sigs { margin-top: 6mm; }
        .sig { width: max-content; max-width: 100%; text-align: center; font-weight: 700; }
        .sig .name { text-decoration: underline; white-space: nowrap; }
        .sig.right { margin-left: auto; margin-top: 3mm; }
    </style>
</head>
<body>
<div class="sheet">
    @for ($copy = 0; $copy < 4; $copy++)
        <div class="slip">
            <div class="letterhead"></div>

            <div class="body">
                <div class="titles">
                    <p class="office">{{ $slip['office'] }}</p>
                    <span class="title">{{ $slip['title'] }}</span>
                </div>

                <div class="name-row">
                    <div class="name-col">
                        <div class="line">{{ $driverName }}</div>
                        <div class="line-label">Name of Driver</div>
                    </div>
                    <div class="date-col">
                        <div class="line">{{ $slipDate }}</div>
                        <div class="line-label">Date</div>
                    </div>
                </div>

                <div class="fees">
                    @foreach ($slip['fees'] as $fee)
                        <div class="fee">
                            <span class="l">{{ $fee['label'] }}</span>
                            <span class="a">{{ $fee['amount'] !== null ? number_format($fee['amount'], 2) : '' }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="sigs">
                    <div class="sig">
                        <div class="name">{{ $slip['secretary']['name'] }}</div>
                        <div>{{ $slip['secretary']['title'] }}</div>
                    </div>
                    <div class="sig right">
                        <div class="name">{{ $slip['officer']['name'] }}</div>
                        <div>{{ $slip['officer']['title'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>
</body>
</html>