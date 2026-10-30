import { createIcons, icons } from 'lucide';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';

createIcons({ icons });
window.createIcons = () => createIcons({ icons });

// Replace native date/time inputs; submitted value stays Y-m-d / H:i.
flatpickr.localize(Indonesian);
document.querySelectorAll('input[type="date"], input[type="time"]').forEach((el) => {
    const isTime = el.type === 'time';
    flatpickr(el, {
        disableMobile: true,
        minDate: el.min || null,
        maxDate: el.max || null,
        ...(isTime
            ? { enableTime: true, noCalendar: true, time_24hr: true, dateFormat: 'H:i' }
            : { dateFormat: 'Y-m-d', altInput: true, altFormat: 'j F Y', altInputClass: el.className }),
        // Modals set .value directly; resync picker state on open.
        onOpen: (_dates, _str, fp) => fp.setDate(fp.input.value || null, false),
    });
});
