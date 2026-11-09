/**
 * FindUMB — API bridge
 * ---------------------------------------------------------------------------
 * Menghubungkan antarmuka yang sudah ada (markup & CSS tidak diubah) dengan
 * backend Laravel + Supabase.
 *
 * Cara kerja:
 *  - Mengambil data dari endpoint /api/*.
 *  - Menimpa fungsi sumber data milik halaman (getUserReports, getStoredNotifications,
 *    getSavedList, dst.) agar membaca data server, bukan localStorage.
 *  - Menimpa fungsi aksi (submitReport, toggleBookmark, sendChatMessage, ...)
 *    agar menulis ke server.
 *  - Mengisi ulang daftar kartu dengan menyalin struktur kartu yang sudah ada
 *    di halaman (template clone), sehingga tampilan tetap identik.
 */
(function () {
    'use strict';

    const CSRF = window.FindUMB_CSRF || '';
    const isAuthed = !!window.FindUMB_USER_ID;

    const api = {
        async request(method, url, body, isForm) {
            const opts = {
                method,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF,
                },
                credentials: 'same-origin',
            };

            if (body && !isForm) {
                opts.headers['Content-Type'] = 'application/json';
                opts.body = JSON.stringify(body);
            } else if (body instanceof FormData) {
                opts.body = body;
            }

            const res = await fetch(url, opts);
            let payload = null;

            try {
                payload = await res.json();
            } catch (e) {
                payload = null;
            }

            if (!res.ok) {
                const message = (payload && (payload.message || (payload.errors && Object.values(payload.errors)[0][0])))
                    || 'Terjadi kesalahan. Coba lagi.';
                const err = new Error(message);
                err.status = res.status;
                err.payload = payload;
                throw err;
            }

            return payload;
        },
        get(url) { return this.request('GET', url); },
        post(url, body, isForm) { return this.request('POST', url, body, isForm); },
        patch(url, body, isForm) { return this.request('PATCH', url, body, isForm); },
    };

    const state = {
        reports: [],
        myReports: [],
        notifications: [],
        conversations: [],
        bookmarks: [],
        unreadNotifications: 0,
        user: null,
        loaded: false,
    };

    // ------------------------------------------------------------------ util

    function escapeHtml(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function toast(message, type) {
        if (typeof window.showToast === 'function') {
            try { window.showToast(message); return; } catch (e) { /* lanjut ke fallback */ }
        }
        let el = document.getElementById('findumb-toast');
        if (!el) {
            el = document.createElement('div');
            el.id = 'findumb-toast';
            el.style.cssText = 'position:fixed;left:50%;bottom:88px;transform:translateX(-50%);background:#0f172a;color:#fff;' +
                'padding:12px 18px;border-radius:12px;font-size:13px;font-weight:600;z-index:99999;max-width:88%;' +
                'box-shadow:0 8px 24px rgba(15,23,42,.25);transition:opacity .25s;text-align:center;';
            document.body.appendChild(el);
        }
        el.textContent = message;
        el.style.background = type === 'error' ? '#b91c1c' : '#0f172a';
        el.style.opacity = '1';
        clearTimeout(el._timer);
        el._timer = setTimeout(() => { el.style.opacity = '0'; }, 3200);
    }

    function currentPath() {
        return window.location.pathname.replace(/\/+$/, '') || '/';
    }

    function queryString() {
        return window.location.search || '';
    }

    // ------------------------------------------------------- kartu laporan

    /**
     * Isi ulang daftar kartu laporan dengan menyalin kartu pertama sebagai
     * template. Struktur kelas & markup hasilnya sama dengan aslinya.
     */
    function hydrateReportCards(container, reports, options) {
        if (!container) return;
        const opts = options || {};
        const template = container.querySelector('.report-card');

        if (!template) return;

        const html = reports.map(function (r) {
            const node = template.cloneNode(true);
            const status = r.type === 'found' ? 'found' : 'lost';

            node.setAttribute('data-id', r.id);
            node.setAttribute('data-status', status);
            node.setAttribute('data-category', r.category_slug || 'lain');
            node.setAttribute('data-title', String(r.title || '').toLowerCase());
            node.setAttribute('data-date', r.date || '');
            if (r.location_slug) node.setAttribute('data-location', r.location_slug);
            node.setAttribute('onclick', 'window.FindUMB.openReport(\'' + r.id + '\', event)');

            const img = node.querySelector('.report-thumb');
            if (img) {
                img.src = r.image || '';
                img.alt = r.title || '';
            }

            const title = node.querySelector('.report-title');
            if (title) title.textContent = r.title || '';

            const loc = node.querySelector('.report-location span');
            if (loc) loc.textContent = r.location_specific || r.location_full || r.location_campus || '';

            const date = node.querySelector('.report-date');
            if (date) date.textContent = r.date_display || '';

            const badge = node.querySelector('.badge-status');
            if (badge) {
                badge.className = 'badge-status ' + (status === 'found' ? 'badge-found' : 'badge-lost');
                badge.textContent = status === 'found' ? 'Ditemukan' : 'Hilang';
            }

            const shareBtn = node.querySelector('[aria-label="Bagikan"]');
            if (shareBtn) shareBtn.setAttribute('onclick', 'handleShare(' + JSON.stringify(r.title || '') + ')');

            const saveBtn = node.querySelector('[aria-label="Simpan"]');
            if (saveBtn) {
                saveBtn.setAttribute('onclick', 'window.FindUMB.toggleBookmark(this)');
                saveBtn.setAttribute('data-report-id', r.id);
                if (state.bookmarks.indexOf(String(r.id)) !== -1) {
                    saveBtn.classList.add('active');
                    const icon = saveBtn.querySelector('i');
                    if (icon) icon.setAttribute('data-lucide', 'bookmark-check');
                }
            }

            return node.outerHTML;
        }).join('');

        container.innerHTML = html || opts.emptyHtml || '';

        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    }

    function reportListContainer() {
        const cards = document.querySelectorAll('.report-card');
        if (!cards.length) return null;
        // Gunakan parent dari kartu pertama.
        return cards[0].parentElement;
    }

    // -------------------------------------------------------------- actions

    async function toggleBookmark(button) {
        const card = button.closest('[data-id]');
        const reportId = button.getAttribute('data-report-id') || (card ? card.getAttribute('data-id') : null);

        if (!reportId) return;

        if (!isAuthed) {
            toast('Masuk terlebih dahulu untuk menyimpan laporan.', 'error');
            return;
        }

        try {
            const res = await api.post('/api/bookmarks/' + reportId, {});
            const icon = button.querySelector('i');

            if (res.saved) {
                button.classList.add('active');
                if (icon) icon.setAttribute('data-lucide', 'bookmark-check');
                if (state.bookmarks.indexOf(String(reportId)) === -1) state.bookmarks.push(String(reportId));
                toast('Laporan disimpan.');
            } else {
                button.classList.remove('active');
                if (icon) icon.setAttribute('data-lucide', 'bookmark');
                state.bookmarks = state.bookmarks.filter(function (id) { return id !== String(reportId); });
                toast('Laporan dihapus dari daftar tersimpan.');
            }

            if (window.lucide) window.lucide.createIcons();
        } catch (e) {
            toast(e.message, 'error');
        }
    }

    function openReport(id, event) {
        // Desktop (>=1024px) memakai modal detail di halaman yang sama;
        // mobile membuka halaman /report/{id}. Sebelumnya handler ini selalu
        // navigasi, sehingga modal sempat terbuka lalu halaman pindah (kedip).
        const pakaiModal = window.innerWidth >= 1024
            && typeof window.openDesktopDetailModal === 'function';

        if (pakaiModal) {
            if (event && typeof event.stopPropagation === 'function') {
                event.stopPropagation();
            }
            window.openDesktopDetailModal(id);
            return;
        }

        window.location.href = '/report/' + id;
    }

    // ------------------------------------------------------------ halaman

    async function boot() {
        try {
            await loadCommon();
        } catch (e) {
            // Tidak menghentikan halaman bila salah satu permintaan gagal.
            console.warn('[FindUMB] gagal memuat sebagian data:', e.message);
        }

        const path = currentPath();

        // Semua halaman sudah dirender server dari Supabase. Jembatan ini TIDAK
        // boleh menimpa daftar kartu lagi — dulu inilah penyebab "kedip"
        // (kartu server → ditimpa ulang oleh fetch API dengan urutan lain).
        if (path === '/messages') return patchMessagesBindings();

        if (path === '/' || path === '/search' || path === '/my-reports'
            || path === '/saved-reports' || path === '/notifications') {
            return;
        }

        if (path === '/report/lost' || path === '/report/found') return;

        if (path === '/profile') return patchProfile();
        if (path === '/settings') return patchSettings();
        if (/^\/report\/\d+$/.test(path)) return patchReportDetail(path.split('/').pop());
    }

    async function loadCommon() {
        const tasks = [];

        tasks.push(api.get('/api/reports' + (currentPath() === '/search' ? queryString() : '?per_page=10'))
            .then(function (res) { state.reports = res.data || []; }));

        if (isAuthed) {
            tasks.push(api.get('/api/my-reports').then(function (res) { state.myReports = res.data || []; }));
            tasks.push(api.get('/api/notifications').then(function (res) {
                state.notifications = res.data || [];
                state.unreadNotifications = res.unread_count || 0;
            }));
            tasks.push(api.get('/api/conversations').then(function (res) { state.conversations = res.data || []; }));
            tasks.push(api.get('/api/bookmarks').then(function (res) {
                state.bookmarks = (res.data || []).map(function (r) { return String(r.id); });
            }));
        }

        await Promise.all(tasks);
        state.loaded = true;

        paintChrome();
    }

    /** Nama pengguna di sidebar + titik notifikasi. */
    function paintChrome() {
        if (window.FindUMB_USER_NAME) {
            document.querySelectorAll('.sidebar-user-name').forEach(function (el) {
                el.textContent = window.FindUMB_USER_NAME;
            });
            document.querySelectorAll('.sidebar-user-role').forEach(function (el) {
                el.textContent = window.FindUMB_USER_ROLE || 'Civitas Akademika UMB';
            });
        }

        const dot = document.getElementById('header-notif-dot');
        if (dot) {
            dot.style.display = state.unreadNotifications > 0 ? '' : 'none';
        }
    }

    function patchHome() {
        const container = reportListContainer();
        if (container && state.reports.length) {
            hydrateReportCards(container, state.reports.slice(0, 10));
        }

        // Halaman beranda punya renderer sendiri berbasis localStorage.
        if (typeof window.getUserReports === 'function') {
            window.getUserReports = function () { return []; };
        }
    }

    function patchSearch() {
        const container = reportListContainer();
        if (container) {
            if (state.reports.length) {
                hydrateReportCards(container, state.reports);
            } else {
                container.innerHTML = '<div style="padding:32px 16px;text-align:center;color:#64748b;font-size:14px;">' +
                    'Belum ada laporan yang cocok dengan pencarian Anda.</div>';
            }
        }

        // Renderer halaman memakai dua sumber: laporan pengguna & katalog mock.
        window.getUserReports = function () { return state.myReports; };
        window.getReportDataById = function (id) {
            const pool = state.reports.concat(state.myReports);
            return pool.find(function (r) { return String(r.id) === String(id); }) || null;
        };

        if (typeof window.renderCustomSearchReports === 'function') {
            try { window.renderCustomSearchReports(); } catch (e) { /* abaikan */ }
        }
    }

    function patchMyReports() {
        window.getUserReports = function () { return state.myReports; };
        window.getReportDataById = function (id) {
            return state.myReports.find(function (r) { return String(r.id) === String(id); }) || null;
        };

        if (typeof window.renderReportsList === 'function') {
            try { window.renderReportsList(); } catch (e) { /* abaikan */ }
        }
    }

    function patchSavedReports() {
        window.getBookmarks = function () { return state.bookmarks.slice(); };
        window.getSavedList = function () {
            return state.reports
                .filter(function (r) { return state.bookmarks.indexOf(String(r.id)) !== -1; })
                .map(function (r) {
                    return {
                        id: r.id,
                        type: r.type,
                        title: r.title,
                        brand: r.brand || '-',
                        category: r.category_label || r.category || 'Lainnya',
                        location: r.location_full || r.location_campus || 'Kampus Meruya',
                        date: r.date_display || 'Baru Saja',
                        storage: r.storage || (r.type === 'found' ? 'Disimpan Penemu' : 'Disimpan Sendiri'),
                        desc: r.features || 'Tidak ada catatan tambahan.',
                        image: r.image,
                    };
                });
        };

        if (typeof window.renderSavedReports === 'function') {
            try { window.renderSavedReports(); } catch (e) { /* abaikan */ }
        }
    }

    function patchNotifications() {
        window.getStoredNotifications = function () { return state.notifications; };

        if (typeof window.renderNotifications === 'function') {
            try { window.renderNotifications(); } catch (e) { /* abaikan */ }
        }

        // Tandai terbaca saat kartu notifikasi diklik.
        document.addEventListener('click', function (event) {
            const card = event.target.closest('[data-notif-id], .notif-card, .notification-card');
            if (!card) return;

            const id = (card.getAttribute('data-notif-id') || '').replace('notif_', '');
            if (!id) return;

            api.post('/api/notifications/' + id + '/read', {}).catch(function () { /* diamkan */ });
        }, true);
    }

    function patchReportDetail(id) {
        // Halaman detail sekarang dirender penuh oleh server dari Supabase.
        // Fetch ulang lalu menimpa DOM = sumber "kedip" (mock/isi lama → data asli).
        return;
        // eslint-disable-next-line no-unreachable
        api.get('/api/reports/' + id).then(function (res) {
            const r = res.data;
            if (!r) return;

            const setText = function (elId, value) {
                const el = document.getElementById(elId);
                if (el && value !== null && value !== undefined && value !== '') el.textContent = value;
            };

            setText('detail-title', r.title);
            setText('reporter-display-name', r.reporter_name);
            setText('detail-post-date', r.date_display);
            setText('detail-date', r.date_display);
            setText('detail-time', r.time);
            setText('detail-location', r.location_full);
            setText('detail-category', r.category_label || r.category);
            setText('detail-brand', r.brand);
            setText('detail-storage', r.storage);
            setText('detail-features', r.features);

            const img = document.getElementById('detail-hero-img');
            if (img && r.image) img.src = r.image;

            const badge = document.getElementById('detail-status-badge');
            if (badge) {
                badge.className = 'badge-status ' + (r.type === 'found' ? 'badge-found' : 'badge-lost');
                badge.textContent = r.type === 'found' ? 'Ditemukan' : 'Hilang';
            }

            // Tombol klaim pada halaman detail.
            document.querySelectorAll('[data-action="claim"], #btn-claim-item').forEach(function (btn) {
                if (!r.can_claim) {
                    btn.setAttribute('disabled', 'disabled');
                    btn.style.opacity = '0.55';
                    btn.style.pointerEvents = 'none';
                }
            });
        }).catch(function () { /* biarkan konten server-rendered */ });
    }

    function patchMessages() {
        patchMessagesBindings();
    }

    /**
     * Kartu percakapan sudah dirender server (dari tabel claims).
     * Di sini kita hanya memasang handler klik ke thread asli — tidak
     * mengganti markup, supaya tidak ada kedip dan tidak ada data palsu.
     */
    function patchMessagesBindings() {
        // Kartu percakapan sudah ditangani Blade lewat openClaimThread(), dan
        // sendChatMessage() sudah menulis ke API. Di sini cukup deep-link saja
        // (tombol klaim mengarah ke /messages?claim=ID).
        const params = new URLSearchParams(window.location.search);
        const initialClaim = params.get('claim');

        if (initialClaim) {
            const tryOpen = function (attempt) {
                if (typeof window.openClaimThread !== 'function') return;

                const conversation = state.conversations.find(function (c) {
                    return String(c.claim_id) === String(initialClaim);
                });

                if (!conversation) {
                    // Data percakapan belum siap — coba lagi sebentar.
                    if (attempt < 8) setTimeout(function () { tryOpen(attempt + 1); }, 400);
                    return;
                }

                window.openClaimThread(
                    conversation.claim_id,
                    conversation.counterpart_name,
                    conversation.item_title,
                    conversation.status || 'submitted',
                    conversation.item_image || null
                );
            };

            setTimeout(function () { tryOpen(0); }, 200);
        }

        // Daftar percakapan ikut ter-update selama halaman ini terbuka.
        startInboxPolling();
    }


    // ------------------------------------------------- chat: refresh otomatis
    // Thread chat ikut ter-update tanpa reload halaman (SYSTEM_DESIGN 12.3).
    // Polling ringan memakai ?after_id=... supaya server hanya mengirim pesan baru.
    const threadPoll = {
        claimId: null,
        lastId: 0,
        timer: null,
        busy: false,
        seconds: 5,
        listening: false,
    };

    const CHAT_EMPTY_HTML = '<div class="chat-thread-empty" style="text-align:center;padding:28px 16px;color:#64748b;font-size:13.5px;line-height:1.6;">'
        + 'Belum ada pesan. Mulai percakapan dengan menyapa pihak terkait.</div>';

    function threadBody() {
        return document.getElementById('chat-messages-body');
    }

    function isThreadOpen() {
        const sheet = document.getElementById('chat-thread-sheet');
        return !!sheet && sheet.classList.contains('active');
    }

    function bubbleHtml(m) {
        const rowClass = m.is_mine ? 'outgoing' : 'incoming';
        return '<div class="chat-bubble-row ' + rowClass + '">' +
            '<div class="chat-bubble">' + escapeHtml(m.message) + '</div>' +
            '<span class="chat-bubble-time">' + escapeHtml(m.time_display || '') + '</span>' +
            '</div>';
    }

    function renderBubbles(messages, append) {
        const body = threadBody();
        if (!body) return;

        // Jangan lompat ke bawah kalau pengguna sedang membaca pesan lama.
        const stick = (body.scrollHeight - body.scrollTop - body.clientHeight) < 140;

        if (append) {
            if (body.querySelector('.chat-thread-empty')) body.innerHTML = '';
            body.insertAdjacentHTML('beforeend', messages.map(bubbleHtml).join(''));
        } else {
            body.innerHTML = messages.length ? messages.map(bubbleHtml).join('') : CHAT_EMPTY_HTML;
        }

        if (stick || !append) body.scrollTop = body.scrollHeight;
    }

    function applyThreadState(res) {
        const input = document.getElementById('chat-input-field');
        const sendBtn = document.getElementById('btn-send-chat');

        [input, sendBtn].forEach(function (el) {
            if (!el) return;
            if (res.can_send_message) {
                el.removeAttribute('disabled');
                el.style.opacity = '';
                if (el.tagName === 'INPUT') el.placeholder = 'Tulis pesan...';
            } else {
                el.setAttribute('disabled', 'disabled');
                el.style.opacity = '0.55';
                if (el.tagName === 'INPUT') el.placeholder = 'Percakapan telah ditutup (hanya-baca).';
            }
        });
    }

    // Samakan kartu percakapan di daftar (snippet, jam, badge unread).
    function syncConversationCard(messages) {
        const last = messages[messages.length - 1];
        if (!last) return;

        const card = document.querySelector('.conversation-card[data-claim="' + String(last.claim_id) + '"]');
        if (!card) return;

        const snippet = card.querySelector('.conversation-snippet');
        const time = card.querySelector('.conversation-time');
        const badge = card.querySelector('.unread-bubble');

        if (snippet) snippet.textContent = last.message;
        if (time) time.textContent = last.time_display || '';
        if (badge) badge.remove();
        card.classList.remove('unread');
    }

    function stopThreadTimer() {
        if (threadPoll.timer) {
            clearTimeout(threadPoll.timer);
            threadPoll.timer = null;
        }
    }

    function scheduleThreadPoll() {
        stopThreadTimer();
        if (!threadPoll.claimId || document.hidden || !isThreadOpen()) return;
        threadPoll.timer = setTimeout(pollThread, Math.max(2500, threadPoll.seconds * 1000));
    }

    async function pollThread() {
        const claimId = threadPoll.claimId;
        if (!claimId) return;

        if (!isThreadOpen()) { stopThreadPolling(); return; }
        if (threadPoll.busy) { scheduleThreadPoll(); return; }

        threadPoll.busy = true;
        try {
            await loadThread(claimId, threadPoll.lastId ? { afterId: threadPoll.lastId } : {});
        } catch (e) {
            // Jaringan putus sesaat — diamkan, coba lagi di tick berikutnya.
        } finally {
            threadPoll.busy = false;
            if (threadPoll.claimId === claimId) scheduleThreadPoll();
        }
    }

    function startThreadPolling(claimId) {
        threadPoll.claimId = String(claimId);

        if (!threadPoll.listening) {
            threadPoll.listening = true;
            document.addEventListener('visibilitychange', function () {
                if (!threadPoll.claimId) return;
                if (document.hidden) stopThreadTimer();
                else pollThread();
            });
        }

        scheduleThreadPoll();
    }

    function stopThreadPolling() {
        threadPoll.claimId = null;
        threadPoll.lastId = 0;
        stopThreadTimer();
    }

    // ------------------------------------------- daftar percakapan (inbox)
    // Saat thread tertutup, kartu percakapan tetap diperbarui supaya pesan
    // masuk kelihatan tanpa reload halaman.
    const inboxPoll = { timer: null, busy: false, seconds: 20 };

    function scheduleInboxPoll() {
        if (inboxPoll.timer) clearTimeout(inboxPoll.timer);
        inboxPoll.timer = setTimeout(refreshInbox, inboxPoll.seconds * 1000);
    }

    function startInboxPolling() {
        if (inboxPoll.timer) return;
        scheduleInboxPoll();
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) refreshInbox();
        });
    }

    async function refreshInbox() {
        const container = document.getElementById('conversations-container');
        if (!container) return;

        if (document.hidden || inboxPoll.busy || isThreadOpen()) {
            scheduleInboxPoll();
            return;
        }

        inboxPoll.busy = true;
        try {
            const res = await api.get('/api/conversations');

            (res.data || []).forEach(function (c) {
                const card = container.querySelector('.conversation-card[data-claim="' + String(c.claim_id) + '"]');
                if (!card) return;

                const snippet = card.querySelector('.conversation-snippet');
                const time = card.querySelector('.conversation-time');
                const bottomRow = card.querySelector('.conversation-bottom-row');

                if (snippet && c.snippet) snippet.textContent = c.snippet;
                if (time) time.textContent = c.time_display || '';

                let badge = card.querySelector('.unread-bubble');
                if (c.unread) {
                    card.classList.add('unread');
                    if (!badge && bottomRow) {
                        badge = document.createElement('span');
                        badge.className = 'unread-bubble';
                        badge.textContent = '1';
                        bottomRow.appendChild(badge);
                    }
                } else if (badge) {
                    badge.remove();
                    card.classList.remove('unread');
                }
            });
        } catch (e) {
            // Diamkan; coba lagi di putaran berikutnya.
        } finally {
            inboxPoll.busy = false;
            scheduleInboxPoll();
        }
    }

    /**
     * Tampilkan kartu aksi sesuai status klaim dari server:
     * submitted → keputusan penemu, in_handover → konfirmasi dua arah,
     * completed → selesai, status terminal lain → semua kartu disembunyikan.
     */
    function applyClaimState(claim) {
        window.FindUMB.claim = claim || null;
        if (!claim) return;

        const decisionCard = document.getElementById('claim-decision-card');
        const actions = document.getElementById('claim-decision-actions');
        const handoverCard = document.getElementById('handover-confirm-card');
        const pendingSection = document.getElementById('handover-pending-section');
        const successSection = document.getElementById('handover-success-section');
        const submitBtn = document.getElementById('btn-submit-handover');
        const status = claim.status;

        if (decisionCard) decisionCard.style.display = status === 'submitted' ? 'flex' : 'none';

        if (status === 'submitted') {
            const iAmFinder = !!claim.i_am_finder;
            const title = document.getElementById('claim-decision-title');
            const sub = document.getElementById('claim-decision-subtitle');
            const tag = document.getElementById('claim-decision-tag');

            if (title) title.textContent = iAmFinder ? 'Klaim Menunggu Keputusan Anda' : 'Menunggu Persetujuan Penemu';
            if (sub) sub.textContent = iAmFinder
                ? 'Setujui bila ciri barang sudah cocok, lalu atur serah terima.'
                : 'Penemu barang sedang memeriksa klaim Anda. Chat tetap bisa dipakai.';
            if (tag) tag.textContent = iAmFinder ? 'Perlu Tindakan' : 'Menunggu';
            if (actions) actions.style.display = iAmFinder ? 'flex' : 'none';

            // DEC-027 — penemu melihat jawaban verifikasi & foto bukti pengklaim.
            const proofBox = document.getElementById('claim-proof-detail');
            const proofText = document.getElementById('claim-proof-answer-text');
            const proofLink = document.getElementById('claim-proof-photo-link');
            const answer = claim.proof_answer ? String(claim.proof_answer).trim() : '';
            const photo = claim.proof_attachment_url || null;

            if (proofBox && proofText && proofLink) {
                const showProof = iAmFinder && (answer !== '' || photo);
                proofBox.style.display = showProof ? 'block' : 'none';

                if (showProof) {
                    proofText.textContent = answer || 'Pengklaim tidak menuliskan jawaban verifikasi.';
                    if (photo) {
                        proofLink.href = photo;
                        proofLink.style.display = 'inline-block';
                    } else {
                        proofLink.removeAttribute('href');
                        proofLink.style.display = 'none';
                    }
                }
            }
        } else if (actions) {
            actions.style.display = 'none';
        }

        if (!handoverCard) return;

        if (status === 'in_handover') {
            handoverCard.style.display = 'flex';
            handoverCard.classList.remove('confirmed');
            if (pendingSection) pendingSection.style.display = 'flex';
            if (successSection) successSection.style.display = 'none';

            if (submitBtn) {
                if (claim.i_confirmed) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span>Menunggu Konfirmasi Pihak Lain</span>';
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>Konfirmasi Barang Diterima</span>';
                }
            }

            const sub = document.querySelector('#handover-pending-section .handover-step-subtitle');
            if (sub) {
                sub.textContent = claim.i_confirmed
                    ? 'Konfirmasi Anda tersimpan — menunggu pihak lain'
                    : (Number(claim.confirmations_count) > 0 ? '1 dari 2 pihak sudah konfirmasi' : 'Ketuk untuk buka verifikasi');
            }
        } else if (status === 'completed') {
            handoverCard.style.display = 'flex';
            handoverCard.classList.add('confirmed');
            if (pendingSection) pendingSection.style.display = 'none';
            if (successSection) successSection.style.display = 'flex';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Serah Terima Selesai</span>';
            }
        } else {
            handoverCard.style.display = 'none';
        }

        if (typeof window.lucide !== 'undefined') {
            try { window.lucide.createIcons(); } catch (e) { /* abaikan */ }
        }

        // Sembunyikan bilah kartu kalau tidak ada aksi yang perlu ditampilkan.
        const tracker = document.getElementById('chat-claim-tracker');
        if (tracker) {
            const anyVisible = [decisionCard, handoverCard].some(function (el) {
                return el && el.style.display !== 'none';
            });
            tracker.style.display = anyVisible ? '' : 'none';
        }
    }

    /**
     * Muat isi thread. Tanpa options = muat penuh (sekaligus menyalakan polling);
     * dengan { afterId } = hanya tambahkan pesan baru di bawah.
     */
    async function loadThread(claimId, options) {
        options = options || {};
        const body = threadBody();
        if (!body) return null;

        const afterId = options.afterId || null;
        let url = '/api/claims/' + claimId + '/messages';
        if (afterId) url += '?after_id=' + encodeURIComponent(afterId);

        const res = await api.get(url);
        const messages = res.data || [];

        if (!afterId) threadPoll.lastId = 0;

        if (afterId) {
            if (messages.length) renderBubbles(messages, true);
        } else {
            renderBubbles(messages, false);
        }

        messages.forEach(function (m) {
            const id = parseInt(m.id, 10);
            if (!isNaN(id) && id > threadPoll.lastId) threadPoll.lastId = id;
        });

        applyThreadState(res);
        applyClaimState(res.claim);

        if (res.poll_seconds) {
            threadPoll.seconds = Math.max(2, parseInt(res.poll_seconds, 10) || 5);
        }

        if (afterId) {
            syncConversationCard(messages);
        } else if (isThreadOpen()) {
            startThreadPolling(claimId);
        }

        return res;
    }

    /**
     * Aksi "Ajukan Klaim" untuk modal detail desktop.
     * Dipakai tombol di welcome / search / notifications.
     */
    async function claimReport(reportId) {
        const id = String(reportId || '').replace('featured-', '');
        if (!id) return;

        if (!isAuthed) {
            window.location.href = '/login?redirect=' + encodeURIComponent('/report/' + id);
            return;
        }

        let report = null;
        try {
            const res = await api.get('/api/reports/' + id);
            report = res.data;
        } catch (e) {
            toast(e.message || 'Laporan tidak dapat dimuat.', 'error');
            return;
        }

        if (!report) return;

        // Pemilik laporan tidak mengklaim barangnya sendiri.
        if (report.is_mine) {
            window.location.href = '/report/' + id;
            return;
        }

        // Klaim hanya untuk barang temuan (PR-04). Kalau ini laporan HILANG dan
        // kita yang menemukannya → buat laporan temuan yang terhubung ke sini.
        if (report.type !== 'found') {
            window.location.href = '/report/found?ref=' + id;
            return;
        }

        if (!report.can_claim) {
            toast('Klaim belum bisa diajukan: mungkin ada klaim aktif lain atau laporan sudah tidak menerima klaim.', 'error');
            return;
        }

        try {
            const res = await api.post('/api/reports/' + id + '/claims', {});
            const claimId = res && res.data ? res.data.id : null;
            toast('Klaim diajukan. Membuka percakapan...');
            window.location.href = claimId ? '/messages?claim=' + claimId : '/messages';
        } catch (e) {
            toast(e.message || 'Klaim gagal diajukan.', 'error');
        }
    }

    async function openThread(claimId) {
        const conversation = state.conversations.find(function (c) { return String(c.claim_id) === String(claimId); });
        window.FindUMB.activeClaimId = claimId;

        if (conversation && typeof window.openChatThread === 'function') {
            window.openChatThread(
                conversation.counterpart_name,
                conversation.item_title,
                conversation.status || 'submitted',
                conversation.item_image || null
            );
        }

        try {
            await loadThread(claimId);
        } catch (e) {
            toast(e.message, 'error');
        }
    }

    function patchReportForm(type) {
        window.submitReport = async function () {
            const value = function (id) {
                const el = document.getElementById(id);
                return el ? el.value.trim() : '';
            };

            const name = value('input-item-name');
            const features = value('input-features');

            if (!name) { toast('Nama barang wajib diisi.', 'error'); return; }
            if (!features) { toast('Ciri khusus barang wajib diisi.', 'error'); return; }

            const categoryLabel = value('select-category');
            const categoryOther = value('input-category-other');
            const categorySelect = document.getElementById('select-category');

            const form = new FormData();
            form.append('type', type);
            form.append('title', name);
            form.append('brand', value('input-brand'));
            form.append('description', features + (value('input-desc') ? '\n\n' + value('input-desc') : ''));
            form.append('campus_location', value('select-location') || 'Kampus Meruya');
            form.append('location', value('input-specific-location'));
            form.append('incident_date', value('input-date-lost'));
            form.append('incident_time', value('input-time-lost'));
            form.append('storage_note', categoryLabel === 'Lainnya' && categoryOther ? categoryOther : '');

            // Kategori dipetakan dari slug yang sudah dipakai halaman.
            if (categorySelect && categorySelect.value) {
                const slugMap = { 'Elektronik & HP': 'elektronik', 'Tas & Ransel': 'tas', 'Jam & Aksesori': 'aksesoris',
                    'Dompet & Kartu': 'dompet', 'Pakaian & Sepatu': 'pakaian', 'Dokumen & KTM': 'dokumen',
                    'Buku & Alat Tulis': 'buku', 'Lainnya': 'lain' };
                const slug = slugMap[categorySelect.value];
                if (slug && window.FindUMB_CATEGORIES && window.FindUMB_CATEGORIES[slug]) {
                    form.append('category_id', window.FindUMB_CATEGORIES[slug]);
                }
            }

            const fileInput = document.getElementById('file-photo-input');
            if (fileInput && fileInput.files && fileInput.files[0]) {
                form.append('photo', fileInput.files[0]);
            }

            try {
                const res = await api.post('/api/reports', form, true);
                toast(res.message || 'Laporan berhasil dibuat.');
                setTimeout(function () { window.location.href = '/my-reports'; }, 900);
            } catch (e) {
                toast(e.message, 'error');
            }
        };
    }

    function patchProfile() {
        const form = document.querySelector('form[data-profile-form]');
        if (!form) return;

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            try {
                const res = await api.patch('/api/profile', formData, true);
                toast(res.message || 'Profil diperbarui.');
            } catch (e) {
                toast(e.message, 'error');
            }
        });
    }

    function patchSettings() {
        document.querySelectorAll('[data-setting-key]').forEach(function (el) {
            el.addEventListener('change', function () {
                const key = el.getAttribute('data-setting-key');
                const value = el.type === 'checkbox' ? el.checked : el.value;
                const payload = { preferences: {} };
                payload.preferences[key] = typeof value === 'boolean' ? value : value === 'true';
                api.patch('/api/settings', payload).catch(function () { /* diamkan */ });
            });
        });
    }

    // --------------------------------------------------------------- expose

    window.FindUMB = {
        api: api,
        state: state,
        activeClaimId: null,
        claim: null,
        openReport: openReport,
        openThread: openThread,
        claimReport: claimReport,
        toggleBookmark: toggleBookmark,
        loadThread: loadThread,
        applyClaimState: applyClaimState,
        startThreadPolling: startThreadPolling,
        stopThreadPolling: stopThreadPolling,
        toast: toast,
        refresh: boot,
    };

    // Fungsi lama di halaman memakai nama global ini.
    window.toggleBookmark = toggleBookmark;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
