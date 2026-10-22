@php
    // ?side=front or ?side=back prints only that side (4 cards per page).
    // Default prints front + back side by side (2 IDs per page).
    $side = in_array(request()->query('side'), ['front', 'back']) ? request()->query('side') : 'both';
    $pages = $tanodIds->values()->chunk($side === 'both' ? 2 : 4);
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
        @page { size: A4 portrait; margin: 0; }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        html, body { margin: 0; padding: 0; }
        body { font-family: 'Montserrat', Arial, Helvetica, sans-serif; color: #111; }

        .sheet {
            width: 210mm;
            height: 296mm;
            display: grid;
            grid-template-columns: repeat(2, 98mm);
            grid-template-rows: repeat(2, 136.9mm);
            gap: 4mm;
            justify-content: center;
            align-content: center;
            page-break-after: always;
            break-after: page;
            overflow: hidden;
        }
        .sheet:last-of-type { page-break-after: auto; break-after: auto; }

        .card { position: relative; width: 98mm; height: 136.9mm; container-type: inline-size; }
        .card .bg { position: absolute; inset: 0; width: 100%; height: 100%; display: block; }

        .field {
            position: absolute;
            font-size: 3.37cqw;
            font-weight: 700;
            line-height: 1.2;
            color: #111;
            white-space: nowrap;
            overflow: hidden;
        }

        /* ---- FRONT (tweak top/left/width in % if a line is off) ---- */
        .f-photo { position: absolute; top: 34.5%; left: 7.2%; width: 32%; height: 30.8%; object-fit: cover; object-position: center top; border-radius: 10px; }
        .f-barangay { top: 16.4%; left: 50%;   width: 27%;   text-align: center; text-transform: uppercase; color: #fff; }
        .f-idno     { top: 43.9%; left: 64.5%; width: 29%; }
        .f-name     { top: 50.5%; left: 47.5%; width: 45.5%; text-transform: uppercase; }
        .f-address  { top: 55.2%; left: 47.5%; width: 45.5%; }
        .f-dob      { top: 58.5%; left: 47.5%; width: 45.5%; }

        /* ---- BACK ---- */
        .b-barangay     { top: 22.5%; left: 60%;   width: 27%;   text-align: center; text-transform: uppercase; }
        .b-name         { top: 43%; left: 32%;   width: 59.5%; }
        .b-address      { top: 48%; left: 32%;   width: 59.5%; }
        .b-contact      { top: 54.3%; left: 29%;   width: 19%;   font-size: 2.76cqw; }
        .b-relationship { top: 54.3%; left: 75.5%; width: 19%;   font-size: 2.76cqw; }
        .b-punong       { top: 73.9%; left: 59.5%; width: 31%;   text-align: center; text-transform: uppercase; font-size: 2.76cqw; }
    </style>
</head>
<body>

{{-- One page per 2 IDs. Each row = FRONT (left) + BACK (right). --}}
@foreach ($pages as $page)
    <div class="sheet">
        @foreach ($page as $tanodId)
            @php $tanod = $tanods->get($tanodId->id_number); @endphp

            {{-- FRONT --}}
            @if ($side !== 'back')
            <div class="card">
                <img class="bg" src="{{ asset('images/front-tanod.png') }}" alt="">
                @if ($tanodId->photo_path)
                    <img class="f-photo" src="{{ asset('storage/' . $tanodId->photo_path) }}" alt="">
                @endif
                <div class="field fit f-barangay">{{ $tanod?->barangay }}</div>
                <div class="field fit f-idno">{{ $tanodId->id_number }}</div>
                <div class="field fit f-name">{{ $tanodId->full_name }}</div>
                <div class="field fit f-address">{{ $tanodId->address }}</div>
                <div class="field fit f-dob">{{ $tanodId->date_of_birth->format('F j, Y') }}</div>
            </div>
            @endif

            {{-- BACK --}}
            @if ($side !== 'front')
            <div class="card">
                <img class="bg" src="{{ asset('images/back-tanod.png') }}" alt="">
                <div class="field fit b-barangay">{{ $tanod?->barangay }}</div>
                <div class="field fit b-name">{{ $tanodId->emergency_contact_name }}</div>
                <div class="field fit b-address">{{ $tanodId->emergency_contact_address }}</div>
                <div class="field fit b-contact">{{ $tanodId->emergency_contact_number }}</div>
                <div class="field fit b-relationship">{{ $tanodId->emergency_contact_relationship }}</div>
                <div class="field fit b-punong">{{ $tanod?->barangay_chairman }}</div>
            </div>
            @endif
        @endforeach
    </div>
@endforeach

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