{{--
    Date of birth field that accepts typed input (DD/MM/YYYY) or a calendar pick.
    Params: $name (default date_of_birth), $value (Y-m-d or d/m/Y), $class, $attrs (extra raw attributes).
--}}
@php
    $name = $name ?? 'date_of_birth';
    $value = $value ?? '';
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', (string) $value, $m)) {
        $value = "{$m[3]}/{$m[2]}/{$m[1]}";
    }
@endphp
<div class="dob-field" style="position:relative;">
    <input type="text" name="{{ $name }}" value="{{ $value }}" class="{{ $class ?? '' }}"
           placeholder="DD/MM/YYYY" inputmode="numeric" maxlength="10" autocomplete="bday"
           style="padding-right:2.6rem;" data-dob {!! $attrs ?? '' !!}>
    <button type="button" data-dob-pick title="Pick from calendar"
            style="position:absolute;right:.35rem;top:50%;transform:translateY(-50%);border:0;background:transparent;color:var(--text-soft, #6c757d);padding:.25rem .45rem;line-height:1;cursor:pointer;">
        <span class="fas fa-calendar-days"></span>
    </button>
    <input type="date" data-dob-native tabindex="-1" aria-hidden="true" max="{{ date('Y-m-d') }}"
           style="position:absolute;right:0;bottom:0;width:1px;height:1px;padding:0;border:0;opacity:0;pointer-events:none;">
</div>

@once
<script>
window.DobInput = (function () {
    const pad = n => String(n).padStart(2, '0');

    // Parses DD/MM/YYYY (also D/M/YYYY, DDMMYYYY, YYYY-MM-DD) into {d, m, y} or null.
    function parse(v) {
        v = (v || '').trim();
        let m;
        if ((m = v.match(/^(\d{1,2})[\/.\-](\d{1,2})[\/.\-](\d{4})$/))) m = { d: +m[1], m: +m[2], y: +m[3] };
        else if ((m = v.match(/^(\d{2})(\d{2})(\d{4})$/))) m = { d: +m[1], m: +m[2], y: +m[3] };
        else if ((m = v.match(/^(\d{4})-(\d{2})-(\d{2})/))) m = { d: +m[3], m: +m[2], y: +m[1] };
        else return null;
        const dt = new Date(m.y, m.m - 1, m.d);
        return dt.getFullYear() === m.y && dt.getMonth() === m.m - 1 && dt.getDate() === m.d ? m : null;
    }

    const toText = p => `${pad(p.d)}/${pad(p.m)}/${p.y}`;
    const toIso = p => `${p.y}-${pad(p.m)}-${pad(p.d)}`;

    function validate(input) {
        const v = input.value.trim();
        const p = parse(v);
        let msg = '';
        if (v && !p) msg = 'Enter a valid date as DD/MM/YYYY.';
        else if (p && toIso(p) >= new Date().toISOString().slice(0, 10)) msg = 'Date of birth must be in the past.';
        input.setCustomValidity(msg);
        if (p) input.value = toText(p);
        return p;
    }

    function fill(input, value) {
        const p = parse(value);
        input.value = p ? toText(p) : '';
        input.setCustomValidity('');
    }

    function init(field) {
        const text = field.querySelector('[data-dob]');
        const native = field.querySelector('[data-dob-native]');

        text.addEventListener('input', e => {
            if ((e.inputType || '').startsWith('delete')) { text.setCustomValidity(''); return; }
            let v = text.value.replace(/[^\d\/]/g, '').slice(0, 10);
            if (/^\d{2}$/.test(v) || /^\d{1,2}\/\d{2}$/.test(v)) v += '/';
            text.value = v;
            text.setCustomValidity('');
        });
        text.addEventListener('blur', () => validate(text));

        field.querySelector('[data-dob-pick]').addEventListener('click', () => {
            const p = parse(text.value);
            native.value = p ? toIso(p) : '';
            try { native.showPicker(); } catch (err) { native.focus(); native.click(); }
        });
        native.addEventListener('change', () => {
            fill(text, native.value);
            text.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    function initAll() { document.querySelectorAll('.dob-field:not([data-dob-ready])').forEach(f => { f.dataset.dobReady = 1; init(f); }); }
    document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', initAll) : initAll();

    return { fill, initAll };
})();
</script>
@endonce
