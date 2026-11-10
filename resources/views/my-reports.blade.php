@extends('layouts.app')

@section('title', 'Laporan Saya — UMBFind')

@section('header')
<header class="my-reports-header">
    <button type="button" class="btn-header-back" onclick="handleMyReportsBack()" aria-label="Kembali">
        <i data-lucide="chevron-left"></i>
    </button>
    <h1 class="my-reports-header-title">Laporan Saya</h1>
</header>
@endsection

@push('styles')
<style>
    /* My Reports Container */
    .my-reports-container {
        padding: 0.9rem 1.25rem 6.5rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }

    /* Top Sticky Header */
    .my-reports-header {
        padding: 0.85rem 1.15rem;
        display: flex;
        align-items: center;
        background: #ffffff;
        border-bottom: 1px solid var(--border-subtle);
        position: sticky;
        top: 0;
        z-index: 40;
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

    .my-reports-header-title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.01em;
        flex: 1;
        margin-left: 0.35rem;
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

    /* Reports List */
    .my-reports-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    /* Report Card */
    .my-report-card {
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        padding: 0.85rem 0.95rem;
        display: flex;
        align-items: center; /* Titik 3 dan konten vertikal di tengah */
        gap: 0.85rem;
        position: relative;
        transition: var(--transition);
        cursor: pointer;
    }

    .my-report-card:hover {
        border-color: #cbd5e1;
    }

    .my-report-thumb {
        width: 72px;
        height: 72px;
        border-radius: 10px;
        object-fit: cover;
        background: #f1f5f9;
        flex-shrink: 0;
    }

    .my-report-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.28rem;
    }

    .my-report-header-row {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        min-width: 0;
        justify-content: flex-start;
    }

    .my-report-title {
        font-family: var(--font-heading);
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
        flex: 0 1 auto;
        margin: 0;
    }

    .my-report-header-row .badge-status {
        flex-shrink: 0;
    }

    .my-report-location {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        color: #64748b;
        min-width: 0;
        width: 100%;
        line-height: 1.35;
    }

    .my-report-location span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
        flex: 1;
    }

    .my-report-location svg, .my-report-location .lucide {
        width: 13px;
        height: 13px;
        color: var(--primary);
        flex-shrink: 0;
    }

    .my-report-date {
        font-size: 0.725rem;
        color: #94a3b8;
    }

    .my-report-aside {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Status Badges */
    .badge-status {
        padding: 0.22rem 0.65rem;
        border-radius: 8px;
        font-family: var(--font-main);
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.1;
    }

    .badge-lost {
        background: #fee2e2;
        color: #e11d48;
    }

    .badge-found {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-resolved {
        background: #e0f2fe;
        color: #0284c7;
    }

    /* Card Actions Row: Hanya Titik 3 */
    .card-actions-row {
        display: flex;
        align-items: center;
        position: relative;
    }

    .btn-card-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-card-action:hover {
        background: #f1f5f9;
        color: var(--primary);
        border-color: #cbd5e1;
    }

    .btn-card-action svg, .btn-card-action .lucide {
        width: 16px;
        height: 16px;
        stroke-width: 2;
        pointer-events: none;
    }

    /* Dropdown Popover for Card Options */
    .card-options-wrap {
        position: relative;
    }

    .card-options-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 6px);
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        min-width: 160px;
        z-index: 100;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: fadeInMenu 0.16s ease;
    }

    @keyframes fadeInMenu {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-options-menu.active {
        display: flex;
    }

    /* When menu is open, raise card stacking context above all sibling cards */
    .my-report-card.menu-open {
        z-index: 50;
    }

    .card-option-item {
        padding: 0.65rem 0.85rem;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-family: var(--font-main);
        font-size: 0.8rem;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
        text-align: left;
        transition: var(--transition);
        width: 100%;
    }

    .card-option-item:hover {
        background: #f8fafc;
        color: var(--primary);
    }

    .card-option-item svg, .card-option-item .lucide {
        width: 15px;
        height: 15px;
        stroke-width: 2.1;
    }

    .card-option-item.danger {
        color: #ef4444;
        border-top: 1px solid #f1f5f9;
    }

    .card-option-item.danger:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    /* Empty State */
    .my-reports-empty {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 3rem 1.5rem;
        background: #ffffff;
        border: 1px dashed var(--border-subtle);
        border-radius: 16px;
        margin-top: 0.5rem;
    }

    .empty-icon-circle {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #f0f7fd;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .empty-icon-circle svg, .empty-icon-circle .lucide {
        width: 32px;
        height: 32px;
        stroke-width: 2;
    }

    .empty-title {
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .empty-desc {
        font-size: 0.8rem;
        color: #64748b;
        max-width: 280px;
        line-height: 1.45;
        margin-bottom: 1.25rem;
    }

    .btn-empty-action {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: var(--primary);
        color: #ffffff;
        font-size: 0.825rem;
        font-weight: 600;
        padding: 0.6rem 1.3rem;
        border-radius: var(--radius-pill);
        text-decoration: none;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(21, 114, 184, 0.25);
    }

    .btn-empty-action:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    /* Modal Backdrop & Sheets */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(3px);
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
    }

    .modal-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-card {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 440px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        transform: scale(0.95);
        transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modal-backdrop.active .modal-card {
        transform: scale(1);
    }

    .modal-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 0.25rem;
    }

    .modal-title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .btn-modal-icon-close {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-modal-icon-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .modal-form-body {
        display: flex;
        flex-direction: column;
        gap: 0.95rem;
    }

    .modal-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .modal-label {
        font-size: 0.775rem;
        font-weight: 600;
        color: #334155;
        line-height: 1.2;
    }

    .modal-input, .modal-select, .modal-textarea {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.65rem 0.85rem;
        font-family: var(--font-main);
        font-size: 0.85rem;
        color: #0f172a;
        outline: none;
        transition: var(--transition);
        width: 100%;
        box-sizing: border-box;
    }

    .modal-input:focus, .modal-select:focus, .modal-textarea:focus {
        border-color: var(--primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.12);
    }

    .modal-textarea {
        resize: vertical;
        min-height: 85px;
        line-height: 1.45;
    }

    .modal-actions-row {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.65rem;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 0.75rem;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
    }

    .btn-modal-cancel:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .btn-modal-submit {
        flex: 1.4;
        padding: 0.75rem;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(21, 114, 184, 0.22);
        text-align: center;
    }

    .btn-modal-submit:hover {
        background: var(--primary-dark);
    }

    .btn-modal-danger {
        flex: 1.4;
        padding: 0.75rem;
        border-radius: 10px;
        border: none;
        background: #ef4444;
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
    }

    .btn-modal-danger:hover {
        background: #dc2626;
    }

    .btn-modal-success {
        flex: 1.4;
        padding: 0.75rem;
        border-radius: 10px;
        border: none;
        background: #16a34a;
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.22);
        text-align: center;
    }

    .btn-modal-success:hover {
        background: #15803d;
    }

    /* Toast Notification */
    .toast-popup {
        position: fixed;
        bottom: 85px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #0f172a;
        color: #ffffff;
        padding: 0.65rem 1.35rem;
        border-radius: var(--radius-pill);
        font-family: var(--font-main);
        font-size: 0.8rem;
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

    /* Desktop Responsive Adjustments (min-width: 1024px) */
    @media (min-width: 1024px) {
        .my-reports-container {
            max-width: 1380px;
            margin: 0 auto;
            padding: 2.25rem clamp(1.75rem, 4vw, 4rem) 4rem clamp(1.75rem, 4vw, 4rem);
            width: 100%;
            box-sizing: border-box;
            gap: 1.35rem;
        }

        .my-report-card {
            cursor: pointer;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .my-report-card:hover {
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
            transform: scale(0.96);
            transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.24s ease;
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
            display: block;
        }

        .desktop-detail-floating-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.5) 0%, rgba(15, 23, 42, 0) 100%);
            z-index: 2;
        }

        .desktop-detail-floating-actions-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .desktop-detail-circle-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            color: #1e293b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .desktop-detail-circle-btn:hover {
            background: #ffffff;
            color: #0f172a;
            transform: scale(1.06);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16);
        }

        .desktop-detail-circle-btn.bookmarked {
            background: #eff6ff;
            color: #1572b8;
            border-color: #bfdbfe;
        }

        .desktop-detail-circle-btn svg, .desktop-detail-circle-btn .lucide {
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
        }

        .desktop-detail-content-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1.65rem 1.25rem 1.65rem;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .desktop-detail-title {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
            line-height: 1.3;
        }

        .desktop-detail-author-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.825rem;
            color: #64748b;
            margin-bottom: 1.15rem;
        }

        .desktop-detail-author-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0284c7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .desktop-detail-author-avatar svg, .desktop-detail-author-avatar .lucide {
            width: 14px;
            height: 14px;
        }

        .desktop-detail-author-name {
            font-weight: 600;
            color: #1e293b;
        }

        .reporter-badge-verified {
            display: inline-flex;
            align-items: center;
            color: #0284c7;
        }

        .reporter-badge-verified svg, .reporter-badge-verified .lucide {
            width: 14px;
            height: 14px;
        }

        .desktop-detail-meta-dot {
            color: #cbd5e1;
        }

        .desktop-detail-props-table {
            border-top: 1px solid #f1f5f9;
            margin-bottom: 1.1rem;
        }

        .desktop-detail-prop-row {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.85rem;
            line-height: 1.45;
            padding: 0.55rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .desktop-detail-prop-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            width: 140px;
            flex-shrink: 0;
            color: #64748b;
            font-weight: 500;
        }

        .desktop-detail-prop-label svg, .desktop-detail-prop-label .lucide {
            width: 15px;
            height: 15px;
            color: #64748b;
            flex-shrink: 0;
            stroke-width: 2.2;
        }

        .desktop-detail-prop-value {
            flex: 1;
            min-width: 0;
            color: #0f172a;
            font-weight: 600;
            word-break: break-word;
        }

        .desktop-detail-desc-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-top: 0.25rem;
        }

        .desktop-detail-desc-heading {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .desktop-detail-desc-content {
            font-size: 0.825rem;
            line-height: 1.55;
            color: #475569;
            margin: 0;
        }

        .desktop-detail-footer-bar {
            padding: 0.95rem 1.65rem 1.15rem 1.65rem;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .desktop-detail-btn-primary {
            flex: 1;
            background: #0f5791;
            color: #ffffff;
            border: none;
            height: 46px;
            border-radius: 12px;
            font-family: var(--font-main);
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.15s ease;
        }

        .desktop-detail-btn-primary:hover {
            background: #0b4372;
        }

        .desktop-detail-btn-primary svg, .desktop-detail-btn-primary .lucide {
            width: 17px;
            height: 17px;
            stroke-width: 2.2;
        }

        .desktop-detail-btn-delete {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .desktop-detail-btn-delete:hover {
            background: #fee2e2;
            color: #b91c1c;
        }

        .desktop-detail-btn-delete svg, .desktop-detail-btn-delete .lucide {
            width: 18px;
            height: 18px;
            stroke-width: 2.2;
        }

        .desktop-detail-btn-external {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .desktop-detail-btn-external:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .desktop-detail-btn-external svg, .desktop-detail-btn-external .lucide {
            width: 18px;
            height: 18px;
            stroke-width: 2.2;
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
<div class="my-reports-container">

    <!-- 1. Status Filter Segment Tabs (Semua, Hilang, Temuan, Selesai) -->
    <div class="filter-segment-wrap" role="tablist">
        <button type="button" class="filter-segment-pill active" onclick="setMyReportsFilter('all', this)">Semua</button>
        <button type="button" class="filter-segment-pill" onclick="setMyReportsFilter('lost', this)">Hilang</button>
        <button type="button" class="filter-segment-pill" onclick="setMyReportsFilter('found', this)">Temuan</button>
        <button type="button" class="filter-segment-pill" onclick="setMyReportsFilter('resolved', this)">Selesai</button>
    </div>

    <!-- 2. Reports List Container -->
    <div class="my-reports-list" id="my-reports-list">
        <article class="my-report-card" data-id="1" onclick="handleCardClick(event, '1')">
            <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80" alt="Samsung SM-A135F" class="my-report-thumb" loading="lazy">
            <div class="my-report-info">
                <div class="my-report-header-row">
                    <h3 class="my-report-title">Samsung SM-A135F</h3>
                    <span class="badge-status badge-lost">Hilang</span>
                </div>
                <div class="my-report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Kampus Meruya — Gedung Tower Lt. 7 R. 702</span>
                </div>
                <span class="my-report-date">20 Jun 2026</span>
            </div>
            <div class="my-report-aside">
                <div class="card-actions-row" onclick="event.stopPropagation()">
                    <div class="card-options-wrap">
                        <button type="button" class="btn-card-action" title="Opsi Laporan" onclick="toggleCardOptions(event, '1')">
                            <i data-lucide="more-vertical"></i>
                        </button>
                        <div class="card-options-menu" id="options-menu-1" onclick="event.stopPropagation()">
                            <button type="button" class="card-option-item" onclick="openEditModal('1')">
                                <i data-lucide="edit-3"></i>
                                <span>Edit Laporan</span>
                            </button>
                            <button type="button" class="card-option-item" onclick="confirmResolveStatus('1')">
                                <i data-lucide="check-circle-2"></i>
                                <span>Tandai Selesai</span>
                            </button>
                            <button type="button" class="card-option-item danger" onclick="openDeleteModal('1')">
                                <i data-lucide="trash-2"></i>
                                <span>Hapus Laporan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <article class="my-report-card" data-id="5" onclick="handleCardClick(event, '5')">
            <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&auto=format&fit=crop&q=80" alt="Cincin Emas Berlian" class="my-report-thumb" loading="lazy">
            <div class="my-report-info">
                <div class="my-report-header-row">
                    <h3 class="my-report-title">Cincin Emas Berlian</h3>
                    <span class="badge-status badge-lost">Hilang</span>
                </div>
                <div class="my-report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Kampus Meruya — Parkir Barat Kampus Meruya</span>
                </div>
                <span class="my-report-date">15 Jan 2026</span>
            </div>
            <div class="my-report-aside">
                <div class="card-actions-row" onclick="event.stopPropagation()">
                    <div class="card-options-wrap">
                        <button type="button" class="btn-card-action" title="Opsi Laporan" onclick="toggleCardOptions(event, '5')">
                            <i data-lucide="more-vertical"></i>
                        </button>
                        <div class="card-options-menu" id="options-menu-5" onclick="event.stopPropagation()">
                            <button type="button" class="card-option-item" onclick="openEditModal('5')">
                                <i data-lucide="edit-3"></i>
                                <span>Edit Laporan</span>
                            </button>
                            <button type="button" class="card-option-item" onclick="confirmResolveStatus('5')">
                                <i data-lucide="check-circle-2"></i>
                                <span>Tandai Selesai</span>
                            </button>
                            <button type="button" class="card-option-item danger" onclick="openDeleteModal('5')">
                                <i data-lucide="trash-2"></i>
                                <span>Hapus Laporan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>

    <!-- 3. Empty State -->
    <div class="my-reports-empty" id="my-reports-empty">
        <div class="empty-icon-circle">
            <i data-lucide="folder-search"></i>
        </div>
        <h3 class="empty-title">Belum Ada Laporan</h3>
        <p class="empty-desc">Anda belum memiliki laporan pada kategori ini. Laporan barang yang Anda buat akan muncul di sini.</p>
        <a href="{{ route('report.lost') }}" class="btn-empty-action">
            <i data-lucide="plus"></i>
            <span>Buat Laporan Baru</span>
        </a>
    </div>

</div>

<!-- EDIT REPORT MODAL (CRUD: Update) -->
<div class="modal-backdrop" id="modal-edit-report" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card">
        <div class="modal-header-row">
            <h2 class="modal-title">Edit Laporan Barang</h2>
            <button type="button" class="btn-modal-icon-close" onclick="closeEditModal()" aria-label="Tutup">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form id="form-edit-report" onsubmit="handleSaveEditReport(event)">
            <input type="hidden" id="edit-report-id">

            <div class="modal-form-body">
                <div class="modal-form-group">
                    <label for="edit-title" class="modal-label">Nama Barang / Judul</label>
                    <input type="text" id="edit-title" class="modal-input" required>
                </div>

                <div class="modal-form-group">
                    <label for="edit-status" class="modal-label">Status Laporan</label>
                    <select id="edit-status" class="modal-select">
                        <option value="lost">Hilang (Sedang Dicari)</option>
                        <option value="found">Temuan (Telah Ditemukan)</option>
                        <option value="resolved">Terselesaikan (Selesai Serah Terima)</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label for="edit-category" class="modal-label">Kategori Barang</label>
                    <select id="edit-category" class="modal-select">
                        <option value="Elektronik & HP">Elektronik &amp; HP</option>
                        <option value="Tas & Ransel">Tas &amp; Ransel</option>
                        <option value="Dompet & Kartu">Dompet &amp; Kartu</option>
                        <option value="Kunci Kendaraan / Ruangan">Kunci Kendaraan / Ruangan</option>
                        <option value="Jam & Aksesori">Jam &amp; Aksesori</option>
                        <option value="Buku, Dokumen & ATK">Buku, Dokumen &amp; ATK</option>
                        <option value="Pakaian & Sepatu">Pakaian &amp; Sepatu</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label for="edit-brand" class="modal-label">Merek / Brand (Opsional)</label>
                    <input type="text" id="edit-brand" class="modal-input" placeholder="Contoh: Samsung, Eiger, Casio">
                </div>

                <div class="modal-form-group">
                    <label for="edit-location" class="modal-label">Lokasi Kampus &amp; Ruangan</label>
                    <input type="text" id="edit-location" class="modal-input" placeholder="Contoh: Gedung Tower Lt. 7 R. 702" required>
                </div>

                <div class="modal-form-group">
                    <label for="edit-date" class="modal-label">Tanggal Kejadian</label>
                    <input type="date" id="edit-date" class="modal-input" required>
                </div>

                <div class="modal-form-group">
                    <label for="edit-features" class="modal-label">Deskripsi &amp; Ciri Khusus</label>
                    <textarea id="edit-features" class="modal-textarea" placeholder="Warna, goresan, stiker, atau ciri pembeda..."></textarea>
                </div>
            </div>

            <div class="modal-actions-row">
                <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- VALIDATION MODAL FOR TANDAI SELESAI / BUKA KEMBALI -->
<div class="modal-backdrop" id="modal-resolve-confirm" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card" style="max-width: 360px; text-align: center;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <i data-lucide="check-circle-2" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="modal-title" style="margin-top: 0.25rem;" id="resolve-modal-title">Selesaikan Laporan?</h3>
        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.45; margin: 0;" id="resolve-modal-desc">Apakah barang ini sudah berhasil ditemukan atau diserahterimakan kepada pemilik yang sah? Status laporan akan diubah menjadi Terselesaikan.</p>
        <input type="hidden" id="resolve-report-id">
        <div class="modal-actions-row" style="margin-top: 0.5rem;">
            <button type="button" class="btn-modal-cancel" onclick="closeResolveModal()">Batal</button>
            <button type="button" class="btn-modal-submit btn-modal-success" id="btn-resolve-confirm-action" onclick="executeResolveReport()">Ya, Selesaikan</button>
        </div>
    </div>
</div>

<!-- VALIDATION MODAL FOR STATUS CHANGE IN EDIT MODAL -->
<div class="modal-backdrop" id="modal-edit-status-confirm" role="dialog" aria-modal="true" aria-hidden="true" style="z-index: 110;">
    <div class="modal-card" style="max-width: 360px; text-align: center;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <i data-lucide="check-circle-2" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 class="modal-title" style="margin-top: 0.25rem;">Validasi Status Laporan</h3>
        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.45; margin: 0;">Apakah Anda yakin ingin menyelesaikan laporan ini? Status barang akan diubah menjadi <strong>Terselesaikan</strong>.</p>
        <div class="modal-actions-row" style="margin-top: 0.5rem;">
            <button type="button" class="btn-modal-cancel" onclick="closeEditStatusConfirmModal()">Batal</button>
            <button type="button" class="btn-modal-submit btn-modal-success" onclick="executeSaveEditWithStatusConfirm()">Ya, Selesaikan</button>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL (CRUD: Delete) -->
<div class="modal-backdrop" id="modal-delete-report" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card" style="max-width: 360px; text-align: center;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <i data-lucide="trash-2" style="width: 26px; height: 26px;"></i>
        </div>
        <h3 class="modal-title" style="margin-top: 0.25rem;">Hapus Laporan?</h3>
        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.45; margin: 0;">Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.</p>
        <input type="hidden" id="delete-report-id">
        <div class="modal-actions-row" style="margin-top: 0.5rem;">
            <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn-modal-danger" onclick="executeDeleteReport()">Hapus</button>
        </div>
    </div>
</div>

<!-- DESKTOP DETAIL MODAL (Floating Modal / Jendela Mengambang) -->
<div class="modal-backdrop desktop-detail-modal-backdrop" id="modal-desktop-detail" onclick="closeDesktopDetailModalOnBackdrop(event)" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="desktop-detail-card" onclick="event.stopPropagation()">
        <!-- Top Image with Floating Action Buttons & Zoom Trigger -->
        <div class="desktop-detail-hero-box" onclick="openImageLightbox(document.getElementById('modal-detail-hero-img').src, document.getElementById('modal-detail-title').textContent)" title="Klik untuk lihat detail foto">
            <img src="" alt="Foto Barang" class="desktop-detail-hero-img" id="modal-detail-hero-img">
            <div class="desktop-detail-zoom-pill">
                <i data-lucide="zoom-in"></i>
                <span>Lihat Detail Foto</span>
            </div>
            <div class="desktop-detail-floating-header">
                <button type="button" class="desktop-detail-circle-btn" onclick="event.stopPropagation(); closeDesktopDetailModal()" aria-label="Tutup Detail" title="Tutup">
                    <i data-lucide="x"></i>
                </button>
                <div class="desktop-detail-floating-actions-right">
                    <button type="button" class="desktop-detail-circle-btn" onclick="event.stopPropagation(); handleDetailModalShare()" aria-label="Bagikan Laporan" title="Bagikan Laporan">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="desktop-detail-circle-btn" id="modal-detail-top-bookmark" onclick="event.stopPropagation(); handleDetailModalBookmark()" aria-label="Simpan Laporan" title="Simpan Laporan">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Scrollable Modal Content -->
        <div class="desktop-detail-content-scroll">
            <!-- Heading Title -->
            <h2 class="desktop-detail-title" id="modal-detail-title">Nama Barang</h2>

            <!-- Author / Reporter Row -->
            <div class="desktop-detail-author-row">
                <div class="desktop-detail-author-avatar">
                    <i data-lucide="user"></i>
                </div>
                <span class="desktop-detail-author-name" id="modal-detail-reporter-name">{{ auth()->user()?->name ?? 'Civitas UMB' }} (Saya)</span>
                <span class="reporter-badge-verified" title="Terverifikasi">
                    <i data-lucide="check-circle-2"></i>
                </span>
                <span class="desktop-detail-meta-dot">•</span>
                <span class="desktop-detail-post-date" id="modal-detail-post-date">12 Mei 2026</span>
            </div>

            <!-- Structured Properties List -->
            <div class="desktop-detail-props-table">
                <!-- Status Laporan -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="tag"></i>
                        <span>Status:</span>
                    </div>
                    <div class="desktop-detail-prop-value">
                        <span class="badge-status badge-lost" id="modal-detail-status-badge">Hilang</span>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="calendar"></i>
                        <span id="modal-label-prop-date">Tanggal Hilang:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-date">12 Mei 2026</div>
                </div>

                <!-- Waktu -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="clock"></i>
                        <span>Waktu:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-time">10.00 - 11.30 WIB</div>
                </div>

                <!-- Lokasi Kampus -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="map-pin"></i>
                        <span>Lokasi Kampus:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-location">Kampus Meruya — Gedung Tower Lt. 7 R. 702</div>
                </div>

                <!-- Kategori Barang -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="layers"></i>
                        <span>Kategori:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-category">Elektronik &amp; HP</div>
                </div>

                <!-- Merek / Tipe -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="bookmark"></i>
                        <span>Merek / Tipe:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-brand">Samsung Galaxy A13</div>
                </div>

                <!-- Status Simpan / Pengamanan -->
                <div class="desktop-detail-prop-row" id="modal-row-prop-storage">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="shield-check"></i>
                        <span>Status Simpan:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-storage">Disimpan Sendiri</div>
                </div>
            </div>

            <!-- Deskripsi & Ciri Khusus -->
            <div class="desktop-detail-desc-section">
                <h3 class="desktop-detail-desc-heading">Deskripsi &amp; Ciri Khusus</h3>
                <p class="desktop-detail-desc-content" id="modal-detail-features">Smartphone Samsung warna hitam...</p>
            </div>
        </div>

        <!-- Bottom Action Bar inside Modal -->
        <div class="desktop-detail-footer-bar">
            <button type="button" class="desktop-detail-btn-primary" id="modal-detail-primary-btn" onclick="handleDetailModalEdit()">
                <i data-lucide="edit-3" id="modal-detail-btn-icon"></i>
                <span id="modal-detail-btn-label">Edit Laporan</span>
            </button>
            <button type="button" class="desktop-detail-btn-delete" id="modal-detail-btn-delete" onclick="handleDetailModalDelete()" aria-label="Hapus Laporan" title="Hapus Laporan">
                <i data-lucide="trash-2"></i>
            </button>
            <a href="#" class="desktop-detail-btn-external" id="modal-detail-open-full" target="_self" title="Buka di Halaman Penuh" aria-label="Buka di Halaman Penuh">
                <i data-lucide="external-link"></i>
            </a>
        </div>
    </div>
</div>

<!-- Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite"></div>
@endsection

@push('scripts')
<script>
    // Laporan milik akun yang login — dari server, bukan seed prototipe.
    const SERVER_REPORTS = @json($reports->map(fn ($r) => $r->toUiArray($viewer))->values());
    let SERVER_BOOKMARKS = @json($savedIds ?? []);
    const IS_AUTHENTICATED = @json(auth()->check());

    let currentFilter = 'all';

    function getUserReports() {
        return Array.isArray(SERVER_REPORTS) ? SERVER_REPORTS : [];
    }

    /**
     * DEC-027 — ambil ulang laporan dari server lalu render.
     * SERVER_REPORTS bersifat `const`, jadi isinya diganti di tempat agar
     * kartu selalu mencerminkan status asli di database.
     */
    async function refreshMyReports() {
        const res = await window.FindUMB.api.get('/api/my-reports');
        const fresh = Array.isArray(res.data) ? res.data : [];

        SERVER_REPORTS.length = 0;
        fresh.forEach((r) => SERVER_REPORTS.push(r));

        if (window.FindUMB && window.FindUMB.state) {
            window.FindUMB.state.myReports = SERVER_REPORTS;
        }

        renderReportsList();
        return SERVER_REPORTS;
    }

    function saveUserReports(reports) {
        // Laporan disimpan lewat API — tidak ada cache localStorage.
    }

    function handleMyReportsBack() {
        if (document.referrer && document.referrer.includes(window.location.host) && !document.referrer.includes('/my-reports')) {
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

    function setMyReportsFilter(filter, el) {
        currentFilter = filter;
        document.querySelectorAll('.filter-segment-pill').forEach(btn => btn.classList.remove('active'));
        if (el) el.classList.add('active');
        renderReportsList();
    }

    function renderReportsList() {
        const reports = getUserReports();
        const listEl = document.getElementById('my-reports-list');
        const emptyEl = document.getElementById('my-reports-empty');
        if (!listEl) return;

        // Filter items
        const filtered = reports.filter(r => {
            if (currentFilter === 'all') return true;
            if (currentFilter === 'resolved') return r.status === 'resolved' || r.status === 'completed';
            if (currentFilter === 'lost') return r.type === 'lost' && r.status !== 'resolved';
            if (currentFilter === 'found') return r.type === 'found' && r.status !== 'resolved';
            return true;
        });

        if (filtered.length === 0) {
            listEl.innerHTML = '';
            listEl.style.display = 'none';
            if (emptyEl) emptyEl.style.display = 'flex';
            return;
        }

        listEl.style.display = 'flex';
        if (emptyEl) emptyEl.style.display = 'none';

        listEl.innerHTML = filtered.map(r => {
            let badgeHtml = '<span class="badge-status badge-lost">Hilang</span>';
            if (r.status === 'resolved' || r.status === 'completed') {
                badgeHtml = '<span class="badge-status badge-resolved">Selesai</span>';
            } else if (r.type === 'found') {
                badgeHtml = '<span class="badge-status badge-found">Temuan</span>';
            }

            const isResolved = r.status === 'resolved' || r.status === 'completed';

            return `
                <article class="my-report-card" data-id="${r.id}" onclick="handleCardClick(event, '${r.id}')">
                    <img src="${r.image || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=240&auto=format&fit=crop&q=80'}" alt="${escapeHtml(r.title)}" class="my-report-thumb" loading="lazy">
                    <div class="my-report-info">
                        <div class="my-report-header-row">
                            <h3 class="my-report-title">${escapeHtml(r.title)}</h3>
                            ${badgeHtml}
                        </div>
                        <div class="my-report-location">
                            <i data-lucide="map-pin"></i>
                            <span>${escapeHtml(r.location_full || r.location_campus || 'Kampus Meruya')}</span>
                        </div>
                        <span class="my-report-date">${r.date_display || r.date || 'Baru saja'}</span>
                    </div>
                    <div class="my-report-aside">
                        <!-- Hanya tombol titik 3 yang berada di tengah card -->
                        <div class="card-actions-row" onclick="event.stopPropagation()">
                            <div class="card-options-wrap">
                                <button type="button" class="btn-card-action" title="Opsi Laporan" onclick="toggleCardOptions(event, '${r.id}')">
                                    <i data-lucide="more-vertical"></i>
                                </button>
                                <div class="card-options-menu" id="options-menu-${r.id}" onclick="event.stopPropagation()">
                                    <button type="button" class="card-option-item" onclick="openEditModal('${r.id}')">
                                        <i data-lucide="edit-3"></i>
                                        <span>Edit Laporan</span>
                                    </button>
                                    <button type="button" class="card-option-item" onclick="confirmResolveStatus('${r.id}')" ${isResolved ? 'style="display:none;"' : ''}>
                                        <i data-lucide="${isResolved ? 'rotate-ccw' : 'check-circle-2'}"></i>
                                        <span>${isResolved ? 'Buka Kembali' : 'Tandai Selesai'}</span>
                                    </button>
                                    <button type="button" class="card-option-item danger" onclick="openDeleteModal('${r.id}')">
                                        <i data-lucide="trash-2"></i>
                                        <span>Hapus Laporan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            `;
        }).join('');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function handleCardClick(event, id) {
        if (event && (
            event.target.closest('.card-actions-row') || 
            event.target.closest('.card-options-wrap') || 
            event.target.closest('.card-options-menu') || 
            event.target.closest('.btn-card-action')
        )) {
            return;
        }
        if (window.innerWidth >= 1024) {
            if (event) event.preventDefault();
            openDesktopDetailModal(id);
        } else {
            window.location.href = `{{ url('/report') }}/${encodeURIComponent(id)}`;
        }
    }

    // ----------------------------------------------------
    // Desktop Floating Detail Modal Functions
    // ----------------------------------------------------
    function getReportDataById(id) {
        const reports = getUserReports();
        return reports.find(r => String(r.id) === String(id));
    }

    function openDesktopDetailModal(id) {
        const report = getReportDataById(id);
        if (!report) return;

        const modal = document.getElementById('modal-desktop-detail');
        if (!modal) return;

        // Populate Image
        const heroImg = document.getElementById('modal-detail-hero-img');
        if (heroImg) {
            heroImg.src = report.image || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=800&auto=format&fit=crop&q=80';
            heroImg.alt = report.title || 'Foto Barang';
        }

        // Title
        const titleEl = document.getElementById('modal-detail-title');
        if (titleEl) titleEl.textContent = report.title || 'Tanpa Judul';

        // Author
        const authorEl = document.getElementById('modal-detail-reporter-name');
        if (authorEl) authorEl.textContent = @js((auth()->user()?->name ?? 'Civitas UMB').' (Saya)');

        // Date posted
        const postDateEl = document.getElementById('modal-detail-post-date');
        if (postDateEl) postDateEl.textContent = report.date_display || report.date || 'Baru saja';

        // Status badge
        const badgeEl = document.getElementById('modal-detail-status-badge');
        if (badgeEl) {
            if (report.status === 'resolved' || report.status === 'completed') {
                badgeEl.className = 'badge-status badge-resolved';
                badgeEl.textContent = 'Terselesaikan';
            } else if (report.type === 'found') {
                badgeEl.className = 'badge-status badge-found';
                badgeEl.textContent = 'Temuan';
            } else {
                badgeEl.className = 'badge-status badge-lost';
                badgeEl.textContent = 'Hilang';
            }
        }

        // Date label & value
        const dateLabel = document.getElementById('modal-label-prop-date');
        if (dateLabel) {
            dateLabel.textContent = report.type === 'found' ? 'Tanggal Temuan:' : 'Tanggal Hilang:';
        }
        const dateEl = document.getElementById('modal-detail-date');
        if (dateEl) dateEl.textContent = report.date_display || report.date || '-';

        // Time
        const timeEl = document.getElementById('modal-detail-time');
        if (timeEl) timeEl.textContent = report.time || '10.00 - 11.30 WIB';

        // Location
        const locEl = document.getElementById('modal-detail-location');
        if (locEl) locEl.textContent = report.location_full || report.location_campus || 'Kampus Meruya';

        // Category
        const catEl = document.getElementById('modal-detail-category');
        if (catEl) catEl.textContent = report.category_label || report.category || 'Lainnya';

        // Brand
        const brandEl = document.getElementById('modal-detail-brand');
        if (brandEl) brandEl.textContent = report.brand || 'Sesuai Ciri Fisik';

        // Storage status
        const storageEl = document.getElementById('modal-detail-storage');
        const storageRow = document.getElementById('modal-row-prop-storage');
        if (storageEl && storageRow) {
            if (report.storage) {
                storageEl.textContent = report.storage;
                storageRow.style.display = 'flex';
            } else {
                storageEl.textContent = 'Disimpan Sendiri';
                storageRow.style.display = 'flex';
            }
        }

        // Description & features
        const descEl = document.getElementById('modal-detail-features');
        if (descEl) {
            descEl.textContent = report.features || 'Tidak ada catatan tambahan mengenai ciri khusus atau deskripsi fisik barang.';
        }

        // External Link to Full Report Page
        const fullPageBtn = document.getElementById('modal-detail-open-full');
        if (fullPageBtn) {
            fullPageBtn.href = `{{ url('/report') }}/${encodeURIComponent(id)}`;
        }

        // Set active id for modal actions
        modal.setAttribute('data-active-id', id);
        syncDetailModalBookmarkState(id);

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function closeDesktopDetailModal() {
        const modal = document.getElementById('modal-desktop-detail');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }

    function closeDesktopDetailModalOnBackdrop(e) {
        if (e.target.id === 'modal-desktop-detail') {
            closeDesktopDetailModal();
        }
    }

    function handleDetailModalEdit() {
        const modal = document.getElementById('modal-desktop-detail');
        const id = modal ? modal.getAttribute('data-active-id') : null;
        if (!id) return;
        closeDesktopDetailModal();
        openEditModal(id);
    }

    function handleDetailModalDelete() {
        const modal = document.getElementById('modal-desktop-detail');
        const id = modal ? modal.getAttribute('data-active-id') : null;
        if (!id) return;
        closeDesktopDetailModal();
        openDeleteModal(id);
    }

    function getStoredBookmarks() {
        return (SERVER_BOOKMARKS || []).map(String);
    }

    function setStoredBookmarks(bookmarks) {
        SERVER_BOOKMARKS = (bookmarks || []).map(String);
    }
    function syncBookmarkToServer(id) {
        if (!IS_AUTHENTICATED) return;
        fetch(`{{ url('/api/bookmarks') }}/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.FindUMB_CSRF || '',
                'Accept': 'application/json',
            },
        }).catch(() => {});
    }


    function handleDetailModalBookmark() {
        const modal = document.getElementById('modal-desktop-detail');
        if (!modal) return;
        const id = modal.getAttribute('data-active-id') || '1';

        let bookmarks = getStoredBookmarks();
        const index = bookmarks.indexOf(String(id));
        if (index === -1) {
            bookmarks.push(String(id));
            showToast('Laporan disimpan ke daftar tersimpan.');
        } else {
            bookmarks.splice(index, 1);
            showToast('Laporan dihapus dari daftar tersimpan.');
        }

        setStoredBookmarks(bookmarks);
        syncDetailModalBookmarkState(id);
    }

    function syncDetailModalBookmarkState(id) {
        const bookmarks = getStoredBookmarks();
        const isBookmarked = bookmarks.includes(String(id));

        const topBtn = document.getElementById('modal-detail-top-bookmark');
        if (topBtn) topBtn.classList.toggle('bookmarked', isBookmarked);
    }

    function handleDetailModalShare() {
        const modal = document.getElementById('modal-desktop-detail');
        const id = modal ? modal.getAttribute('data-active-id') || '1' : '1';
        const titleEl = document.getElementById('modal-detail-title');
        const title = titleEl ? titleEl.textContent : 'Laporan';
        const reportUrl = `${window.location.origin}/report/${encodeURIComponent(id)}`;

        if (navigator.share) {
            navigator.share({
                title: title + ' — UMBFind',
                text: 'Periksa laporan saya "' + title + '" di UMBFind.',
                url: reportUrl,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(reportUrl).then(() => {
                showToast('Tautan laporan disalin ke clipboard!');
            }).catch(() => {
                showToast('Tautan siap dibagikan.');
            });
        }
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const lightbox = document.getElementById('modal-image-lightbox');
            if (lightbox && lightbox.classList.contains('active')) {
                return;
            }
            closeDesktopDetailModal();
        }
    });

    function toggleCardOptions(event, id) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        const menu = document.getElementById(`options-menu-${id}`);
        const card = menu ? menu.closest('.my-report-card') : null;
        const isAlreadyActive = menu && menu.classList.contains('active');
        
        // Close all menus & reset z-index
        closeAllCardOptions();

        if (!isAlreadyActive && menu) {
            menu.classList.add('active');
            if (card) card.classList.add('menu-open');
        }
    }

    function closeAllCardOptions() {
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
        document.querySelectorAll('.my-report-card').forEach(c => c.classList.remove('menu-open'));
    }

    // Close options menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.card-options-wrap')) {
            closeAllCardOptions();
        }
    });

    // Tandai Selesai / Buka Kembali (in-app modal)
    // DEC-027: "Tandai Selesai" benar-benar menutup laporan di server, dan
    // status selesai bersifat permanen (BR-13) sehingga tidak bisa dibuka lagi.
    function confirmResolveStatus(id) {
        closeAllCardOptions();
        let reports = getUserReports();
        const index = reports.findIndex(r => String(r.id) === String(id));
        if (index === -1) return;

        const current = reports[index].status;
        const isResolved = current === 'resolved' || current === 'completed';

        if (isResolved) {
            showToast('Laporan yang sudah selesai bersifat permanen dan tidak dapat dibuka kembali.');
            return;
        }

        document.getElementById('resolve-report-id').value = id;
        const titleEl = document.getElementById('resolve-modal-title');
        const descEl = document.getElementById('resolve-modal-desc');
        const btnEl = document.getElementById('btn-resolve-confirm-action');

        if (titleEl) titleEl.textContent = 'Selesaikan Laporan?';
        if (descEl) descEl.innerHTML = 'Apakah barang ini sudah berhasil ditemukan? Status laporan akan diubah menjadi <strong>Terselesaikan</strong> dan tidak tampil lagi di daftar laporan publik.';
        if (btnEl) {
            btnEl.textContent = 'Ya, Selesaikan';
            btnEl.className = 'btn-modal-success';
        }

        openResolveModal(id);
    }

    function openResolveModal(id) {
        document.getElementById('resolve-report-id').value = id;
        const modal = document.getElementById('modal-resolve-confirm');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeResolveModal() {
        const modal = document.getElementById('modal-resolve-confirm');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    // DEC-027: simpan ke server (dulu hanya menulis localStorage sehingga
    // status tidak pernah berubah setelah halaman dimuat ulang).
    async function executeResolveReport() {
        const id = document.getElementById('resolve-report-id').value;
        if (!id) return;

        const btn = document.getElementById('btn-resolve-confirm-action');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';
        }

        try {
            const res = await window.FindUMB.api.post('/api/reports/' + id + '/resolve');
            closeResolveModal();
            showToast(res.message || 'Laporan ditandai sudah ketemu.');

            // Ambil ulang dari server supaya kartu mencerminkan status asli.
            await refreshMyReports();
        } catch (err) {
            showToast(err.message || 'Gagal menyelesaikan laporan.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Ya, Selesaikan';
            }
        }
    }

    // CRUD: Edit Modal Open & Save
    function openEditModal(id) {
        closeAllCardOptions();
        const reports = getUserReports();
        const item = reports.find(r => String(r.id) === String(id));
        if (!item) return;

        document.getElementById('edit-report-id').value = item.id;
        document.getElementById('edit-title').value = item.title || '';
        document.getElementById('edit-status').value = item.status === 'resolved' ? 'resolved' : (item.type || 'lost');
        document.getElementById('edit-category').value = item.category || 'Elektronik & HP';
        document.getElementById('edit-brand').value = item.brand || '';
        document.getElementById('edit-location').value = item.location_full || item.location_campus || '';
        document.getElementById('edit-date').value = item.date || new Date().toISOString().split('T')[0];
        document.getElementById('edit-features').value = item.features || '';

        const modal = document.getElementById('modal-edit-report');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeEditModal() {
        const modal = document.getElementById('modal-edit-report');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function handleSaveEditReport(event) {
        event.preventDefault();
        const id = document.getElementById('edit-report-id').value;
        let reports = getUserReports();
        const index = reports.findIndex(r => String(r.id) === String(id));
        if (index === -1) return;

        const statusVal = document.getElementById('edit-status').value;

        // Validate completion modal requirements
        if (statusVal === 'resolved' && reports[index].status !== 'resolved') {
            openEditStatusConfirmModal();
            return;
        }

        executeSaveEditCommit();
    }

    function openEditStatusConfirmModal() {
        const modal = document.getElementById('modal-edit-status-confirm');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeEditStatusConfirmModal() {
        const modal = document.getElementById('modal-edit-status-confirm');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function executeSaveEditWithStatusConfirm() {
        closeEditStatusConfirmModal();
        executeSaveEditCommit();
    }

    // DEC-027: simpan perubahan ke server (dulu hanya localStorage).
    async function executeSaveEditCommit() {
        const id = document.getElementById('edit-report-id').value;
        if (!id) return;

        const statusVal = document.getElementById('edit-status').value;
        const newTitle = document.getElementById('edit-title').value.trim();
        const newCategory = document.getElementById('edit-category').value;
        const newBrand = document.getElementById('edit-brand').value.trim();
        const newLocation = document.getElementById('edit-location').value.trim();
        const newDate = document.getElementById('edit-date').value;
        const newFeatures = document.getElementById('edit-features').value.trim();

        if (!newTitle) {
            showToast('Nama barang wajib diisi.');
            return;
        }

        // Nama kategori di form dipetakan ke slug yang dipakai API.
        const slugMap = {
            'Elektronik & HP': 'elektronik', 'Tas & Ransel': 'tas', 'Dompet & Kartu': 'dompet',
            'Jam & Aksesori': 'aksesoris', 'Pakaian & Sepatu': 'pakaian', 'Buku, Dokumen & ATK': 'dokumen',
            'Buku & Alat Tulis': 'buku', 'Kunci Kendaraan / Ruangan': 'kunci', 'Lainnya': 'lain'
        };
        const slug = slugMap[newCategory];
        const categoryId = (slug && window.FindUMB_CATEGORIES) ? window.FindUMB_CATEGORIES[slug] : null;

        const payload = {
            title: newTitle,
            brand: newBrand,
            location: newLocation,
            incident_date: newDate || null,
            description: newFeatures,
        };
        if (categoryId) payload.category_id = categoryId;

        try {
            await window.FindUMB.api.patch('/api/reports/' + id, payload);

            // Status selesai punya endpoint sendiri (tidak lewat PATCH).
            if (statusVal === 'resolved') {
                await window.FindUMB.api.post('/api/reports/' + id + '/resolve');
            }

            closeEditModal();
            showToast('Laporan berhasil diperbarui.');

            await refreshMyReports();
        } catch (err) {
            showToast(err.message || 'Gagal memperbarui laporan.');
        }
    }

    // CRUD: Delete Modal & Execution
    function openDeleteModal(id) {
        closeAllCardOptions();
        document.getElementById('delete-report-id').value = id;
        const modal = document.getElementById('modal-delete-report');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modal-delete-report');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    // DEC-027: hapus = batalkan laporan di server (bukan hanya localStorage).
    async function executeDeleteReport() {
        const id = document.getElementById('delete-report-id').value;
        if (!id) return;

        const btn = document.querySelector('#modal-delete-report .btn-modal-danger');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Menghapus...';
        }

        try {
            const res = await window.FindUMB.api.post('/api/reports/' + id + '/cancel', {
                reason: 'Dibatalkan oleh pembuat laporan.',
            });

            closeDeleteModal();
            showToast(res.message || 'Laporan berhasil dibatalkan.');

            await refreshMyReports();
        } catch (err) {
            showToast(err.message || 'Gagal menghapus laporan.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Hapus';
            }
        }
    }

    function formatDateIndo(dateStr) {
        if (!dateStr) return '';
        try {
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const day = parseInt(parts[2], 10);
                const month = months[parseInt(parts[1], 10) - 1];
                const year = parts[0];
                return `${day} ${month} ${year}`;
            }
        } catch(e) {}
        return dateStr;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function initMyReportsPage() {
        renderReportsList();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMyReportsPage);
    } else {
        initMyReportsPage();
    }
</script>
@endpush
