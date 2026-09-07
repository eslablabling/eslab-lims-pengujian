/**
 * ESLab LIMS - Mobile Navigation Helper
 */
document.addEventListener('DOMContentLoaded', function () {
    // Quick search or filter handlers if present
    const searchInput = document.getElementById('sidebarSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase().trim();
            const navItems = document.querySelectorAll('.nav-menu .nav-item, .nav-menu .nav-link');
            navItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
