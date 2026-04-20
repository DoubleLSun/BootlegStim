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
    const genresBtn = document.getElementById('genresBtn');
    const genresDropdown = document.getElementById('genresDropdown');
    const searchForm = document.getElementById('steamNavSearchForm');
    const searchInput = document.getElementById('steamNavSearchInput');
    const searchPreview = document.getElementById('steamSearchPreview');

    if (!nav || !lowerWrap || !recBtn || !recDropdown || !genresBtn || !genresDropdown || !searchForm || !searchInput || !searchPreview) {
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
            genresDropdown.classList.remove('is-open');
            genresBtn.setAttribute('aria-expanded', 'false');
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
        genresDropdown.classList.remove('is-open');
        genresBtn.setAttribute('aria-expanded', 'false');
    });

    genresBtn.addEventListener('click', () => {
        const isOpen = genresDropdown.classList.toggle('is-open');
        genresBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        recDropdown.classList.remove('is-open');
        recBtn.setAttribute('aria-expanded', 'false');
    });

    let searchPreviewRequest = null;
    const closeSearchPreview = () => {
        searchPreview.classList.remove('is-open');
        searchPreview.innerHTML = '';
    };

    const renderSearchPreview = (results) => {
        if (!Array.isArray(results) || results.length === 0) {
            searchPreview.innerHTML = '<p class="steam-search-preview-empty">No matching titles.</p>';
            searchPreview.classList.add('is-open');
            return;
        }

        searchPreview.innerHTML = results.map((row) => {
            const cover = row.cover_image || 'https://via.placeholder.com/120x45?text=Game';
            const title = row.title || 'Untitled Game';
            const url = row.url || '#';
            return `<a class="steam-search-preview-item" href="${url}"><img src="${cover}" alt="${title}"><span>${title}</span></a>`;
        }).join('');
        searchPreview.classList.add('is-open');
    };

    searchInput.addEventListener('input', () => {
        const q = searchInput.value.trim();
        if (q.length === 0) {
            closeSearchPreview();
            return;
        }

        if (searchPreviewRequest) {
            searchPreviewRequest.abort();
        }

        searchPreviewRequest = new AbortController();
        fetch(`/search/preview?q=${encodeURIComponent(q)}`, { signal: searchPreviewRequest.signal })
            .then((res) => res.ok ? res.json() : Promise.reject(new Error('Preview failed')))
            .then((payload) => renderSearchPreview(payload.results || []))
            .catch(() => {
                // Ignore abort errors and silent failures for preview UX.
            });
    });

    searchInput.addEventListener('focus', () => {
        if (searchPreview.children.length > 0) {
            searchPreview.classList.add('is-open');
        }
    });

    searchForm.addEventListener('submit', () => {
        closeSearchPreview();
    });

    document.addEventListener('click', (event) => {
        if (!lowerWrap.contains(event.target)) {
            recDropdown.classList.remove('is-open');
            recBtn.setAttribute('aria-expanded', 'false');
            genresDropdown.classList.remove('is-open');
            genresBtn.setAttribute('aria-expanded', 'false');
        }

        if (!searchForm.contains(event.target)) {
            closeSearchPreview();
        }
    });
})();
