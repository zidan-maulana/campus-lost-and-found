@extends('layouts.app')

@section('title', 'Profil — Find UMB')

@section('header')
<div style="display: none;" aria-hidden="true"></div>
@endsection

@push('styles')
<style>
    /* ==========================================================================
       PROFILE PAGE STYLES - DUAL RESPONSIVE (MOBILE & DESKTOP)
       Desktop matches other pages (max-width: 1380px)
       ========================================================================== */

    /* --------------------------------------------------------------------------
       1. Mobile Layout (< 1024px)
       -------------------------------------------------------------------------- */
    .profile-mobile-layout {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 100%;
        background: #f8fafc;
        margin-bottom: -95px; /* Memanjang ke bawah hingga belakang bottom nav */
    }

    .profile-desktop-layout {
        display: none;
    }

    /* Mobile Top Main Card */
    .profile-top-card {
        background: #ffffff;
        border-radius: 0 0 46px 46px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        border-bottom: 1px solid rgba(15, 23, 42, 0.04);
        position: relative;
        margin-bottom: 1.25rem;
    }

    /* Top Full-Width Curved Blue Hero Card */
    .profile-hero-card {
        margin: 0;
        width: 100%;
        border-radius: 0 0 46px 46px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        text-align: center;
        box-shadow: 0 8px 24px rgba(15, 87, 145, 0.16);
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .profile-white-section {
        background: #ffffff;
        border-radius: 0 0 46px 46px;
        position: relative;
        width: 100%;
    }

    .hero-svg-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
        z-index: 1;
    }

    .hero-content-wrap {
        position: relative;
        z-index: 2;
        padding: 1.35rem 1.25rem 3.1rem 1.25rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
    }

    .btn-user-pencil {
        background: transparent;
        border: none;
        padding: 0.15rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.85);
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        border-radius: 50%;
        margin-left: 0.2rem;
        flex-shrink: 0;
    }

    .btn-user-pencil:hover, .btn-user-pencil:active {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        transform: scale(1.1);
    }

    .btn-user-pencil svg, .btn-user-pencil .lucide {
        width: 14px;
        height: 14px;
        stroke-width: 2.2;
        color: inherit;
        stroke: currentColor;
    }

    .hero-user-details {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
        padding: 0 0.5rem;
    }

    .hero-name-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
    }

    .hero-user-name {
        font-family: var(--font-heading);
        font-size: 1.45rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.015em;
        line-height: 1.2;
    }

    .hero-user-sub {
        font-family: var(--font-main);
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.88);
        font-weight: 400;
        letter-spacing: 0.01em;
    }

    /* Overlapping Avatar */
    .profile-avatar-wrap {
        display: flex;
        justify-content: center;
        margin-top: -42px;
        position: relative;
        z-index: 10;
        margin-bottom: 1.25rem;
    }

    .profile-avatar-box {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
    }

    .profile-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar-fallback {
        font-family: var(--font-heading);
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--primary);
    }

    /* Stats Row Mobile */
    .profile-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        text-align: center;
        padding: 0.5rem 1rem 1.85rem 1rem;
        max-width: 440px;
        margin: 0 auto;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .stat-val {
        font-family: var(--font-heading);
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.15;
        letter-spacing: -0.015em;
    }

    .stat-lbl {
        font-family: var(--font-main);
        font-size: 0.785rem;
        font-weight: 500;
        color: #94a3b8;
        margin-top: 0.25rem;
        letter-spacing: 0.01em;
    }

    /* Menu Card Mobile */
    .profile-body-content {
        padding: 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .profile-menu-card {
        background: #ffffff;
        border-radius: 46px 46px 0 0;
        border: 1px solid rgba(15, 23, 42, 0.05);
        border-bottom: none;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.035);
        display: flex;
        flex-direction: column;
        padding: 1.35rem 0.75rem 115px 0.75rem;
        width: 100%;
        flex: 1;
    }

    .menu-group-header {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        padding: 0.75rem 1.25rem 0.45rem 1.25rem;
    }

    .profile-menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.25rem;
        border-radius: 16px;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        transition: var(--transition);
    }

    .profile-menu-item:hover {
        background: #f8fafc;
    }

    .profile-item-left {
        display: flex;
        align-items: center;
        gap: 0.95rem;
    }

    .profile-item-icon {
        width: 21px;
        height: 21px;
        stroke-width: 2.1;
        color: #1572b8;
        stroke: #1572b8;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .profile-menu-item:hover .profile-item-icon {
        transform: scale(1.08);
        color: #0f5791;
    }

    .profile-item-title {
        font-family: var(--font-main);
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
    }

    .item-chevron {
        color: #cbd5e1;
        stroke: #cbd5e1;
        width: 17px;
        height: 17px;
        stroke-width: 2.2;
        flex-shrink: 0;
    }

    .profile-notif-pill {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.2rem 0.55rem;
        border-radius: 9999px;
        line-height: 1;
    }

    .profile-item-right-val {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        max-width: 58%;
        justify-content: flex-end;
    }

    .profile-val-text {
        font-family: var(--font-main);
        font-size: 0.84rem;
        font-weight: 500;
        color: #64748b;
        text-align: right;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .profile-item-static {
        cursor: default;
    }

    .profile-item-static:hover {
        background: transparent;
    }

    .profile-item-static:hover .profile-item-icon {
        transform: none;
        color: #1572b8;
    }

    /* Sign Out Button */
    .btn-signout-wrap {
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        padding: 0 0.85rem;
        width: 100%;
    }

    .btn-signout {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.85rem 1.5rem;
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #ffe4e6;
        border-radius: var(--radius-md);
        font-family: var(--font-main);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-signout:hover {
        background: #ffe4e6;
        border-color: #fecdd3;
    }

    .btn-signout svg, .btn-signout .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
        color: #e11d48;
        stroke: #e11d48;
    }


    /* --------------------------------------------------------------------------
       2. Desktop Layout (min-width: 1024px)
       Lebar Konsisten dengan Halaman Lain (max-width: 1380px)
       -------------------------------------------------------------------------- */
    @media (min-width: 1024px) {
        .profile-mobile-layout {
            display: none !important;
        }

        .profile-desktop-layout {
            display: block !important;
            background: #ffffff;
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
        }

        /* Container konsisten dengan halaman lain: max-width 1380px */
        .profile-desktop-container {
            max-width: 1380px;
            margin: 0 auto;
            padding: 2.25rem clamp(1.75rem, 4vw, 4rem) 4rem clamp(1.75rem, 4vw, 4rem);
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            box-sizing: border-box;
            width: 100%;
        }

        /* Top Heading "Halo, Fauzi!" & Subtext */
        .profile-desktop-header {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-bottom: 0.35rem;
        }

        .profile-desktop-title {
            font-family: var(--font-heading);
            font-size: 1.65rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.02em;
            line-height: 1.25;
            margin: 0;
        }

        .profile-desktop-sub {
            font-family: var(--font-main);
            font-size: 0.9rem;
            color: #64748b;
            margin: 0;
            line-height: 1.45;
        }

        .btn-header-edit-profile,
        .btn-card-edit-profile {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.35rem;
            border-radius: 9999px;
            border: none;
            background: var(--primary);
            color: #ffffff;
            font-family: var(--font-main);
            font-size: 0.86rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 2px 6px rgba(21, 114, 184, 0.22);
            white-space: nowrap;
            text-decoration: none;
        }

        .btn-header-edit-profile:hover,
        .btn-card-edit-profile:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(21, 114, 184, 0.32);
            color: #ffffff;
        }

        .btn-header-edit-profile:active,
        .btn-card-edit-profile:active {
            transform: scale(0.98);
        }

        .btn-header-edit-profile svg, .btn-header-edit-profile .lucide,
        .btn-card-edit-profile svg, .btn-card-edit-profile .lucide {
            width: 14px;
            height: 14px;
            stroke-width: 2.2;
            color: #ffffff;
            stroke: #ffffff;
        }

        /* Base Card Styling (Clean White Box with Subtle Border) */
        .profile-desktop-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.65rem 1.85rem;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
            transition: border-color 0.2s ease;
        }

        /* CARD 1: User Summary Card */
        .profile-summary-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .profile-summary-left {
            display: flex;
            align-items: center;
            gap: 1.35rem;
        }

        .profile-summary-avatar-box {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            overflow: hidden;
            background: #f1f5f9;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #e2e8f0;
            position: relative;
            cursor: pointer;
        }

        .profile-summary-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-summary-avatar-fallback {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }

        .profile-summary-details {
            display: flex;
            flex-direction: column;
        }

        .profile-summary-name {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
            letter-spacing: -0.015em;
        }

        .profile-summary-role {
            font-family: var(--font-main);
            font-size: 0.885rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        /* Card Section Header */
        .card-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.35rem;
        }

        .card-section-title {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .card-section-sub {
            font-family: var(--font-main);
            font-size: 0.815rem;
            color: #64748b;
            margin-top: 0.2rem;
        }

        /* CARD 2: Personal information (Hanya Nama dan NIM) */
        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            column-gap: 3.5rem;
            row-gap: 1.45rem;
        }

        .info-field-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .info-field-label {
            font-family: var(--font-main);
            font-size: 0.785rem;
            font-weight: 500;
            color: #94a3b8;
        }

        .info-field-value {
            font-family: var(--font-main);
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            word-break: break-word;
        }

        /* CARD 3: Activity Section (Aktivitas Saya) */
        .desktop-stats-counters {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-counter-box {
            display: flex;
            align-items: center;
            gap: 0.95rem;
            padding: 0.95rem 1.15rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
        }

        .stat-counter-box:hover {
            background: #ffffff;
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            transform: translateY(-1px);
        }

        .stat-counter-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-counter-icon svg, .stat-counter-icon .lucide {
            width: 20px;
            height: 20px;
            stroke-width: 2.2;
        }

        .stat-counter-text {
            display: flex;
            flex-direction: column;
        }

        .stat-counter-val {
            font-family: var(--font-heading);
            font-size: 1.3rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.15;
        }

        .stat-counter-label {
            font-family: var(--font-main);
            font-size: 0.775rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 0.15rem;
        }

        .desktop-activity-list {
            display: flex;
            flex-direction: column;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .desktop-activity-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            transition: var(--transition);
        }

        .desktop-activity-row:last-child {
            border-bottom: none;
        }

        .desktop-activity-row:hover {
            background: #f8fafc;
        }

        .desktop-act-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .desktop-act-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f0f7fd;
            color: #1572b8;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .desktop-act-icon-box svg, .desktop-act-icon-box .lucide {
            width: 19px;
            height: 19px;
            stroke-width: 2.1;
        }

        .desktop-act-info {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .desktop-act-title {
            font-family: var(--font-main);
            font-size: 0.885rem;
            font-weight: 600;
            color: #0f172a;
        }

        .desktop-act-desc {
            font-family: var(--font-main);
            font-size: 0.765rem;
            color: #64748b;
        }

        .desktop-act-right {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .desktop-act-pill {
            font-size: 0.725rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
        }

        .desktop-act-pill.pill-amber {
            background: #fef3c7;
            color: #d97706;
            font-weight: 700;
        }

        .desktop-act-pill.pill-red {
            background: #fee2e2;
            color: #ef4444;
            font-weight: 700;
        }

        .desktop-act-chevron {
            width: 17px;
            height: 17px;
            color: #94a3b8;
            stroke-width: 2.2;
        }
    }

    /* --------------------------------------------------------------------------
       3. Edit Profile Modal Styles (Hanya Edit Nama & Foto Profil)
       -------------------------------------------------------------------------- */
    .profile-edit-modal-sheet {
        max-width: 480px;
        width: 100%;
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem 1.65rem;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
    }

    .profile-edit-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.4rem;
    }

    .btn-modal-close-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-modal-close-icon:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-modal-close-icon svg, .btn-modal-close-icon .lucide {
        width: 16px;
        height: 16px;
        stroke-width: 2.2;
    }

    /* Profile Avatar Wrapper & Floating Camera Badge */
    .profile-avatar-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .avatar-camera-btn {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #1572b8;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.22);
        cursor: pointer;
        transition: var(--transition);
        z-index: 15;
    }

    .avatar-camera-btn:hover {
        background: #0f5791;
        transform: scale(1.1);
    }

    .avatar-camera-btn svg, .avatar-camera-btn .lucide {
        width: 13px;
        height: 13px;
        stroke-width: 2.3;
        color: #ffffff;
        stroke: #ffffff;
    }

    .profile-form-vertical {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
        margin-bottom: 1.5rem;
    }

    .profile-input-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .profile-input-label {
        font-family: var(--font-main);
        font-size: 0.785rem;
        font-weight: 600;
        color: #475569;
    }

    .profile-input {
        width: 100%;
        padding: 0.65rem 0.85rem;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-family: var(--font-main);
        font-size: 0.875rem;
        color: #1e293b;
        background: #ffffff;
        outline: none;
        transition: var(--transition);
        box-sizing: border-box;
    }

    .profile-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.12);
    }

    .profile-input-disabled {
        background: #f1f5f9 !important;
        color: #64748b !important;
        cursor: not-allowed;
        border-color: #e2e8f0 !important;
    }

    .profile-input-hint {
        font-size: 0.725rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin-top: 0.15rem;
    }

    .profile-modal-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        padding-top: 0.85rem;
        border-top: 1px solid #f1f5f9;
    }

    .btn-profile-cancel {
        padding: 0.6rem 1.15rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        font-family: var(--font-main);
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-profile-cancel:hover {
        background: #f8fafc;
        color: #1e293b;
    }

    .btn-profile-submit {
        padding: 0.6rem 1.45rem;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: #ffffff;
        font-family: var(--font-main);
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.2);
    }

    .btn-profile-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(21, 114, 184, 0.28);
    }

    /* Floating Toast */
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
</style>
@endpush

