@extends('layouts.app')

@section('title', 'Pencarian Laporan — Find UMB')

@push('styles')
<style>
    /* Search Page Container */
    .search-container {
        padding: 0.5rem 1.25rem 0.65rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.95rem;
    }

    /* Search Top Row (Input + Dropdown) */
    .search-top-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
    }

    .category-dropdown-wrapper {
        display: none;
    }

    /* Main Search Input Bar */
    .search-input-wrapper {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        padding: 0.35rem 6px 0.35rem 1rem;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
        transition: var(--transition);
        position: relative;
    }

    .search-input-wrapper:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.12);
    }

    .search-input-icon {
        color: var(--text-muted);
        display: flex;
        align-items: center;
    }

    .search-input-icon svg, .search-input-icon .lucide {
        width: 19px;
        height: 19px;
        stroke-width: 2.2;
    }

    .search-main-field {
        flex: 1;
        border: none;
        background: transparent;
        font-family: var(--font-main);
        font-size: 0.875rem;
        color: var(--text-main);
        outline: none;
        min-width: 0;
    }

    .search-main-field::placeholder {
        color: var(--text-muted);
        font-size: 0.875rem;
    }



    /* Month Filter Calendar Icon Trigger in Search Bar */
    .month-filter-container {
        position: relative;
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .btn-month-filter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: #f0f7ff;
        border: 1px solid rgba(21, 114, 184, 0.16);
        border-radius: 10px;
        color: var(--primary);
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        user-select: none;
        padding: 0;
        flex-shrink: 0;
    }

    .btn-month-filter:hover, .btn-month-filter:active {
        background: #e0f0fe;
        border-color: rgba(21, 114, 184, 0.28);
        color: var(--primary-dark, #0d5a94);
    }

    .btn-month-filter.active {
        background: #dbeafe;
        border-color: rgba(21, 114, 184, 0.35);
        color: var(--primary);
    }

    .btn-month-filter svg, .btn-month-filter .lucide {
        width: 17px;
        height: 17px;
        stroke-width: 2.1;
        color: var(--primary);
        stroke: var(--primary);
        transition: var(--transition);
    }

    .btn-month-filter:hover svg, .btn-month-filter:hover .lucide {
        color: var(--primary-dark, #0d5a94);
        stroke: var(--primary-dark, #0d5a94);
    }

    .btn-month-filter.active svg, .btn-month-filter.active .lucide {
        color: var(--primary);
        stroke: var(--primary);
    }

    /* Small dot badge to indicate active filter on calendar icon */
    .month-active-dot {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 7px;
        height: 7px;
        background-color: var(--primary);
        border: 1.5px solid #ffffff;
        border-radius: 50%;
        display: none;
    }

    .btn-month-filter.active .month-active-dot {
        display: block;
    }

    /* Floating Month Dropdown Card */
    .month-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 165px;
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
        z-index: 100;
        padding: 0.35rem;
        display: flex;
        flex-direction: column;
        animation: dropdownFadeIn 0.15s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .month-dropdown-header {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #94a3b8;
        padding: 0.35rem 0.55rem 0.25rem 0.55rem;
    }

    .month-dropdown-list {
        max-height: 220px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 2px;
        scrollbar-width: thin;
    }

    .month-dropdown-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.45rem 0.6rem;
        background: transparent;
        border: none;
        border-radius: 8px;
        font-family: var(--font-main);
        font-size: 0.8rem;
        font-weight: 500;
        color: #334155;
        text-align: left;
        cursor: pointer;
        transition: var(--transition);
    }

    .month-dropdown-item:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .month-dropdown-item.selected {
        background: #f0f7fd;
        color: #0284c7;
        font-weight: 700;
    }

    .month-dropdown-item .item-check {
        width: 14px;
        height: 14px;
        stroke-width: 2.5;
        color: #0284c7;
        display: none;
    }

    .month-dropdown-item.selected .item-check {
        display: inline-block;
    }

    /* 1. Status Segmented Switch Control (All | Lost | Found) */
    .status-segmented-track {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 14px;
        position: relative;
        gap: 4px;
    }

    .status-segment-pill {
        flex: 1;
        border: none;
        background: transparent;
        color: #64748b;
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 500;
        padding: 0.55rem 0.5rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        text-align: center;
        user-select: none;
    }

    .status-segment-pill.active {
        background: #ffffff;
        color: #0f172a;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
    }

    /* 2. Horizontal Scrollable Category Chips */
    .categories-scroll-track {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding-bottom: 2px;
    }

    .categories-scroll-track::-webkit-scrollbar {
        display: none;
    }

    .category-chip {
        flex-shrink: 0;
        border: 1px solid var(--border-subtle);
        background: #ffffff;
        color: #475569;
        font-family: var(--font-main);
        font-size: 0.8rem;
        font-weight: 500;
        padding: 0.45rem 1rem;
        border-radius: var(--radius-pill);
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
        user-select: none;
    }

    .category-chip:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .category-chip.active {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.2);
    }

    /* 3. Results Count Meta Row */
    .results-meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.15rem;
    }

    .results-count-text {
        font-family: var(--font-main);
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
    }

    .btn-clear-filters {
        background: transparent;
        border: none;
        color: var(--primary);
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
    }

    .btn-clear-filters:hover {
        text-decoration: underline;
    }

    /* 4. Reports List (Vertical Stack) */
    .search-reports-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .report-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.055);
        border-radius: var(--radius-md);
        padding: 0.65rem 0.75rem;
        display: flex;
        gap: 0.8rem;
        align-items: flex-start;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
        text-decoration: none;
        color: inherit;
        position: relative;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .report-card:active {
        transform: scale(0.99);
    }

    .report-thumb {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        background: #f1f5f9;
        flex-shrink: 0;
        align-self: flex-start;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .report-info {
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

    .report-title {
        font-family: var(--font-main);
        font-size: 0.925rem;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
    }

    .report-location {
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

    .report-location span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        min-width: 0;
        flex: 1;
        line-height: 1.35;
    }

    .report-location svg, .report-location .lucide {
        width: 13.5px;
        height: 13.5px;
        color: #1572b8;
        flex-shrink: 0;
        stroke-width: 2.2;
    }

    .report-date {
        font-family: var(--font-main);
        font-size: 0.75rem;
        font-weight: 400;
        color: #64748b;
    }

    .report-aside {
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
        gap: 0.55rem;
    }

    .btn-action-icon {
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: var(--transition);
    }

    .btn-action-icon svg, .btn-action-icon .lucide {
        width: 16px;
        height: 16px;
        stroke-width: 1.8;
        transition: var(--transition);
    }

    .btn-action-icon:hover {
        color: var(--primary);
    }

    .btn-action-icon.bookmarked svg, .btn-action-icon.bookmarked .lucide {
        fill: var(--primary);
        stroke: var(--primary);
    }

    .badge-resolved {
        background: #e0f2fe;
        color: #0284c7;
    }

    /* Card Options Dropdown (Titik 3 untuk laporan sendiri) */
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
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12);
        min-width: 150px;
        z-index: 60;
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
        padding: 1.4rem;
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
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
    }

    .modal-title {
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
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
    }

    .modal-input, .modal-select, .modal-textarea {
        background: #f8fafc;
        border: 1px solid var(--border-subtle);
        border-radius: 10px;
        padding: 0.6rem 0.85rem;
        font-family: var(--font-main);
        font-size: 0.825rem;
        color: #0f172a;
        outline: none;
        transition: var(--transition);
        width: 100%;
    }

    .modal-input:focus, .modal-select:focus, .modal-textarea:focus {
        border-color: var(--primary);
        background: #ffffff;
    }

    .modal-textarea {
        resize: vertical;
        min-height: 75px;
    }

    .modal-actions-row {
        display: flex;
        gap: 0.65rem;
        margin-top: 0.5rem;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 0.7rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-modal-cancel:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .btn-modal-submit {
        flex: 1.4;
        padding: 0.7rem;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.25);
    }

    .btn-modal-submit:hover {
        background: var(--primary-dark);
    }

    /* 5. Friendly Empty State */
    .search-empty-state {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2.5rem 1.5rem;
        background: #ffffff;
        border: 1px dashed var(--border-subtle);
        border-radius: 18px;
        margin-top: 0.5rem;
    }

    .empty-mascot-box {
        width: 105px;
        height: 105px;
        margin-bottom: 1rem;
    }

    .empty-mascot-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 4px 10px rgba(15, 23, 42, 0.1));
    }

    .empty-title {
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.4rem;
    }

    .empty-desc {
        font-family: var(--font-main);
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.45;
        max-width: 280px;
        margin-bottom: 1.25rem;
    }

    .empty-reset-btn {
        background: var(--primary);
        color: #ffffff;
        border: none;
        font-family: var(--font-main);
        font-size: 0.825rem;
        font-weight: 600;
        padding: 0.6rem 1.4rem;
        border-radius: var(--radius-pill);
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.25);
    }

    .empty-reset-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    /* 7. Floating Toast Notification */
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
        .search-container {
            max-width: 1380px;
            margin: 0 auto;
            padding: 2.25rem clamp(1.75rem, 4vw, 4rem) 4rem clamp(1.75rem, 4vw, 4rem);
            width: 100%;
            box-sizing: border-box;
            gap: 1.35rem;
        }

        .search-top-row {
            gap: 0.85rem;
        }

        .search-input-wrapper {
            height: 48px;
            box-sizing: border-box;
            border-radius: 14px;
            padding: 0 6px 0 1.15rem;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .search-main-field {
            font-size: 0.95rem;
        }

        /* Desktop Category Dropdown Option Button */
        .category-dropdown-wrapper {
            display: inline-block !important;
            position: relative;
            flex-shrink: 0;
        }

        .category-filter-dropdown-btn {
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            height: 48px;
            box-sizing: border-box;
            padding: 0 1.15rem;
            background: linear-gradient(135deg, #0c559c 0%, #1572b8 52%, #2aa5e0 100%);
            border: 1.5px solid transparent;
            border-radius: 14px;
            font-family: var(--font-main);
            font-size: 0.88rem;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
            white-space: nowrap;
            box-shadow: none !important;
        }

        .category-filter-dropdown-btn:hover {
            background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 52%, #2499d3 100%);
            box-shadow: none !important;
            transform: translateY(-0.5px);
        }

        .category-filter-dropdown-btn.open {
            background: linear-gradient(135deg, #0a4d8d 0%, #1369aa 52%, #2499d3 100%);
            box-shadow: none !important;
        }

        .category-filter-dropdown-btn .dropdown-btn-content {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .category-filter-dropdown-btn .dropdown-filter-text {
            color: #ffffff;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .category-filter-dropdown-btn .dropdown-filter-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 0.4rem;
            border-radius: 9999px;
            background-color: #ffffff;
            color: #0c559c;
            font-size: 0.75rem;
            font-weight: 700;
            line-height: 1;
            box-shadow: none !important;
            transition: all 0.18s ease;
        }

        .category-filter-dropdown-btn .dropdown-chevron-icon {
            width: 16px;
            height: 16px;
            stroke-width: 2.4;
            color: #ffffff;
            stroke: #ffffff;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            opacity: 0.9;
        }

        .category-filter-dropdown-btn.open .dropdown-chevron-icon {
            transform: rotate(180deg);
            opacity: 1;
        }

        /* Category Filter Menu Popup */
        .category-filter-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 230px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(15, 23, 42, 0.05);
            z-index: 100;
            padding: 0.45rem;
            display: flex;
            flex-direction: column;
            gap: 2px;
            animation: categoryDropdownFade 0.16s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes categoryDropdownFade {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .category-filter-menu-header {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            padding: 0.35rem 0.65rem 0.25rem 0.65rem;
        }

        .category-filter-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.55rem 0.75rem;
            border-radius: 10px;
            background: transparent;
            border: none;
            font-family: var(--font-main);
            font-size: 0.825rem;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease;
            text-align: left;
        }

        .category-filter-option:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .category-filter-option.selected {
            background: #eff6ff;
            color: #1572b8;
            font-weight: 600;
        }

        .category-option-left {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .category-option-left svg, .category-option-left .lucide {
            width: 15px;
            height: 15px;
            stroke-width: 2.1;
            color: #64748b;
        }

        .category-filter-option.selected .category-option-left svg,
        .category-filter-option.selected .category-option-left .lucide {
            color: #1572b8;
        }

        .category-option-right {
            display: flex;
            align-items: center;
        }

        .category-option-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 0.35rem;
            border-radius: 9999px;
            background-color: #ef4444; /* Red indicator badge */
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            line-height: 1;
        }

        /* Hide Horizontal Category Chips on Desktop */
        .categories-scroll-track {
            display: none !important;
        }

        .search-reports-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .report-card {
            padding: 0.85rem 1rem;
            border-radius: 16px;
        }

        .report-card:hover {
            transform: none !important;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02) !important;
            border-color: rgba(15, 23, 42, 0.055) !important;
        }

        /* ----------------------------------------------------
           Desktop Floating Detail Modal (Jendela Mengambang)
           ---------------------------------------------------- */
        .desktop-detail-modal-backdrop {
            z-index: 150;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
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

        .desktop-detail-content-scroll::-webkit-scrollbar-button {
            display: none;
            width: 0;
            height: 0;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .desktop-detail-content-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
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

        .desktop-detail-btn-bookmark, .desktop-detail-btn-external {
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

        .desktop-detail-btn-bookmark:hover, .desktop-detail-btn-external:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .desktop-detail-btn-bookmark.bookmarked {
            background: #eff6ff;
            color: #1572b8;
            border-color: #bfdbfe;
        }

        .desktop-detail-btn-bookmark svg, .desktop-detail-btn-external svg,
        .desktop-detail-btn-bookmark .lucide, .desktop-detail-btn-external .lucide {
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
<div class="search-container">
    <!-- Search Bar & Category Dropdown Row -->
    <div class="search-top-row">
        <div class="search-input-wrapper">
            <span class="search-input-icon">
                <i data-lucide="search"></i>
            </span>
            <input type="text" id="live-search-input" class="search-main-field" value="{{ request('q', '') }}" placeholder="Cari barang hilang atau temuan..." autocomplete="off">
            <!-- Month Filter Dropdown Trigger & Floating Menu -->
            <div class="month-filter-container" id="month-filter-container">
                <button type="button" class="btn-month-filter" id="btn-month-filter" onclick="toggleMonthDropdown(event)" aria-haspopup="listbox" aria-expanded="false" aria-label="Filter Berdasarkan Bulan" title="Filter Bulan">
                    <i data-lucide="calendar"></i>
                    <span class="month-active-dot" id="month-active-dot"></span>
                </button>

                <!-- Floating Dropdown Option List -->
                <div class="month-dropdown-menu" id="month-dropdown-menu" role="listbox" style="display: none;">
                    <div class="month-dropdown-header">Filter Bulan</div>
                    <div class="month-dropdown-list">
                        <button type="button" class="month-dropdown-item selected" data-month="all" onclick="selectMonthFilter('all', 'Semua Bulan')">
                            <span>Semua Bulan</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="01" onclick="selectMonthFilter('01', 'Januari')">
                            <span>Januari</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="02" onclick="selectMonthFilter('02', 'Februari')">
                            <span>Februari</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="03" onclick="selectMonthFilter('03', 'Maret')">
                            <span>Maret</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="04" onclick="selectMonthFilter('04', 'April')">
                            <span>April</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="05" onclick="selectMonthFilter('05', 'Mei')">
                            <span>Mei</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="06" onclick="selectMonthFilter('06', 'Juni')">
                            <span>Juni</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="07" onclick="selectMonthFilter('07', 'Juli')">
                            <span>Juli</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="08" onclick="selectMonthFilter('08', 'Agustus')">
                            <span>Agustus</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="09" onclick="selectMonthFilter('09', 'September')">
                            <span>September</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="10" onclick="selectMonthFilter('10', 'Oktober')">
                            <span>Oktober</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="11" onclick="selectMonthFilter('11', 'November')">
                            <span>November</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                        <button type="button" class="month-dropdown-item" data-month="12" onclick="selectMonthFilter('12', 'Desember')">
                            <span>Desember</span>
                            <i data-lucide="check" class="item-check"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Dropdown Option Button (Desktop) -->
        <div class="category-dropdown-wrapper" id="category-dropdown-wrapper">
            <button type="button" 
                    class="category-filter-dropdown-btn" 
                    id="btn-category-filter-dropdown" 
                    aria-haspopup="true" 
                    aria-expanded="false" 
                    onclick="toggleCategoryFilterDropdown(event)"
                    title="Filter Kategori">
                <span class="dropdown-btn-content">
                    <span class="dropdown-filter-text" id="category-dropdown-label">Semua Kategori</span>
                    <span class="dropdown-filter-badge" id="category-dropdown-badge">10</span>
                </span>
                <i data-lucide="chevron-down" class="dropdown-chevron-icon" id="category-dropdown-chevron"></i>
            </button>

            <div class="category-filter-menu" id="category-filter-menu" role="menu" style="display: none;" onclick="event.stopPropagation()">
                <div class="category-filter-menu-header">Pilih Kategori</div>
                
                <button type="button" class="category-filter-option selected" data-category="all" role="menuitem" onclick="selectCategoryFromDropdown('all')">
                    <div class="category-option-left">
                        <i data-lucide="layers"></i>
                        <span class="category-option-label">Semua Kategori</span>
                    </div>
                    <div class="category-option-right">
                        <span class="category-option-count" id="category-count-all">10</span>
                    </div>
                </button>

                <button type="button" class="category-filter-option" data-category="elektronik" role="menuitem" onclick="selectCategoryFromDropdown('elektronik')">
                    <div class="category-option-left">
                        <i data-lucide="smartphone"></i>
                        <span class="category-option-label">Elektronik</span>
                    </div>
                    <div class="category-option-right">
                        <span class="category-option-count" id="category-count-elektronik">3</span>
                    </div>
                </button>

                <button type="button" class="category-filter-option" data-category="tas" role="menuitem" onclick="selectCategoryFromDropdown('tas')">
                    <div class="category-option-left">
                        <i data-lucide="briefcase"></i>
                        <span class="category-option-label">Tas &amp; Dompet</span>
                    </div>
                    <div class="category-option-right">
                        <span class="category-option-count" id="category-count-tas">2</span>
                    </div>
                </button>

                <button type="button" class="category-filter-option" data-category="dokumen" role="menuitem" onclick="selectCategoryFromDropdown('dokumen')">
                    <div class="category-option-left">
                        <i data-lucide="file-text"></i>
                        <span class="category-option-label">Dokumen &amp; KTM</span>
                    </div>
                    <div class="category-option-right">
                        <span class="category-option-count" id="category-count-dokumen">2</span>
                    </div>
                </button>

                <button type="button" class="category-filter-option" data-category="pakaian" role="menuitem" onclick="selectCategoryFromDropdown('pakaian')">
                    <div class="category-option-left">
                        <i data-lucide="shirt"></i>
                        <span class="category-option-label">Pakaian</span>
                    </div>
                    <div class="category-option-right">
                        <span class="category-option-count" id="category-count-pakaian">1</span>
                    </div>
                </button>

                <button type="button" class="category-filter-option" data-category="kunci" role="menuitem" onclick="selectCategoryFromDropdown('kunci')">
                    <div class="category-option-left">
                        <i data-lucide="key"></i>
                        <span class="category-option-label">Kunci</span>
                    </div>
                    <div class="category-option-right">
                        <span class="category-option-count" id="category-count-kunci">1</span>
                    </div>
                </button>

                <button type="button" class="category-filter-option" data-category="aksesoris" role="menuitem" onclick="selectCategoryFromDropdown('aksesoris')">
                    <div class="category-option-left">
                        <i data-lucide="watch"></i>
                        <span class="category-option-label">Aksesoris</span>
                    </div>
                    <div class="category-option-right">
                        <span class="category-option-count" id="category-count-aksesoris">2</span>
                    </div>
                </button>

                <button type="button" class="category-filter-option" data-category="lainnya" role="menuitem" onclick="selectCategoryFromDropdown('lainnya')">
                    <div class="category-option-left">
                        <i data-lucide="help-circle"></i>
                        <span class="category-option-label">Lainnya</span>
                    </div>
                    <div class="category-option-right">
                        <span class="category-option-count" id="category-count-lainnya">0</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- 1. Status Segmented Switch Control (Semua | Hilang | Temuan) -->
    <div class="status-segmented-track" role="tablist">
        <button type="button" class="status-segment-pill active" data-status="all" role="tab" aria-selected="true" onclick="setStatusFilter('all', this)">
            Semua
        </button>
        <button type="button" class="status-segment-pill" data-status="lost" role="tab" aria-selected="false" onclick="setStatusFilter('lost', this)">
            Hilang
        </button>
        <button type="button" class="status-segment-pill" data-status="found" role="tab" aria-selected="false" onclick="setStatusFilter('found', this)">
            Temuan
        </button>
    </div>

    <!-- 2. Horizontal Scrollable Category Chips -->
    <div class="categories-scroll-track" role="tablist">
        <button type="button" class="category-chip active" data-category="all" onclick="setCategoryFilter('all', this)">
            Semua Kategori
        </button>
        <button type="button" class="category-chip" data-category="elektronik" onclick="setCategoryFilter('elektronik', this)">
            Elektronik
        </button>
        <button type="button" class="category-chip" data-category="tas" onclick="setCategoryFilter('tas', this)">
            Tas & Dompet
        </button>
        <button type="button" class="category-chip" data-category="dokumen" onclick="setCategoryFilter('dokumen', this)">
            Dokumen & KTM
        </button>
        <button type="button" class="category-chip" data-category="pakaian" onclick="setCategoryFilter('pakaian', this)">
            Pakaian
        </button>
        <button type="button" class="category-chip" data-category="kunci" onclick="setCategoryFilter('kunci', this)">
            Kunci
        </button>
        <button type="button" class="category-chip" data-category="aksesoris" onclick="setCategoryFilter('aksesoris', this)">
            Aksesoris
        </button>
        <button type="button" class="category-chip" data-category="lainnya" onclick="setCategoryFilter('lainnya', this)">
            Lainnya
        </button>
    </div>

    <!-- 3. Results Count Meta -->
    <div class="results-meta-row">
        <span class="results-count-text" id="results-count-label">Menampilkan 10 Laporan</span>
        <button type="button" class="btn-clear-filters" id="btn-reset-quick" onclick="resetAllFilters()" style="display: none;">
            Reset Filter
        </button>
    </div>

    <!-- 4. Reports List (Vertical Stack) -->
    <div class="search-reports-list" id="search-reports-container">
        <!-- Item 1: Samsung SM-A135F (Hilang) -->
        <article class="report-card" data-id="1" data-status="lost" data-category="elektronik" data-location="tower" data-title="samsung sm-a135f smartphone" data-date="2026-06-20">
            <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=240&auto=format&fit=crop&q=80" alt="Samsung SM-A135F" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Samsung SM-A135F</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Gedung Tower Lt. 7</span>
                </div>
                <span class="report-date">20 Jun 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-lost">Hilang</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Samsung SM-A135F')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 2: Paspor & Berkas Beasiswa (Hilang) -->
        <article class="report-card" data-id="2" data-status="lost" data-category="dokumen" data-location="gedung_e" data-title="paspor passport berkas dokumen beasiswa" data-date="2026-05-12">
            <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=240&auto=format&fit=crop&q=80" alt="Paspor dan Dokumen" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Paspor & Berkas Beasiswa</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Gedung E R. 204</span>
                </div>
                <span class="report-date">12 Mei 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-lost">Hilang</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Paspor & Berkas')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 3: Tasbih & Gelang Manik Merah (Temuan) -->
        <article class="report-card" data-id="3" data-status="found" data-category="aksesoris" data-location="masjid" data-title="tasbih gelang manik merah aksesoris ibadah" data-date="2026-01-15">
            <img src="https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?w=240&auto=format&fit=crop&q=80" alt="Tasbih Manik Merah" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Tasbih & Gelang Manik Merah</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Masjid Manarul 'Amal UMB</span>
                </div>
                <span class="report-date">15 Jan 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-found">Temuan</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Tasbih Manik')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 4: Kunci Motor Honda & Remote (Temuan) -->
        <article class="report-card" data-id="4" data-status="found" data-category="kunci" data-location="parkir" data-title="kunci motor honda remote gantungan" data-date="2026-01-10">
            <img src="https://images.unsplash.com/photo-1582139329536-e7284fece509?w=240&auto=format&fit=crop&q=80" alt="Kunci Motor Honda" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Kunci Motor Honda & Remote</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Parkir Barat Kampus Meruya</span>
                </div>
                <span class="report-date">10 Jan 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-found">Temuan</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Kunci Motor Honda')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 5: Cincin Emas Berlian (Hilang) -->
        <article class="report-card" data-id="5" data-status="lost" data-category="aksesoris" data-location="parkir" data-title="cincin emas berlian perhiasan gold ring" data-date="2026-01-15">
            <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=240&auto=format&fit=crop&q=80" alt="Cincin Emas" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Cincin Emas Berlian</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Parkir Barat Kampus Meruya</span>
                </div>
                <span class="report-date">15 Jan 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-lost">Hilang</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Cincin Emas')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 6: Tablet Samsung Tab S8 (Hilang) -->
        <article class="report-card" data-id="6" data-status="lost" data-category="elektronik" data-location="gedung_e" data-title="tablet samsung tab s8 stylus pen" data-date="2026-01-14">
            <img src="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=240&auto=format&fit=crop&q=80" alt="Tablet Samsung" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Tablet Samsung Tab S8</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Gedung E Lt. 3</span>
                </div>
                <span class="report-date">14 Jan 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-lost">Hilang</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Tablet Samsung')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 7: Tas Laptop Lenovo ThinkPad (Hilang) -->
        <article class="report-card" data-id="7" data-status="lost" data-category="tas" data-location="lab" data-title="tas laptop lenovo thinkpad ransel hitam" data-date="2026-01-18">
            <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=240&auto=format&fit=crop&q=80" alt="Tas Laptop" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Tas Laptop Lenovo ThinkPad</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Lab Komputer Gedung Tower</span>
                </div>
                <span class="report-date">18 Jan 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-lost">Hilang</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Tas Laptop Lenovo')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 8: Dompet Kulit & KTM Mahasiswa (Hilang) -->
        <article class="report-card" data-id="8" data-status="lost" data-category="tas" data-location="kantin" data-title="ktm dompet kulit cokelat kartu mahasiswa" data-date="2026-02-08">
            <img src="https://images.unsplash.com/photo-1627123424574-724758594e93?w=240&auto=format&fit=crop&q=80" alt="Dompet Kulit" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Dompet Kulit & KTM Mahasiswa</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Kantin Gedung D</span>
                </div>
                <span class="report-date">08 Feb 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-lost">Hilang</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Dompet Kulit & KTM')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 9: Jaket Almamater Biru UMB (Temuan) -->
        <article class="report-card" data-id="9" data-status="found" data-category="pakaian" data-location="perpustakaan" data-title="jaket almamater biru mercu buana umb pakaian" data-date="2026-01-22">
            <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=240&auto=format&fit=crop&q=80" alt="Jaket Almamater" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Jaket Almamater Biru UMB</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Perpustakaan Pusat Lt. 2</span>
                </div>
                <span class="report-date">22 Jan 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-found">Temuan</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Jaket Almamater')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>

        <!-- Item 10: Earphone TWS Soundcore Anker (Temuan) -->
        <article class="report-card" data-id="10" data-status="found" data-category="elektronik" data-location="gazebo" data-title="earphone tws soundcore anker headset wireless" data-date="2026-02-02">
            <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=240&auto=format&fit=crop&q=80" alt="Earphone TWS" class="report-thumb" loading="lazy">
            <div class="report-info">
                <h3 class="report-title">Earphone TWS Anker Soundcore</h3>
                <div class="report-location">
                    <i data-lucide="map-pin"></i>
                    <span>Gazebo Taman Kampus</span>
                </div>
                <span class="report-date">02 Feb 2026</span>
            </div>
            <div class="report-aside">
                <span class="badge-status badge-found">Temuan</span>
                <div class="card-actions-row">
                    <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('Earphone TWS Anker')">
                        <i data-lucide="share-2"></i>
                    </button>
                    <button type="button" class="btn-action-icon" aria-label="Simpan" onclick="toggleBookmark(this)">
                        <i data-lucide="bookmark"></i>
                    </button>
                </div>
            </div>
        </article>
    </div>

    <!-- 5. Empty State (When no results match) -->
    <div class="search-empty-state" id="search-empty-state">
        <div class="empty-mascot-box">
            <picture>
                <source srcset="{{ asset('images/mascot.webp') }}" type="image/webp">
                <img src="{{ asset('images/mascot.png') }}" alt="Maskot FindUMB" class="empty-mascot-img" width="105" height="105" loading="lazy">
            </picture>
        </div>
        <h3 class="empty-title">Tidak Ada Laporan yang Cocok</h3>
        <p class="empty-desc">Barang yang Anda cari belum ditemukan. Coba gunakan kata kunci lain atau ubah pengaturan filter.</p>
        <button type="button" class="empty-reset-btn" id="btn-reset-empty" onclick="resetAllFilters()">
            Reset Semua Filter
        </button>
    </div>
</div>

<!-- EDIT REPORT MODAL (CRUD: Update) -->
<div class="modal-backdrop" id="modal-edit-report" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card">
        <div class="modal-header-row">
            <h2 class="modal-title">Edit Laporan Barang</h2>
            <button type="button" class="btn-modal-icon-close" onclick="closeSearchEditModal()" aria-label="Tutup">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form id="form-edit-report" onsubmit="handleSaveSearchEdit(event)">
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

                <div class="modal-actions-row">
                    <button type="button" class="btn-modal-cancel" onclick="closeSearchEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
                </div>
            </div>
        </form>
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
            <button type="button" class="btn-modal-cancel" onclick="closeSearchEditStatusConfirmModal()">Batal</button>
            <button type="button" class="btn-modal-submit btn-modal-success" onclick="executeSearchSaveEditWithStatusConfirm()">Ya, Selesaikan</button>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal-backdrop" id="modal-delete-report" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-card" style="max-width: 360px; text-align: center;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <i data-lucide="trash-2" style="width: 26px; height: 26px;"></i>
        </div>
        <h3 class="modal-title" style="margin-top: 0.25rem;">Hapus Laporan?</h3>
        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.45; margin: 0;">Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.</p>
        <input type="hidden" id="delete-report-id">
        <div class="modal-actions-row" style="margin-top: 0.5rem;">
            <button type="button" class="btn-modal-cancel" onclick="closeSearchDeleteModal()">Batal</button>
            <button type="button" class="btn-modal-danger" onclick="executeSearchDelete()">Hapus</button>
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
                <span class="desktop-detail-author-name" id="modal-detail-reporter-name">Civitas UMB</span>
                <span class="reporter-badge-verified" title="Terverifikasi">
                    <i data-lucide="check-circle-2"></i>
                </span>
                <span class="desktop-detail-meta-dot">•</span>
                <span class="desktop-detail-post-date" id="modal-detail-post-date">12 Mei 2026</span>
            </div>

            <!-- Structured Properties List (Persis Image 1) -->
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
                    <div class="desktop-detail-prop-value" id="modal-detail-location">Kampus Meruya — Gedung E R. 204</div>
                </div>

                <!-- Kategori Barang -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="layers"></i>
                        <span>Kategori:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-category">Buku, Dokumen &amp; ATK</div>
                </div>

                <!-- Merek / Tipe -->
                <div class="desktop-detail-prop-row">
                    <div class="desktop-detail-prop-label">
                        <i data-lucide="bookmark"></i>
                        <span>Merek / Tipe:</span>
                    </div>
                    <div class="desktop-detail-prop-value" id="modal-detail-brand">Dokumen Resmi Imigrasi</div>
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
                <p class="desktop-detail-desc-content" id="modal-detail-features">Map plastik biru berisi Paspor Republik Indonesia...</p>
            </div>
        </div>

        <!-- Bottom Action Bar inside Modal -->
        <div class="desktop-detail-footer-bar">
            <a href="{{ route('messages') }}" class="desktop-detail-btn-primary" id="modal-detail-primary-btn">
                <i data-lucide="hand" id="modal-detail-btn-icon"></i>
                <span id="modal-detail-btn-label">Saya Menemukan Barang Ini</span>
            </a>
            <button type="button" class="desktop-detail-btn-bookmark" id="modal-detail-footer-bookmark" onclick="handleDetailModalBookmark()" aria-label="Simpan Laporan" title="Simpan Laporan">
                <i data-lucide="bookmark"></i>
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
    // State Filter Manajemen
    let currentStatusFilter = 'all';
    let currentCategoryFilter = 'all';
    let currentMonthFilter = 'all';
    let currentSortOrder = 'newest';
    let currentQuery = '';

    // ----------------------------------------------------
    // Desktop Floating Detail Modal Catalog & Handlers
    // ----------------------------------------------------
    const mockReportsCatalog = {
        '1': {
            id: '1',
            type: 'lost',
            title: 'Samsung SM-A135F',
            brand: 'Samsung Galaxy A13',
            category: 'Elektronik & HP',
            category_slug: 'elektronik',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gedung Tower Lt. 7 R. 702',
            location_full: 'Kampus Meruya — Gedung Tower Lt. 7 R. 702',
            date: '2026-06-20',
            date_display: '20 Jun 2026',
            time: '14.30 WIB (Siang)',
            storage: 'Disimpan Sendiri',
            features: 'Smartphone Samsung warna hitam, terpasang casing transparan dengan stiker logo Informatika di bagian belakang. Layar dalam keadaan terkunci.',
            image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswa Fasilkom',
            reporter_meta: 'Civitas Terverifikasi • NIM 4152***'
        },
        '2': {
            id: '2',
            type: 'lost',
            title: 'Paspor & Berkas Beasiswa',
            brand: 'Dokumen Resmi Imigrasi',
            category: 'Buku, Dokumen & ATK',
            category_slug: 'dokumen',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gedung E R. 204',
            location_full: 'Kampus Meruya — Gedung E R. 204',
            date: '2026-05-12',
            date_display: '12 Mei 2026',
            time: '10.00 - 11.30 WIB',
            storage: 'Disimpan Sendiri',
            features: 'Map plastik biru berisi Paspor Republik Indonesia atas nama mahasiswa dan berkas pendaftaran beasiswa luar negeri.',
            image: 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswi FEB',
            reporter_meta: 'Civitas Terverifikasi • NIM 4312***'
        },
        '3': {
            id: '3',
            type: 'found',
            title: 'Tasbih & Gelang Manik Merah',
            brand: 'Aksesoris Ibadah',
            category: 'Jam & Aksesori',
            category_slug: 'aksesoris',
            location_campus: 'Kampus Meruya',
            location_specific: 'Masjid Manarul \'Amal UMB, Area Saf Laki-laki',
            location_full: 'Kampus Meruya — Masjid Manarul \'Amal UMB',
            date: '2026-01-15',
            date_display: '15 Jan 2026',
            time: 'Ba\'da Salat Dzuhur',
            storage: 'Dititipkan di Pengurus DKM Masjid',
            features: 'Tasbih kayu berbutir merah marun berjumlah 33 butir dan gelang manik elastis. Saat ini diamankan di meja pengurus DKM.',
            image: 'https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Pengurus DKM Manarul \'Amal',
            reporter_meta: 'Pengurus Masjid Kampus UMB'
        },
        '4': {
            id: '4',
            type: 'found',
            title: 'Kunci Motor Honda & Remote',
            brand: 'Honda Smart Key',
            category: 'Kunci Kendaraan / Ruangan',
            category_slug: 'kunci',
            location_campus: 'Kampus Meruya',
            location_specific: 'Parkir Barat Kampus Meruya (Dekat Pohon Rindang)',
            location_full: 'Kampus Meruya — Parkir Barat Kampus Meruya',
            date: '2026-01-10',
            date_display: '10 Jan 2026',
            time: 'Pagi (± 08.15 WIB)',
            storage: 'Dititipkan di Pos Satpam Parkir',
            features: 'Kunci motor Honda dengan gantungan tali lanyard hitam bertuliskan One Heart dan remote alarm hitam.',
            image: 'https://images.unsplash.com/photo-1582139329536-e7284fece509?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Petugas Keamanan Parkir',
            reporter_meta: 'Biro Keamanan & Ketertiban Kampus'
        },
        '5': {
            id: '5',
            type: 'lost',
            title: 'Cincin Emas Berlian',
            brand: 'Perhiasan Emas Kuning',
            category: 'Jam & Aksesori',
            category_slug: 'aksesoris',
            location_campus: 'Kampus Meruya',
            location_specific: 'Parkir Barat Kampus Meruya',
            location_full: 'Kampus Meruya — Parkir Barat Kampus Meruya',
            date: '2026-01-15',
            date_display: '15 Jan 2026',
            time: 'Siang saat menuju kendaraan',
            storage: 'Disimpan Sendiri',
            features: 'Cincin emas sepasang model pertunangan/pernikahan dengan ukiran inisial halus di lingkar dalam. Terjatuh di sekitar parkiran.',
            image: 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Civitas UMB',
            reporter_meta: 'Civitas Terverifikasi • UMB Meruya'
        },
        '6': {
            id: '6',
            type: 'lost',
            title: 'Tablet Samsung Tab S8',
            brand: 'Samsung Galaxy Tab S8',
            category: 'Elektronik & HP',
            category_slug: 'elektronik',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gedung E Lt. 3 Ruang Diskusi',
            location_full: 'Kampus Meruya — Gedung E Lt. 3 Ruang Diskusi',
            date: '2026-01-14',
            date_display: '14 Jan 2026',
            time: 'Sore (± 16.00 WIB)',
            storage: 'Disimpan Sendiri',
            features: 'Tablet warna graphite abu-abu tua dengan book cover magnetic hitam dan S-Pen menempel di punggung tablet.',
            image: 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswa Desain (FDSK)',
            reporter_meta: 'Civitas Terverifikasi • NIM 4232***'
        },
        '7': {
            id: '7',
            type: 'lost',
            title: 'Tas Laptop Lenovo ThinkPad',
            brand: 'ThinkPad Essential Backpack',
            category: 'Tas & Ransel',
            category_slug: 'tas',
            location_campus: 'Kampus Meruya',
            location_specific: 'Lab Komputer Gedung Tower Lt. 4',
            location_full: 'Kampus Meruya — Lab Komputer Gedung Tower',
            date: '2026-01-18',
            date_display: '18 Jan 2026',
            time: '13.00 WIB usai praktikum',
            storage: 'Dititipkan di Lab Komputer Tower',
            features: 'Ransel laptop hitam bergaris merah khas ThinkPad, di dalamnya ada charger laptop 65W dan buku catatan kuliah.',
            image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswa Teknik Elektro',
            reporter_meta: 'Civitas Terverifikasi • NIM 4142***'
        },
        '8': {
            id: '8',
            type: 'lost',
            title: 'Dompet Kulit & KTM Mahasiswa',
            brand: 'Dompet Kulit Cokelat',
            category: 'Dompet & Kartu',
            category_slug: 'tas',
            location_campus: 'Kampus Meruya',
            location_specific: 'Kantin Gedung D Lantai 1',
            location_full: 'Kampus Meruya — Kantin Gedung D',
            date: '2026-02-08',
            date_display: '08 Feb 2026',
            time: 'Istirahat makan siang',
            storage: 'Dititipkan di Pengelola Kantin',
            features: 'Dompet kulit warna cokelat tua berisi KTM UMB, e-KTP, kartu debit bank, dan uang tunai.',
            image: 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Mahasiswa Fikom',
            reporter_meta: 'Civitas Terverifikasi • NIM 4422***'
        },
        '9': {
            id: '9',
            type: 'found',
            title: 'Jaket Almamater UMB',
            brand: 'Jas Almamater Resmi UMB',
            category: 'Pakaian & Sepatu',
            category_slug: 'pakaian',
            location_campus: 'Kampus Meruya',
            location_specific: 'Perpustakaan Pusat Lantai 2, Meja Baca Mandiri',
            location_full: 'Kampus Meruya — Perpustakaan Pusat Lt. 2',
            date: '2026-01-22',
            date_display: '22 Jan 2026',
            time: '17.00 WIB (Sore)',
            storage: 'Dititipkan di Staf Perpustakaan',
            features: 'Jaket almamater biru navy ukuran L dengan badge bordir logo Universitas Mercu Buana di dada kiri. Tertinggal di sandaran kursi.',
            image: 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Staf Perpustakaan UMB',
            reporter_meta: 'Pengelola Fasilitas Perpustakaan'
        },
        '10': {
            id: '10',
            type: 'found',
            title: 'Earphone TWS Anker Soundcore',
            brand: 'Anker Soundcore R50i',
            category: 'Elektronik & HP',
            category_slug: 'elektronik',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gazebo Taman Depan Gedung Tower',
            location_full: 'Kampus Meruya — Gazebo Taman Kampus',
            date: '2026-02-02',
            date_display: '02 Feb 2026',
            time: '15.45 WIB (Sore)',
            storage: 'Dititipkan di Pos Keamanan Kampus',
            features: 'Charging case TWS warna hitam matte beserta sepasang earphone di dalamnya. Kondisi baterai masih menyala.',
            image: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
            reporter_name: 'Civitas UMB',
            reporter_meta: 'Civitas Terverifikasi • Kampus Meruya'
        }
    };

    function getReportDataById(id) {
        const idStr = String(id);
        try {
            const raw = localStorage.getItem('findumb_custom_reports');
            if (raw) {
                const list = JSON.parse(raw);
                if (Array.isArray(list)) {
                    const found = list.find(r => String(r.id) === idStr);
                    if (found) {
                        return {
                            ...found,
                            reporter_name: found.reporter_name || (found.is_mine ? 'Ahmad Fauzi (Saya)' : 'Civitas UMB'),
                            reporter_meta: found.reporter_meta || 'Civitas Terverifikasi • NIM 4152***'
                        };
                    }
                }
            }
        } catch(e) {}

        if (mockReportsCatalog[idStr]) {
            return mockReportsCatalog[idStr];
        }

        return mockReportsCatalog['1'];
    }

    function openDesktopDetailModal(id) {
        const report = getReportDataById(id);
        if (!report) return;

        const modal = document.getElementById('modal-desktop-detail');
        if (!modal) return;

        // Title
        const titleEl = document.getElementById('modal-detail-title');
        if (titleEl) titleEl.textContent = report.title || 'Detail Laporan';

        // Hero Image
        const imgEl = document.getElementById('modal-detail-hero-img');
        if (imgEl) {
            imgEl.src = report.image || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=800&auto=format&fit=crop&q=80';
            imgEl.alt = report.title || 'Foto Barang';
        }

        // Status Badge
        const badgeEl = document.getElementById('modal-detail-status-badge');
        if (badgeEl) {
            if (report.status === 'resolved' || report.status === 'completed') {
                badgeEl.className = 'badge-status badge-resolved';
                badgeEl.textContent = 'Selesai';
            } else if (report.type === 'found') {
                badgeEl.className = 'badge-status badge-found';
                badgeEl.textContent = 'Temuan';
            } else {
                badgeEl.className = 'badge-status badge-lost';
                badgeEl.textContent = 'Hilang';
            }
        }

        // Reporter & Date
        const repNameEl = document.getElementById('modal-detail-reporter-name');
        if (repNameEl) repNameEl.textContent = report.reporter_name || 'Civitas UMB';

        const postDateEl = document.getElementById('modal-detail-post-date');
        if (postDateEl) postDateEl.textContent = report.date_display || report.date || 'Baru Saja';

        // Location
        const locEl = document.getElementById('modal-detail-location');
        if (locEl) locEl.textContent = report.location_full || report.location_campus || 'Kampus Meruya';

        // Date Metric
        const dateEl = document.getElementById('modal-detail-date');
        const labelDateEl = document.getElementById('modal-label-prop-date');
        if (dateEl) dateEl.textContent = report.date_display || report.date || 'Hari Ini';
        if (labelDateEl) {
            labelDateEl.textContent = report.type === 'found' ? 'Tanggal Temuan:' : 'Tanggal Hilang:';
        }

        // Time Metric
        const timeEl = document.getElementById('modal-detail-time');
        if (timeEl) {
            if (report.time) {
                timeEl.textContent = report.time.replace(/^waktu\s*:\s*/i, '').trim();
            } else {
                timeEl.textContent = 'Tidak ditentukan';
            }
        }

        // Category & Brand
        const catEl = document.getElementById('modal-detail-category');
        if (catEl) catEl.textContent = report.category_label || report.category || 'Lainnya';

        const brandEl = document.getElementById('modal-detail-brand');
        if (brandEl) brandEl.textContent = report.brand || 'Sesuai Ciri Fisik';

        // Storage status
        const storageEl = document.getElementById('modal-detail-storage');
        const storageRow = document.getElementById('modal-row-prop-storage');
        if (storageEl && storageRow) {
            if (report.storage) {
                storageEl.textContent = report.storage;
                storageRow.style.display = 'flex';
            } else if (report.type === 'found') {
                storageEl.textContent = 'Disimpan Sendiri';
                storageRow.style.display = 'flex';
            } else {
                storageRow.style.display = 'none';
            }
        }

        // Description & features
        const descEl = document.getElementById('modal-detail-features');
        if (descEl) {
            descEl.textContent = report.features || 'Tidak ada catatan tambahan mengenai ciri khusus atau deskripsi fisik barang.';
        }

        // Primary Action Button
        const primaryBtn = document.getElementById('modal-detail-primary-btn');
        const btnLabel = document.getElementById('modal-detail-btn-label');
        if (primaryBtn && btnLabel) {
            if (report.status === 'resolved' || report.status === 'completed') {
                btnLabel.textContent = 'Laporan Selesai';
                primaryBtn.style.background = '#64748b';
                primaryBtn.style.pointerEvents = 'none';
            } else if (report.type === 'found') {
                btnLabel.textContent = 'Ajukan Klaim Barang Ini';
                primaryBtn.style.background = '#0f5791';
                primaryBtn.style.pointerEvents = 'auto';
            } else {
                btnLabel.textContent = 'Saya Menemukan Barang Ini';
                primaryBtn.style.background = '#0f5791';
                primaryBtn.style.pointerEvents = 'auto';
            }
        }

        // External Link to Full Report Page
        const fullPageBtn = document.getElementById('modal-detail-open-full');
        if (fullPageBtn) {
            const fallbackId = String(report.id).replace('featured-', '');
            fullPageBtn.href = `{{ url('/report') }}/${encodeURIComponent(fallbackId)}`;
        }

        // Set active id for bookmarks & shares
        modal.setAttribute('data-active-id', report.id || id);
        syncDetailModalBookmarkState(report.id || id);

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

    function handleDetailModalBookmark() {
        const modal = document.getElementById('modal-desktop-detail');
        if (!modal) return;
        const id = modal.getAttribute('data-active-id') || '1';
        toggleBookmark(id);
    }

    function syncDetailModalBookmarkState(id) {
        const bookmarks = getStoredBookmarks();
        const isBookmarked = bookmarks.includes(String(id));

        const topBtn = document.getElementById('modal-detail-top-bookmark');
        const footerBtn = document.getElementById('modal-detail-footer-bookmark');

        if (topBtn) topBtn.classList.toggle('bookmarked', isBookmarked);
        if (footerBtn) footerBtn.classList.toggle('bookmarked', isBookmarked);
    }

    function handleDetailModalShare() {
        const modal = document.getElementById('modal-desktop-detail');
        const id = modal ? modal.getAttribute('data-active-id') || '1' : '1';
        const titleEl = document.getElementById('modal-detail-title');
        const title = titleEl ? titleEl.textContent : 'Laporan';
        const fallbackId = String(id).replace('featured-', '');
        const reportUrl = `${window.location.origin}/report/${encodeURIComponent(fallbackId)}`;

        if (navigator.share) {
            navigator.share({
                title: title + ' — Find UMB',
                text: 'Periksa laporan ' + title + ' di Find UMB (Campus Lost & Found).',
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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const lightbox = document.getElementById('modal-image-lightbox');
            if (lightbox && lightbox.classList.contains('active')) {
                return; // Lightbox will be closed by global handler
            }
            closeMonthDropdown();
            closeCategoryFilterDropdown();
            closeDesktopDetailModal();
        }
    });

    // Inisialisasi
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        renderCustomSearchReports();
        syncCardBookmarks();
        updateCategoryDropdownUI();

        const searchInput = document.getElementById('live-search-input');
        const urlParams = new URLSearchParams(window.location.search);
        const queryParam = urlParams.get('q') || (searchInput ? searchInput.value : '');
        if (queryParam) {
            currentQuery = queryParam.toLowerCase().trim();
            if (searchInput) {
                searchInput.value = queryParam;
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                currentQuery = e.target.value.toLowerCase().trim();
                applyFilterAndSearch();
            });

            // Autofocus smoothly if user came directly
            setTimeout(() => {
                searchInput.focus();
            }, 100);
        }

        // Close month dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const dropdownWrapper = document.getElementById('month-filter-container');
            if (dropdownWrapper && !dropdownWrapper.contains(e.target)) {
                closeMonthDropdown();
            }

            const catDropdownWrapper = document.getElementById('category-dropdown-wrapper');
            if (catDropdownWrapper && !catDropdownWrapper.contains(e.target)) {
                closeCategoryFilterDropdown();
            }
        });

        // Open month filter dropdown if requested via URL param (e.g. from home search bar calendar icon)
        if (urlParams.get('open_month') === '1') {
            setTimeout(() => {
                toggleMonthDropdown();
            }, 150);
        }

        applyFilterAndSearch();

        // Card Click Navigation to Detail Page / Floating Desktop Modal
        document.addEventListener('click', (e) => {
            const card = e.target.closest('.report-card');
            if (card && !e.target.closest('.card-actions-row') && !e.target.closest('.btn-action-icon') && !e.target.closest('.card-options-menu')) {
                const id = card.getAttribute('data-id') || '1';
                if (window.innerWidth >= 1024) {
                    e.preventDefault();
                    openDesktopDetailModal(id);
                } else {
                    window.location.href = `{{ url('/report') }}/${encodeURIComponent(id)}`;
                }
            }
        });
    });

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function escapeJs(str) {
        if (!str) return '';
        return String(str).replace(/'/g, "\\'").replace(/"/g, '\\"');
    }

    // Default Seed Reports for User
    const DEFAULT_USER_REPORTS = [
        {
            id: '1',
            type: 'lost',
            title: 'Samsung SM-A135F',
            brand: 'Samsung Galaxy A13',
            category: 'Elektronik & HP',
            category_slug: 'elektronik',
            category_label: 'Elektronik & HP',
            location_campus: 'Kampus Meruya',
            location_specific: 'Gedung Tower Lt. 7 R. 702',
            location_full: 'Kampus Meruya — Gedung Tower Lt. 7 R. 702',
            date: '2026-06-20',
            date_display: '20 Jun 2026',
            time: '14.30 WIB (Siang)',
            storage: 'Disimpan Sendiri',
            features: 'Smartphone Samsung warna hitam, terpasang casing transparan dengan stiker logo Informatika di bagian belakang. Layar dalam keadaan terkunci.',
            image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
            status: 'lost',
            is_mine: true,
            created_at: '2026-06-20T14:30:00.000Z'
        },
        {
            id: '5',
            type: 'lost',
            title: 'Cincin Emas Berlian',
            brand: 'Perhiasan Emas Kuning',
            category: 'Jam & Aksesori',
            category_slug: 'aksesoris',
            category_label: 'Jam & Aksesori',
            location_campus: 'Kampus Meruya',
            location_specific: 'Parkir Barat Kampus Meruya',
            location_full: 'Kampus Meruya — Parkir Barat Kampus Meruya',
            date: '2026-01-15',
            date_display: '15 Jan 2026',
            time: 'Siang saat menuju kendaraan',
            storage: 'Disimpan Sendiri',
            features: 'Cincin emas sepasang model pertunangan/pernikahan dengan ukiran inisial halus di lingkar dalam. Terjatuh di sekitar parkiran.',
            image: 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800&auto=format&fit=crop&q=80',
            status: 'lost',
            is_mine: true,
            created_at: '2026-01-15T12:00:00.000Z'
        }
    ];

    function getUserReports() {
        try {
            let list = [];
            const raw = localStorage.getItem('findumb_custom_reports');
            if (raw) {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    list = parsed;
                }
            }
            if (!list || list.length === 0) {
                list = [...DEFAULT_USER_REPORTS];
                localStorage.setItem('findumb_reports_initialized', 'true');
                localStorage.setItem('findumb_custom_reports', JSON.stringify(list));
                return list;
            }
            return list.filter(r => {
                const idStr = String(r.id);
                if (['2', '3', '4', '6', '7', '8'].includes(idStr)) return false;
                if (r.is_mine === false) return false;
                if (idStr === '1' || idStr === '5') return true;
                if (idStr.startsWith('rep_') || r.is_mine === true) return true;
                if (r.reporter_name && r.reporter_name.includes('Ahmad Fauzi')) return true;
                return false;
            });
        } catch(e) {
            return [...DEFAULT_USER_REPORTS];
        }
    }

    function isMyReport(id) {
        const userReports = getUserReports();
        return userReports.some(r => String(r.id) === String(id));
    }

    function toggleSearchCardOptions(event, id) {
        event.stopPropagation();
        const menu = document.getElementById(`options-menu-${id}`);
        const isAlreadyActive = menu && menu.classList.contains('active');
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
        if (!isAlreadyActive && menu) {
            menu.classList.add('active');
        }
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
    });

    function openSearchEditModal(id) {
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
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

    function closeSearchEditModal() {
        const modal = document.getElementById('modal-edit-report');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function handleSaveSearchEdit(event) {
        event.preventDefault();
        const id = document.getElementById('edit-report-id').value;
        let reports = getUserReports();
        const index = reports.findIndex(r => String(r.id) === String(id));
        if (index === -1) return;

        const statusVal = document.getElementById('edit-status').value;

        // Validate completion modal requirements
        if (statusVal === 'resolved' && reports[index].status !== 'resolved') {
            openSearchEditStatusConfirmModal();
            return;
        }

        executeSearchSaveEditCommit();
    }

    function openSearchEditStatusConfirmModal() {
        const modal = document.getElementById('modal-edit-status-confirm');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeSearchEditStatusConfirmModal() {
        const modal = document.getElementById('modal-edit-status-confirm');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function executeSearchSaveEditWithStatusConfirm() {
        closeSearchEditStatusConfirmModal();
        executeSearchSaveEditCommit();
    }

    function executeSearchSaveEditCommit() {
        const id = document.getElementById('edit-report-id').value;
        let reports = getUserReports();
        const index = reports.findIndex(r => String(r.id) === String(id));
        if (index === -1) return;

        const statusVal = document.getElementById('edit-status').value;
        const newTitle = document.getElementById('edit-title').value.trim();
        const newCategory = document.getElementById('edit-category').value;
        const newBrand = document.getElementById('edit-brand').value.trim();
        const newLocation = document.getElementById('edit-location').value.trim();
        const newDate = document.getElementById('edit-date').value;
        const newFeatures = document.getElementById('edit-features').value.trim();

        reports[index].title = newTitle;
        reports[index].category = newCategory;
        reports[index].category_label = newCategory;
        reports[index].brand = newBrand;
        reports[index].location_full = newLocation;
        reports[index].location_campus = newLocation;
        reports[index].date = newDate;
        reports[index].date_display = formatDateIndo(newDate);
        reports[index].features = newFeatures;

        if (statusVal === 'resolved') {
            reports[index].status = 'resolved';
        } else {
            reports[index].type = statusVal;
            reports[index].status = statusVal;
        }

        try {
            localStorage.setItem('findumb_custom_reports', JSON.stringify(reports));
        } catch(e) {}

        closeSearchEditModal();
        renderCustomSearchReports();
        syncCardBookmarks();
        updateCategoryDropdownUI();
        applyFilterAndSearch();
        showToast('✓ Laporan berhasil diperbarui!');
    }

    function confirmDeleteSearchReport(id) {
        document.querySelectorAll('.card-options-menu').forEach(m => m.classList.remove('active'));
        const idInput = document.getElementById('delete-report-id');
        if (idInput) idInput.value = id;
        const modal = document.getElementById('modal-delete-report');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeSearchDeleteModal() {
        const modal = document.getElementById('modal-delete-report');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function executeSearchDelete() {
        const idInput = document.getElementById('delete-report-id');
        const id = idInput ? idInput.value : null;
        if (!id) return;

        let reports = getUserReports();
        reports = reports.filter(r => String(r.id) !== String(id));
        try {
            localStorage.setItem('findumb_custom_reports', JSON.stringify(reports));
        } catch(e) {}

        const card = document.querySelector(`.report-card[data-id="${id}"]`);
        if (card) card.remove();

        closeSearchDeleteModal();
        applyFilterAndSearch();
        showToast('Laporan berhasil dihapus.');
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

    // Render user-created reports from localStorage into search results
    function renderCustomSearchReports() {
        const userReports = getUserReports();
        const container = document.getElementById('search-reports-container');
        if (!container) return;

        // 1. Sync existing static cards
        document.querySelectorAll('#search-reports-container .report-card').forEach(card => {
            const id = card.getAttribute('data-id');
            const userItem = userReports.find(r => String(r.id) === String(id));

            if ((id === '1' || id === '5') && !userItem) {
                card.remove();
                return;
            }

            if (userItem) {
                const titleEl = card.querySelector('.report-title');
                if (titleEl) titleEl.textContent = userItem.title;
                const locEl = card.querySelector('.report-location span');
                if (locEl) locEl.textContent = userItem.location_full || userItem.location_campus;
                const dateEl = card.querySelector('.report-date');
                if (dateEl) dateEl.textContent = userItem.date_display || userItem.date;

                const badge = card.querySelector('.badge-status');
                if (badge) {
                    if (userItem.status === 'resolved' || userItem.status === 'completed') {
                        badge.className = 'badge-status badge-resolved';
                        badge.textContent = 'Selesai';
                        card.setAttribute('data-status', 'resolved');
                    } else if (userItem.type === 'found') {
                        badge.className = 'badge-status badge-found';
                        badge.textContent = 'Temuan';
                        card.setAttribute('data-status', 'found');
                    } else {
                        badge.className = 'badge-status badge-lost';
                        badge.textContent = 'Hilang';
                        card.setAttribute('data-status', 'lost');
                    }
                }

                // Ganti tombol bookmark dengan tombol option (titik 3)
                const actionsRow = card.querySelector('.card-actions-row');
                if (actionsRow && !actionsRow.querySelector('.card-options-wrap')) {
                    const bookmarkBtn = actionsRow.querySelector('button[aria-label="Simpan"]');
                    if (bookmarkBtn) bookmarkBtn.remove();

                    const optionsWrap = document.createElement('div');
                    optionsWrap.className = 'card-options-wrap';
                    optionsWrap.innerHTML = `
                        <button type="button" class="btn-action-icon btn-card-options" aria-label="Opsi Laporan" onclick="toggleSearchCardOptions(event, '${id}')">
                            <i data-lucide="more-vertical"></i>
                        </button>
                        <div class="card-options-menu" id="options-menu-${id}">
                            <button type="button" class="card-option-item" onclick="openSearchEditModal('${id}')">
                                <i data-lucide="edit-3"></i>
                                <span>Edit Laporan</span>
                            </button>
                            <button type="button" class="card-option-item danger" onclick="confirmDeleteSearchReport('${id}')">
                                <i data-lucide="trash-2"></i>
                                <span>Hapus Laporan</span>
                            </button>
                        </div>
                    `;
                    actionsRow.appendChild(optionsWrap);
                }
            }
        });

        // 2. Render newly created reports (rep_...)
        const newReports = userReports.filter(r => !document.querySelector(`.report-card[data-id="${r.id}"]`));
        const reportsToRender = [...newReports].reverse();

        reportsToRender.forEach(report => {
            let badgeClass = 'badge-lost';
            let badgeLabel = 'Hilang';
            if (report.status === 'resolved' || report.status === 'completed') {
                badgeClass = 'badge-resolved';
                badgeLabel = 'Selesai';
            } else if (report.type === 'found') {
                badgeClass = 'badge-found';
                badgeLabel = 'Temuan';
            }

            const card = document.createElement('article');
            card.className = 'report-card';
            card.setAttribute('data-id', report.id || ('rep_' + Math.random().toString(36).substr(2, 9)));
            card.setAttribute('data-status', (report.status === 'resolved' ? 'resolved' : (report.type || 'lost')));
            card.setAttribute('data-category', report.category_slug || 'lainnya');
            card.setAttribute('data-location', report.location_slug || 'all');
            card.setAttribute('data-title', ((report.title || '') + ' ' + (report.brand || '') + ' ' + (report.features || '') + ' ' + (report.location_specific || '')).toLowerCase());
            card.setAttribute('data-date', report.date || '2026-10-28');

            card.innerHTML = `
                <img src="${report.image || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=240&auto=format&fit=crop&q=80'}" alt="${escapeHtml(report.title)}" class="report-thumb" loading="lazy">
                <div class="report-info">
                    <h3 class="report-title">${escapeHtml(report.title)}</h3>
                    <div class="report-location">
                        <i data-lucide="map-pin"></i>
                        <span>${escapeHtml(report.location_full || report.location_campus || 'Kampus Meruya')}</span>
                    </div>
                    <span class="report-date">${report.date_display || report.date || 'Baru Saja'}</span>
                </div>
                <div class="report-aside">
                    <span class="badge-status ${badgeClass}">${badgeLabel}</span>
                    <div class="card-actions-row">
                        <button type="button" class="btn-action-icon" aria-label="Bagikan" onclick="handleShare('${escapeJs(report.title)}')">
                            <i data-lucide="share-2"></i>
                        </button>
                        <!-- Titik 3 menggantikan bookmark -->
                        <div class="card-options-wrap">
                            <button type="button" class="btn-action-icon btn-card-options" aria-label="Opsi Laporan" onclick="toggleSearchCardOptions(event, '${report.id}')">
                                <i data-lucide="more-vertical"></i>
                            </button>
                            <div class="card-options-menu" id="options-menu-${report.id}">
                                <button type="button" class="card-option-item" onclick="openSearchEditModal('${report.id}')">
                                    <i data-lucide="edit-3"></i>
                                    <span>Edit Laporan</span>
                                </button>
                                <button type="button" class="card-option-item danger" onclick="confirmDeleteSearchReport('${report.id}')">
                                    <i data-lucide="trash-2"></i>
                                    <span>Hapus Laporan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.prepend(card);
        });

        syncCardBookmarks();

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // 1. Status Filter (Semua, Hilang, Temuan)
    function setStatusFilter(status, el) {
        currentStatusFilter = status;
        document.querySelectorAll('.status-segment-pill').forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        });
        if (el) {
            el.classList.add('active');
            el.setAttribute('aria-selected', 'true');
        }
        applyFilterAndSearch();
    }

    // 2. Kategori Filter (Dropdown & Chips Synchronized)
    const CATEGORY_MAP = {
        'all': { label: 'Semua Kategori', icon: 'layers' },
        'elektronik': { label: 'Elektronik', icon: 'smartphone' },
        'tas': { label: 'Tas & Dompet', icon: 'briefcase' },
        'dokumen': { label: 'Dokumen & KTM', icon: 'file-text' },
        'pakaian': { label: 'Pakaian', icon: 'shirt' },
        'kunci': { label: 'Kunci', icon: 'key' },
        'aksesoris': { label: 'Aksesoris', icon: 'watch' },
        'lainnya': { label: 'Lainnya', icon: 'help-circle' }
    };

    function getCategoryCounts() {
        const cards = Array.from(document.querySelectorAll('#search-reports-container .report-card'));
        const counts = {
            all: cards.length,
            elektronik: 0,
            tas: 0,
            dokumen: 0,
            pakaian: 0,
            kunci: 0,
            aksesoris: 0,
            lainnya: 0
        };
        cards.forEach(card => {
            const cat = card.getAttribute('data-category');
            if (cat && counts.hasOwnProperty(cat)) {
                counts[cat]++;
            } else if (counts.hasOwnProperty('lainnya')) {
                counts.lainnya++;
            }
        });
        return counts;
    }

    function updateCategoryDropdownUI() {
        const counts = getCategoryCounts();
        
        // Update individual option count badges in the menu
        Object.keys(counts).forEach(cat => {
            const badgeEl = document.getElementById(`category-count-${cat}`);
            if (badgeEl) {
                badgeEl.textContent = counts[cat];
            }
        });

        // Update main dropdown button label and badge
        const currentMeta = CATEGORY_MAP[currentCategoryFilter] || CATEGORY_MAP['all'];
        const labelEl = document.getElementById('category-dropdown-label');
        const badgeEl = document.getElementById('category-dropdown-badge');

        if (labelEl) labelEl.textContent = currentMeta.label;
        if (badgeEl) badgeEl.textContent = counts[currentCategoryFilter] !== undefined ? counts[currentCategoryFilter] : counts['all'];

        // Update selected state in dropdown menu items
        document.querySelectorAll('.category-filter-option').forEach(item => {
            const itemCat = item.getAttribute('data-category');
            item.classList.toggle('selected', itemCat === currentCategoryFilter);
        });
    }

    function toggleCategoryFilterDropdown(e) {
        if (e) e.stopPropagation();
        const menu = document.getElementById('category-filter-menu');
        const btn = document.getElementById('btn-category-filter-dropdown');
        if (!menu || !btn) return;

        const isOpen = menu.style.display !== 'none';
        if (isOpen) {
            closeCategoryFilterDropdown();
        } else {
            closeMonthDropdown();
            updateCategoryDropdownUI();
            menu.style.display = 'flex';
            btn.classList.add('open');
            btn.setAttribute('aria-expanded', 'true');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }

    function closeCategoryFilterDropdown() {
        const menu = document.getElementById('category-filter-menu');
        const btn = document.getElementById('btn-category-filter-dropdown');
        if (menu) menu.style.display = 'none';
        if (btn) {
            btn.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }
    }

    function selectCategoryFromDropdown(category) {
        setCategoryFilter(category);
        const meta = CATEGORY_MAP[category] || CATEGORY_MAP['all'];
        showToast(`Kategori: ${meta.label}`);
    }

    function setCategoryFilter(category, el) {
        currentCategoryFilter = category;
        document.querySelectorAll('.category-chip').forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-category') === category);
        });
        if (el && el.classList && el.classList.contains('category-chip')) {
            el.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }
        updateCategoryDropdownUI();
        closeCategoryFilterDropdown();
        applyFilterAndSearch();
    }

    // 3. Clear Input
    function clearSearchInput() {
        const input = document.getElementById('live-search-input');
        if (input) {
            input.value = '';
            currentQuery = '';
            input.focus();
        }
        applyFilterAndSearch();
    }

    // 4. Month Dropdown Filter Handlers
    function toggleMonthDropdown(e) {
        if (e) e.stopPropagation();
        const menu = document.getElementById('month-dropdown-menu');
        const btn = document.getElementById('btn-month-filter');
        if (!menu || !btn) return;

        const isOpen = menu.style.display !== 'none';
        if (isOpen) {
            closeMonthDropdown();
        } else {
            menu.style.display = 'flex';
            btn.classList.add('dropdown-open');
            btn.setAttribute('aria-expanded', 'true');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }

    function closeMonthDropdown() {
        const menu = document.getElementById('month-dropdown-menu');
        const btn = document.getElementById('btn-month-filter');
        if (menu) menu.style.display = 'none';
        if (btn) {
            btn.classList.remove('dropdown-open');
            btn.setAttribute('aria-expanded', 'false');
        }
    }

    function selectMonthFilter(monthValue, monthLabel) {
        currentMonthFilter = monthValue;

        // Active State Styling on Calendar Button
        const btn = document.getElementById('btn-month-filter');
        if (btn) {
            if (monthValue !== 'all') {
                btn.classList.add('active');
                btn.setAttribute('title', `Filter Bulan: ${monthLabel}`);
            } else {
                btn.classList.remove('active');
                btn.setAttribute('title', 'Filter Bulan');
            }
        }

        // Highlight selected item in menu
        document.querySelectorAll('.month-dropdown-item').forEach(item => {
            if (item.getAttribute('data-month') === monthValue) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });

        closeMonthDropdown();
        applyFilterAndSearch();

        if (monthValue !== 'all') {
            showToast(`Memfilter laporan bulan ${monthLabel}`);
        } else {
            showToast('Menampilkan laporan semua bulan');
        }
    }

    // 5. Eksekusi Filter Gabungan & Live Search
    function applyFilterAndSearch() {
        const container = document.getElementById('search-reports-container');
        const emptyState = document.getElementById('search-empty-state');
        const countLabel = document.getElementById('results-count-label');
        const resetQuickBtn = document.getElementById('btn-reset-quick');
        
        let cards = Array.from(document.querySelectorAll('#search-reports-container .report-card'));
        let visibleCount = 0;

        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            const category = card.getAttribute('data-category');
            const dateStr = card.getAttribute('data-date') || '';
            const cardMonth = dateStr.split('-')[1] || '';
            const title = card.getAttribute('data-title') || '';
            const cardLocationText = (card.querySelector('.report-location span')?.textContent || '').toLowerCase();

            // Match Status
            const matchStatus = (currentStatusFilter === 'all') || (status === currentStatusFilter);

            // Match Category
            const matchCategory = (currentCategoryFilter === 'all') || (category === currentCategoryFilter);

            // Match Month (Replacing Location)
            const matchMonth = (currentMonthFilter === 'all') || (cardMonth === currentMonthFilter);

            // Match Query
            const matchQuery = (currentQuery === '') || 
                               title.includes(currentQuery) || 
                               cardLocationText.includes(currentQuery);

            if (matchStatus && matchCategory && matchMonth && matchQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sorting
        cards.sort((a, b) => {
            const dateA = new Date(a.getAttribute('data-date'));
            const dateB = new Date(b.getAttribute('data-date'));
            return currentSortOrder === 'newest' ? dateB - dateA : dateA - dateB;
        });
        cards.forEach(c => container.appendChild(c));

        // Update Counter
        if (countLabel) {
            countLabel.textContent = `Menampilkan ${visibleCount} Laporan`;
        }

        // Tampilkan/Sembunyikan Empty State
        if (visibleCount === 0) {
            container.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            container.style.display = 'flex';
            emptyState.style.display = 'none';
        }

        // Tampilkan tombol Reset Cepat jika filter aktif
        const isFilterActive = (currentStatusFilter !== 'all' || currentCategoryFilter !== 'all' || currentMonthFilter !== 'all' || currentQuery !== '');
        if (resetQuickBtn) {
            resetQuickBtn.style.display = isFilterActive ? 'inline-block' : 'none';
        }
    }

    // 6. Reset Semua Filter
    function resetAllFilters() {
        const input = document.getElementById('live-search-input');
        if (input) input.value = '';
        currentQuery = '';

        setStatusFilter('all', document.querySelector('.status-segment-pill[data-status="all"]'));
        setCategoryFilter('all', document.querySelector('.category-chip[data-category="all"]'));

        currentMonthFilter = 'all';
        const btn = document.getElementById('btn-month-filter');
        if (btn) {
            btn.classList.remove('active');
            btn.setAttribute('title', 'Filter Bulan');
        }
        document.querySelectorAll('.month-dropdown-item').forEach(item => {
            item.classList.toggle('selected', item.getAttribute('data-month') === 'all');
        });

        applyFilterAndSearch();
        showToast('Filter direset ke semula.');
    }

    // 7. Toast & Action Handlers
    function showToast(message) {
        const toast = document.getElementById('toast-popup');
        if (toast) {
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }
    }

    function getStoredBookmarks() {
        try {
            const raw = localStorage.getItem('findumb_bookmarks');
            if (raw) {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) return parsed.map(String);
            }
        } catch(e) {}
        const defaultBookmarks = ['2', '3', '7'];
        try {
            if (!localStorage.getItem('findumb_bookmarks_init')) {
                localStorage.setItem('findumb_bookmarks', JSON.stringify(defaultBookmarks));
                localStorage.setItem('findumb_bookmarks_init', 'true');
            }
        } catch(e) {}
        return defaultBookmarks;
    }

    function setStoredBookmarks(bookmarks) {
        try {
            localStorage.setItem('findumb_bookmarks', JSON.stringify(bookmarks));
            localStorage.setItem('findumb_bookmarks_init', 'true');
        } catch(e) {}
    }

    function toggleBookmark(btnOrId) {
        let id = null;
        let btn = null;

        if (typeof btnOrId === 'string' || typeof btnOrId === 'number') {
            id = String(btnOrId);
        } else if (btnOrId && btnOrId.nodeType) {
            btn = btnOrId;
            const card = btn.closest('.report-card');
            if (card) id = card.getAttribute('data-id');
        }

        if (!id) return;

        let bookmarks = getStoredBookmarks();
        const index = bookmarks.indexOf(id);
        const isAdding = (index === -1);

        if (isAdding) {
            bookmarks.push(id);
            showToast('Laporan disimpan ke daftar tersimpan.');
        } else {
            bookmarks.splice(index, 1);
            showToast('Laporan dihapus dari daftar tersimpan.');
        }

        setStoredBookmarks(bookmarks);
        syncCardBookmarks();
        syncDetailModalBookmarkState(id);
    }

    function syncCardBookmarks() {
        const bookmarks = getStoredBookmarks();
        document.querySelectorAll('#search-reports-container .report-card').forEach(card => {
            const id = String(card.getAttribute('data-id') || '');
            const btn = card.querySelector('.card-actions-row button[aria-label="Simpan"]');
            if (btn && id) {
                btn.classList.toggle('bookmarked', bookmarks.includes(id));
            }
        });
    }

    function handleShare(title) {
        if (navigator.share) {
            navigator.share({
                title: title + ' — Find UMB',
                text: 'Periksa laporan ' + title + ' di Find UMB (Campus Lost & Found).',
                url: window.location.href,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showToast('Tautan laporan disalin ke clipboard!');
            }).catch(() => {
                showToast('Tautan siap dibagikan.');
            });
        }
    }
</script>
@endpush
