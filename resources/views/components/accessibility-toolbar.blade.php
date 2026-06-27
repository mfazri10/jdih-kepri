{{-- ═══ ACCESSIBILITY TOOLBAR (WCAG 2.1) ═══ --}}
<div id="a11y-toolbar" class="a11y-toolbar">
    <button id="a11y-toggle" class="a11y-toggle-btn" title="Aksesibilitas" aria-label="Buka toolbar aksesibilitas">
        <i class="bi bi-universal-access-circle"></i>
    </button>

    <div id="a11y-panel" class="a11y-panel d-none">
        <div class="a11y-panel-header">
            <h6 class="mb-0 fw-bold"><i class="bi bi-universal-access-circle me-2"></i>Aksesibilitas</h6>
            <button id="a11y-close" class="btn-close btn-close-sm" aria-label="Tutup"></button>
        </div>

        <div class="a11y-panel-body">
            {{-- Ukuran Teks --}}
            <div class="a11y-section">
                <label class="a11y-label">Ukuran Teks</label>
                <div class="a11y-btn-group">
                    <button class="a11y-btn" data-action="font-decrease" title="Perkecil">
                        <i class="bi bi-dash-lg"></i> A-
                    </button>
                    <button class="a11y-btn" data-action="font-reset" title="Normal">
                        A
                    </button>
                    <button class="a11y-btn" data-action="font-increase" title="Perbesar">
                        <i class="bi bi-plus-lg"></i> A+
                    </button>
                </div>
            </div>

            {{-- Kontras --}}
            <div class="a11y-section">
                <label class="a11y-label">Kontras</label>
                <div class="a11y-btn-group">
                    <button class="a11y-btn" data-action="contrast-normal" title="Normal">
                        <i class="bi bi-circle-half"></i> Normal
                    </button>
                    <button class="a11y-btn" data-action="contrast-high" title="Kontras Tinggi">
                        <i class="bi bi-circle-fill"></i> Tinggi
                    </button>
                </div>
            </div>

            {{-- Grayscale --}}
            <div class="a11y-section">
                <label class="a11y-label">Warna</label>
                <div class="a11y-btn-group">
                    <button class="a11y-btn" data-action="grayscale-off" title="Warna Normal">
                        <i class="bi bi-palette"></i> Warna
                    </button>
                    <button class="a11y-btn" data-action="grayscale-on" title="Abu-abu">
                        <i class="bi bi-circle"></i> Grayscale
                    </button>
                </div>
            </div>

            {{-- Link Highlight --}}
            <div class="a11y-section">
                <label class="a11y-label">Navigasi</label>
                <div class="a11y-btn-group">
                    <button class="a11y-btn" data-action="links-highlight" title="Sorot Link">
                        <i class="bi bi-link-45deg"></i> Sorot Link
                    </button>
                    <button class="a11y-btn" data-action="cursor-big" title="Kursor Besar">
                        <i class="bi bi-mouse"></i> Kursor
                    </button>
                </div>
            </div>

            {{-- Reset --}}
            <div class="a11y-section">
                <button class="a11y-btn a11y-reset-btn w-100" data-action="reset" title="Reset Semua">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Semua
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .a11y-toolbar {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .a11y-toggle-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: none;
        background: #1e3a5f;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .a11y-toggle-btn:hover {
        background: #2563eb;
        transform: scale(1.1);
    }

    .a11y-panel {
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 280px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .a11y-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: #f8f9fa;
        border-bottom: 1px solid #e5e7eb;
    }

    .a11y-panel-body {
        padding: 12px;
    }

    .a11y-section {
        margin-bottom: 12px;
    }

    .a11y-section:last-child {
        margin-bottom: 0;
    }

    .a11y-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .a11y-btn-group {
        display: flex;
        gap: 4px;
    }

    .a11y-btn {
        flex: 1;
        padding: 8px 10px;
        border: 1px solid #d1d5db;
        background: white;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #374151;
        text-align: center;
    }

    .a11y-btn:hover {
        background: #f3f4f6;
        border-color: #2563eb;
        color: #2563eb;
    }

    .a11y-btn.active {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
    }

    .a11y-reset-btn {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }

    .a11y-reset-btn:hover {
        background: #fee2e2;
        border-color: #f87171;
    }

    /* High Contrast Mode */
    body.a11y-high-contrast {
        background: #000 !important;
        color: #fff !important;
    }

    body.a11y-high-contrast a {
        color: #ffff00 !important;
    }

    body.a11y-high-contrast .card,
    body.a11y-high-contrast .navbar,
    body.a11y-high-contrast .footer {
        background: #000 !important;
        border: 2px solid #fff !important;
    }

    body.a11y-high-contrast .btn-primary {
        background: #ffff00 !important;
        color: #000 !important;
    }

    /* Grayscale Mode */
    body.a11y-grayscale {
        filter: grayscale(100%);
    }

    /* Link Highlight */
    body.a11y-links-highlight a {
        text-decoration: underline !important;
        text-decoration-thickness: 3px !important;
        text-underline-offset: 3px !important;
    }

    /* Big Cursor */
    body.a11y-big-cursor,
    body.a11y-big-cursor * {
        cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Cpath d='M4 0 L4 28 L12 20 L20 28 Z' fill='black' stroke='white' stroke-width='2'/%3E%3C/svg%3E") 4 0, auto !important;
    }

    /* Focus outline for keyboard nav */
    body.a11y-links-highlight a:focus,
    body.a11y-links-highlight button:focus,
    body.a11y-links-highlight input:focus,
    body.a11y-links-highlight select:focus {
        outline: 3px solid #ffff00 !important;
        outline-offset: 2px !important;
    }
