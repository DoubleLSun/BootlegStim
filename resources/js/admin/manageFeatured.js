(() => {
    const adminRoot = document.getElementById('admin-page-root');
    if (!adminRoot) {
        return;
    }

    const gameCreateRoute = adminRoot.dataset.gameCreateRoute || '';
    const genreCreateRoute = adminRoot.dataset.genreCreateRoute || '';

    const gameModal = document.getElementById('gameModal');
    const gameForm = document.getElementById('gameForm');
    const gameModalTitle = document.getElementById('gameModalTitle');

    const genreModal = document.getElementById('genreModal');
    const genreForm = document.getElementById('genreForm');
    const genreModalTitle = document.getElementById('genreModalTitle');

    const openModal = (id) => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
        }
    };

    const closeModal = (id) => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        }
    };

    document.querySelectorAll('[data-open-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-open-modal');
            const mode = button.getAttribute('data-mode') || 'create';

            if (modalId === 'gameModal' && gameForm && gameModalTitle) {
                if (mode === 'create') {
                    gameModalTitle.textContent = 'Create Game';
                    gameForm.action = gameCreateRoute;
                    gameForm.reset();
                    document.querySelectorAll('#game-genre_ids option').forEach((opt) => {
                        opt.selected = false;
                    });
                } else {
                    gameModalTitle.textContent = 'Edit Game';
                    gameForm.action = button.getAttribute('data-action') || gameCreateRoute;
                    document.getElementById('game-title').value = button.getAttribute('data-title') || '';
                    document.getElementById('game-description').value = button.getAttribute('data-description') || '';
                    document.getElementById('game-price').value = button.getAttribute('data-price') || '';
                    document.getElementById('game-release_date').value = button.getAttribute('data-release_date') || '';
                    document.getElementById('game-developer_id').value = button.getAttribute('data-developer_id') || '';
                    document.getElementById('game-publisher_id').value = button.getAttribute('data-publisher_id') || '';
                    document.getElementById('game-cover_image').value = button.getAttribute('data-cover_image') || '';

                    const selectedIds = (button.getAttribute('data-genre_ids') || '')
                        .split(',')
                        .map((v) => v.trim())
                        .filter((v) => v !== '');

                    document.querySelectorAll('#game-genre_ids option').forEach((opt) => {
                        opt.selected = selectedIds.includes(opt.value);
                    });
                }
            }

            if (modalId === 'genreModal' && genreForm && genreModalTitle) {
                if (mode === 'create') {
                    genreModalTitle.textContent = 'Create Genre';
                    genreForm.action = genreCreateRoute;
                    genreForm.reset();
                    const displayFlag = document.getElementById('genre-display_flag');
                    if (displayFlag) {
                        displayFlag.checked = true;
                    }
                } else {
                    genreModalTitle.textContent = 'Edit Genre';
                    genreForm.action = button.getAttribute('data-action') || genreCreateRoute;
                    document.getElementById('genre-name').value = button.getAttribute('data-name') || '';
                    document.getElementById('genre-slug').value = button.getAttribute('data-slug') || '';
                    document.getElementById('genre-description').value = button.getAttribute('data-description') || '';
                    document.getElementById('genre-display_flag').checked = button.getAttribute('data-display_flag') === '1';
                }
            }

            openModal(modalId);
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach((button) => {
        button.addEventListener('click', () => closeModal(button.getAttribute('data-close-modal')));
    });

    [gameModal, genreModal].forEach((modal) => {
        if (!modal) {
            return;
        }

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modal.id);
            }
        });
    });

    document.querySelectorAll('.media-file-input').forEach((input) => {
        input.addEventListener('change', () => {
            const targetId = input.getAttribute('data-preview-target');
            const target = targetId ? document.getElementById(targetId) : null;
            if (!target) {
                return;
            }

            target.innerHTML = '';
            Array.from(input.files || []).forEach((file) => {
                if (!file.type.startsWith('image/')) {
                    return;
                }

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = 'preview';
                target.appendChild(img);
            });
        });
    });
})();