@section('content')

<!-- ===========================================================================
     1. MOBILE LAYOUT (< 1024px)
     Menyisakan HANYA "Aktivitas Saya" sesuai instruksi
     =========================================================================== -->
<div class="profile-mobile-layout">

    <!-- TOP COMBINED CARD (Blue Curved Header + White Shape) -->
    <div class="profile-top-card">
        <!-- Curved Blue Hero Card -->
        <section class="profile-hero-card">
            <svg class="hero-svg-bg" viewBox="0 0 480 155" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="bgGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#0f5da0"/>
                        <stop offset="100%" stop-color="#1879c2"/>
                    </linearGradient>
                    <linearGradient id="darkWave" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#0a3d68"/>
                        <stop offset="100%" stop-color="#0e4f84"/>
                    </linearGradient>
                    <linearGradient id="lightWave" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.46"/>
                        <stop offset="100%" stop-color="#2ba6e1" stop-opacity="0.22"/>
                    </linearGradient>
                </defs>
                <rect width="480" height="155" fill="url(#bgGrad)"/>
                <path d="M 0 0 L 115 0 C 122 48, 142 105, 178 155 L 0 155 Z" fill="url(#darkWave)"/>
                <path d="M 330 0 C 348 48, 382 105, 428 155 L 480 155 L 480 0 Z" fill="url(#lightWave)"/>
            </svg>

            <div class="hero-content-wrap">
                <div class="hero-user-details">
                    <div class="hero-name-row">
                        <h1 class="hero-user-name" id="user-display-name">Ahmad Fauzi</h1>
                        <button type="button" class="btn-user-pencil" onclick="openEditProfileModal()" title="Ubah Profil" aria-label="Ubah Profil">
                            <i data-lucide="pencil"></i>
                        </button>
                    </div>
                    <span class="hero-user-sub" id="user-display-sub">41521010045@student.mercubuana.ac.id</span>
                </div>
            </div>
        </section>

        <!-- White Section with Avatar & Stats -->
        <div class="profile-white-section">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar-wrapper" onclick="triggerAvatarUpload()" title="Klik untuk ganti foto profil">
                    <div class="profile-avatar-box">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=240&auto=format&fit=crop&q=80" alt="Foto Profil" class="profile-avatar-img user-profile-avatar-elem" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <span class="profile-avatar-fallback" style="display: none;">A</span>
                    </div>
                    <div class="avatar-camera-btn" aria-label="Ganti Foto Profil">
                        <i data-lucide="camera"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="profile-stats-row">
                <div class="stat-item">
                    <span class="stat-val" id="stat-reports-count">2</span>
                    <span class="stat-lbl">Laporan</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val" id="stat-recovered-count">1</span>
                    <span class="stat-lbl">Terselesaikan</span>
                </div>
                <div class="stat-item" onclick="window.location.href='{{ route('saved.reports') }}'" style="cursor: pointer;">
                    <span class="stat-val" id="stat-saved-count">3</span>
                    <span class="stat-lbl">Disimpan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Card: Informasi & Aktivitas -->
    <div class="profile-body-content">
        <div class="profile-menu-card">
            <!-- Bagian 1: Informasi Pribadi & Akademik -->
            <div class="menu-group-header">Informasi Pribadi &amp; Akademik</div>

            <!-- Nama Lengkap -->
            <div class="profile-menu-item profile-item-static">
                <div class="profile-item-left">
                    <i data-lucide="user" class="profile-item-icon"></i>
                    <span class="profile-item-title">Nama Lengkap</span>
                </div>
                <div class="profile-item-right-val">
                    <span class="profile-val-text" id="user-mobile-info-name">Ahmad Fauzi</span>
                </div>
            </div>

            <!-- NIM (Permanen) -->
            <div class="profile-menu-item profile-item-static" title="NIM resmi terdaftar">
                <div class="profile-item-left">
                    <i data-lucide="id-card" class="profile-item-icon"></i>
                    <span class="profile-item-title">NIM</span>
                </div>
                <div class="profile-item-right-val">
                    <span class="profile-val-text" id="user-mobile-info-nim">41521010045</span>
                </div>
            </div>

            <!-- Fakultas -->
            <div class="profile-menu-item profile-item-static">
                <div class="profile-item-left">
                    <i data-lucide="building-2" class="profile-item-icon"></i>
                    <span class="profile-item-title">Fakultas</span>
                </div>
                <div class="profile-item-right-val">
                    <span class="profile-val-text" id="user-mobile-info-faculty">-</span>
                </div>
            </div>

            <!-- Program Studi -->
            <div class="profile-menu-item profile-item-static">
                <div class="profile-item-left">
                    <i data-lucide="graduation-cap" class="profile-item-icon"></i>
                    <span class="profile-item-title">Program Studi</span>
                </div>
                <div class="profile-item-right-val">
                    <span class="profile-val-text" id="user-mobile-info-prodi">-</span>
                </div>
            </div>

            <!-- Bagian 2: Aktivitas Saya -->
            <div class="menu-group-header" style="margin-top: 1.25rem;">Aktivitas Saya</div>

            <!-- 1. Laporan Saya -->
            <a href="{{ route('my.reports') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="file-text" class="profile-item-icon"></i>
                    <span class="profile-item-title">Laporan Saya</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </a>

            <!-- 2. Barang Disimpan (Direct ke Halaman Khusus) -->
            <a href="{{ route('saved.reports') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="bookmark" class="profile-item-icon"></i>
                    <span class="profile-item-title">Barang Disimpan</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="profile-notif-pill" id="mobile-saved-pill" style="background: #fef3c7; color: #d97706;">3</span>
                    <i data-lucide="chevron-right" class="item-chevron"></i>
                </div>
            </a>

            <!-- 3. Pesan & Klaim Masuk -->
            <a href="{{ route('messages') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="message-square" class="profile-item-icon"></i>
                    <span class="profile-item-title">Pesan &amp; Klaim Masuk</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </a>

            <!-- 4. Notifikasi Aktivitas -->
            <a href="{{ route('notifications') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="bell" class="profile-item-icon"></i>
                    <span class="profile-item-title">Notifikasi Aktivitas</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="profile-notif-pill" id="profile-notif-pill" style="display: none; background: #fee2e2; color: #ef4444;">3 Baru</span>
                    <i data-lucide="chevron-right" class="item-chevron"></i>
                </div>
            </a>

            <!-- Group 2: Pengaturan & Bantuan -->
            <div class="menu-group-header" style="margin-top: 1.25rem;">Pengaturan &amp; Bantuan</div>

            <!-- 5. Pengaturan Aplikasi -->
            <a href="{{ route('settings') }}" class="profile-menu-item">
                <div class="profile-item-left">
                    <i data-lucide="settings" class="profile-item-icon"></i>
                    <span class="profile-item-title">Pengaturan Aplikasi</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </a>

            <!-- 6. Buku Panduan & Informasi -->
            <div class="profile-menu-item" onclick="openGuideModal()" style="cursor: pointer;">
                <div class="profile-item-left">
                    <i data-lucide="book-open" class="profile-item-icon"></i>
                    <span class="profile-item-title">Buku Panduan &amp; Informasi</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 7. Kebijakan Privasi -->
            <div class="profile-menu-item" onclick="openPrivacyModal()" style="cursor: pointer;">
                <div class="profile-item-left">
                    <i data-lucide="shield-check" class="profile-item-icon"></i>
                    <span class="profile-item-title">Kebijakan Privasi</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 8. Syarat & Ketentuan -->
            <div class="profile-menu-item" onclick="openTermsModal()" style="cursor: pointer;">
                <div class="profile-item-left">
                    <i data-lucide="file-check" class="profile-item-icon"></i>
                    <span class="profile-item-title">Syarat &amp; Ketentuan</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 9. Tentang FindUMB -->
            <div class="profile-menu-item" onclick="openAboutModal()" style="cursor: pointer;">
                <div class="profile-item-left">
                    <i data-lucide="info" class="profile-item-icon"></i>
                    <span class="profile-item-title">Tentang FindUMB</span>
                </div>
                <i data-lucide="chevron-right" class="item-chevron"></i>
            </div>

            <!-- 10. TOMBOL KELUAR (SIGN OUT) -->
            <div class="btn-signout-wrap">
                <button type="button" class="btn-signout" onclick="handleSignOut()">
                    <i data-lucide="log-out"></i>
                    <span>Keluar dari Akun</span>
                </button>
            </div>
        </div>
    </div>