</style>

<script>
(function() {
    const STORAGE_KEY = 'jdih_a11y';
    const body = document.body;
    const panel = document.getElementById('a11y-panel');
    const toggle = document.getElementById('a11y-toggle');
    const close = document.getElementById('a11y-close');

    // Load saved preferences
    const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
    let fontSize = saved.fontSize || 16;
    let highContrast = saved.highContrast || false;
    let grayscale = saved.grayscale || false;
    let linksHighlight = saved.linksHighlight || false;
    let bigCursor = saved.bigCursor || false;

    function apply() {
        document.documentElement.style.fontSize = fontSize + 'px';

        body.classList.toggle('a11y-high-contrast', highContrast);
        body.classList.toggle('a11y-grayscale', grayscale);
        body.classList.toggle('a11y-links-highlight', linksHighlight);
        body.classList.toggle('a11y-big-cursor', bigCursor);

        // Update active states
        document.querySelectorAll('.a11y-btn').forEach(btn => btn.classList.remove('active'));

        if (fontSize > 16) document.querySelector('[data-action="font-increase"]')?.classList.add('active');
        if (fontSize < 16) document.querySelector('[data-action="font-decrease"]')?.classList.add('active');
        if (fontSize === 16) document.querySelector('[data-action="font-reset"]')?.classList.add('active');
        if (highContrast) document.querySelector('[data-action="contrast-high"]')?.classList.add('active');
        else document.querySelector('[data-action="contrast-normal"]')?.classList.add('active');
        if (grayscale) document.querySelector('[data-action="grayscale-on"]')?.classList.add('active');
        else document.querySelector('[data-action="grayscale-off"]')?.classList.add('active');
        if (linksHighlight) document.querySelector('[data-action="links-highlight"]')?.classList.add('active');
        if (bigCursor) document.querySelector('[data-action="cursor-big"]')?.classList.add('active');

        localStorage.setItem(STORAGE_KEY, JSON.stringify({
            fontSize, highContrast, grayscale, linksHighlight, bigCursor
        }));
    }

    // Toggle panel
    toggle.addEventListener('click', () => panel.classList.toggle('d-none'));
    close.addEventListener('click', () => panel.classList.add('d-none'));

    // Button actions
    document.querySelectorAll('.a11y-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;

            switch(action) {
                case 'font-decrease': fontSize = Math.max(12, fontSize - 2); break;
                case 'font-reset': fontSize = 16; break;
                case 'font-increase': fontSize = Math.min(24, fontSize + 2); break;
                case 'contrast-normal': highContrast = false; break;
                case 'contrast-high': highContrast = true; break;
                case 'grayscale-off': grayscale = false; break;
                case 'grayscale-on': grayscale = true; break;
                case 'links-highlight': linksHighlight = !linksHighlight; break;
                case 'cursor-big': bigCursor = !bigCursor; break;
                case 'reset':
                    fontSize = 16;
                    highContrast = false;
                    grayscale = false;
                    linksHighlight = false;
                    bigCursor = false;
                    break;
            }

            apply();
        });
    });

    // Apply on load
    apply();
})();
</script>
