/**
 * IAMJOS - Alpine.js Comprehensive Setup
 *
 * Best Practice: Load Alpine via npm (bundled by Vite), NOT via CDN.
 * This ensures a single instance, proper plugin registration,
 * and compatibility with Livewire 3.
 *
 * Livewire 3 does NOT auto-inject Alpine anymore in all cases.
 * We explicitly control Alpine here for full predictability.
 */

import './bootstrap';

import Alpine from 'alpinejs';

// ─── Plugins ──────────────────────────────────────────────────────────────────

/**
 * @alpinejs/collapse
 * Enables x-collapse directive for smooth height animations.
 * Used by: sidebar menus, accordion panels, submission details.
 */
import Collapse from '@alpinejs/collapse';

/**
 * @alpinejs/persist
 * Enables $persist magic helper for localStorage persistence.
 * Used by: sidebarCollapsed state, user preferences.
 */
import Persist from '@alpinejs/persist';

/**
 * @alpinejs/focus
 * Enables x-trap directive for focus management in modals/dialogs.
 * Used by: modal dialogs, dropdowns.
 */
import Focus from '@alpinejs/focus';

/**
 * @alpinejs/intersect
 * Enables x-intersect directive for scroll-based interactions.
 * Used by: lazy loading, animations on scroll.
 */
import Intersect from '@alpinejs/intersect';

// ─── Register Plugins ─────────────────────────────────────────────────────────

Alpine.plugin(Collapse);
Alpine.plugin(Persist);
Alpine.plugin(Focus);
Alpine.plugin(Intersect);

// ─── Global Stores ────────────────────────────────────────────────────────────

/**
 * Global notification store.
 * Components can access via: $store.notifications
 */
Alpine.store('notifications', {
    count: 0,
    items: [],
    setCount(n) { this.count = n; },
    setItems(items) { this.items = items; },
});

/**
 * Global sidebar state — persisted in localStorage.
 * Components can access via: $store.sidebar
 */
Alpine.store('sidebar', {
    collapsed: Alpine.$persist(false).as('sidebar_collapsed'),
    open: false,
    toggle() { this.collapsed = !this.collapsed; },
    openMobile() { this.open = true; },
    closeMobile() { this.open = false; },
});

// ─── Global Magic Helpers ─────────────────────────────────────────────────────

/**
 * $formatDate magic helper.
 * Usage: <span x-text="$formatDate(date)"></span>
 */
Alpine.magic('formatDate', () => (dateStr, locale = 'id-ID') => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString(locale, {
        year: 'numeric', month: 'long', day: 'numeric',
    });
});

/**
 * $toast magic helper — lightweight toast notification.
 * Usage: <button @click="$toast('Saved!', 'success')">Save</button>
 */
Alpine.magic('toast', () => (message, type = 'success') => {
    const event = new CustomEvent('toast', { detail: { message, type } });
    window.dispatchEvent(event);
});

// ─── Start Alpine ─────────────────────────────────────────────────────────────

/**
 * IMPORTANT: When using Livewire 3, Alpine is injected by Livewire itself.
 * We expose Alpine to window so Livewire can detect and reuse our instance.
 * This prevents the "multiple instances" warning.
 *
 * We do NOT call Alpine.start() here because Livewire will handle it.
 */
window.Alpine = Alpine;
// Alpine.start(); <--- Removed for Livewire 3 compatibility