</div>


<!-- ===========================================================================
     2. DESKTOP LAYOUT (min-width: 1024px)
     Lebar Konsisten (max-width: 1380px) Sesuai Referensi Gambar 2
     =========================================================================== -->
<div class="profile-desktop-layout">
    <div class="profile-desktop-container">

        <!-- Page Title & Greeting with Subtext -->
        <div class="profile-desktop-header">
            <h1 class="profile-desktop-title" id="profile-greeting-title">Selamat Datang Kembali, Fauzi!</h1>
            <p class="profile-desktop-sub">Kelola informasi profil civitas, pantau status penanganan laporan, dan cek barang tersimpan Anda.</p>
        </div>

        <!-- CARD 1: User Summary Card -->
        <div class="profile-desktop-card profile-summary-card">
            <div class="profile-summary-left">
                <div class="profile-avatar-wrapper" onclick="triggerAvatarUpload()" title="Klik untuk ganti foto profil">
                    <div class="profile-summary-avatar-box">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=240&auto=format&fit=crop&q=80" alt="Foto Profil" class="profile-summary-avatar-img user-profile-avatar-elem" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <span class="profile-summary-avatar-fallback" style="display: none;">A</span>
                    </div>
                    <div class="avatar-camera-btn" aria-label="Ganti Foto Profil">
                        <i data-lucide="camera"></i>
                    </div>
                </div>
                <div class="profile-summary-details">
                    <h2 class="profile-summary-name" id="user-desktop-name">Ahmad Fauzi</h2>
                    <p class="profile-summary-role" id="user-desktop-role">Mahasiswa</p>
                </div>
            </div>
            <button type="button" class="btn-header-edit-profile btn-card-edit-profile" id="btn-card-edit-profile" onclick="openEditProfileModal()" title="Ubah Profil">
                <span>Edit</span>
                <i data-lucide="pencil"></i>
            </button>
        </div>

        <!-- CARD 2: Informasi Pribadi & Akademik -->
        <div class="profile-desktop-card profile-info-card">
            <div class="card-section-header">
                <h2 class="card-section-title">Informasi Pribadi &amp; Akademik</h2>
            </div>
            <div class="profile-info-grid">
                <!-- Field 1: Nama Lengkap -->
                <div class="info-field-group">
                    <span class="info-field-label">Nama Lengkap</span>
                    <span class="info-field-value" id="user-info-name">Ahmad Fauzi</span>
                </div>

                <!-- Field 2: NIM (Tetap / Permanen) -->
                <div class="info-field-group">
                    <span class="info-field-label">Nomor Induk Mahasiswa (NIM)</span>
                    <span class="info-field-value" id="user-info-nim">41521010045</span>
                </div>

                <!-- Field 3: Fakultas -->
                <div class="info-field-group">
                    <span class="info-field-label">Fakultas</span>
                    <span class="info-field-value" id="user-info-faculty">-</span>
                </div>

                <!-- Field 4: Program Studi -->
                <div class="info-field-group">
                    <span class="info-field-label">Program Studi</span>
                    <span class="info-field-value" id="user-info-prodi">-</span>
                </div>
            </div>
        </div>

        <!-- CARD 3: Aktivitas Saya (Bagian yang Disisakan, Menggantikan Card 3 Gambar 2) -->
        <div class="profile-desktop-card profile-activity-card">
            <div class="card-section-header">
                <div>
                    <h2 class="card-section-title">Aktivitas Saya</h2>
                    <p class="card-section-sub">Ringkasan status pelaporan dan interaksi Anda di FindUMB</p>
                </div>
            </div>

            <!-- Stats Counters Row -->
            <div class="desktop-stats-counters">
                <!-- Counter 1: Laporan Dibuat -->
                <a href="{{ route('my.reports') }}" class="stat-counter-box" title="Lihat Laporan Dibuat">
                    <div class="stat-counter-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i data-lucide="file-text"></i>
                    </div>
                    <div class="stat-counter-text">
                        <span class="stat-counter-val" id="stat-desktop-reports">2</span>
                        <span class="stat-counter-label">Laporan Dibuat</span>
                    </div>
                </a>

                <!-- Counter 2: Terselesaikan -->
                <a href="{{ route('my.reports') }}" class="stat-counter-box" title="Lihat Laporan Selesai">
                    <div class="stat-counter-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i data-lucide="check-circle-2"></i>
                    </div>
                    <div class="stat-counter-text">
                        <span class="stat-counter-val" id="stat-desktop-recovered">1</span>
                        <span class="stat-counter-label">Terselesaikan</span>
                    </div>
                </a>

                <!-- Counter 3: Barang Disimpan (Direct ke Halaman Khusus) -->
                <a href="{{ route('saved.reports') }}" class="stat-counter-box" title="Lihat Barang Disimpan">
                    <div class="stat-counter-icon" style="background: #e0f2fe; color: #0284c7;">
                        <i data-lucide="bookmark"></i>
                    </div>
                    <div class="stat-counter-text">
                        <span class="stat-counter-val" id="stat-desktop-saved">3</span>
                        <span class="stat-counter-label">Barang Disimpan</span>
                    </div>
                </a>
            </div>

            <!-- Activity Items List -->
            <div class="desktop-activity-list">
                <!-- 1. Laporan Saya -->
                <a href="{{ route('my.reports') }}" class="desktop-activity-row">
                    <div class="desktop-act-left">
                        <div class="desktop-act-icon-box">
                            <i data-lucide="file-text"></i>
                        </div>
                        <div class="desktop-act-info">
                            <span class="desktop-act-title">Laporan Saya</span>
                            <span class="desktop-act-desc">Kelola dan pantau barang hilang atau temuan yang Anda daftarkan</span>
                        </div>
                    </div>
                    <div class="desktop-act-right">
                        <span class="desktop-act-pill" id="badge-desktop-my-reports">2 Laporan</span>
                        <i data-lucide="chevron-right" class="desktop-act-chevron"></i>
                    </div>
                </a>

                <!-- 2. Barang Disimpan (Direct ke Halaman Khusus) -->
                <a href="{{ route('saved.reports') }}" class="desktop-activity-row">
                    <div class="desktop-act-left">
                        <div class="desktop-act-icon-box">
                            <i data-lucide="bookmark"></i>
                        </div>
                        <div class="desktop-act-info">
                            <span class="desktop-act-title">Barang Disimpan</span>
                            <span class="desktop-act-desc">Daftar bookmark barang temuan dan kehilangan yang Anda simpan</span>
                        </div>
                    </div>
                    <div class="desktop-act-right">
                        <span class="desktop-act-pill pill-amber" id="badge-desktop-saved-count">3 Disimpan</span>
                        <i data-lucide="chevron-right" class="desktop-act-chevron"></i>
                    </div>
                </a>

                <!-- 3. Pesan & Klaim Masuk -->
                <a href="{{ route('messages') }}" class="desktop-activity-row">
                    <div class="desktop-act-left">
                        <div class="desktop-act-icon-box">
                            <i data-lucide="message-square"></i>
                        </div>
                        <div class="desktop-act-info">
                            <span class="desktop-act-title">Pesan &amp; Klaim Masuk</span>
                            <span class="desktop-act-desc">Verifikasi ciri khusus dan koordinasi serah terima barang</span>
                        </div>
                    </div>
                    <div class="desktop-act-right">
                        <span class="desktop-act-pill pill-red">12 Percakapan</span>
                        <i data-lucide="chevron-right" class="desktop-act-chevron"></i>
                    </div>
                </a>

                <!-- 4. Notifikasi Aktivitas -->
                <a href="{{ route('notifications') }}" class="desktop-activity-row">
                    <div class="desktop-act-left">
                        <div class="desktop-act-icon-box">
                            <i data-lucide="bell"></i>
                        </div>
                        <div class="desktop-act-info">
                            <span class="desktop-act-title">Notifikasi Aktivitas</span>
                            <span class="desktop-act-desc">Pemberitahuan klaim baru, masa tunggu 72 jam, dan jadwal serah terima</span>
                        </div>
                    </div>
                    <div class="desktop-act-right">
                        <span class="desktop-act-pill pill-red" id="badge-desktop-notif">15+ Baru</span>
                        <i data-lucide="chevron-right" class="desktop-act-chevron"></i>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

