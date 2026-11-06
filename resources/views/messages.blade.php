@extends('layouts.app')

@section('title', 'Pesan & Klaim — Find UMB')

@push('styles')
<style>
    /* Messages Page Container - Consistent Spacing with Search View */
    .messages-container {
        padding: 0.5rem 1.25rem 0.65rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.95rem;
    }

    /* 1. Search Bar for Conversations */
    .messages-search-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
        transition: var(--transition);
        position: relative;
    }

    .messages-search-wrapper:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.12);
    }

    .messages-search-icon {
        color: var(--text-muted);
        display: flex;
        align-items: center;
    }

    .messages-search-icon svg, .messages-search-icon .lucide {
        width: 19px;
        height: 19px;
        stroke-width: 2.2;
    }

    .messages-search-field {
        flex: 1;
        border: none;
        background: transparent;
        font-family: var(--font-main);
        font-size: 0.875rem;
        color: var(--text-main);
        outline: none;
        min-width: 0;
    }

    .messages-search-field::placeholder {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .messages-clear-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 0.2rem;
        border-radius: 50%;
        transition: var(--transition);
    }

    .messages-clear-btn:hover {
        color: #0f172a;
        background: #f1f5f9;
    }

    .messages-clear-btn svg, .messages-clear-btn .lucide {
        width: 16px;
        height: 16px;
        stroke-width: 2.2;
    }

    /* 2. Status Segmented Switch Control (Semua | Klaim Aktif | Selesai) */
    .messages-segmented-track {
        display: flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: var(--radius-pill);
        position: relative;
        gap: 4px;
    }

    .messages-segment-pill {
        flex: 1;
        min-width: 0;
        white-space: nowrap;
        border: none;
        background: transparent;
        color: #64748b;
        font-family: var(--font-main);
        font-size: 0.785rem;
        font-weight: 500;
        padding: 0.52rem 0.35rem;
        border-radius: var(--radius-pill);
        cursor: pointer;
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        text-align: center;
        user-select: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
    }

    .messages-segment-pill span {
        white-space: nowrap;
    }

    .messages-segment-pill.active {
        background: #ffffff;
        color: #0f172a;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
    }

    .segment-badge-count {
        font-size: 0.685rem;
        padding: 0.1rem 0.4rem;
        border-radius: var(--radius-pill);
        background: #e2e8f0;
        color: #475569;
        font-weight: 600;
        flex-shrink: 0;
        line-height: 1.2;
    }

    .messages-segment-pill.active .segment-badge-count {
        background: var(--primary-light);
        color: var(--primary-dark);
    }

    /* 3. Results Count Meta Row */
    .messages-meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.05rem;
    }

    .messages-count-text {
        font-family: var(--font-main);
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
    }

    .btn-clear-msg-filters {
        background: transparent;
        border: none;
        color: var(--primary);
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
        display: none;
    }

    .btn-clear-msg-filters:hover {
        text-decoration: underline;
    }

    /* 4. Conversations Stack List */
    .conversations-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .conversation-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.055);
        border-radius: var(--radius-md);
        padding: 0.75rem 0.85rem;
        display: flex;
        gap: 0.8rem;
        align-items: center;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        position: relative;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .conversation-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .conversation-card:active {
        transform: scale(0.99);
    }

    .conversation-card.unread {
        background: #f8fafc;
    }

    /* Avatar with status indicator */
    .conversation-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .conversation-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        background: #e2e8f0;
        display: block;
        border: 1.5px solid #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .avatar-online-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 12px;
        height: 12px;
        background: #22c55e;
        border: 2px solid #ffffff;
        border-radius: 50%;
    }

    /* Conversation Info */
    .conversation-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .conversation-top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .conversation-title-group {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        min-width: 0;
        flex: 1;
        overflow: hidden;
    }

    .conversation-user-name {
        font-family: var(--font-main);
        font-size: 0.885rem;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .conversation-item-tag {
        font-family: var(--font-main);
        display: inline-block;
        vertical-align: middle;
        background: #f1f5f9;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 500;
        padding: 0.12rem 0.5rem;
        border-radius: var(--radius-pill);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
        min-width: 0;
        flex: 0 1 auto;
        line-height: 1.35;
    }

    .conversation-time {
        font-family: var(--font-main);
        font-size: 0.725rem;
        color: #94a3b8;
        font-weight: 500;
        flex-shrink: 0;
    }

    /* Preview message row */
    .conversation-bottom-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .conversation-snippet {
        font-family: var(--font-main);
        font-size: 0.775rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }

    .conversation-card.unread .conversation-snippet {
        color: #0f172a;
        font-weight: 600;
    }

    .unread-bubble {
        min-width: 20px;
        height: 20px;
        padding: 0 5px;
        background: #ef4444;
        color: #ffffff;
        font-size: 0.685rem;
        font-weight: 700;
        border-radius: var(--radius-pill);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .read-check-icon {
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
    }

    .read-check-icon svg, .read-check-icon .lucide {
        width: 15px;
        height: 15px;
    }

    /* 5. Friendly Empty State */
    .messages-empty-state {
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

    /* 6. Interactive Chat Thread Modal / Full Sheet Overlay */
    .chat-thread-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(3px);
        z-index: 100;
        display: none;
        align-items: flex-end;
        justify-content: center;
        transition: opacity 0.25s ease;
    }

    .chat-thread-overlay.active {
        display: flex;
    }

    .chat-thread-card {
        background: #ffffff;
        width: 100%;
        max-width: 480px;
        height: 92vh;
        border-radius: 24px 24px 0 0;
        display: flex;
        flex-direction: column;
        box-shadow: 0 -4px 24px rgba(15, 23, 42, 0.16);
        animation: slideUpChat 0.26s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }

    @keyframes slideUpChat {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }

    /* Chat Header */
    .chat-sheet-header {
        padding: 0.85rem 1.15rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #ffffff;
        z-index: 10;
    }

    .chat-back-btn {
        background: #f8fafc;
        border: 1px solid var(--border-subtle);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        cursor: pointer;
        transition: var(--transition);
    }

    .chat-back-btn:hover {
        background: #f1f5f9;
        color: var(--primary);
    }

    .chat-back-btn svg, .chat-back-btn .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
    }

    .chat-header-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
    }

    .chat-header-meta {
        flex: 1;
        min-width: 0;
    }

    .chat-header-name {
        font-family: var(--font-main);
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .chat-header-item-title {
        font-family: var(--font-main);
        font-size: 0.725rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Top Handover Action Widget (PRD PR-06 - Replaces old stepper) */
    .chat-claim-tracker {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.65rem 1.15rem;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        z-index: 10;
    }

    /* Chat Messages Body */
    .chat-messages-body {
        flex: 1;
        padding: 1rem 1.15rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        background: #f8fafc;
    }

    .chat-bubble-row {
        display: flex;
        flex-direction: column;
        max-width: 82%;
        flex-shrink: 0;
    }

    .chat-bubble-row.incoming {
        align-self: flex-start;
    }

    .chat-bubble-row.outgoing {
        align-self: flex-end;
    }

    .chat-bubble {
        padding: 0.65rem 0.85rem;
        border-radius: 16px;
        font-family: var(--font-main);
        font-size: 0.825rem;
        line-height: 1.4;
    }

    .chat-bubble-row.incoming .chat-bubble {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .chat-bubble-row.outgoing .chat-bubble {
        background: var(--primary);
        color: #ffffff;
        border-bottom-right-radius: 4px;
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.25);
    }

    .chat-bubble-time {
        font-size: 0.675rem;
        color: #94a3b8;
        margin-top: 0.2rem;
        padding: 0 0.2rem;
    }

    .chat-bubble-row.outgoing .chat-bubble-time {
        text-align: right;
    }

    /* Enhanced Handover Action Card (PRD PR-06) */
    .handover-confirm-box {
        background: linear-gradient(145deg, #ffffff 0%, #f0fdf4 100%);
        border: 1.5px solid #86efac;
        border-radius: 16px;
        padding: 0.7rem 0.95rem;
        display: flex;
        flex-direction: column;
        margin-top: 0;
        box-shadow: 0 2px 8px -2px rgba(22, 163, 74, 0.08), 0 1px 3px -1px rgba(15, 23, 42, 0.03);
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .handover-confirm-box.confirmed {
        border-color: #86efac;
        background: #f0fdf4;
        padding: 0.85rem 1rem;
    }

    .handover-card-pending,
    .handover-card-success {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    /* Clickable Header for Accordion */
    .handover-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        cursor: pointer;
        user-select: none;
        border-radius: 10px;
        transition: background 0.15s ease;
    }

    .handover-card-header.static {
        cursor: default;
    }

    .handover-card-header:hover:not(.static) .handover-confirm-title {
        color: #15803d;
    }

    .handover-title-wrapper {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        min-width: 0;
    }

    .handover-title-text-group {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .handover-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #dcfce7;
        color: #15803d;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(22, 163, 74, 0.15);
    }

    .handover-icon-badge.success {
        background: #16a34a;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(22, 163, 74, 0.3);
    }

    .handover-icon-badge svg, .handover-icon-badge .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2.3;
    }

    .handover-confirm-title {
        font-family: var(--font-heading);
        font-size: 0.825rem;
        font-weight: 700;
        color: #14532d;
        margin: 0;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.15s ease;
    }

    .handover-confirm-title.text-success {
        color: #15803d;
    }

    .handover-step-subtitle {
        font-family: var(--font-main);
        font-size: 0.675rem;
        color: #64748b;
        display: block;
        margin-top: 0.05rem;
        white-space: nowrap;
    }

    .handover-header-right {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        flex-shrink: 0;
    }

    .handover-step-tag {
        font-family: var(--font-main);
        font-size: 0.625rem;
        font-weight: 700;
        color: #15803d;
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        padding: 0.15rem 0.5rem;
        border-radius: var(--radius-pill);
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    .handover-step-tag.success {
        background: #16a34a;
        color: #ffffff;
        border-color: #16a34a;
    }

    .handover-chevron-btn {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), background 0.15s ease, color 0.15s ease;
    }

    .handover-card-header:hover .handover-chevron-btn {
        background: #e2e8f0;
        color: #0f172a;
    }

    .handover-chevron-btn svg, .handover-chevron-btn .lucide {
        width: 14px;
        height: 14px;
        stroke-width: 2.4;
    }

    /* Accordion Toggle States */
    .handover-confirm-box.expanded .handover-chevron-btn {
        transform: rotate(180deg);
        background: #dcfce7;
        color: #15803d;
    }

    .handover-collapsible-body {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .handover-confirm-box.expanded .handover-collapsible-body {
        grid-template-rows: 1fr;
    }

    .handover-collapsible-content {
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        opacity: 0;
        transform: translateY(-4px);
        transition: opacity 0.25s ease, transform 0.25s ease, padding 0.25s ease;
    }

    .handover-confirm-box.expanded .handover-collapsible-content {
        opacity: 1;
        transform: translateY(0);
        padding-top: 0.75rem;
    }

    .handover-confirm-desc {
        font-family: var(--font-main);
        font-size: 0.735rem;
        color: #334155;
        line-height: 1.45;
        background: rgba(255, 255, 255, 0.65);
        border: 1px solid rgba(220, 252, 231, 0.8);
        border-radius: 10px;
        padding: 0.55rem 0.75rem;
    }

    .handover-confirm-desc.success {
        background: #ffffff;
        border-color: #bbf7d0;
        color: #1e293b;
    }

    /* Verification Checklist inside card */
    .handover-checkbox-wrap {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        cursor: pointer;
        user-select: none;
        padding: 0.55rem 0.75rem;
        border-radius: 10px;
        background: #ffffff;
        border: 1.4px solid #cbd5e1;
        transition: all 0.2s ease;
    }

    .handover-checkbox-wrap:hover {
        border-color: #86efac;
        background: #f0fdf4;
    }

    .handover-checkbox-wrap.error {
        border-color: #ef4444;
        background: #fef2f2;
        animation: shakeCard 0.35s ease;
    }

    @keyframes shakeCard {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-5px); }
        40% { transform: translateX(5px); }
        60% { transform: translateX(-3px); }
        80% { transform: translateX(3px); }
    }

    .handover-checkbox {
        width: 17px;
        height: 17px;
        margin-top: 1px;
        accent-color: #16a34a;
        cursor: pointer;
        flex-shrink: 0;
    }

    .handover-checkbox-label {
        font-family: var(--font-main);
        font-size: 0.735rem;
        color: #1e293b;
        line-height: 1.35;
        font-weight: 500;
    }

    .handover-checkbox-label strong {
        color: #15803d;
        font-weight: 600;
    }

    .handover-validation-error {
        display: none;
        align-items: center;
        gap: 0.35rem;
        font-family: var(--font-main);
        font-size: 0.7rem;
        color: #dc2626;
        font-weight: 600;
        padding: 0 0.2rem;
    }

    .handover-validation-error.show {
        display: flex;
    }

    .handover-validation-error svg, .handover-validation-error .lucide {
        width: 14px;
        height: 14px;
        stroke-width: 2.2;
        flex-shrink: 0;
    }

    /* Button Action */
    .btn-action-handover {
        background: #16a34a;
        color: #ffffff;
        border: none;
        padding: 0.65rem 1.15rem;
        border-radius: var(--radius-pill);
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        text-align: center;
        box-shadow: none;
        transition: background 0.15s ease;
    }

    .btn-action-handover:hover {
        background: #15803d;
        box-shadow: none;
    }

    .btn-action-handover:active {
        background: #166534;
        box-shadow: none;
    }

    /* Handover Confirmation Dialog Modal (PRD PR-06) */
    .confirm-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 150;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        transition: opacity 0.2s ease;
    }

    .confirm-modal-overlay.active {
        display: flex;
    }

    .confirm-modal-dialog {
        background: #ffffff;
        width: 100%;
        max-width: 380px;
        border-radius: 20px;
        padding: 1.4rem 1.25rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        animation: popModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes popModal {
        from { transform: scale(0.92); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .confirm-modal-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #dcfce7;
        color: #15803d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.85rem;
        box-shadow: 0 0 0 6px #f0fdf4;
    }

    .confirm-modal-icon-circle svg, .confirm-modal-icon-circle .lucide {
        width: 26px;
        height: 26px;
        stroke-width: 2.3;
    }

    .confirm-modal-title {
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 0.35rem 0;
    }

    .confirm-modal-desc {
        font-family: var(--font-main);
        font-size: 0.775rem;
        color: #64748b;
        line-height: 1.45;
        margin: 0 0 1.15rem 0;
    }

    .confirm-modal-summary {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.35rem 0.95rem;
        display: flex;
        flex-direction: column;
        margin-bottom: 1.25rem;
        text-align: left;
    }

    .confirm-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.735rem;
        font-family: var(--font-main);
        padding: 0.58rem 0.2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .confirm-summary-row:last-child {
        border-bottom: none;
    }

    .confirm-summary-row .summary-label {
        color: #64748b;
        font-weight: 500;
    }

    .confirm-summary-row .summary-value {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    .summary-badge-resolved {
        font-size: 0.65rem;
        font-weight: 700;
        color: #15803d;
        background: #dcfce7;
        padding: 0.15rem 0.45rem;
        border-radius: var(--radius-pill);
    }

    .confirm-modal-actions {
        display: flex;
        gap: 0.65rem;
        width: 100%;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 0.65rem 0.75rem;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: var(--radius-pill);
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-modal-cancel:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-modal-confirm {
        flex: 1.4;
        padding: 0.65rem 0.85rem;
        background: #16a34a;
        color: #ffffff;
        border: none;
        border-radius: var(--radius-pill);
        font-family: var(--font-main);
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: none;
        text-align: center;
        transition: background 0.15s ease;
    }

    .btn-modal-confirm:hover {
        background: #15803d;
        box-shadow: none;
    }

    .btn-modal-confirm:active {
        background: #166534;
        box-shadow: none;
    }

    /* Chat Input Footer */
    .chat-sheet-footer {
        padding: 0.75rem 1.15rem 1.25rem 1.15rem;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .chat-input-field {
        flex: 1;
        border: 1px solid var(--border-subtle);
        background: #f8fafc;
        padding: 0.65rem 0.95rem;
        border-radius: var(--radius-pill);
        font-family: var(--font-main);
        font-size: 0.825rem;
        color: var(--text-main);
        outline: none;
        transition: var(--transition);
    }

    .chat-input-field:focus {
        background: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(21, 114, 184, 0.12);
    }

    .chat-send-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--primary);
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(21, 114, 184, 0.25);
    }

    .chat-send-btn:hover {
        background: var(--primary-dark);
        transform: scale(1.04);
    }

    .chat-send-btn svg, .chat-send-btn .lucide {
        width: 17px;
        height: 17px;
        stroke-width: 2.2;
    }

    /* Floating Toast Notification */
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
        z-index: 200;
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

    /* Responsive Desktop Adaptations (min-width: 1024px) */
    @media (min-width: 1024px) {
        /* 1. Hapus search bar baris kedua pada desktop karena bersifat redundan dengan header search bar */
        .messages-search-wrapper {
            display: none !important;
        }

        /* 2. Sembunyikan segmented filter track pada desktop karena telah digantikan oleh dropdown option button di header */
        .messages-segmented-track {
            display: none !important;
        }

        /* Container padding selaras dengan header desktop */
        .messages-container {
            padding: 0.65rem clamp(1.5rem, 3.5vw, 3.5rem) 3rem clamp(1.5rem, 3.5vw, 3.5rem);
            max-width: 100%;
            margin: 0;
            gap: 1.15rem;
        }

        .messages-meta-row {
            margin-top: 0.25rem;
            padding: 0 0.2rem;
        }

        .messages-count-text {
            font-size: 0.885rem;
            font-weight: 600;
            color: #475569;
        }

        .conversations-list {
            gap: 0.8rem;
        }

        .conversation-card {
            padding: 0.95rem 1.25rem;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .conversation-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
            transform: translateY(-1px);
        }

        .conversation-user-name {
            font-size: 0.95rem;
        }

        /* Nama barang dibuat lengkap pada desktop */
        .conversation-title-group {
            max-width: none !important;
            overflow: visible !important;
        }

        .conversation-item-tag {
            max-width: none !important;
            overflow: visible !important;
            text-overflow: clip !important;
            white-space: nowrap !important;
            font-size: 0.775rem;
            padding: 0.16rem 0.65rem;
        }

        .conversation-snippet {
            font-size: 0.835rem;
        }

        /* Chat Thread Modal on Desktop: Elegan Centered Modal */
        .chat-thread-overlay {
            align-items: center;
            padding: 1.5rem;
        }

        .chat-thread-card {
            max-width: 580px;
            height: 86vh;
            max-height: 740px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
            animation: popChatDesktop 0.24s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes popChatDesktop {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    }
</style>
@endpush

@section('content')
<div class="messages-container">
    <!-- 1. Search Bar for Conversations -->
    <div class="messages-search-wrapper">
        <span class="messages-search-icon">
            <i data-lucide="search"></i>
        </span>
        <input type="text" id="live-messages-search" class="messages-search-field" placeholder="Cari percakapan, nama, atau barang..." autocomplete="off">
        <button type="button" class="messages-clear-btn" id="btn-clear-messages-search" aria-label="Hapus Pencarian" onclick="clearMessagesSearch()">
            <i data-lucide="x"></i>
        </button>
    </div>

    <!-- 2. Segmented Status Filter Pill -->
    <div class="messages-segmented-track" role="tablist">
        <button type="button" class="messages-segment-pill active" data-filter="all" onclick="setMessagesStatusFilter('all', this)" role="tab" aria-selected="true">
            <span>Semua</span>
            <span class="segment-badge-count" id="count-pill-all">5</span>
        </button>
        <button type="button" class="messages-segment-pill" data-filter="active" onclick="setMessagesStatusFilter('active', this)" role="tab" aria-selected="false">
            <span style="white-space: nowrap;">Klaim Aktif</span>
            <span class="segment-badge-count" id="count-pill-active">3</span>
        </button>
        <button type="button" class="messages-segment-pill" data-filter="completed" onclick="setMessagesStatusFilter('completed', this)" role="tab" aria-selected="false">
            <span>Selesai</span>
            <span class="segment-badge-count" id="count-pill-completed">2</span>
        </button>
    </div>

    <!-- 3. Results Meta Row -->
    <div class="messages-meta-row">
        <span class="messages-count-text" id="messages-count-label">Menampilkan 5 Percakapan</span>
        <button type="button" class="btn-clear-msg-filters" id="btn-reset-msg-filters" onclick="resetAllMessagesFilter()">Reset Filter</button>
    </div>

    <!-- 4. Conversations List (Vertical Stack) -->
    <div class="conversations-list" id="conversations-container">
        
        <!-- Conversation 1: Ahmad Fauzi (Handover Ongoing) -->
        <div class="conversation-card unread" 
             id="conv-card-ahmad"
             data-conv-id="ahmad"
             data-filter="active" 
             data-name="ahmad fauzi fti" 
             data-item="samsung galaxy a13 sm-a135f" 
             onclick="openChatThread('Ahmad Fauzi (FTI)', 'Samsung Galaxy A13 (SM-A135F)', 'in_handover', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&auto=format&fit=crop&q=80', 'ahmad')">
            <div class="conversation-avatar-wrap">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&auto=format&fit=crop&q=80" alt="Ahmad Fauzi" class="conversation-avatar" loading="lazy">
                <span class="avatar-online-dot" title="Online"></span>
            </div>
            <div class="conversation-info">
                <div class="conversation-top-row">
                    <div class="conversation-title-group">
                        <span class="conversation-user-name">Ahmad Fauzi</span>
                        <span class="conversation-item-tag">Samsung Galaxy A13 (SM-A135F)</span>
                    </div>
                    <span class="conversation-time">10:42</span>
                </div>
                <div class="conversation-bottom-row">
                    <span class="conversation-snippet">Bisa ketemuan di Lobby Gedung Tower jam 13.00 untuk serah terima?</span>
                    <span class="unread-bubble">1</span>
                </div>
            </div>
        </div>

        <!-- Conversation 2: Siti Nurhaliza (Claim Submitted) -->
        <div class="conversation-card unread" 
             id="conv-card-siti"
             data-conv-id="siti"
             data-filter="active" 
             data-name="siti nurhaliza feb" 
             data-item="dompet kulit ktm mahasiswa cokelat" 
             onclick="openChatThread('Siti Nurhaliza (FEB)', 'Dompet Kulit & KTM Mahasiswa', 'submitted', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=160&auto=format&fit=crop&q=80', 'siti')">
            <div class="conversation-avatar-wrap">
                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=160&auto=format&fit=crop&q=80" alt="Siti Nurhaliza" class="conversation-avatar" loading="lazy">
                <span class="avatar-online-dot" title="Online"></span>
            </div>
            <div class="conversation-info">
                <div class="conversation-top-row">
                    <div class="conversation-title-group">
                        <span class="conversation-user-name">Siti Nurhaliza</span>
                        <span class="conversation-item-tag">Dompet Kulit & KTM Mahasiswa</span>
                    </div>
                    <span class="conversation-time">09:15</span>
                </div>
                <div class="conversation-bottom-row">
                    <span class="conversation-snippet">Halo kak, saya sudah lampirkan foto identitas KTM yang tertinggal di Kantin D.</span>
                    <span class="unread-bubble">2</span>
                </div>
            </div>
        </div>

        <!-- Conversation 3: Budi Santoso (Handover Active) -->
        <div class="conversation-card" 
             id="conv-card-budi"
             data-conv-id="budi"
             data-filter="active" 
             data-name="budi santoso staf lab fti" 
             data-item="tas laptop lenovo thinkpad" 
             onclick="openChatThread('Budi Santoso (Staf Lab)', 'Tas Laptop Lenovo ThinkPad', 'in_handover', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=160&auto=format&fit=crop&q=80', 'budi')">
            <div class="conversation-avatar-wrap">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=160&auto=format&fit=crop&q=80" alt="Budi Santoso" class="conversation-avatar" loading="lazy">
            </div>
            <div class="conversation-info">
                <div class="conversation-top-row">
                    <div class="conversation-title-group">
                        <span class="conversation-user-name">Budi Santoso</span>
                        <span class="conversation-item-tag">Tas Laptop Lenovo ThinkPad</span>
                    </div>
                    <span class="conversation-time">Kemarin</span>
                </div>
                <div class="conversation-bottom-row">
                    <span class="conversation-snippet">Barang sudah diamankan di Ruang Lab Komputer Lt. 3 ya. Silakan mampir.</span>
                </div>
            </div>
        </div>

        <!-- Conversation 4: Dinda Kirana (Handover Completed) -->
        <div class="conversation-card" 
             id="conv-card-dinda"
             data-conv-id="dinda"
             data-filter="completed" 
             data-name="dinda kirana fdsk" 
             data-item="earphone tws anker soundcore" 
             onclick="openChatThread('Dinda Kirana (FDSK)', 'Earphone TWS Anker Soundcore', 'completed', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=160&auto=format&fit=crop&q=80', 'dinda')">
            <div class="conversation-avatar-wrap">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=160&auto=format&fit=crop&q=80" alt="Dinda Kirana" class="conversation-avatar" loading="lazy">
            </div>
            <div class="conversation-info">
                <div class="conversation-top-row">
                    <div class="conversation-title-group">
                        <span class="conversation-user-name">Dinda Kirana</span>
                        <span class="conversation-item-tag">Earphone TWS Anker Soundcore</span>
                    </div>
                    <span class="conversation-time">22 Okt</span>
                </div>
                <div class="conversation-bottom-row">
                    <span class="conversation-snippet">Alhamdulillah earphone-nya sudah kembali dengan aman. Terima kasih banyak kak!</span>
                </div>
            </div>
        </div>

        <!-- Conversation 5: Rizky Pratama (Handover Completed) -->
        <div class="conversation-card" 
             id="conv-card-rizky"
             data-conv-id="rizky"
             data-filter="completed" 
             data-name="rizky pratama fasilkom" 
             data-item="paspor berkas beasiswa" 
             onclick="openChatThread('Rizky Pratama (FASILKOM)', 'Paspor & Berkas Beasiswa', 'completed', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=160&auto=format&fit=crop&q=80', 'rizky')">
            <div class="conversation-avatar-wrap">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=160&auto=format&fit=crop&q=80" alt="Rizky Pratama" class="conversation-avatar" loading="lazy">
            </div>
            <div class="conversation-info">
                <div class="conversation-top-row">
                    <div class="conversation-title-group">
                        <span class="conversation-user-name">Rizky Pratama</span>
                        <span class="conversation-item-tag">Paspor & Berkas Beasiswa</span>
                    </div>
                    <span class="conversation-time">18 Okt</span>
                </div>
                <div class="conversation-bottom-row">
                    <span class="conversation-snippet">Serah terima dokumen beasiswa telah dikonfirmasi kedua pihak.</span>
                </div>
            </div>
        </div>

    </div>

    <!-- 5. Friendly Empty State (When no chats match) -->
    <div class="messages-empty-state" id="messages-empty-state">
        <div class="empty-mascot-box">
            <picture>
                <source srcset="{{ asset('images/mascot.webp') }}" type="image/webp">
                <img src="{{ asset('images/mascot.png') }}" alt="Maskot FindUMB" class="empty-mascot-img" width="105" height="105" loading="lazy">
            </picture>
        </div>
        <h3 class="empty-title">Tidak Ada Percakapan yang Cocok</h3>
        <p class="empty-desc">Tidak ditemukan riwayat pesan dengan kata kunci tersebut. Coba gunakan nama civitas atau barang lain.</p>
        <button type="button" class="empty-reset-btn" id="btn-reset-empty-messages" onclick="resetAllMessagesFilter()">
            Reset Pencarian
        </button>
    </div>
</div>

<!-- 6. Interactive Chat Thread Modal / Bottom Sheet -->
<div class="chat-thread-overlay" id="chat-thread-sheet" onclick="closeChatThread(event)">
    <div class="chat-thread-card" onclick="event.stopPropagation()">
        
        <!-- Header Chat Thread -->
        <div class="chat-sheet-header">
            <button type="button" class="chat-back-btn" onclick="closeChatThread()" aria-label="Tutup Obrolan">
                <i data-lucide="chevron-left"></i>
            </button>
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&auto=format&fit=crop&q=80" alt="Avatar" id="chat-thread-avatar" class="chat-header-avatar">
            <div class="chat-header-meta">
                <div class="chat-header-name">
                    <span id="chat-thread-name">Ahmad Fauzi</span>
                </div>
                <div class="chat-header-item-title" id="chat-thread-item">Samsung SM-A135F</div>
            </div>
        </div>

        <!-- Top Handover Action Widget (PRD PR-06 - Replaces old stepper) -->
        <div class="chat-claim-tracker" id="chat-claim-tracker">
            <div class="handover-confirm-box" id="handover-confirm-card">
                <!-- 1. Action State (Pending Confirmation) -->
                <div class="handover-card-pending" id="handover-pending-section">
                    <!-- Clickable Header Row for Accordion Dropdown -->
                    <div class="handover-card-header" id="handover-card-header" onclick="toggleHandoverCardAccordion()" role="button" tabindex="0" aria-expanded="false" aria-label="Buka formulir serah terima fisik">
                        <div class="handover-title-wrapper">
                            <div class="handover-icon-badge">
                                <i data-lucide="handshake"></i>
                            </div>
                            <div class="handover-title-text-group">
                                <h4 class="handover-confirm-title">Konfirmasi Serah Terima Fisik</h4>
                                <span class="handover-step-subtitle">Ketuk untuk buka verifikasi</span>
                            </div>
                        </div>
                        <div class="handover-header-right">
                            <span class="handover-step-tag">Tahap Terakhir</span>
                            <div class="handover-chevron-btn" id="handover-chevron-icon" aria-hidden="true">
                                <i data-lucide="chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Collapsible Body Section -->
                    <div class="handover-collapsible-body" id="handover-collapsible-body">
                        <div class="handover-collapsible-content">
                            <div class="handover-confirm-desc">
                                Pastikan Anda telah bertemu langsung di area publik kampus dan memeriksa kondisi fisik barang sebelum menyelesaikan klaim.
                            </div>

                            <label class="handover-checkbox-wrap" id="handover-checkbox-wrap">
                                <input type="checkbox" id="handover-check-agree" class="handover-checkbox" onchange="clearHandoverValidationError()">
                                <span class="handover-checkbox-label">
                                    Saya telah bertemu langsung dan <strong>memeriksa kecocokan fisik</strong> barang ini.
                                </span>
                            </label>

                            <div class="handover-validation-error" id="handover-validation-error">
                                <i data-lucide="alert-circle"></i>
                                <span>Mohon centang konfirmasi pemeriksaan fisik sebelum melanjutkan.</span>
                            </div>

                            <button type="button" class="btn-action-handover" id="btn-submit-handover" onclick="handleHandoverButtonClick()">
                                <span>Konfirmasi Barang Diterima</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Success State (After Confirmed) -->
                <div class="handover-card-success" id="handover-success-section" style="display: none;">
                    <div class="handover-card-header static">
                        <div class="handover-title-wrapper">
                            <div class="handover-icon-badge success">
                                <i data-lucide="check-circle-2"></i>
                            </div>
                            <div class="handover-title-text-group">
                                <h4 class="handover-confirm-title text-success">Serah Terima Berhasil Diselesaikan!</h4>
                                <span class="handover-step-subtitle">Laporan telah ditutup & diselesaikan</span>
                            </div>
                        </div>
                        <span class="handover-step-tag success">Selesai</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Chat Body -->
        <div class="chat-messages-body" id="chat-messages-body">
            
            <div class="chat-bubble-row incoming">
                <div class="chat-bubble">
                    Halo kak, terima kasih banyak sudah mempublikasikan barang temuan ini. Samsung Galaxy ini memang milik saya yang tertinggal di Lt. 7.
                </div>
                <span class="chat-bubble-time">10:30</span>
            </div>

            <div class="chat-bubble-row outgoing">
                <div class="chat-bubble">
                    Halo mas Ahmad! Sama-sama. Saya temukan di bangku depan lift Lt. 7. Untuk serah terima barangnya mau janjian jam berapa dan di mana?
                </div>
                <span class="chat-bubble-time">10:35</span>
            </div>

            <div class="chat-bubble-row incoming">
                <div class="chat-bubble">
                    Bisa ketemuan di Lobby Gedung Tower jam 13.00 untuk serah terima mas? Nanti saya tunjukkan bukti passcode layarnya.
                </div>
                <span class="chat-bubble-time">10:42</span>
            </div>

        </div>

        <!-- Chat Input Footer -->
        <div class="chat-sheet-footer">
            <input type="text" class="chat-input-field" id="chat-input-field" placeholder="Tulis pesan..." autocomplete="off" onkeydown="if(event.key==='Enter') sendChatMessage()">
            <button type="button" class="chat-send-btn" onclick="sendChatMessage()" aria-label="Kirim Pesan">
                <i data-lucide="send"></i>
            </button>
        </div>

    </div>
</div>

<!-- Handover Confirmation Modal Dialog (PRD PR-06) -->
<div class="confirm-modal-overlay" id="confirm-handover-modal" onclick="closeConfirmHandoverModal(event)">
    <div class="confirm-modal-dialog" onclick="event.stopPropagation()">
        <div class="confirm-modal-icon-circle">
            <i data-lucide="shield-check"></i>
        </div>
        <h3 class="confirm-modal-title">Selesaikan Serah Terima?</h3>
        <p class="confirm-modal-desc">
            Tindakan ini permanen. Setelah dikonfirmasi, proses klaim selesai dan obrolan ditutup.
        </p>

        <div class="confirm-modal-summary">
            <div class="confirm-summary-row">
                <span class="summary-label">Nama Barang</span>
                <span class="summary-value" id="modal-item-name">Samsung SM-A135F</span>
            </div>
            <div class="confirm-summary-row">
                <span class="summary-label">Pihak Terkait</span>
                <span class="summary-value" id="modal-user-name">Ahmad Fauzi (FTI)</span>
            </div>
            <div class="confirm-summary-row">
                <span class="summary-label">Status Akhir</span>
                <span class="summary-badge-resolved">Laporan Selesai (Resolved)</span>
            </div>
        </div>

        <div class="confirm-modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeConfirmHandoverModal()">
                Periksa Lagi
            </button>
            <button type="button" class="btn-modal-confirm" id="btn-modal-execute-confirm" onclick="executeFinalHandoverConfirm()">
                <span>Ya, Selesaikan</span>
            </button>
        </div>
    </div>
</div>

<!-- Toast Popup Notification -->
<div class="toast-popup" id="toast-popup" role="status" aria-live="polite"></div>
@endsection

@push('scripts')
<script>
    let currentMessagesFilter = 'all';
    let currentMessagesQuery = '';

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // 1. Mobile local search input listener
        const searchInput = document.getElementById('live-messages-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                currentMessagesQuery = e.target.value.toLowerCase().trim();
                const clearBtn = document.getElementById('btn-clear-messages-search');
                if (clearBtn) {
                    clearBtn.style.display = currentMessagesQuery.length > 0 ? 'flex' : 'none';
                }
                // Sync with desktop top search input if present
                const desktopInput = document.getElementById('desktop-messages-search-input');
                if (desktopInput && desktopInput.value !== e.target.value) {
                    desktopInput.value = e.target.value;
                    const desktopClear = document.getElementById('btn-clear-desktop-messages');
                    if (desktopClear) desktopClear.style.display = currentMessagesQuery.length > 0 ? 'inline-flex' : 'none';
                }
                applyMessagesFilter();
            });
        }

        // 2. Desktop top header search input listener
        const desktopSearchInput = document.getElementById('desktop-messages-search-input');
        if (desktopSearchInput) {
            desktopSearchInput.addEventListener('input', (e) => {
                currentMessagesQuery = e.target.value.toLowerCase().trim();
                const clearBtn = document.getElementById('btn-clear-desktop-messages');
                if (clearBtn) {
                    clearBtn.style.display = currentMessagesQuery.length > 0 ? 'inline-flex' : 'none';
                }
                // Sync with mobile search input if present
                const mobileInput = document.getElementById('live-messages-search');
                if (mobileInput && mobileInput.value !== e.target.value) {
                    mobileInput.value = e.target.value;
                    const mobileClear = document.getElementById('btn-clear-messages-search');
                    if (mobileClear) mobileClear.style.display = currentMessagesQuery.length > 0 ? 'flex' : 'none';
                }
                applyMessagesFilter();
            });
        }

        // 3. Close Dropdown when clicking outside or pressing Escape
        document.addEventListener('click', (e) => {
            const dropdownWrap = document.getElementById('messages-filter-dropdown-wrapper');
            if (dropdownWrap && !dropdownWrap.contains(e.target)) {
                closeMessagesFilterDropdown();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMessagesFilterDropdown();
            }
        });

        updateCounts();
        applyStoredUnreadStateToCards();

        // Listen for storage changes across tabs or window updates
        window.addEventListener('storage', (e) => {
            if (e.key === 'findumb_unread_conversations') {
                applyStoredUnreadStateToCards();
            }
        });

        window.addEventListener('findumb:messages-updated', () => {
            applyStoredUnreadStateToCards();
        });

        // Check if navigated from Report Detail with claim intent
        const urlParams = new URLSearchParams(window.location.search);
        const claimItem = urlParams.get('item');
        if (claimItem) {
            setTimeout(() => {
                showToast(`Membuka percakapan untuk: ${claimItem}`);
            }, 300);
        }
    });

    // Dropdown Option Button Interactions (Top Header Desktop)
    function toggleMessagesFilterDropdown(e) {
        if (e) e.stopPropagation();
        const btn = document.getElementById('btn-messages-filter-dropdown');
        const menu = document.getElementById('messages-filter-menu');
        if (!menu || !btn) return;

        const isOpen = menu.classList.contains('open');
        if (isOpen) {
            closeMessagesFilterDropdown();
        } else {
            openMessagesFilterDropdown();
        }
    }

    function openMessagesFilterDropdown() {
        const btn = document.getElementById('btn-messages-filter-dropdown');
        const menu = document.getElementById('messages-filter-menu');
        if (!menu || !btn) return;

        menu.classList.add('open');
        btn.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeMessagesFilterDropdown() {
        const btn = document.getElementById('btn-messages-filter-dropdown');
        const menu = document.getElementById('messages-filter-menu');
        if (menu) menu.classList.remove('open');
        if (btn) {
            btn.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }
    }

    // Select Filter from Dropdown (Desktop) or Segment Pill (Mobile)
    function selectMessagesFilter(filter, label) {
        currentMessagesFilter = filter;

        // 1. Update Dropdown Button UI (Desktop)
        const labelEl = document.getElementById('messages-dropdown-label');
        if (labelEl) labelEl.textContent = label;

        // 2. Update active state in dropdown options
        document.querySelectorAll('.messages-filter-option').forEach(opt => {
            if (opt.getAttribute('data-filter') === filter) {
                opt.classList.add('active');
            } else {
                opt.classList.remove('active');
            }
        });

        // 3. Sync active state with mobile segmented pill buttons
        document.querySelectorAll('.messages-segment-pill').forEach(btn => {
            if (btn.getAttribute('data-filter') === filter) {
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');
            } else {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            }
        });

        // 4. Update dropdown badge count
        updateDropdownBadge();

        // 5. Close menu and apply filter
        closeMessagesFilterDropdown();
        applyMessagesFilter();
    }

    // Mobile Segment Pill Trigger
    function setMessagesStatusFilter(filter, el) {
        let label = 'Semua';
        if (filter === 'active') label = 'Klaim Aktif';
        if (filter === 'completed') label = 'Selesai';
        selectMessagesFilter(filter, label);
    }

    // Clear Mobile Search Field
    function clearMessagesSearch() {
        const input = document.getElementById('live-messages-search');
        if (input) {
            input.value = '';
            input.focus();
        }
        const desktopInput = document.getElementById('desktop-messages-search-input');
        if (desktopInput) desktopInput.value = '';

        currentMessagesQuery = '';
        const clearBtn = document.getElementById('btn-clear-messages-search');
        if (clearBtn) clearBtn.style.display = 'none';
        const desktopClearBtn = document.getElementById('btn-clear-desktop-messages');
        if (desktopClearBtn) desktopClearBtn.style.display = 'none';

        applyMessagesFilter();
    }

    // Clear Desktop Top Header Search Field
    function clearDesktopMessagesSearch() {
        const desktopInput = document.getElementById('desktop-messages-search-input');
        if (desktopInput) {
            desktopInput.value = '';
            desktopInput.focus();
        }
        const mobileInput = document.getElementById('live-messages-search');
        if (mobileInput) mobileInput.value = '';

        currentMessagesQuery = '';
        const desktopClearBtn = document.getElementById('btn-clear-desktop-messages');
        if (desktopClearBtn) desktopClearBtn.style.display = 'none';
        const mobileClearBtn = document.getElementById('btn-clear-messages-search');
        if (mobileClearBtn) mobileClearBtn.style.display = 'none';

        applyMessagesFilter();
    }

    // Reset All Filters
    function resetAllMessagesFilter() {
        clearMessagesSearch();
        clearDesktopMessagesSearch();
        selectMessagesFilter('all', 'Semua');
        showToast('Filter percakapan direset.');
    }

    // Update Counts across Mobile Pills and Desktop Dropdown
    function updateCounts() {
        const allCards = document.querySelectorAll('#conversations-container .conversation-card');
        let totalAll = allCards.length;
        let totalActive = 0;
        let totalCompleted = 0;

        allCards.forEach(card => {
            const filterType = card.getAttribute('data-filter');
            if (filterType === 'active') totalActive++;
            if (filterType === 'completed') totalCompleted++;
        });

        // Mobile pills counts
        const pillAll = document.getElementById('count-pill-all');
        const pillActive = document.getElementById('count-pill-active');
        const pillCompleted = document.getElementById('count-pill-completed');

        if (pillAll) pillAll.textContent = totalAll;
        if (pillActive) pillActive.textContent = totalActive;
        if (pillCompleted) pillCompleted.textContent = totalCompleted;

        // Desktop dropdown option counts
        const dropAll = document.getElementById('dropdown-count-all');
        const dropActive = document.getElementById('dropdown-count-active');
        const dropCompleted = document.getElementById('dropdown-count-completed');

        if (dropAll) dropAll.textContent = totalAll;
        if (dropActive) dropActive.textContent = totalActive;
        if (dropCompleted) dropCompleted.textContent = totalCompleted;

        updateDropdownBadge();
    }

    function updateDropdownBadge() {
        const badgeEl = document.getElementById('messages-dropdown-badge');
        if (!badgeEl) return;

        const allCards = document.querySelectorAll('#conversations-container .conversation-card');
        let count = allCards.length;
        if (currentMessagesFilter === 'active') {
            count = Array.from(allCards).filter(c => c.getAttribute('data-filter') === 'active').length;
        } else if (currentMessagesFilter === 'completed') {
            count = Array.from(allCards).filter(c => c.getAttribute('data-filter') === 'completed').length;
        }
        badgeEl.textContent = count;
    }

    // 5. Apply Filter & Search Logic
    function applyMessagesFilter() {
        const container = document.getElementById('conversations-container');
        const emptyState = document.getElementById('messages-empty-state');
        const countLabel = document.getElementById('messages-count-label');
        const resetBtn = document.getElementById('btn-reset-msg-filters');

        const cards = Array.from(document.querySelectorAll('#conversations-container .conversation-card'));
        let visibleCount = 0;

        cards.forEach(card => {
            const filterType = card.getAttribute('data-filter');
            const name = card.getAttribute('data-name') || '';
            const item = card.getAttribute('data-item') || '';

            const matchFilter = (currentMessagesFilter === 'all') || (filterType === currentMessagesFilter);
            const matchQuery = (currentMessagesQuery === '') || name.includes(currentMessagesQuery) || item.includes(currentMessagesQuery);

            if (matchFilter && matchQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (countLabel) {
            countLabel.textContent = `Menampilkan ${visibleCount} Percakapan`;
        }

        if (visibleCount === 0) {
            container.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            container.style.display = 'flex';
            emptyState.style.display = 'none';
        }

        const isFiltered = (currentMessagesFilter !== 'all' || currentMessagesQuery !== '');
        if (resetBtn) {
            resetBtn.style.display = isFiltered ? 'inline-block' : 'none';
        }
    }

    // Unread Conversation State Storage & Badge Sync
    function getStoredUnreadConversations() {
        if (typeof window.getStoredUserUnreadConversations === 'function') {
            return window.getStoredUserUnreadConversations();
        }
        try {
            const raw = localStorage.getItem('findumb_unread_conversations');
            if (raw !== null) {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) return parsed;
            }
        } catch(e) {}
        return ['ahmad', 'siti'];
    }

    function applyStoredUnreadStateToCards() {
        const unreadList = getStoredUnreadConversations();
        const cards = document.querySelectorAll('#conversations-container .conversation-card');
        cards.forEach(card => {
            const convId = card.getAttribute('data-conv-id');
            if (convId) {
                const isUnread = unreadList.includes(convId);
                const bubble = card.querySelector('.unread-bubble');
                if (!isUnread) {
                    card.classList.remove('unread');
                    if (bubble) bubble.style.display = 'none';
                } else {
                    card.classList.add('unread');
                    if (bubble) bubble.style.display = 'inline-flex';
                }
            }
        });
        if (typeof window.syncMessagesBadges === 'function') {
            window.syncMessagesBadges(unreadList);
        }
    }

    // 6. Open & Close Chat Thread Modal
    function openChatThread(name, item, status, avatarUrl, convId) {
        if (convId) {
            let unreadList = getStoredUnreadConversations();
            if (unreadList.includes(convId)) {
                unreadList = unreadList.filter(id => id !== convId);
                try {
                    localStorage.setItem('findumb_unread_conversations', JSON.stringify(unreadList));
                } catch(e) {}

                const card = document.getElementById('conv-card-' + convId) || document.querySelector(`[data-conv-id="${convId}"]`);
                if (card) {
                    card.classList.remove('unread');
                    const bubble = card.querySelector('.unread-bubble');
                    if (bubble) bubble.style.display = 'none';
                }

                if (typeof window.syncMessagesBadges === 'function') {
                    window.syncMessagesBadges(unreadList);
                }
                window.dispatchEvent(new CustomEvent('findumb:messages-updated', { detail: { unreadList } }));
            }
        }

        document.getElementById('chat-thread-name').textContent = name;
        document.getElementById('chat-thread-item').textContent = item;
        if (avatarUrl) {
            document.getElementById('chat-thread-avatar').src = avatarUrl;
        }

        const handoverCard = document.getElementById('handover-confirm-card');
        const pendingSection = document.getElementById('handover-pending-section');
        const successSection = document.getElementById('handover-success-section');
        const checkbox = document.getElementById('handover-check-agree');
        const errorMsg = document.getElementById('handover-validation-error');
        const checkWrap = document.getElementById('handover-checkbox-wrap');

        // Reset validation & sections
        if (checkbox) checkbox.checked = false;
        if (errorMsg) errorMsg.classList.remove('show');
        if (checkWrap) checkWrap.classList.remove('error');
        toggleHandoverCardAccordion(false);

        if (status === 'completed') {
            if (handoverCard) {
                handoverCard.style.display = 'flex';
                handoverCard.classList.add('confirmed');
            }
            if (pendingSection) pendingSection.style.display = 'none';
            if (successSection) successSection.style.display = 'flex';
        } else {
            if (handoverCard) {
                handoverCard.style.display = 'flex';
                handoverCard.classList.remove('confirmed');
            }
            if (pendingSection) pendingSection.style.display = 'flex';
            if (successSection) successSection.style.display = 'none';
        }

        const overlay = document.getElementById('chat-thread-sheet');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
            const body = document.getElementById('chat-messages-body');
            if (body) body.scrollTop = body.scrollHeight + 500;
        }, 150);
    }

    // Toggle Collapsible Accordion Card
    function toggleHandoverCardAccordion(forceOpen) {
        const card = document.getElementById('handover-confirm-card');
        const header = document.getElementById('handover-card-header');
        if (!card) return;

        const isCurrentlyExpanded = card.classList.contains('expanded');
        const shouldExpand = (typeof forceOpen === 'boolean') ? forceOpen : !isCurrentlyExpanded;

        if (shouldExpand) {
            card.classList.add('expanded');
            if (header) header.setAttribute('aria-expanded', 'true');
            const sub = card.querySelector('#handover-pending-section .handover-step-subtitle');
            if (sub) sub.textContent = 'Ketuk untuk sembunyikan';
            setTimeout(() => {
                const body = document.getElementById('chat-messages-body');
                if (body) body.scrollTop = body.scrollHeight + 300;
            }, 80);
        } else {
            card.classList.remove('expanded');
            if (header) header.setAttribute('aria-expanded', 'false');
            const sub = card.querySelector('#handover-pending-section .handover-step-subtitle');
            if (sub) sub.textContent = 'Ketuk untuk buka verifikasi';
        }
    }

    function closeChatThread(e) {
        if (!e || e.target.id === 'chat-thread-sheet' || (e.target && e.target.closest && e.target.closest('.chat-back-btn'))) {
            document.getElementById('chat-thread-sheet').classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    // 7. Send Chat Message Interaction
    function sendChatMessage() {
        const input = document.getElementById('chat-input-field');
        const text = input.value.trim();
        if (!text) return;

        const body = document.getElementById('chat-messages-body');
        const bubbleRow = document.createElement('div');
        bubbleRow.className = 'chat-bubble-row outgoing';
        bubbleRow.innerHTML = `
            <div class="chat-bubble">${escapeHtml(text)}</div>
            <span class="chat-bubble-time">Baru saja</span>
        `;
        body.appendChild(bubbleRow);
        input.value = '';
        body.scrollTop = body.scrollHeight;
    }

    // 8. Handover Validation & Modal Interactions (PRD PR-06)
    function handleHandoverButtonClick() {
        const checkbox = document.getElementById('handover-check-agree');
        const errorMsg = document.getElementById('handover-validation-error');
        const checkWrap = document.getElementById('handover-checkbox-wrap');

        // Ensure card is expanded so validation is visible
        toggleHandoverCardAccordion(true);

        // Validation Check: User must tick the physical inspection agreement
        if (!checkbox || !checkbox.checked) {
            if (errorMsg) errorMsg.classList.add('show');
            if (checkWrap) {
                checkWrap.classList.add('error');
                setTimeout(() => checkWrap.classList.remove('error'), 400);
            }
            return;
        }

        // Passed Validation -> Open Confirmation Dialog Modal
        const modal = document.getElementById('confirm-handover-modal');
        if (modal) {
            const currentItem = document.getElementById('chat-thread-item').textContent || 'Barang Laporan';
            const currentUser = document.getElementById('chat-thread-name').textContent || 'Civitas UMB';
            const modalItem = document.getElementById('modal-item-name');
            const modalUser = document.getElementById('modal-user-name');
            if (modalItem) modalItem.textContent = currentItem;
            if (modalUser) modalUser.textContent = currentUser;

            modal.classList.add('active');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    function clearHandoverValidationError() {
        const checkbox = document.getElementById('handover-check-agree');
        const errorMsg = document.getElementById('handover-validation-error');
        const checkWrap = document.getElementById('handover-checkbox-wrap');
        if (checkbox && checkbox.checked) {
            if (errorMsg) errorMsg.classList.remove('show');
            if (checkWrap) checkWrap.classList.remove('error');
        }
    }

    function closeConfirmHandoverModal(e) {
        if (!e || e.target.id === 'confirm-handover-modal' || (e.target && e.target.closest && e.target.closest('.btn-modal-cancel'))) {
            const modal = document.getElementById('confirm-handover-modal');
            if (modal) modal.classList.remove('active');
        }
    }

    function executeFinalHandoverConfirm() {
        const confirmBtn = document.getElementById('btn-modal-execute-confirm');
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = `<span>Menyimpan...</span>`;
        }

        setTimeout(() => {
            const modal = document.getElementById('confirm-handover-modal');
            if (modal) modal.classList.remove('active');

            handleConfirmHandover();

            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = `<span>Ya, Selesaikan</span>`;
            }
        }, 250);
    }

    function handleConfirmHandover() {
        const pendingSection = document.getElementById('handover-pending-section');
        const successSection = document.getElementById('handover-success-section');
        const handoverCard = document.getElementById('handover-confirm-card');

        if (pendingSection) pendingSection.style.display = 'none';
        if (successSection) successSection.style.display = 'flex';
        if (handoverCard) handoverCard.classList.add('confirmed');

        if (typeof lucide !== 'undefined') lucide.createIcons();
        showToast('Konfirmasi serah terima berhasil disimpan!');
    }

    // Helper: Toast
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

    function escapeHtml(string) {
        return String(string).replace(/[&<>"'`=\/]/g, function (s) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
                '/': '&#x2F;',
                '`': '&#x60;',
                '=': '&#x3D;'
            }[s];
        });
    }
</script>
@endpush
