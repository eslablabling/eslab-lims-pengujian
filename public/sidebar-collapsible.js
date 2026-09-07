/**
 * ESLab LIMS - Sidebar Toggle & Responsive Navigation Controller
 * Manual Toggle & LocalStorage State Management (No Auto Hover Flicker)
 */

(function () {
    'use strict';

    // Inisialisasi status sidebar dari LocalStorage saat DOM siap
    function initSidebarState() {
        const isCollapsed = localStorage.getItem('eslab_sidebar_collapsed') === 'true';
        const sidebar = document.querySelector('.sidebar');
        const mainWrapper = document.querySelector('.main, .main-wrapper, .content');

        if (isCollapsed && window.innerWidth >= 992) {
            document.body.classList.add('sidebar-collapsed');
            if (sidebar) sidebar.classList.add('collapsed');
            if (mainWrapper) mainWrapper.classList.add('sidebar-collapsed');
        } else {
            document.body.classList.remove('sidebar-collapsed');
            if (sidebar) sidebar.classList.remove('collapsed');
            if (mainWrapper) mainWrapper.classList.remove('sidebar-collapsed');
        }

        updateToggleButtons(isCollapsed);
    }

    // Fungsi Toggle Manual
    window.toggleSidebar = function (event) {
        if (event && event.preventDefault) event.preventDefault();

        // Pada tampilan mobile (< 992px), toggle mobile-open drawer
        if (window.innerWidth < 992) {
            window.toggleMobileSidebar();
            return;
        }

        const isCurrentlyCollapsed = document.body.classList.contains('sidebar-collapsed');
        const shouldCollapse = !isCurrentlyCollapsed;

        const sidebar = document.querySelector('.sidebar');
        const mainWrapper = document.querySelector('.main, .main-wrapper, .content');

        if (shouldCollapse) {
            document.body.classList.add('sidebar-collapsed');
            if (sidebar) sidebar.classList.add('collapsed');
            if (mainWrapper) mainWrapper.classList.add('sidebar-collapsed');
            localStorage.setItem('eslab_sidebar_collapsed', 'true');
        } else {
            document.body.classList.remove('sidebar-collapsed');
            if (sidebar) sidebar.classList.remove('collapsed');
            if (mainWrapper) mainWrapper.classList.remove('sidebar-collapsed');
            localStorage.setItem('eslab_sidebar_collapsed', 'false');
        }

        updateToggleButtons(shouldCollapse);
    };

    // Fungsi Update Icon & Title Tombol Toggle
    function updateToggleButtons(isCollapsed) {
        const toggleBtns = document.querySelectorAll('#sidebarToggleBtn, #sidebarPinBtn, .sidebar-toggle-btn');
        toggleBtns.forEach(btn => {
            const icon = btn.querySelector('i, span');
            if (btn.id === 'sidebarPinBtn') {
                btn.title = isCollapsed ? 'Buka Penuh Sidebar' : 'Ciutkan Sidebar (Compact)';
                if (isCollapsed) {
                    btn.classList.remove('active');
                } else {
                    btn.classList.add('active');
                }
            } else {
                btn.title = isCollapsed ? 'Buka Sidebar' : 'Ciutkan Sidebar';
            }
        });
    }

    // Toggle Mobile Sidebar (Offcanvas / Drawer)
    window.toggleMobileSidebar = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        const sidebar = document.querySelector('.sidebar');
        const backdrop = document.getElementById('mobileBackdrop') || document.querySelector('.mobile-backdrop');

        if (sidebar) {
            const isOpen = sidebar.classList.toggle('mobile-open');
            if (backdrop) {
                if (isOpen) {
                    backdrop.classList.add('active');
                } else {
                    backdrop.classList.remove('active');
                }
            }
        }
    };

    // Keyboard Shortcut (Ctrl + B / Alt + B) untuk Toggle Sidebar
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
            e.preventDefault();
            window.toggleSidebar(e);
        }
    });

    // Event Listener DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarState);
    } else {
        initSidebarState();
    }
})();