<!-- ===========================================================================
     3. MODAL EDIT PROFIL (Edit Nama, Fakultas, & Program Studi. NIM Tetap)
     =========================================================================== -->
<div class="modal-overlay" id="modal-edit-profile" onclick="closeEditProfileModal(event)">
    <div class="modal-sheet profile-edit-modal-sheet" onclick="event.stopPropagation()">
        <div class="modal-pill-drag"></div>
        <div class="profile-edit-modal-header">
            <h3 class="modal-title">Ubah Profil</h3>
            <button type="button" class="btn-modal-close-icon" onclick="closeEditProfileModal()" aria-label="Tutup">
                <i data-lucide="x"></i>
            </button>
        </div>
        <p class="modal-desc">Perbarui informasi profil civitas Anda. NIM bersifat permanen dan terdaftar di universitas.</p>
        
        <form id="form-edit-profile" onsubmit="saveProfileEdit(event)">
            <div class="profile-form-vertical">
                <!-- 1. Nama Lengkap (Dapat Diedit) -->
                <div class="profile-input-group">
                    <label class="profile-input-label" for="edit-profile-name">Nama Lengkap</label>
                    <input type="text" id="edit-profile-name" class="profile-input" required value="Ahmad Fauzi">
                </div>

                <!-- 2. NIM (HANYA BACA / TIDAK DAPAT DIEDIT) -->
                <div class="profile-input-group">
                    <label class="profile-input-label" for="edit-profile-nim">Nomor Induk Mahasiswa (NIM)</label>
                    <input type="text" id="edit-profile-nim" class="profile-input profile-input-disabled" value="41521010045" readonly disabled>
                    <span class="profile-input-hint">
                        <i data-lucide="lock" style="width: 12px; height: 12px;"></i>
                        <span>NIM resmi terdaftar pada pangkalan data UMB dan tidak dapat diubah</span>
                    </span>
                </div>

                <!-- 3. Fakultas (Opsional, Default "-") -->
                <div class="profile-input-group">
                    <label class="profile-input-label" for="edit-profile-faculty">Fakultas</label>
                    <input type="text" id="edit-profile-faculty" list="faculty-suggestions" class="profile-input" placeholder="Masukkan nama fakultas (opsional)">
                    <datalist id="faculty-suggestions">
                        <option value="Fakultas Ilmu Komputer">
                        <option value="Fakultas Teknik">
                        <option value="Fakultas Ekonomi dan Bisnis">
                        <option value="Fakultas Ilmu Komunikasi">
                        <option value="Fakultas Desain dan Seni Kreatif">
                        <option value="Fakultas Psikologi">
                    </datalist>
                </div>

                <!-- 4. Program Studi (Opsional, Default "-") -->
                <div class="profile-input-group">
                    <label class="profile-input-label" for="edit-profile-prodi">Program Studi</label>
                    <input type="text" id="edit-profile-prodi" list="prodi-suggestions" class="profile-input" placeholder="Masukkan program studi (opsional)">
                    <datalist id="prodi-suggestions">
                        <option value="S1 Teknik Informatika">
                        <option value="S1 Sistem Informasi">
                        <option value="S1 Teknik Sipil">
                        <option value="S1 Teknik Mesin">
                        <option value="S1 Teknik Elektro">
                        <option value="S1 Teknik Industri">
                        <option value="S1 Manajemen">
                        <option value="S1 Akuntansi">
                        <option value="S1 Ilmu Komunikasi">
                        <option value="S1 Desain Komunikasi Visual">
                        <option value="S1 Desain Interior">
                        <option value="S1 Psikologi">
                    </datalist>
                </div>
            </div>

            <div class="profile-modal-actions">
                <button type="button" class="btn-profile-cancel" onclick="closeEditProfileModal()">Batal</button>
                <button type="submit" class="btn-profile-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden File Input untuk Ganti Foto Profil dari Icon Kamera Utama -->
