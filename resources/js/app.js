import { createIcons, icons } from 'lucide';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';

createIcons({ icons });
window.createIcons = () => createIcons({ icons });

// Date inputs only: flatpickr's time-only mode fills 12:00 on open+blur,
// which would fake an optional jam_pulang. Native time inputs stay.
flatpickr.localize(Indonesian);
document.querySelectorAll('input[type="date"]').forEach((el) => {
    const fp = flatpickr(el, {
        disableMobile: true,
        // No <select> in the header, else the global Select2 init hijacks it.
        monthSelectorType: 'static',
        minDate: el.min || null,
        maxDate: el.max || null,
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'j F Y',
        altInputClass: el.className,
    });
    // readonly inputs skip `required` validation; block typing instead.
    fp.altInput.readOnly = false;
    fp.altInput.inputMode = 'none';
    fp.altInput.addEventListener('beforeinput', (e) => e.preventDefault());
});

// Calendar lives in <body> and is positioned once on open; re-anchor it on any
// scroll (page, modal, container) so it stays glued to its input.
document.addEventListener('scroll', () => {
    document.querySelectorAll('.flatpickr-input').forEach((el) => {
        if (el._flatpickr?.isOpen) el._flatpickr._positionCalendar();
    });
}, { capture: true, passive: true });
