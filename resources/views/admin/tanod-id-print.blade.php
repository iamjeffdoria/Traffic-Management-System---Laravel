@php
    // Up to 4 per sheet. Pad to 4 so the grid always has 4 slots.
    $slots = array_pad($tanodIds->values()->all(), 4, null);

    // Back sheet is mirrored left-to-right per row, so the backs line up with the
    // fronts when printing double-sided (flip on long edge).
    $backSlots = [$slots[1], $slots[0], $slots[3], $slots[2]];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tanod ID - Print</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=block" rel="stylesheet">
    <style>
        @page { size: letter portrait; margin: 0; }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        html, body { margin: 0; padding: 0; }
        body { font-family: 'Montserrat', Arial, Helvetica, sans-serif; color: #111; }

        .sheet {
            width: 8.5in;
            height: 10.95in;
            display: grid;
            grid-template-columns: repeat(2, 3.4in);
            grid-template-rows: repeat(2, 4.75in);
            gap: 0.3in;
            justify-content: center;
            align-content: center;
            page-break-after: always;
            break-after: page;
            overflow: hidden;
        }
        .sheet:last-of-type { page-break-after: auto; break-after: auto; }

        .card { position: relative; width: 3.4in; height: 4.75in; }
        .card .bg { position: absolute; inset: 0; width: 100%; height: 100%; display: block; }

        .field {
            position: absolute;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.2;
            color: #111;
            white-space: nowrap;
            overflow: hidden;
        }

        /* ---- FRONT (tweak top/left/width in % if a line is off) ---- */
        .f-barangay { top: 16.4%; left: 50%;   width: 27%;   text-align: center; text-transform: uppercase; }
        .f-idno     { top: 43.9%; left: 64.5%; width: 29%; }
        .f-name     { top: 50.5%; left: 47.5%; width: 45.5%; text-transform: uppercase; }
        .f-address  { top: 54.4%; left: 47.5%; width: 45.5%; }
        .f-dob      { top: 57.9%; left: 47.5%; width: 45.5%; }

        /* ---- BACK ---- */
        .b-barangay     { top: 26.5%; left: 60%;   width: 27%;   text-align: center; text-transform: uppercase; }
        .b-name         { top: 43.3%; left: 32%;   width: 59.5%; }
        .b-address      { top: 48.2%; left: 32%;   width: 59.5%; }
        .b-contact      { top: 54.5%; left: 29%;   width: 19%;   font-size: 9px; }
        .b-relationship { top: 54.5%; left: 75.5%; width: 19%;   font-size: 9px; }
        .b-punong       { top: 73.9%; left: 59.5%; width: 31%;   text-align: center; text-transform: uppercase; font-size: 9px; }
    </style>
</head>
<body>

{{-- PAGE 1: FRONTS --}}
<div class="sheet">
    @foreach ($slots as $tanodId)
        @if ($tanodId)
            @php $tanod = $tanods->get($tanodId->id_number); @endphp
            <div class="card">
                <img class="bg" src="{{ asset('images/tanod-front.png') }}" alt="">
                <div class="field fit f-barangay">{{ $tanod?->barangay }}</div>
                <div class="field fit f-idno">{{ $tanodId->id_number }}</div>
                <div class="field fit f-name">{{ $tanodId->full_name }}</div>
                <div class="field fit f-address">{{ $tanodId->address }}</div>
                <div class="field fit f-dob">{{ $tanodId->date_of_birth->format('F j, Y') }}</div>
            </div>
        @else
            <div class="card"></div>
        @endif
    @endforeach
</div>

{{-- PAGE 2: BACKS (mirrored per row for double-sided printing) --}}
<div class="sheet">
    @foreach ($backSlots as $tanodId)
        @if ($tanodId)
            @php $tanod = $tanods->get($tanodId->id_number); @endphp
            <div class="card">
                <img class="bg" src="{{ asset('images/tanod-back.png') }}" alt="">
                <div class="field fit b-barangay">{{ $tanod?->barangay }}</div>
                <div class="field fit b-name">{{ $tanodId->emergency_contact_name }}</div>
                <div class="field fit b-address">{{ $tanodId->emergency_contact_address }}</div>
                <div class="field fit b-contact">{{ $tanodId->emergency_contact_number }}</div>
                <div class="field fit b-relationship">{{ $tanodId->emergency_contact_relationship }}</div>
                <div class="field fit b-punong">{{ $tanod?->barangay_chairman }}</div>
            </div>
        @else
            <div class="card"></div>
        @endif
    @endforeach
</div>

<script>
    // Shrink any text that's too long for its line instead of cutting it off.
    window.addEventListener('load', () => {
        const run = () => document.querySelectorAll('.fit').forEach((el) => {
            let size = parseFloat(getComputedStyle(el).fontSize);
            while (el.scrollWidth > el.clientWidth + 0.5 && size > 6) {
                size -= 0.5;
                el.style.fontSize = size + 'px';
            }
        });
        (document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(run);
    });
</script>
</body>
</html>