<input type="file" id="input-avatar-file" accept="image/*" style="display: none;" onchange="handleAvatarFileSelect(this)">

<!-- Modal 1: Kebijakan Privasi -->
<div class="modal-overlay" id="privacy-modal" onclick="closePrivacyModal(event)">
    <div class="modal-sheet guide-modal-sheet" onclick="event.stopPropagation()">
        <div class="modal-pill-drag"></div>
        <h3 class="modal-title">Kebijakan Privasi</h3>
        <p class="modal-desc">Kebijakan perlindungan data dan privasi civitas akademika Universitas Mercu Buana pada platform FindUMB.</p>
        
        <div class="guide-modal-body">
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="shield-check"></i>
                    </div>
                    <h4 class="guide-card-title">Perlindungan Identitas Civitas</h4>
                </div>
                <ul class="guide-list">
                    <li>Kontak pribadi civitas (nomor HP, WhatsApp, &amp; email) <strong>tidak pernah dipublikasikan terbuka</strong> untuk mencegah spam dan penipuan.</li>
                    <li>Data akun civitas (NIM dan email student) hanya digunakan untuk keperluan verifikasi keabsahan civitas internal UMB.</li>
                    <li>Tampilan publik pada laporan barang hanya mencantumkan nama panggilan/singkat dan fakultas pelapor.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="lock"></i>
                    </div>
                    <h4 class="guide-card-title">Keamanan Komunikasi Internal</h4>
                </div>
                <ul class="guide-list">
                    <li>Seluruh koordinasi awal serah terima dilakukan melalui fitur <strong>Pesan / Chat</strong> resmi yang terisolasi di dalam FindUMB.</li>
                    <li>Sistem tidak meminta dan melarang civitas membagikan informasi sensitif perbankan (PIN, kode OTP, atau transfer uang).</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="eye-off"></i>
                    </div>
                    <h4 class="guide-card-title">Kerahasiaan Ciri Khusus Barang</h4>
                </div>
                <ul class="guide-list">
                    <li>Rincian ciri khusus rahasia (seperti nomor seri, goresan mikro, atau stiker pembeda) tidak dipublikasikan ke publik.</li>
                    <li>Ciri khusus hanya digunakan sebagai instrumen pencocokan bukti kepemilikan sah saat klaim serah terima berlangsung.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="database"></i>
                    </div>
                    <h4 class="guide-card-title">Retensi &amp; Kendali Data</h4>
                </div>
                <ul class="guide-list">
                    <li>Civitas memiliki kendali penuh untuk memperbarui atau menghapus laporan barang melalui menu <strong>Laporan Saya</strong>.</li>
                    <li>Laporan yang telah tuntas diubah statusnya menjadi <strong>Terselesaikan</strong> dan diarsipkan secara aman.</li>
                </ul>
            </div>
        </div>

        <button type="button" class="btn-guide-close" onclick="closePrivacyModal()">Tutup Kebijakan</button>
    </div>
