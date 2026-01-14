/**
 * theme.js - Vanilla JS Refactor for Enterprise Theme
 * @copyright  2026 onwards LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    "use strict";

    return {
        init: function() {
            // 1. Initialize Scroll To Top
            const backToTopBtn = document.getElementById('backToTop');
            if (backToTopBtn) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 300) {
                        backToTopBtn.classList.add('visible');
                        backToTopBtn.style.opacity = '1';
                        backToTopBtn.style.pointerEvents = 'auto';
                    } else {
                        backToTopBtn.classList.remove('visible');
                        backToTopBtn.style.opacity = '0';
                        backToTopBtn.style.pointerEvents = 'none';
                    }
                });

                backToTopBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // 2. Add 'scrolled' class to header on scroll
            const header = document.getElementById('header');
            if (header) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 10) {
                        header.classList.add('is-scrolled');
                    } else {
                        header.classList.remove('is-scrolled');
                    }
                });
            }

            // 3. Initialize Tooltips (using Bootstrap 5 via Moodle core)
            // Moodle 4 usually handles this automatically, but we can force re-init if needed.
        }
    };
});
