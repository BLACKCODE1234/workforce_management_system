import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    initNavToggle();
    initDashboardToggle();
    initOtpBoxes();
});

function initDashboardToggle() {
    const toggle = document.querySelector('.dashboard-toggle');
    const nav = document.querySelector('#dashboard-nav');
    const backdrop = document.querySelector('#dashboard-backdrop');

    if (!toggle || !nav) {
        return;
    }

    const setOpen = (open) => {
        nav.classList.toggle('is-open', open);
        if (backdrop) {
            backdrop.classList.toggle('is-visible', open);
        }
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    };

    toggle.addEventListener('click', () => {
        setOpen(!nav.classList.contains('is-open'));
    });

    nav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    if (backdrop) {
        backdrop.addEventListener('click', () => setOpen(false));
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
}

function initNavToggle() {
    const toggle = document.querySelector('.nav-toggle');
    const menu = document.querySelector('#nav-menu');

    if (!toggle || !menu) {
        return;
    }

    const setOpen = (open) => {
        menu.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    };

    toggle.addEventListener('click', () => {
        setOpen(!menu.classList.contains('is-open'));
    });

    menu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 840) {
            setOpen(false);
        }
    });
}

function initOtpBoxes() {
    const form = document.querySelector('.otp-form');
    const hidden = document.querySelector('#otp');
    const boxes = Array.from(document.querySelectorAll('.otp-box'));

    if (!form || !hidden || boxes.length === 0) {
        return;
    }

    const syncHidden = () => {
        hidden.value = boxes.map((box) => box.value.replace(/\D/g, '')).join('');
    };

    // Prefill boxes if validation returned an old OTP value.
    if (hidden.value.length === boxes.length) {
        hidden.value.split('').forEach((digit, index) => {
            if (boxes[index]) {
                boxes[index].value = digit;
            }
        });
    }

    boxes.forEach((box, index) => {
        box.addEventListener('input', () => {
            box.value = box.value.replace(/\D/g, '').slice(0, 1);
            syncHidden();

            if (box.value && index < boxes.length - 1) {
                boxes[index + 1].focus();
            }
        });

        box.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace' && !box.value && index > 0) {
                boxes[index - 1].focus();
            }
        });

        box.addEventListener('paste', (event) => {
            event.preventDefault();
            const pasted = (event.clipboardData || window.clipboardData)
                .getData('text')
                .replace(/\D/g, '')
                .slice(0, boxes.length);

            pasted.split('').forEach((digit, offset) => {
                if (boxes[offset]) {
                    boxes[offset].value = digit;
                }
            });

            syncHidden();
            const focusIndex = Math.min(pasted.length, boxes.length - 1);
            boxes[focusIndex].focus();
        });
    });

    form.addEventListener('submit', syncHidden);
}