</div>

<!-- Modal 2: Syarat & Ketentuan -->
<div class="modal-overlay" id="terms-modal" onclick="closeTermsModal(event)">
    <div class="modal-sheet guide-modal-sheet" onclick="event.stopPropagation()">
        <div class="modal-pill-drag"></div>
        <h3 class="modal-title">Syarat &amp; Ketentuan</h3>
        <p class="modal-desc">Pedoman etika dan aturan penggunaan layanan Lost &amp; Found di lingkungan Universitas Mercu Buana.</p>
        
        <div class="guide-modal-body">
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="scale"></i>
                    </div>
                    <h4 class="guide-card-title">Integritas &amp; Kejujuran Pelaporan</h4>
                </div>
                <ul class="guide-list">
                    <li>Setiap civitas wajib memberikan informasi pelaporan yang <strong>benar, akurat, dan dapat dipertanggungjawabkan</strong>.</li>
                    <li>Dilarang keras membuat laporan fiktif, melakukan klaim sepihak tanpa bukti, atau mengunggah konten provokatif.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="user-check"></i>
                    </div>
                    <h4 class="guide-card-title">Prosedur Klaim Kepemilikan</h4>
                </div>
                <ul class="guide-list">
                    <li>Pengklaim barang temuan <strong>wajib membuktikan ciri khusus rahasia</strong> kepada penemu atau petugas sebelum barang diserahkan.</li>
                    <li>Penemu atau pos penitipan kampus berhak menolak klaim jika bukti kepemilikan tidak dapat dipertanggungjawabkan.</li>
                    <li>Serah terima fisik diimbau dilakukan di area kampus yang ramai atau difasilitasi oleh Pos Satpam Resmi UMB.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="building-2"></i>
                    </div>
                    <h4 class="guide-card-title">Penanganan Barang Berharga</h4>
                </div>
                <ul class="guide-list">
                    <li>Barang bernilai tinggi (laptop, dompet, perhiasan, KTP, KTM, &amp; dokumen negara) sangat disarankan segera dititipkan ke <strong>Pos Satpam Gerbang Barat / Gedung Tower</strong>.</li>
                    <li>FindUMB berfungsi sebagai sarana publikasi informasi civitas dan tidak bertanggung jawab atas kelalaian fisik di luar sistem.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="alert-triangle"></i>
                    </div>
                    <h4 class="guide-card-title">Sanksi Kode Etik Kampus</h4>
                </div>
                <ul class="guide-list">
                    <li>Upaya penggelapan barang temuan atau pemalsuan identitas akan diteruskan ke <strong>Biro Kemahasiswaan &amp; Komisi Disiplin UMB</strong>.</li>
                    <li>Pelanggaran berat akan dikenakan sanksi akademik sesuai peraturan kode etik civitas akademika Universitas Mercu Buana.</li>
                </ul>
            </div>
        </div>

        <button type="button" class="btn-guide-close" onclick="closeTermsModal()">Tutup Ketentuan</button>
    </div>
</div>

