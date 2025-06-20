import './bootstrap';
import Alpine from 'alpinejs'

Alpine.data('eqsearch', (initialQuery = '') => ({
    query: initialQuery,
    results: [],
    loading: false,

    async load() {
        if (this.query.length < 2) {
            this.results = [];
            this.loading = false;
            return;
        }

        this.loading = true;

        try {
            const res = await fetch(`/search/suggest?q=${encodeURIComponent(this.query)}`);
            const data = await res.json();
            this.results = data;
        } catch (e) {
            //console.error('error loading search results:', e);
        } finally {
            this.loading = false;
        }
    }
}));

Alpine.data('itemDrops', (itemId) => ({
    itemId,
    loading: true,
    drops: [],
    async load() {
        this.loading = true;
        try {
            const res = await fetch(`/items/drops_by_zone/${this.itemId}`);
            const data = await res.json();
            this.drops = data;
        } catch (e) {
            console.error('error loading npc droppers:', e);
        } finally {
            this.loading = false;
        }
    }
}));

Alpine.store('itemSearch', {
    open: false,
    toggle() {
        this.open = !this.open;
        localStorage.setItem('item_search', this.open);
    },
    init() {
        this.open = localStorage.getItem('item_search') === 'true';
    }
});

Alpine.store('tooltip', {
    content: '',
    visible: false,
    cache: new Map(),
    tooltipEl: null,

    async loadTooltip(url, triggerEl, event) {
        if (!triggerEl) return;
        if (event && event.preventDefault) event.preventDefault();

        this.loadingUrl = url;
        this.tooltipEl = document.getElementById('global-tooltip');

        const effectsOnly = triggerEl.dataset.effectsOnly === '1';
        if (effectsOnly) {
            url += '?effects-only=1';
        }

        if (this.cache.has(url)) {
            this.content = this.cache.get(url);
            this.loadingUrl = null;
        } else {
            try {
                const response = await fetch(url);
                const data = await response.json();
                this.cache.set(url, data.html);
                this.content = data.html;
            } catch (err) {
                this.content = '<div class="text-error">Failed to load tooltip.</div>';
            }
            this.loadingUrl = null;
        }

        this.visible = true;

        requestAnimationFrame(() => {
            this.positionTooltip(event, triggerEl);
        });
    },

    hideTooltip() {
        this.visible = false;
    },

    positionTooltip(e, triggerEl) {
        const tooltip = this.tooltipEl;
        if (!tooltip || !triggerEl) return;

        tooltip.style.visibility = 'hidden';
        tooltip.style.display = 'block';

        const tooltipHeight = tooltip.offsetHeight;
        const tooltipWidth = tooltip.offsetWidth;
        const rect = triggerEl.getBoundingClientRect();
        const scrollX = window.scrollX;
        const scrollY = window.scrollY;

        let top = rect.top + rect.height / 2 - tooltipHeight / 2 + scrollY;
        let left;

        const spaceRight = window.innerWidth - (rect.right + 10);
        const spaceLeft = rect.left - 10;

        if (spaceRight >= tooltipWidth) {
            left = rect.right + 10 + scrollX;
        } else if (spaceLeft >= tooltipWidth) {
            left = rect.left - tooltipWidth - 10 + scrollX;
        } else {
            left = scrollX + rect.left + (rect.width / 2) - (tooltipWidth / 2);
        }

        // Vertical bounds
        const maxBottom = scrollY + window.innerHeight - 10;
        if (top + tooltipHeight > maxBottom) {
            top = maxBottom - tooltipHeight;
        }
        if (top < scrollY + 10) {
            top = scrollY + 10;
        }

        tooltip.style.left = `${left}px`;
        tooltip.style.top = `${top}px`;
        tooltip.style.visibility = 'visible';
    }
});

window.Alpine = Alpine
Alpine.start()

document.addEventListener("DOMContentLoaded", function () {
    const logo = document.getElementById('eqemu-desktop-logo');
    const navbartrigger = document.getElementById('navbar-trigger');

    const observer = new IntersectionObserver(([entry]) => {
        if (entry.intersectionRatio === 0) {
            logo.classList.add('stuck');
        } else {
            logo.classList.remove('stuck');
        }
    });

    observer.observe(navbartrigger);

    // faction select
    const select = document.getElementById('select-faction');
    if (select) {
        select.addEventListener('change', (e) => {
            const value = e.target.value;
            if (value) {
                window.location.href = `/factions/${value}`;
            }
        });
    }

    // pet class select
    const petSelect = document.getElementById('select-pet-class');
    if (petSelect) {
        petSelect.addEventListener('change', (e) => {
            const value = e.target.value;
            if (value) {
                window.location.href = `/pets/${value}`;
            }
        });
    }

    // populate task reward column with something at least
    const rewardCol = document.querySelectorAll('td.task-rewards');
    rewardCol.forEach(function (reward) {
        if (reward.textContent.trim() === '') {
            reward.textContent = '-';
        }
    });
});

document.body.addEventListener('click', () => {
    Alpine.store('tooltip').hideTooltip();
});
