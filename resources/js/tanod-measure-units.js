const CFG = {
    height: {
        factors: { cm: 1, mm: 0.1, m: 100, in: 2.54, ft: 30.48, yd: 91.44 },
        aliases: {
            cm: 'cm', cms: 'cm', centimeter: 'cm', centimeters: 'cm', centimetre: 'cm', centimetres: 'cm',
            mm: 'mm', millimeter: 'mm', millimeters: 'mm', millimetre: 'mm', millimetres: 'mm',
            m: 'm', meter: 'm', meters: 'm', metre: 'm', metres: 'm',
            in: 'in', inch: 'in', inches: 'in', '"': 'in',
            ft: 'ft', foot: 'ft', feet: 'ft', "'": 'ft',
            yd: 'yd', yds: 'yd', yard: 'yd', yards: 'yd',
        },
    },
    weight: {
        factors: { kg: 1, g: 0.001, lb: 0.45359237, oz: 0.028349523125, st: 6.35029318 },
        aliases: {
            kg: 'kg', kgs: 'kg', kilo: 'kg', kilos: 'kg', kilogram: 'kg', kilograms: 'kg',
            g: 'g', gm: 'g', gms: 'g', gram: 'g', grams: 'g',
            lb: 'lb', lbs: 'lb', pound: 'lb', pounds: 'lb',
            oz: 'oz', ounce: 'oz', ounces: 'oz',
            st: 'st', stone: 'st', stones: 'st',
        },
    },
};

const PATTERN = /(\d+(?:\.\d+)?|\.\d+)\s*([a-z]+|["'])?\.?/g;

const tidy = (n) => String(Number(n.toFixed(4)));

// Reads text like 170, 5'7", 5 ft 7 in, 12 st 4 lb.
// Returns null (empty), false (unreadable) or { base, used, explicit }.
function parse(raw, defaultUnit, cfg) {
    let text = String(raw ?? '').trim().toLowerCase();
    if (text === '') return null;

    text = text.replace(/[′’‘`´]/g, "'").replace(/[″”“]/g, '"').replace(/''/g, '"');

    const matches = [...text.matchAll(PATTERN)];
    if (!matches.length) return false;

    const leftover = text.replace(PATTERN, '').replace(/and|[,&]/g, ' ').trim();
    if (leftover !== '') return false;

    let base = 0;
    let previous = null;
    let explicit = false;
    const used = [];

    for (const m of matches) {
        const token = m[2] || '';
        let unit;

        if (token === '') {
            unit = previous === 'ft' ? 'in' : previous === 'st' ? 'lb' : defaultUnit;
        } else {
            unit = cfg.aliases[token];
            explicit = true;
        }

        if (!unit || !cfg.factors[unit]) return false;

        base += parseFloat(m[1]) * cfg.factors[unit];
        if (!used.includes(unit)) used.push(unit);
        previous = unit;
    }

    return { base, used, explicit };
}

// Whatever is currently in the boxes, in cm (height) or kg (weight).
// Returns null (empty) or false (unreadable).
function readBase(wrap, kind, unit) {
    const cfg = CFG[kind];
    const ftIn = kind === 'height' && unit === 'ft_in';
    const main = wrap.querySelector('[data-measure-main]');
    const inchesEl = wrap.querySelector('[data-measure-inches]');

    const a = parse(main.value, ftIn ? 'ft' : unit, cfg);
    const b = ftIn && inchesEl ? parse(inchesEl.value, 'in', cfg) : null;

    if (a === false || b === false) return false;
    if (a === null && b === null) return null;

    return (a ? a.base : 0) + (b ? b.base : 0);
}

// Shows a cm / kg value in the boxes using the chosen unit.
function writeValue(wrap, kind, unit, base) {
    const main = wrap.querySelector('[data-measure-main]');
    const inches = wrap.querySelector('[data-measure-inches]');
    const showInches = kind === 'height' && unit === 'ft_in';

    if (inches) {
        inches.classList.toggle('hidden', !showInches);
        if (!showInches) inches.value = '';
    }

    if (base === null) {
        main.value = '';
        return;
    }

    if (showInches) {
        const totalInches = base / 2.54;
        let feet = Math.floor(totalInches / 12);
        let rest = Number((totalInches - feet * 12).toFixed(2));

        if (rest >= 12) {
            feet += 1;
            rest = 0;
        }

        main.value = feet;
        inches.value = tidy(rest);
        return;
    }

    main.value = tidy(base / CFG[kind].factors[unit]);
}

// Dropdown changed: convert what is typed into the new unit.
document.addEventListener('change', (event) => {
    if (!event.target.matches('[data-measure-unit]')) return;

    const select = event.target;
    const wrap = select.closest('[data-measure]');
    const kind = wrap.dataset.measure;

    const base = readBase(wrap, kind, select.dataset.prev);
    if (base !== false) writeValue(wrap, kind, select.value, base);

    select.dataset.prev = select.value;
});

// Text typed with units (5'7", 143 lbs...): switch the dropdown to match.
document.addEventListener('change', (event) => {
    if (!event.target.matches('[data-measure-main]')) return;

    const wrap = event.target.closest('[data-measure]');
    const kind = wrap.dataset.measure;
    const cfg = CFG[kind];
    const select = wrap.querySelector('[data-measure-unit]');
    const inchesEl = wrap.querySelector('[data-measure-inches]');
    const current = select.value;

    const parsed = parse(event.target.value, current === 'ft_in' ? 'ft' : current, cfg);
    if (!parsed || !parsed.explicit) return;

    const { used } = parsed;
    let base = parsed.base;
    let target;

    const feetAndInches = kind === 'height' && used.every((u) => u === 'ft' || u === 'in');

    if (feetAndInches && (used.length === 2 || current === 'ft_in')) {
        target = 'ft_in';

        // "5'" typed while the inches box already has a value: keep the inches.
        if (!used.includes('in') && current === 'ft_in' && inchesEl) {
            const extra = parse(inchesEl.value, 'in', cfg);
            if (extra) base += extra.base;
        }
    } else if (used.length === 1) {
        target = used[0];
    } else {
        target = used[used.length - 1];
    }

    select.value = target;
    select.dataset.prev = target;
    writeValue(wrap, kind, target, base);
});

// Put the unit pickers back to cm / kg (used when the edit modal opens with stored values).
function resetTanodMeasureUnits(form) {
    form.querySelectorAll('[data-measure]').forEach((wrap) => {
        const select = wrap.querySelector('[data-measure-unit]');
        const fallback = wrap.dataset.measure === 'height' ? 'cm' : 'kg';

        select.value = fallback;
        select.dataset.prev = fallback;

        const inches = wrap.querySelector('[data-measure-inches]');
        if (inches) {
            inches.value = '';
            inches.classList.add('hidden');
        }
    });
}
window.resetTanodMeasureUnits = resetTanodMeasureUnits;