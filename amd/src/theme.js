/**
 * theme.js - Modernized version
 * @copyright  2026 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {
    "use strict";

    const ThemeAcademi = {
        init: function() {
            this.initNavbar();
            this.initScrollTop();
            this.initFooter();
            this.initDynamicWidths();
        },

        initNavbar: function() {
            // Fix white user avatar on dark/colored nav
            const avatars = document.querySelectorAll('.usermenu .avatar img[src$="/u/f2"]');
            avatars.forEach(img => {
                if (!img.src.includes('_white')) {
                    img.src += "_white";
                }
            });

            // Improved Drawer State handling
            const page = document.querySelector('#page');
            const observer = (mutationList) => {
                mutationList.forEach(mutation => {
                    if (mutation.attributeName === 'class') {
                        this.updateHeaderClass(page);
                    }
                });
            };

            const config = { attributes: true };
            const drawerObserver = new MutationObserver(observer);
            if (page) {
                drawerObserver.observe(page, config);
                this.updateHeaderClass(page);
            }
        },

        updateHeaderClass: function(page) {
            const headerMain = document.querySelector('.header-main');
            if (!headerMain) return;
            
            if (page.classList.contains('show-drawer-right')) {
                headerMain.classList.add('show-drawer-right');
            } else {
                headerMain.classList.remove('show-drawer-right');
            }
        },

        initScrollTop: function() {
            const backToTop = $('#backToTop');
            $(window).on('scroll', () => {
                if ($(window).scrollTop() > 300) {
                    backToTop.fadeIn(200);
                } else {
                    backToTop.fadeOut(200);
                }
            });

            backToTop.on('click', (e) => {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        },

        initFooter: function() {
            const footer = document.querySelector('footer#page-footer');
            if (footer && footer.textContent.trim().length === 0) {
                footer.classList.add('empty-footer');
            }
        },

        initDynamicWidths: function() {
            // Handle page size setting interactivity
            const pageSizeSelect = document.getElementById('id_s_theme_academi_pagesize');
            const customWidthInput = document.querySelector('#admin-pagesizecustomval input[type=text]');
            
            if (pageSizeSelect && customWidthInput) {
                const toggle = () => {
                    customWidthInput.disabled = (pageSizeSelect.value === 'container' || pageSizeSelect.value === 'default');
                };
                pageSizeSelect.addEventListener('change', toggle);
                toggle();
            }
        }
    };

    return {
        init: function() {
            ThemeAcademi.init();
        }
    };
});