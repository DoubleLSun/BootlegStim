(() => {
    if (window.__steamNavInitialized) {
        return;
    }
    window.__steamNavInitialized = true;

    const nav = document.getElementById('steamNav');
    // entire lower wrap should be retracted
    const lowerWrap = document.getElementById('steamNavLowerWrap');
    const recBtn = document.getElementById('recommendationsBtn');
    const recDropdown = document.getElementById('recommendationsDropdown');

    if (!nav || !lowerWrap || !recBtn || !recDropdown) {
        return;
    }

    let lastY = window.scrollY || 0;

    window.addEventListener('scroll', () => {
        const y = window.scrollY || 0;
        const delta = y - lastY;

        if (y > 100 && delta > 4) {
            nav.classList.add('is-compact');
            recDropdown.classList.remove('is-open');
            recBtn.setAttribute('aria-expanded', 'false');
            // retract animation to hide entire lower wrap
            lowerWrap.style.animation = 'retract 0.3s forwards';
            lowerWrap.style.display = 'none';
        } else if (delta < -4 || y <= 40) {
            nav.classList.remove('is-compact');
            lowerWrap.style.animation = 'expand 0.3s forwards';
            lowerWrap.style.display = 'block';
        }

        lastY = y;
    }, { passive: true });

    recBtn.addEventListener('click', () => {
        const isOpen = recDropdown.classList.toggle('is-open');
        recBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', (event) => {
        if (!lowerWrap.contains(event.target)) {
            recDropdown.classList.remove('is-open');
            recBtn.setAttribute('aria-expanded', 'false');
        }
    });
})();
