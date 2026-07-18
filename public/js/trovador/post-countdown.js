"use strict";

/**
 * Trovador — F1. Live countdown badges on post cards.
 * Pure client-side; reads data attributes rendered by post-box.blade.php.
 *
 *   data-release = unix seconds when the post becomes public (future only)
 *   data-expire  = unix seconds when the post disappears (rendered only if <72h)
 *
 * - release in the future  -> "📅 Se publica en Xh Ymin"
 * - expire in the future    -> "⏱ Desaparece en Xh Ymin" (red when <2h left)
 * - expire reached          -> the post card is hidden
 */
var TrovadorCountdown = {

    fmt: function (secs) {
        if (secs < 0) secs = 0;
        var h = Math.floor(secs / 3600);
        var m = Math.floor((secs % 3600) / 60);
        var s = Math.floor(secs % 60);
        if (h > 0) return h + 'h ' + m + 'min';
        if (m > 0) return m + 'min ' + s + 's';
        return s + 's';
    },

    tick: function () {
        var now = Math.floor(Date.now() / 1000);

        document.querySelectorAll('.trovador-countdown').forEach(function (el) {
            var release = parseInt(el.getAttribute('data-release') || '0', 10);
            var expire = parseInt(el.getAttribute('data-expire') || '0', 10);

            // Anticipation countdown (release in the future).
            if (release && release > now) {
                el.classList.remove('text-danger');
                el.innerHTML = '📅 ' + (el.getAttribute('data-release-label') || 'Se publica en') +
                    ' ' + TrovadorCountdown.fmt(release - now);
                return;
            }

            // Expiry countdown.
            if (expire) {
                var remaining = expire - now;
                if (remaining <= 0) {
                    // Hide the whole post card once expired.
                    var card = el.closest('[data-post-container]') || el.closest('.post-wrapper') || el.closest('.card');
                    if (card) {
                        card.style.display = 'none';
                    }
                    el.textContent = '';
                    return;
                }
                el.innerHTML = '⏱ ' + (el.getAttribute('data-expire-label') || 'Desaparece en') +
                    ' ' + TrovadorCountdown.fmt(remaining);
                // Urgent styling under 2 hours.
                if (remaining < 7200) {
                    el.classList.add('text-danger');
                } else {
                    el.classList.remove('text-danger');
                }
                return;
            }

            el.textContent = '';
        });
    },

    initialize: function () {
        if (!document.querySelector('.trovador-countdown')) {
            // Still start the interval; cards can be injected by pagination later.
        }
        TrovadorCountdown.tick();
        setInterval(TrovadorCountdown.tick, 1000);
    }
};

document.addEventListener('DOMContentLoaded', function () {
    TrovadorCountdown.initialize();
});