<!-- Modal 3: Tentang FindUMB -->
<div class="modal-overlay" id="about-modal" onclick="closeAboutModal(event)">
    <div class="modal-sheet guide-modal-sheet" onclick="event.stopPropagation()">
        <div class="modal-pill-drag"></div>
        <h3 class="modal-title">Tentang FindUMB</h3>
        <p class="modal-desc">Platform digital Lost &amp; Found terpadu civitas akademika Universitas Mercu Buana.</p>
        
        <div class="guide-modal-body">
            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="sparkles"></i>
                    </div>
                    <h4 class="guide-card-title">Platform Lost &amp; Found Kampus</h4>
                </div>
                <ul class="guide-list">
                    <li><strong>FindUMB</strong> dirancang untuk mewujudkan ekosistem kampus yang saling peduli, jujur, dan aman dalam mengembalikan barang hilang civitas.</li>
                    <li>Dilengkapi fitur pelaporan cepat, pencarian kategori cerdas, sistem verifikasi chat internal, dan integrasi pos penitipan resmi.</li>
                    <li>Versi Rilis: <strong>v1.0.0 (Responsive Web &amp; Mobile-Optimized)</strong>.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="map-pin"></i>
                    </div>
                    <h4 class="guide-card-title">Cakupan Wilayah Kampus UMB</h4>
                </div>
                <ul class="guide-list">
                    <li><strong>Kampus Meruya (Pusat)</strong>: Gedung Tower, Blok A s/d E, Parkir Barat/Timur, Kantin, &amp; Masjid Manarul 'Amal.</li>
                    <li><strong>Kampus Menteng, Kampus Pejaten, &amp; Kampus Warung Buncit</strong>.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="code-2"></i>
                    </div>
                    <h4 class="guide-card-title">Pengembang &amp; Civitas</h4>
                </div>
                <ul class="guide-list">
                    <li>Pengembang:
                        <ul style="margin-top: 0.3rem; padding-left: 1.15rem; list-style-type: disc; display: flex; flex-direction: column; gap: 0.2rem;">
                            <li><strong>Muhammad Isya Zidan Maulana</strong> (NIM: 41524010001)</li>
                            <li><strong>Faiz Muhammad Ridho</strong> (NIM: 41524010046)</li>
                            <li><strong>Rifky Nabil Firdaus</strong> (41524010055)</li>
                        </ul>
                    </li>
                    <li>Program Studi: <strong>Teknik Informatika — Fakultas Ilmu Komputer</strong>.</li>
                    <li>Institusi: <strong>Universitas Mercu Buana</strong> Jakarta.</li>
                </ul>
            </div>

            <div class="guide-card">
                <div class="guide-card-header">
                    <div class="guide-card-icon">
                        <i data-lucide="help-circle"></i>
                    </div>
                    <h4 class="guide-card-title">Layanan Bantuan &amp; Helpdesk</h4>
                </div>
                <ul class="guide-list">
                    <li>Email Dukungan: <strong>-</strong></li>
                    <li>Lokasi Fisik: <strong>Pos Pelayanan Keamanan Kampus Meruya (24 Jam)</strong>.</li>
                    <li>Saran &amp; Masukan: Hubungi biro administrasi kampus atau melalui pesan pengelola aplikasi.</li>
                </ul>
            </div>
        </div>

        <button type="button" class="btn-guide-close" onclick="closeAboutModal()">Tutup Informasi</button>
    </div>
</div>

