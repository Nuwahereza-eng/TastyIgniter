{{--
    Floating language switcher powered by Sunbird AI.

    Sits in the bottom-left as a small pill button. Clicking opens a menu of
    languages; selecting one calls /ajax/translate to translate all visible
    text on the page in batches, caches the result in localStorage so future
    visits are instant, and persists the choice in a cookie.

    No vendor template changes required — runs entirely client-side and works
    across every page (including vendor Livewire components).
--}}
<style>
    .uga-lang-fab {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 1040;
        background: #ffffff;
        color: #ff4900;
        border: 1px solid #ffe0d1;
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .uga-lang-fab:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.18); }
    .uga-lang-fab i { color: #ff4900; }
    .uga-lang-menu {
        position: fixed;
        bottom: 64px;
        left: 20px;
        z-index: 1041;
        background: #ffffff;
        border: 1px solid #eee;
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
        padding: 6px;
        min-width: 200px;
        display: none;
    }
    .uga-lang-menu.open { display: block; }
    .uga-lang-menu button {
        display: flex;
        align-items: center;
        width: 100%;
        background: transparent;
        border: none;
        text-align: left;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #333;
        cursor: pointer;
    }
    .uga-lang-menu button:hover { background: #fff5ef; color: #ff4900; }
    .uga-lang-menu button.active { background: #ff4900; color: white; }
    .uga-lang-menu button .check { margin-left: auto; opacity: 0; }
    .uga-lang-menu button.active .check { opacity: 1; }
    .uga-lang-toast {
        position: fixed;
        bottom: 80px;
        left: 20px;
        z-index: 1042;
        background: rgba(0,0,0,0.85);
        color: #fff;
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 999px;
        display: none;
    }
    .uga-lang-toast.show { display: inline-block; }

    /* Full-page curtain shown while the initial translation pass runs so
       the user doesn't see English flicker into the target language. */
    .uga-lang-curtain {
        position: fixed;
        inset: 0;
        z-index: 2147483646;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 14px;
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s ease;
        color: #ff4900;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .uga-lang-curtain.show { opacity: 1; pointer-events: all; }
    .uga-lang-curtain .uga-spin {
        width: 36px; height: 36px; border-radius: 50%;
        border: 3px solid #ffe0d1; border-top-color: #ff4900;
        animation: uga-spin 0.9s linear infinite;
    }
    @keyframes uga-spin { to { transform: rotate(360deg); } }
</style>

<button type="button" class="uga-lang-fab" id="ugaLangFab" aria-label="Change language">
    <i class="fa fa-language"></i>
    <span id="ugaLangLabel">English</span>
</button>
<div class="uga-lang-menu" id="ugaLangMenu" role="menu"></div>
<div class="uga-lang-toast" id="ugaLangToast"></div>
<div class="uga-lang-curtain" id="ugaLangCurtain" aria-hidden="true">
    <div class="uga-spin"></div>
    <div id="ugaLangCurtainText">Translating page…</div>
</div>

<script>
(function () {
    const LANGS = {
        eng: 'English',
        lug: 'Luganda',
        ach: 'Acholi',
        teo: 'Ateso',
        lgg: 'Lugbara',
        nyn: 'Runyankole',
    };
    const COOKIE = 'uga_lang';
    const STORAGE_PREFIX = 'uga_tx_';   // per-language localStorage cache
    const BATCH_SIZE = 30;              // strings per backend round-trip (server fans out in parallel)
    const MIN_LEN = 2;                  // skip very short text nodes
    const MAX_LEN = 1500;               // Sunbird ignores very long strings

    function getCookie(name) {
        return document.cookie.split('; ').reduce((r, v) => {
            const [k, ...rest] = v.split('=');
            return k === name ? decodeURIComponent(rest.join('=')) : r;
        }, '');
    }
    function setCookie(name, value, days) {
        const d = new Date(); d.setTime(d.getTime() + days * 86400000);
        document.cookie = `${name}=${encodeURIComponent(value)}; expires=${d.toUTCString()}; path=/; SameSite=Lax`;
    }

    function loadCache(target) {
        try { return JSON.parse(localStorage.getItem(STORAGE_PREFIX + target) || '{}'); }
        catch (e) { return {}; }
    }
    function saveCache(target, obj) {
        try { localStorage.setItem(STORAGE_PREFIX + target, JSON.stringify(obj)); } catch (e) {}
    }

    const fab = document.getElementById('ugaLangFab');
    const menu = document.getElementById('ugaLangMenu');
    const label = document.getElementById('ugaLangLabel');
    const toast = document.getElementById('ugaLangToast');
    const curtain = document.getElementById('ugaLangCurtain');
    const curtainText = document.getElementById('ugaLangCurtainText');

    function showCurtain(text) {
        if (!curtain) return;
        if (curtainText && text) curtainText.textContent = text;
        curtain.classList.add('show');
        // Safety: never block the page for more than 6 seconds. If Sunbird
        // is slow or down, we hide the curtain and let the user see whatever
        // we managed to translate (or fall back to English).
        clearTimeout(showCurtain._t);
        showCurtain._t = setTimeout(hideCurtain, 6000);
    }
    function hideCurtain() {
        if (curtain) curtain.classList.remove('show');
        clearTimeout(showCurtain._t);
    }

    // Per-language flag so we only fetch the static dictionary once per
    // browser session per target language.
    const dictLoaded = {};
    async function ensureDictionary(target) {
        if (target === 'eng' || dictLoaded[target]) return;
        dictLoaded[target] = true;
        try {
            const res = await fetch('/ajax/translate/dictionary/' + target, {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' },
            });
            if (!res.ok) return;
            const body = await res.json();
            const map = body.translations || {};
            if (!Object.keys(map).length) return;
            const cache = loadCache(target);
            // Dictionary entries only fill gaps; never overwrite a live
            // Sunbird translation already cached.
            for (const k of Object.keys(map)) {
                if (cache[k] === undefined || cache[k] === k) {
                    cache[k] = map[k];
                }
            }
            saveCache(target, cache);
        } catch (e) { /* offline-friendly */ }
    }

    let current = getCookie(COOKIE) || 'eng';
    if (!LANGS[current]) current = 'eng';

    function renderMenu() {
        menu.innerHTML = Object.entries(LANGS).map(([code, name]) => `
            <button type="button" data-code="${code}" class="${code === current ? 'active' : ''}" role="menuitem">
                <span>${name}</span>
                <span class="check"><i class="fa fa-check"></i></span>
            </button>
        `).join('');
        menu.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                menu.classList.remove('open');
                switchTo(btn.dataset.code);
            });
        });
    }
    renderMenu();
    label.textContent = LANGS[current];

    fab.addEventListener('click', (e) => {
        e.stopPropagation();
        menu.classList.toggle('open');
    });
    document.addEventListener('click', (e) => {
        if (!menu.contains(e.target) && e.target !== fab) menu.classList.remove('open');
    });

    function showToast(text) {
        toast.textContent = text;
        toast.classList.add('show');
        clearTimeout(showToast._t);
        showToast._t = setTimeout(() => toast.classList.remove('show'), 2200);
    }

    // Collect candidate text nodes from the visible page. We skip <script>,
    // <style>, our own switcher, code blocks, and elements explicitly marked
    // data-no-translate. Nodes that have already been translated for the
    // current language are skipped via the data-uga-tx attribute on the
    // parent element.
    const SKIP_TAGS = new Set(['SCRIPT', 'STYLE', 'CODE', 'PRE', 'TEXTAREA', 'INPUT', 'SELECT', 'OPTION', 'NOSCRIPT', 'IFRAME', 'SVG']);
    const MAX_NODES = 1200; // safety cap so massive pages can't tie up the main thread
    function collectNodes() {
        const nodes = [];
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
            acceptNode(node) {
                const p = node.parentElement;
                if (!p) return NodeFilter.FILTER_REJECT;
                if (SKIP_TAGS.has(p.tagName)) return NodeFilter.FILTER_REJECT;
                if (p.closest('[data-no-translate]')) return NodeFilter.FILTER_REJECT;
                if (p.closest('#ugaLangMenu, #ugaLangFab, #ugaLangToast')) return NodeFilter.FILTER_REJECT;
                const text = (node.nodeValue || '').trim();
                if (text.length < MIN_LEN || text.length > MAX_LEN) return NodeFilter.FILTER_REJECT;
                // skip pure numbers, prices, codes
                if (/^[\d\s.,:/\-#$€£¥%()+*]+$/.test(text)) return NodeFilter.FILTER_REJECT;
                return NodeFilter.FILTER_ACCEPT;
            }
        });
        let n;
        while ((n = walker.nextNode())) {
            nodes.push(n);
            if (nodes.length >= MAX_NODES) break;
        }
        return nodes;
    }

    async function fetchTranslations(texts, target) {
        const res = await fetch('/ajax/translate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ target, texts }),
        });
        if (!res.ok) {
            const body = await res.text();
            console.warn('[Sunbird] HTTP', res.status, body.slice(0, 200));
            throw new Error('translate-failed-' + res.status);
        }
        const body = await res.json();
        return body.translations || {};
    }

    /**
     * Apply translations. Two-phase:
     *   1. Synchronously swap any node we already have in cache (free, instant).
     *   2. Asynchronously fetch missing strings in parallel batches, then
     *      apply them as each batch returns. Never blocks page load.
     *
     * Accepts options:
     *   showCurtainIfMissing — when true, draws a white "Translating…" curtain
     *   over the page while phase 2 runs so the user never sees the English
     *   flicker. Used on the very first translation (cold cache).
     */
    function applyLanguage(target, opts = {}) {
        if (target === 'eng' || !LANGS[target]) return Promise.resolve();

        // Make sure the static UI dictionary is loaded once — this gives us
        // ~150 common UI strings instantly without any Sunbird call.
        return ensureDictionary(target).then(() => doApply(target, opts));
    }

    function doApply(target, opts = {}) {
        const cache = loadCache(target);

        // Self-heal: drop identity entries from past broken responses.
        let stripped = 0;
        for (const k of Object.keys(cache)) {
            if (cache[k] === k) { delete cache[k]; stripped++; }
        }
        if (stripped) saveCache(target, cache);

        const nodes = collectNodes();
        nodes.forEach(n => { if (!n.__ugaOriginal) n.__ugaOriginal = n.nodeValue; });

        // CRITICAL: reset every stamped node back to the original English
        // BEFORE applying the new language. Otherwise, when switching from
        // one non-English language to another, nodes whose translation isn't
        // cached yet would keep showing the PREVIOUS language's text.
        // Also catch nodes that were stamped on a prior pass but didn't make
        // it into this collectNodes() result.
        const allStamped = [];
        const stampWalker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        let sn;
        while ((sn = stampWalker.nextNode())) if (sn.__ugaOriginal) allStamped.push(sn);
        allStamped.forEach(n => { n.nodeValue = n.__ugaOriginal; });

        // Phase 1 — instant swap from cache.
        const need = [];
        const seen = new Set();
        nodes.forEach(n => {
            const txt = n.__ugaOriginal.trim();
            const translated = cache[txt];
            if (translated && translated !== txt) {
                const leading = n.__ugaOriginal.match(/^\s*/)[0];
                const trailing = n.__ugaOriginal.match(/\s*$/)[0];
                n.nodeValue = leading + translated + trailing;
                return;
            }
            if (translated !== undefined) return; // cached as-is, skip
            if (seen.has(txt)) return;
            seen.add(txt);
            need.push(txt);
        });

        if (need.length === 0) return Promise.resolve();

        // Show the curtain only when the caller wants it (first cold pass)
        // and we actually have work to do.
        const useCurtain = !!opts.showCurtainIfMissing;
        if (useCurtain) showCurtain('Translating to ' + LANGS[target] + '…');

        // Phase 2 — fetch missing in parallel, apply as each batch returns.
        const batches = [];
        for (let i = 0; i < need.length; i += BATCH_SIZE) {
            batches.push(need.slice(i, i + BATCH_SIZE));
        }
        const inflight = batches.map(batch =>
            fetchTranslations(batch, target)
                .then(map => {
                    Object.assign(cache, map);
                    // apply only the nodes that match this batch
                    const fresh = new Set(Object.keys(map));
                    nodes.forEach(n => {
                        const txt = n.__ugaOriginal.trim();
                        if (!fresh.has(txt)) return;
                        const translated = map[txt];
                        if (!translated || translated === txt) return;
                        const leading = n.__ugaOriginal.match(/^\s*/)[0];
                        const trailing = n.__ugaOriginal.match(/\s*$/)[0];
                        n.nodeValue = leading + translated + trailing;
                    });
                })
                .catch(e => console.warn('[Sunbird] batch failed', e))
        );

        // Persist cache once all batches settle, then drop the curtain.
        return Promise.allSettled(inflight).then(() => {
            saveCache(target, cache);
            if (useCurtain) hideCurtain();
        });
    }

    function restoreEnglish() {
        const nodes = collectNodes().concat([]);
        // Also walk for any node that already has __ugaOriginal stamped.
        const all = [];
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        let n;
        while ((n = walker.nextNode())) if (n.__ugaOriginal) all.push(n);
        all.forEach(n => { n.nodeValue = n.__ugaOriginal; });
    }

    async function switchTo(code) {
        if (!LANGS[code]) return;
        current = code;
        setCookie(COOKIE, code, 365);
        label.textContent = LANGS[code];
        renderMenu();

        if (code === 'eng') {
            restoreEnglish();
            hideCurtain();
            showToast('English');
            return;
        }

        // Manual switch — show the curtain so the page transitions cleanly
        // instead of flickering English-then-translated.
        try {
            await applyLanguage(code, { showCurtainIfMissing: true });
            showToast(LANGS[code]);
        } catch (e) {
            console.warn(e);
            hideCurtain();
            showToast('Translation unavailable');
        }
    }

    // Debounce repeated re-applies (Livewire often fires many morph events in
    // quick succession; we don't want to walk the DOM N times).
    let pending = null;
    // Defer work to idle time when possible so we never compete with the
    // page's critical render path.
    const schedule = (fn, ms = 300) => {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => setTimeout(fn, 0), { timeout: ms + 500 });
        } else {
            setTimeout(fn, ms);
        }
    };
    function scheduleApply(delay = 250, opts = {}) {
        if (current === 'eng') return;
        if (pending) clearTimeout(pending);
        pending = setTimeout(() => {
            pending = null;
            schedule(() => applyLanguage(current, opts).catch(() => {}));
        }, delay);
    }

    // Auto-apply saved preference on page load. Show the curtain on the
    // FIRST cold pass (when uncached strings need to be fetched), so users
    // never see English flicker into the chosen language. On subsequent
    // visits everything is cached and there's no curtain at all.
    if (current !== 'eng') {
        const initialApply = () => scheduleApply(30, { showCurtainIfMissing: true });
        if (document.readyState === 'complete') {
            initialApply();
        } else {
            window.addEventListener('load', initialApply, { once: true });
        }
    }

    // Re-translate when Livewire swaps DOM (debounced, no curtain).
    document.addEventListener('livewire:navigated', () => scheduleApply(200));
    if (window.Livewire && Livewire.hook) {
        Livewire.hook('message.processed', () => scheduleApply(200));
        Livewire.hook('morph.updated', () => scheduleApply(200));
    }
})();
</script>
