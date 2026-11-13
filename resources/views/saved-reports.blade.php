@extends('layouts.app')

@section('title', 'Barang Disimpan — UMBFind')

@section('header')
<header class="saved-header">
    <div class="saved-header-left">
        <button type="button" class="btn-header-back" onclick="handleSavedBack()" aria-label="Kembali">
            <i data-lucide="chevron-left"></i>
        </button>
        <div class="saved-title-group">
            <h1 class="saved-header-title">Barang Disimpan</h1>
            <span class="header-count-badge" id="header-saved-count">3</span>
        </div>
    </div>
</header>
@endsection

@push('styles')
<style>
    /* Saved Reports Container */
    .saved-container {
        padding: 0.9rem 1.25rem 6.5rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }

    /* Top Sticky Header */
    .saved-header {
        padding: 0.85rem 1.15rem;
        display: flex;
        align-items: center;
        background: #ffffff;
        border-bottom: 1px solid var(--border-subtle);
        position: sticky;
        top: 0;
        z-index: 40;
    }

    .saved-header-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-header-back {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        cursor: pointer;
        transition: var(--transition);
        margin-left: -4px;
    }

    .btn-header-back:hover {
        background: #f1f5f9;
    }

    .btn-header-back svg, .btn-header-back .lucide {
        width: 22px;
        height: 22px;
        stroke-width: 2.2;
    }

    .saved-title-group {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .saved-header-title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.01em;
    }

    .header-count-badge {
        font-family: var(--font-main);
        font-size: 0.72rem;
        font-weight: 700;
        color: #d97706;
        background: #fef3c7;
        padding: 0.15rem 0.55rem;
        border-radius: 9999px;
    }

    /* Segment Filter Tabs */
    .filter-segment-wrap {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 14px;
        gap: 4px;
        overflow-x: auto;
    }

    .filter-segment-pill {
        flex: 1;
        min-width: fit-content;
        text-align: center;
        padding: 0.52rem 0.85rem;
        border-radius: 10px;
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }

    .filter-segment-pill.active {
        background: #ffffff;
        color: var(--primary);
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.08);
    }

    /* Saved List Grid */
    .saved-reports-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    /* Card Item - UMBFind standard compact layout */
    .saved-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.055);
        border-radius: var(--radius-md);
        padding: 0.65rem 0.75rem;
        display: flex;
        gap: 0.8rem;
        align-items: flex-start;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
        cursor: pointer;
        position: relative;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }

    .saved-card:active {
        transform: scale(0.99);
    }

    .saved-card-thumb {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        background: #f1f5f9;
        flex-shrink: 0;
        align-self: flex-start;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .saved-card-content {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 72px;
        height: auto;
        gap: 0.25rem;
        padding: 0.05rem 0;
    }

    .saved-card-title {
        font-family: var(--font-main);
        font-size: 0.925rem;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
        margin: 0;
    }

    .saved-card-location {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 400;
        color: #64748b;
        min-width: 0;
        width: 100%;
        line-height: 1.35;
    }

    .saved-card-location span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
        flex: 1;
        line-height: 1.35;
    }

    .saved-card-location svg, .saved-card-location .lucide {
        width: 13.5px;
        height: 13.5px;
        color: #1572b8;
        flex-shrink: 0;
        stroke-width: 2.2;
    }

    .saved-card-date {
        font-family: var(--font-main);
        font-size: 0.75rem;
        font-weight: 400;
        color: #64748b;
    }

    .saved-card-aside {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        min-height: 72px;
        height: auto;
        align-self: stretch;
        flex-shrink: 0;
        padding: 0.05rem 0;
    }

    /* Badges */
    .badge-status {
        padding: 0.22rem 0.65rem;
        border-radius: 8px;
        font-family: var(--font-main);
        font-size: 0.725rem;
        font-weight: 500;
        letter-spacing: 0;
        line-height: 1.1;
    }

    .badge-lost {
        background-color: #fee2e2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }

    .badge-found {
        background-color: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .card-actions-row {
        display: flex;
        align-items: center;
    }

    .btn-saved-bookmark {
        background: transparent;
        border: none;
        color: #d97706;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: var(--transition);
        margin: -4px -4px 0 0;
    }

    .btn-saved-bookmark:hover {
        background: #fef3c7;
    }

    .btn-saved-bookmark svg, .btn-saved-bookmark .lucide {
        width: 17px;
        height: 17px;
        stroke-width: 2.1;
        fill: #d97706;
        stroke: #d97706;
    }

    /* Modal Pill Badges */
    .saved-type-pill {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 0.15rem 0.55rem;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .saved-type-pill.found {
        background: #dcfce7;
        color: #16a34a;
    }

    .saved-type-pill.lost {
        background: #fee2e2;
        color: #ef4444;
    }

    .saved-category-pill {
        font-size: 0.7rem;
        font-weight: 500;
        color: #64748b;
        background: #f1f5f9;
        padding: 0.15rem 0.5rem;
        border-radius: 6px;
    }

    /* Empty State */
    .saved-empty-state {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 3.5rem 1.5rem;
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        margin-top: 1rem;
    }

    .saved-empty-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #fef3c7;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .saved-empty-icon svg, .saved-empty-icon .lucide {
        width: 28px;
        height: 28px;
    }

    .saved-empty-title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .saved-empty-desc {
        font-size: 0.825rem;
        color: #64748b;
        max-width: 320px;
        margin-bottom: 1.25rem;
        line-height: 1.45;
    }

    .btn-explore-reports {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.35rem;
        border-radius: 10px;
        background: var(--primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.2);
    }

    .btn-explore-reports:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    /* Modal Backdrop System */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 150;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .modal-backdrop.active {
        display: flex !important;
    }

    /* Floating Toast Popup */
    .toast-popup {
        position: fixed;
        bottom: 95px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #0f172a;
        color: #ffffff;
        padding: 0.65rem 1.35rem;
        border-radius: var(--radius-pill);
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 500;
        z-index: 120;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.25);
        white-space: nowrap;
        text-align: center;
    }

    .toast-popup.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    /* Desktop Viewport (min-width: 1024px) */
    @media (min-width: 1024px) {
        .saved-header {
            padding: 0.95rem clamp(1.75rem, 4vw, 4rem);
        }

        .saved-container {
            max-width: 1380px;
            margin: 0 auto;
            padding: 2.25rem clamp(1.75rem, 4vw, 4rem) 4rem clamp(1.75rem, 4vw, 4rem);
            width: 100%;
            box-sizing: border-box;
            gap: 1.35rem;
        }

        .saved-reports-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .saved-card {
            padding: 0.85rem 1rem;
            border-radius: 16px;
        }

        .saved-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        /* Desktop Floating Detail Modal (Jendela Mengambang) */
        .desktop-detail-modal-backdrop {
            z-index: 150;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            align-items: center !important;
            justify-content: center !important;
            padding: 1.5rem;
        }

        .desktop-detail-modal-backdrop.active {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .desktop-detail-card {
            background: #ffffff;
            border-radius: 24px;
            width: 100%;
            max-width: 560px;
            max-height: 86vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(15, 23, 42, 0.06);
            transform: scale(0.95);
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .desktop-detail-modal-backdrop.active .desktop-detail-card {
            transform: scale(1);
        }

        .desktop-detail-hero-box {
            position: relative;
            width: 100%;
            height: 220px;
            background: #f1f5f9;
            flex-shrink: 0;
            overflow: hidden;
            cursor: pointer;
        }

        .desktop-detail-hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .desktop-detail-hero-box:hover .desktop-detail-hero-img {
            transform: scale(1.03);
        }

        .desktop-detail-zoom-pill {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(6px);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            pointer-events: none;
        }

        .desktop-detail-floating-header {
            position: absolute;
            top: 14px;
            left: 14px;
            right: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
        }

        .desktop-detail-circle-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(15, 23, 42, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e293b;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
            transition: var(--transition);
        }

        .desktop-detail-circle-btn:hover {
            background: #ffffff;
            transform: scale(1.08);
        }

        .desktop-detail-scroll-area {
            padding: 1.35rem 1.65rem 1.5rem 1.65rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.15rem;
            flex: 1;
        }

        .desktop-detail-title {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
        }

        .desktop-detail-meta-list {
            display: flex;
            flex-direction: column;
            border-top: 1px solid #f1f5f9;
        }

        .desktop-detail-meta-row {
            display: flex;
            align-items: flex-start;
            padding: 0.65rem 0;
            border-bottom: 1px solid #f8fafc;
            font-size: 0.835rem;
        }

        .desktop-detail-meta-label {
            width: 140px;
            flex-shrink: 0;
            color: #64748b;
            font-weight: 500;
        }

        .desktop-detail-meta-val {
            color: #0f172a;
            font-weight: 600;
            flex: 1;
        }

        .desktop-detail-desc-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.815rem;
            color: #334155;
            line-height: 1.5;
        }

        .desktop-detail-actions-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-top: 0.5rem;
        }

        .btn-modal-action-chat {
            flex: 1;
            padding: 0.7rem 1.25rem;
            border-radius: 10px;
            border: none;
            background: var(--primary);
            color: #ffffff;
            font-family: var(--font-main);
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-modal-action-chat:hover {
            background: var(--primary-dark);
        }

        .btn-modal-action-remove {
            padding: 0.7rem 1rem;
            border-radius: 10px;
            border: 1px solid #fed7aa;
            background: #fffbeb;
            color: #d97706;
            font-family: var(--font-main);
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-modal-action-remove:hover {
            background: #fef3c7;
        }
    }

    @media (max-width: 1023px) {
        .desktop-detail-modal-backdrop {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="saved-container">

    <!-- 1. Filter Segment Tabs (Semua, Temuan, Hilang) -->
    <div class="filter-segment-wrap" role="tablist">
        <button type="button" class="filter-segment-pill active" onclick="setSavedFilter('all', this)">Semua</button>
        <button type="button" class="filter-segment-pill" onclick="setSavedFilter('found', this)">Temuan</button>
        <button type="button" class="filter-segment-pill" onclick="setSavedFilter('lost', this)">Hilang</button>
    </div>

    <!-- 2. List of Saved Reports -->
    <div class="saved-reports-list" id="saved-reports-list">
        <!-- Injected via JavaScript from active saved items -->
    </div>

    <!-- 3. Empty State -->
    <div class="saved-empty-state" id="saved-empty-state">
        <div class="saved-empty-icon">
            <i data-lucide="bookmark"></i>
        </div>
        <h3 class="saved-empty-title">Belum Ada Barang Disimpan</h3>
        <p class="saved-empty-desc">Tandai barang hilang atau temuan yang ingin Anda pantau perkembangannya dengan menekan tombol simpan.</p>
        <a href="{{ route('search') }}" class="btn-explore-reports">
            <i data-lucide="compass"></i>
            <span>Jelajahi Laporan</span>
        </a>
    </div>

</div>

<!-- 4. Floating Detail Modal (Jendela Mengambang Desktop) -->
<div class="modal-backdrop desktop-detail-modal-backdrop" id="modal-desktop-detail" onclick="closeDesktopDetailModalOnBackdrop(event)" role="dialog" aria-modal="true" aria-hidden="true" style="display: none;">
    <div class="desktop-detail-card" onclick="event.stopPropagation()">
        <!-- Top Image with Floating Buttons -->
        <div class="desktop-detail-hero-box" onclick="openImageLightbox(document.getElementById('modal-detail-hero-img').src, document.getElementById('modal-detail-title').textContent)" title="Klik untuk lihat foto penuh">
            <img src="" alt="Foto Barang" class="desktop-detail-hero-img" id="modal-detail-hero-img">
            <div class="desktop-detail-zoom-pill">
                <i data-lucide="zoom-in"></i>
                <span>Lihat Foto Penuh</span>
            </div>
            <div class="desktop-detail-floating-header">
                <button type="button" class="desktop-detail-circle-btn" onclick="event.stopPropagation(); closeDesktopDetailModal()" aria-label="Tutup" title="Tutup">
                    <i data-lucide="x"></i>
                </button>
                <button type="button" class="desktop-detail-circle-btn" onclick="event.stopPropagation(); handleSavedModalShare()" aria-label="Bagikan" title="Bagikan Laporan">
                    <i data-lucide="share-2"></i>
                </button>
            </div>
        </div>

        <!-- Scrollable Details -->
        <div class="desktop-detail-scroll-area">
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.4rem;">
                    <span class="saved-type-pill" id="modal-detail-type-pill">Temuan</span>
                    <span class="saved-category-pill" id="modal-detail-category">Elektronik</span>
                </div>
                <h3 class="desktop-detail-title" id="modal-detail-title">Judul Laporan</h3>
            </div>

            <div class="desktop-detail-meta-list">
                <div class="desktop-detail-meta-row">
                    <span class="desktop-detail-meta-label">Merek / Model:</span>
                    <span class="desktop-detail-meta-val" id="modal-detail-brand">-</span>
                </div>
                <div class="desktop-detail-meta-row">
                    <span class="desktop-detail-meta-label">Lokasi Kampus:</span>
                    <span class="desktop-detail-meta-val" id="modal-detail-location">-</span>
                </div>
                <div class="desktop-detail-meta-row">
                    <span class="desktop-detail-meta-label">Tanggal Lapor:</span>
                    <span class="desktop-detail-meta-val" id="modal-detail-date">-</span>
                </div>
                <div class="desktop-detail-meta-row">
                    <span class="desktop-detail-meta-label">Status Simpan:</span>
                    <span class="desktop-detail-meta-val" id="modal-detail-storage">Dititipkan di Pos Kampus</span>
                </div>
            </div>

            <div>
                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Deskripsi &amp; Ciri Khusus:</span>
                <p class="desktop-detail-desc-box" id="modal-detail-desc" style="margin-top: 0.35rem;">-</p>
            </div>

            <div class="desktop-detail-actions-row">
                <a href="{{ route('messages') }}" class="btn-modal-action-chat" id="btn-modal-chat">
                    <i data-lucide="message-square"></i>
                    <span>Kirim Pesan / Ajukan Klaim</span>
                </a>
                <button type="button" class="btn-modal-action-remove" id="btn-modal-unsave" onclick="handleModalUnsave()">
                    <i data-lucide="bookmark-x"></i>
                    <span>Hapus Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite"></div>

<!-- 6. Lightbox Modal -->
<div class="modal-backdrop lightbox-modal-backdrop" id="modal-lightbox" onclick="closeImageLightbox(event)" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 200; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="position: relative; max-width: 90vw; max-height: 90vh;">
        <button type="button" onclick="closeImageLightbox()" style="position: absolute; top: -40px; right: 0; background: transparent; border: none; color: white; cursor: pointer; font-size: 1.5rem;">
            <i data-lucide="x"></i>
        </button>
        <img src="" id="lightbox-full-img" alt="Foto Besar" style="max-width: 90vw; max-height: 80vh; object-fit: contain; border-radius: 12px;">
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Laporan tersimpan langsung dari Supabase.
    const SERVER_REPORTS = @json($reports->map(fn ($r) => $r->toUiArray($viewer))->values());
    let SERVER_BOOKMARKS = SERVER_REPORTS.map(r => String(r.id));
    // (katalog mock dihapus — semua data dari Supabase)

    let currentFilter = 'all';
    let activeSavedModalItem = null;

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        initSavedReports();
        renderSavedReports();
    });

    function getBookmarks() {
        return (SERVER_BOOKMARKS || []).map(String);
    }

    function saveBookmarks(bookmarks) {
        SERVER_BOOKMARKS = (bookmarks || []).map(String);
    }

    function initSavedReports() {
        getBookmarks();
    }

    function getUserReports() {
        return Array.isArray(SERVER_REPORTS) ? SERVER_REPORTS : [];
    }

    function getSavedList() {
        // $reports dari controller SUDAH hanya laporan yang disimpan user ini.
        return (SERVER_REPORTS || []).map(r => ({
            id: r.id,
            type: r.type || 'lost',
            title: r.title,
            brand: r.brand || '-',
            category: r.category_label || r.category || 'Lainnya',
            location: r.location_full || r.location_campus || 'Kampus Meruya',
            date: r.date_display || r.date || 'Baru Saja',
            storage: r.storage || (r.type === 'found' ? 'Disimpan Penemu' : 'Disimpan Sendiri'),
            desc: r.features || 'Tidak ada catatan tambahan.',
            image: r.image
        }));
    }

    function setSavedFilter(type, btn) {
        currentFilter = type;
        document.querySelectorAll('.filter-segment-pill').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        renderSavedReports();
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderSavedReports() {
        const listContainer = document.getElementById('saved-reports-list');
        const emptyState = document.getElementById('saved-empty-state');
        const headerCount = document.getElementById('header-saved-count');
        if (!listContainer) return;

        const allItems = getSavedList();
        if (headerCount) headerCount.textContent = allItems.length;

        let filtered = allItems;
        if (currentFilter !== 'all') {
            filtered = allItems.filter(item => item.type === currentFilter);
        }

        if (filtered.length === 0) {
            listContainer.style.display = 'none';
            if (emptyState) emptyState.style.display = 'flex';
            return;
        }

        listContainer.style.display = '';
        if (emptyState) emptyState.style.display = 'none';

        listContainer.innerHTML = filtered.map(item => `
            <article class="saved-card" onclick="handleSavedCardClick('${item.id}')">
                <img src="${item.image}" alt="${escapeHtml(item.title)}" class="saved-card-thumb" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=300';">
                <div class="saved-card-content">
                    <h3 class="saved-card-title">${escapeHtml(item.title)}</h3>
                    <div class="saved-card-location">
                        <i data-lucide="map-pin"></i>
                        <span>${escapeHtml(item.location)}</span>
                    </div>
                    <span class="saved-card-date">${escapeHtml(item.date)}</span>
                </div>
                <div class="saved-card-aside">
                    <span class="badge-status ${item.type === 'found' ? 'badge-found' : 'badge-lost'}">${item.type === 'found' ? 'Temuan' : 'Hilang'}</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-saved-bookmark" onclick="event.stopPropagation(); handleUnsaveItem('${item.id}', '${escapeHtml(item.title).replace(/'/g, "\\'")}')" title="Hapus dari Tersimpan" aria-label="Hapus dari Tersimpan">
                            <i data-lucide="bookmark"></i>
                        </button>
                    </div>
                </div>
            </article>
        `).join('');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function handleSavedCardClick(id) {
        const item = getSavedList().find(i => String(i.id) === String(id));
        if (!item) return;

        // If desktop (min-width: 1024px), open floating modal
        if (window.innerWidth >= 1024) {
            openDesktopDetailModal(item);
        } else {
            // Mobile: can also open modal or direct to detail
            openDesktopDetailModal(item);
        }
    }

    function openDesktopDetailModal(item) {
        activeSavedModalItem = item;
        const modal = document.getElementById('modal-desktop-detail');
        if (!modal) return;

        document.getElementById('modal-detail-hero-img').src = item.image;
        document.getElementById('modal-detail-title').textContent = item.title;
        document.getElementById('modal-detail-brand').textContent = item.brand || '-';
        document.getElementById('modal-detail-category').textContent = item.category || '-';
        document.getElementById('modal-detail-location').textContent = item.location || '-';
        document.getElementById('modal-detail-date').textContent = item.date || '-';
        document.getElementById('modal-detail-storage').textContent = item.storage || 'Disimpan Sendiri';
        document.getElementById('modal-detail-desc').textContent = item.desc || '-';

        const pill = document.getElementById('modal-detail-type-pill');
        if (pill) {
            pill.textContent = item.type === 'found' ? 'Temuan' : 'Hilang';
            pill.className = `saved-type-pill ${item.type}`;
        }

        modal.style.display = 'flex';
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeDesktopDetailModal() {
        const modal = document.getElementById('modal-desktop-detail');
        if (modal) {
            modal.classList.remove('active');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
        activeSavedModalItem = null;
    }

    function closeDesktopDetailModalOnBackdrop(e) {
        if (e.target.id === 'modal-desktop-detail') {
            closeDesktopDetailModal();
        }
    }

    function handleUnsaveItem(id, title) {
        const idStr = String(id);
        let bookmarks = getBookmarks();
        bookmarks = bookmarks.filter(i => String(i) !== idStr && String(i) !== `saved_${idStr}`);
        saveBookmarks(bookmarks);
        renderSavedReports();
        showToast(`✓ "${title}" dihapus dari barang disimpan.`);
    }

    function handleModalUnsave() {
        if (!activeSavedModalItem) return;
        const id = activeSavedModalItem.id;
        const title = activeSavedModalItem.title;
        closeDesktopDetailModal();
        handleUnsaveItem(id, title);
    }

    function handleSavedModalShare() {
        if (!activeSavedModalItem) return;
        if (navigator.share) {
            navigator.share({
                title: activeSavedModalItem.title,
                text: `Lihat barang ${activeSavedModalItem.title} di UMBFind Kampus Mercu Buana.`,
                url: window.location.href
            }).catch(() => {});
        } else {
            showToast('✓ Tautan laporan berhasil disalin ke clipboard!');
        }
    }

    function openImageLightbox(src, title) {
        const lb = document.getElementById('modal-lightbox');
        const img = document.getElementById('lightbox-full-img');
        if (lb && img) {
            img.src = src;
            lb.style.display = 'flex';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeImageLightbox(e) {
        if (!e || e.target.id === 'modal-lightbox' || e.target.closest('button')) {
            const lb = document.getElementById('modal-lightbox');
            if (lb) lb.style.display = 'none';
        }
    }

    function handleSavedBack() {
        if (document.referrer && document.referrer.includes(window.location.host) && !document.referrer.includes('/saved-reports')) {
            window.location.href = document.referrer;
        } else {
            window.location.href = "{{ route('profile') }}";
        }
    }

    function showToast(message) {
        const toast = document.getElementById('toast-popup');
        if (toast) {
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2600);
        }
    }
</script>
@endpush
