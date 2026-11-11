/**
 * scripts/script_navbar.js
 * 1. Gerenciamento do menu dropdown de perfil do usuário.
 * 2. Alternador de Tema (Modo Claro / Modo Escuro) sincronizado com <meta name="color-scheme">.
 * 3. Detecção silenciosa e automática de desempenho do navegador e rede (CPU, RAM, 2G/3G, GPU e FPS),
 *    permitindo que as páginas desativem automaticamente recursos pesados em computadores mais fracos.
 */
(function () {
    const root = document.documentElement;

    function ensureColorSchemeMeta() {
        let meta = document.querySelector('meta[name="color-scheme"]');
        if (!meta) {
            meta = document.createElement('meta');
            meta.name = 'color-scheme';
            document.head.appendChild(meta);
        }
        return meta;
    }

    function safeGetStorage(key) {
        try {
            return localStorage.getItem(key);
        } catch (_) {
            return null;
        }
    }

    function safeSetStorage(key, val) {
        try {
            if (val === null) {
                localStorage.removeItem(key);
            } else {
                localStorage.setItem(key, val);
            }
        } catch (_) {}
    }

    /* ======================================================================
       GERENCIADOR DE TEMA (MODO CLARO / MODO ESCURO — ÁREA LOGADA)
       ====================================================================== */
    function getResolvedTheme(schemeValue) {
        if (schemeValue === 'light' || schemeValue === 'dark') {
            return schemeValue;
        }
        const sysLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
        return sysLight ? 'light' : 'dark';
    }

    function updateThemeUI(resolvedTheme) {
        const labelEl = document.getElementById('theme-toggle-label');
        const menuTextEl = document.getElementById('menu-theme-text');
        const menuIconEl = document.getElementById('menu-theme-icon');
        const btnToggle = document.getElementById('btn-theme-toggle');

        const isLight = resolvedTheme === 'light';
        if (labelEl) {
            labelEl.textContent = isLight ? 'Modo Escuro' : 'Modo Claro';
        }
        if (menuTextEl) {
            menuTextEl.textContent = isLight ? 'Ativar Modo Escuro' : 'Ativar Modo Claro';
        }
        if (menuIconEl) {
            menuIconEl.className = isLight ? 'bi bi-moon-stars-fill me-2 text-cyan' : 'bi bi-sun-fill me-2 text-cyan';
        }
        if (btnToggle) {
            btnToggle.setAttribute('aria-pressed', isLight ? 'true' : 'false');
            btnToggle.title = isLight ? 'Alternar para Modo Escuro' : 'Alternar para Modo Claro';
        }
    }

    function applyTheme(schemeValue, persist = true) {
        const meta = ensureColorSchemeMeta();
        const validScheme = (schemeValue === 'light' || schemeValue === 'dark' || schemeValue === 'light dark')
            ? schemeValue
            : 'dark';

        meta.content = validScheme;
        const resolved = getResolvedTheme(validScheme);
        root.setAttribute('data-theme', resolved);

        if (persist) {
            safeSetStorage('color-scheme', validScheme);
        }

        updateThemeUI(resolved);
        window.dispatchEvent(new CustomEvent('trackflow:themechange', { detail: { theme: resolved } }));
    }

    function toggleTheme() {
        const current = root.getAttribute('data-theme') || 'dark';
        const next = current === 'light' ? 'dark' : 'light';
        applyTheme(next, true);
    }

    window.TrackFlowTheme = {
        applyTheme,
        toggleTheme,
        isLight: () => (root.getAttribute('data-theme') === 'light')
    };

    /* ======================================================================
       DETECÇÃO AUTOMÁTICA E SILENCIOSA DE DESEMPENHO (PC FRACO / REDE 3G)
       ====================================================================== */
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;

    function isSlowNetwork() {
        if (!conn) return false;
        const eff = String(conn.effectiveType || '');
        return Boolean(
            conn.saveData ||
            eff === 'slow-2g' ||
            eff === '2g' ||
            eff === '3g' ||
            (typeof conn.downlink === 'number' && conn.downlink > 0 && conn.downlink < 2.5) ||
            (typeof conn.rtt === 'number' && conn.rtt > 200)
        );
    }

    const perfState = {
        cores: navigator.hardwareConcurrency || 4,
        memory: navigator.deviceMemory || null,
        slowNet: isSlowNetwork(),
        reducedMotion: Boolean(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches),
        gpuWeak: false,
        opsPerMs: 50,
        fps: 60,
        tier: root.getAttribute('data-perf') || 'high'
    };

    function evaluateAutoPerf() {
        let isLow = false;
        if (perfState.reducedMotion || perfState.slowNet || perfState.gpuWeak) {
            isLow = true;
        } else if (perfState.cores <= 4 || (perfState.memory !== null && perfState.memory <= 4)) {
            isLow = true;
        } else if (perfState.opsPerMs < 20 || perfState.fps < 40) {
            isLow = true;
        }

        const nextTier = isLow ? 'low' : 'high';
        if (perfState.tier !== nextTier || root.getAttribute('data-perf') !== nextTier) {
            perfState.tier = nextTier;
            root.setAttribute('data-perf', nextTier);
            window.dispatchEvent(new CustomEvent('trackflow:perfchange', { detail: { tier: nextTier } }));
        }
    }

    function runSilentHardwareChecks() {
        // 1. Checagem rápida de CPU (~2.5ms)
        try {
            const t0 = performance.now();
            let it = 0;
            let acc = 1.0;
            while ((performance.now() - t0) < 2.5) {
                for (let i = 0; i < 500; i++) {
                    acc = (acc * 1.000001 + Math.sqrt(i + 1)) % 10000;
                }
                it += 500;
            }
            const dt = Math.max(0.5, performance.now() - t0);
            perfState.opsPerMs = Math.round((it / dt) / 1000);
            if (acc < 0) console.log(acc);
        } catch (_) {}

        // 2. Checagem de GPU por software
        try {
            const canvas = document.createElement('canvas');
            const gl = canvas.getContext('webgl', { powerPreference: 'low-power' });
            if (!gl) {
                perfState.gpuWeak = true;
            } else {
                const dbg = gl.getExtension('WEBGL_debug_renderer_info');
                if (dbg) {
                    const renderer = String(gl.getParameter(dbg.UNMASKED_RENDERER_WEBGL) || '');
                    if (/swiftshader|llvmpipe|basic render|software|microsoft basic|gma|hd graphics (2000|3000|4000)/i.test(renderer)) {
                        perfState.gpuWeak = true;
                    }
                }
            }
        } catch (_) {}

        evaluateAutoPerf();

        // 3. Monitoramento passivo de FPS (30 quadros)
        if (window.requestAnimationFrame && perfState.tier !== 'low') {
            let frames = 0;
            let start = 0;
            const checkFps = (ts) => {
                if (document.hidden) return;
                if (!start) start = ts;
                frames++;
                if (frames < 30) {
                    requestAnimationFrame(checkFps);
                } else {
                    const elapsed = Math.max(1, ts - start);
                    perfState.fps = Math.min(60, Math.round((frames * 1000) / elapsed));
                    evaluateAutoPerf();
                }
            };
            requestAnimationFrame(checkFps);
        }
    }

    if (conn && typeof conn.addEventListener === 'function') {
        conn.addEventListener('change', () => {
            perfState.slowNet = isSlowNetwork();
            evaluateAutoPerf();
        });
    }

    window.TrackFlowPerf = {
        isLowEnd: () => (root.getAttribute('data-perf') === 'low')
    };

    /* ======================================================================
       INICIALIZAÇÃO DOM & EVENTOS
       ====================================================================== */
    document.addEventListener('DOMContentLoaded', () => {
        const storedScheme = safeGetStorage('color-scheme') || 'dark';
        applyTheme(storedScheme, false);

        // Executa diagnóstico silencioso em idle time sem bloquear a renderização
        const runIdle = window.requestIdleCallback || ((cb) => setTimeout(cb, 60));
        runIdle(runSilentHardwareChecks);

        const btnTheme = document.getElementById('btn-theme-toggle');
        const menuThemeAction = document.getElementById('menu-theme-action');
        if (btnTheme) btnTheme.addEventListener('click', toggleTheme);
        if (menuThemeAction) menuThemeAction.addEventListener('click', toggleTheme);

        window.addEventListener('storage', (e) => {
            if (e.key === 'color-scheme') {
                applyTheme(e.newValue || 'dark', false);
            }
        });

        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const currentMeta = ensureColorSchemeMeta().content;
                if (currentMeta === 'light dark') {
                    applyTheme('light dark', false);
                }
            });
        }

        const perfil = document.querySelector('.menu-nav .perfil');
        const menu = document.getElementById('menu_user') || document.querySelector('.menu-nav .menu');

        if (perfil && menu) {
            perfil.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('ativo');
                const expandido = menu.classList.contains('ativo');
                perfil.setAttribute('aria-expanded', expandido ? 'true' : 'false');
            });

            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && !perfil.contains(e.target)) {
                    menu.classList.remove('ativo');
                    perfil.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && menu.classList.contains('ativo')) {
                    menu.classList.remove('ativo');
                    perfil.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
})();