<!-- Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite"></div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        loadUserProfile();
        updateProfileStats();
        updateProfileNotifBadge();
    });

    function triggerAvatarUpload() {
        const fileInput = document.getElementById('input-avatar-file');
        if (fileInput) fileInput.click();
    }

    function handleAvatarFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 5 * 1024 * 1024) {
                showToast('Ukuran foto maksimal 5 MB.');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarDataUrl = e.target.result;
                document.querySelectorAll('.user-profile-avatar-elem').forEach(img => {
                    img.src = avatarDataUrl;
                    img.style.display = 'block';
                });
                document.querySelectorAll('.profile-avatar-fallback, .profile-summary-avatar-fallback').forEach(fb => {
                    fb.style.display = 'none';
                });

                let existingProfile = {};
                try {
                    const raw = localStorage.getItem('findumb_user_profile');
                    if (raw) existingProfile = JSON.parse(raw);
                } catch(err) {}

                const updated = {
                    ...existingProfile,
                    avatar: avatarDataUrl
                };

                try {
                    localStorage.setItem('findumb_user_profile', JSON.stringify(updated));
                } catch(err) {}

                showToast('✓ Foto profil berhasil diperbarui!');
            };
            reader.readAsDataURL(file);
        }
    }

    function getGreetingName(fullName) {
        if (!fullName) return 'Fauzi';
        const trimmed = fullName.trim();
        if (trimmed.toLowerCase().includes('fauzi')) return 'Fauzi';
        const parts = trimmed.split(/\s+/);
        return parts[0] || 'Fauzi';
    }

    function loadUserProfile() {
        try {
            const raw = localStorage.getItem('findumb_user_profile');
            let profile = {};
            if (raw) {
                profile = JSON.parse(raw);
            }

            const name = profile.name || 'Ahmad Fauzi';
            const faculty = (profile.faculty && profile.faculty.trim() !== '') ? profile.faculty.trim() : '-';
            const prodi = (profile.prodi && profile.prodi.trim() !== '') ? profile.prodi.trim() : '-';

            const elGreeting = document.getElementById('profile-greeting-title');
            if (elGreeting) elGreeting.textContent = `Selamat Datang Kembali, ${getGreetingName(name)}!`;

            const elName = document.getElementById('user-display-name');
            if (elName) elName.textContent = name;
            const elDeskName = document.getElementById('user-desktop-name');
            if (elDeskName) elDeskName.textContent = name;
            const elInfoName = document.getElementById('user-info-name');
            if (elInfoName) elInfoName.textContent = name;

            const elFaculty = document.getElementById('user-info-faculty');
            if (elFaculty) elFaculty.textContent = faculty;
            const elProdi = document.getElementById('user-info-prodi');
            if (elProdi) elProdi.textContent = prodi;

            const elMobName = document.getElementById('user-mobile-info-name');
            if (elMobName) elMobName.textContent = name;
            const elMobFaculty = document.getElementById('user-mobile-info-faculty');
            if (elMobFaculty) elMobFaculty.textContent = faculty;
            const elMobProdi = document.getElementById('user-mobile-info-prodi');
            if (elMobProdi) elMobProdi.textContent = prodi;

            const elRole = document.getElementById('user-desktop-role');
            if (elRole) {
                elRole.textContent = prodi !== '-' ? `Mahasiswa — ${prodi}` : 'Mahasiswa';
            }

            if (profile.avatar) {
                document.querySelectorAll('.user-profile-avatar-elem').forEach(img => {
                    img.src = profile.avatar;
                    img.style.display = 'block';
                });
                document.querySelectorAll('.profile-avatar-fallback, .profile-summary-avatar-fallback').forEach(fb => {
                    fb.style.display = 'none';
                });
            }
        } catch(e) {}
    }

    function openEditProfileModal() {
        const modal = document.getElementById('modal-edit-profile');
        if (!modal) return;

        let profile = {};
        try {
            const raw = localStorage.getItem('findumb_user_profile');
            if (raw) profile = JSON.parse(raw);
        } catch(e) {}

        const currentName = profile.name || document.getElementById('user-desktop-name')?.textContent || 'Ahmad Fauzi';
        const currentFaculty = (profile.faculty && profile.faculty !== '-') ? profile.faculty : '';
        const currentProdi = (profile.prodi && profile.prodi !== '-') ? profile.prodi : '';

        document.getElementById('edit-profile-name').value = currentName.trim();
        document.getElementById('edit-profile-nim').value = '41521010045';
        document.getElementById('edit-profile-faculty').value = currentFaculty;
        document.getElementById('edit-profile-prodi').value = currentProdi;

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeEditProfileModal(e) {
        if (!e || e.target.id === 'modal-edit-profile' || e.target.classList.contains('btn-profile-cancel') || e.target.closest('.btn-modal-close-icon')) {
            const modal = document.getElementById('modal-edit-profile');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    }

    function saveProfileEdit(e) {
        e.preventDefault();
        const name = document.getElementById('edit-profile-name').value.trim();
        const facultyInput = document.getElementById('edit-profile-faculty').value.trim();
        const prodiInput = document.getElementById('edit-profile-prodi').value.trim();

        if (!name) {
            showToast('Nama lengkap tidak boleh kosong.');
            const nameInput = document.getElementById('edit-profile-name');
            if (nameInput) nameInput.focus();
            return;
        }

        let existingProfile = {};
        try {
            const raw = localStorage.getItem('findumb_user_profile');
            if (raw) existingProfile = JSON.parse(raw);
        } catch(err) {}

        const profileData = {
            ...existingProfile,
            name: name,
            nim: '41521010045',
            faculty: facultyInput ? facultyInput : '-',
            prodi: prodiInput ? prodiInput : '-'
        };

        try {
            localStorage.setItem('findumb_user_profile', JSON.stringify(profileData));
        } catch(err) {}

        // Update elements in DOM
        const elGreeting = document.getElementById('profile-greeting-title');
        if (elGreeting) elGreeting.textContent = `Selamat Datang Kembali, ${getGreetingName(name)}!`;

        document.getElementById('user-display-name').textContent = name;
        document.getElementById('user-desktop-name').textContent = name;
        document.getElementById('user-info-name').textContent = name;

        const elFaculty = document.getElementById('user-info-faculty');
        if (elFaculty) elFaculty.textContent = profileData.faculty;
        const elProdi = document.getElementById('user-info-prodi');
        if (elProdi) elProdi.textContent = profileData.prodi;

        const elMobName = document.getElementById('user-mobile-info-name');
        if (elMobName) elMobName.textContent = name;
        const elMobFaculty = document.getElementById('user-mobile-info-faculty');
        if (elMobFaculty) elMobFaculty.textContent = profileData.faculty;
        const elMobProdi = document.getElementById('user-mobile-info-prodi');
        if (elMobProdi) elMobProdi.textContent = profileData.prodi;

        const elRole = document.getElementById('user-desktop-role');
        if (elRole) {
            elRole.textContent = profileData.prodi !== '-' ? `Mahasiswa — ${profileData.prodi}` : 'Mahasiswa';
        }

        closeEditProfileModal();
        showToast('✓ Profil civitas berhasil diperbarui!');
    }

    function handleEditProfile() {
        openEditProfileModal();
    }

    function updateProfileNotifBadge() {
        const pill = document.getElementById('profile-notif-pill');
        const deskNotif = document.getElementById('badge-desktop-notif');
        try {
            const raw = localStorage.getItem('findumb_notifications');
            let unreadCount = 3;
            if (raw) {
                const list = JSON.parse(raw);
                if (Array.isArray(list)) {
                    unreadCount = list.filter(n => !n.read).length;
                }
            }
            if (unreadCount > 0) {
                if (pill) {
                    pill.textContent = `${unreadCount} Baru`;
                    pill.style.display = 'inline-block';
                }
                if (deskNotif) {
                    deskNotif.textContent = `${unreadCount} Baru`;
                }
            } else {
                if (pill) pill.style.display = 'none';
                if (deskNotif) deskNotif.textContent = 'Semua Dibaca';
            }
        } catch(e) {}
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

    const DEFAULT_USER_REPORTS = [
        {
            id: '1',
            type: 'lost',
            title: 'Samsung SM-A135F',
            brand: 'Samsung Galaxy A13',
            category: 'Elektronik & HP',
            status: 'lost',
            is_mine: true
        },
        {
            id: '5',
            type: 'lost',
            title: 'Cincin Emas Berlian',
            brand: 'Perhiasan Emas Kuning',
            category: 'Jam & Aksesori',
            status: 'lost',
            is_mine: true
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

    function updateProfileStats() {
        try {
            const userReports = getUserReports();
            const statReports = document.getElementById('stat-reports-count');
            const statRecovered = document.getElementById('stat-recovered-count');
            const statSaved = document.getElementById('stat-saved-count');

            const deskReports = document.getElementById('stat-desktop-reports');
            const deskRecovered = document.getElementById('stat-desktop-recovered');
            const deskSaved = document.getElementById('stat-desktop-saved');
            const deskMyReportsBadge = document.getElementById('badge-desktop-my-reports');
            const deskSavedBadge = document.getElementById('badge-desktop-saved-count');
            const mobSavedPill = document.getElementById('mobile-saved-pill');

            const count = userReports.length;
            const completedCount = userReports.filter(r => r.status === 'resolved' || r.status === 'completed' || r.type === 'found').length;
            const completedVal = completedCount > 0 ? completedCount : 1;

            let savedCount = 3;
            try {
                const rawBookmarks = localStorage.getItem('findumb_bookmarks');
                if (rawBookmarks) {
                    const parsed = JSON.parse(rawBookmarks);
                    if (Array.isArray(parsed)) savedCount = parsed.length;
                } else {
                    const rawSaved = localStorage.getItem('findumb_saved_reports_list');
                    if (rawSaved) {
                        const parsed = JSON.parse(rawSaved);
                        if (Array.isArray(parsed)) savedCount = parsed.length;
                    }
                }
            } catch(e) {}

            if (statReports) statReports.textContent = count;
            if (statRecovered) statRecovered.textContent = completedVal;
            if (statSaved) statSaved.textContent = savedCount;

            if (deskReports) deskReports.textContent = count;
            if (deskRecovered) deskRecovered.textContent = completedVal;
            if (deskSaved) deskSaved.textContent = savedCount;
            if (deskMyReportsBadge) deskMyReportsBadge.textContent = `${count} Laporan`;
            if (deskSavedBadge) deskSavedBadge.textContent = `${savedCount} Disimpan`;
            if (mobSavedPill) mobSavedPill.textContent = savedCount;
        } catch(e) {}
    }

    function handleSignOut() {
        if (confirm('Apakah Anda ingin keluar dari sesi akun FindUMB?')) {
            showToast('Anda telah keluar.');
            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 800);
        }
    }

    function openPrivacyModal() {
        const modal = document.getElementById('privacy-modal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closePrivacyModal(e) {
        if (!e || e.target.id === 'privacy-modal' || e.target.classList.contains('btn-guide-close') || e.target.closest('.btn-guide-close')) {
            const modal = document.getElementById('privacy-modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    }

    function openTermsModal() {
        const modal = document.getElementById('terms-modal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeTermsModal(e) {
        if (!e || e.target.id === 'terms-modal' || e.target.classList.contains('btn-guide-close') || e.target.closest('.btn-guide-close')) {
            const modal = document.getElementById('terms-modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    }

    function openAboutModal() {
        const modal = document.getElementById('about-modal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function closeAboutModal(e) {
        if (!e || e.target.id === 'about-modal' || e.target.classList.contains('btn-guide-close') || e.target.closest('.btn-guide-close')) {
            const modal = document.getElementById('about-modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    }
</script>
@endpush
