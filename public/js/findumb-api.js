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

        if (path === '/') return patchHome();
        if (path === '/search') return patchSearch();
        if (path === '/my-reports') return patchMyReports();
        if (path === '/saved-reports') return patchSavedReports();
        if (path === '/notifications') return patchNotifications();
        if (path === '/messages') return patchMessages();
        if (path === '/report/lost') return patchReportForm('lost');
        if (path === '/report/found') return patchReportForm('found');
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
        const container = document.querySelector('.conversations-list');
        const template = container ? container.querySelector('.conversation-card') : null;

        if (container && template && state.conversations.length) {
            container.innerHTML = state.conversations.map(function (c) {
                const node = template.cloneNode(true);
                const status = c.can_send_message ? 'active' : 'completed';

                node.setAttribute('data-filter', status);
                node.setAttribute('data-name', String(c.counterpart_name || '').toLowerCase());
                node.setAttribute('data-item', String(c.item_title || '').toLowerCase());
                node.setAttribute('data-claim-id', c.claim_id);
                node.classList.toggle('unread', !!c.unread);
                node.setAttribute('onclick', 'window.FindUMB.openThread(\'' + c.claim_id + '\')');

                const avatar = node.querySelector('.conversation-avatar');
                if (avatar) avatar.alt = c.counterpart_name || '';

                const name = node.querySelector('.conversation-user-name');
                if (name) name.textContent = c.counterpart_name || 'Civitas';

                const tag = node.querySelector('.conversation-item-tag');
                if (tag) tag.textContent = c.item_title || '';

                const time = node.querySelector('.conversation-time');
                if (time) time.textContent = c.time_display || '';

                const snippet = node.querySelector('.conversation-snippet');
                if (snippet) snippet.textContent = c.snippet || 'Belum ada pesan.';

                const bubble = node.querySelector('.unread-bubble');
                if (bubble) bubble.style.display = c.unread ? '' : 'none';

                return node.outerHTML;
            }).join('');

            if (window.lucide) window.lucide.createIcons();
        }

        window.sendChatMessage = async function () {
            const input = document.getElementById('chat-input-field');
            const claimId = window.FindUMB.activeClaimId;

            if (!input || !claimId) return;

            const text = input.value.trim();
            if (!text) return;

            input.value = '';

            try {
                await api.post('/api/claims/' + claimId + '/messages', { message: text });
                await loadThread(claimId);
            } catch (e) {
                input.value = text;
                toast(e.message, 'error');
            }
        };

        // Buka percakapan bila URL memuat ?claim=
        const params = new URLSearchParams(window.location.search);
        const initialClaim = params.get('claim');
        if (initialClaim) {
            setTimeout(function () { window.FindUMB.openThread(initialClaim); }, 300);
        }
    }

    async function loadThread(claimId) {
        const body = document.getElementById('chat-messages-body');
        if (!body) return;

        const res = await api.get('/api/claims/' + claimId + '/messages');
        const messages = res.data || [];

        body.innerHTML = messages.map(function (m) {
            const rowClass = m.is_mine ? 'outgoing' : 'incoming';
            return '<div class="chat-bubble-row ' + rowClass + '">' +
                '<div class="chat-bubble">' + escapeHtml(m.message) + '</div>' +
                '<span class="chat-bubble-time">' + escapeHtml(m.time_display || '') + '</span>' +
                '</div>';
        }).join('');

        body.scrollTop = body.scrollHeight;

        const input = document.getElementById('chat-input-field');
        const sendBtn = document.getElementById('btn-send-chat');

        [input, sendBtn].forEach(function (el) {
            if (!el) return;
            if (res.can_send_message) {
                el.removeAttribute('disabled');
                el.style.opacity = '';
            } else {
                el.setAttribute('disabled', 'disabled');
                el.style.opacity = '0.55';
                if (el.tagName === 'INPUT') el.placeholder = 'Percakapan telah ditutup (hanya-baca).';
            }
        });

        window.FindUMB.claim = res.claim;
    }

    async function openThread(claimId) {
        const conversation = state.conversations.find(function (c) { return String(c.claim_id) === String(claimId); });
        window.FindUMB.activeClaimId = claimId;

        if (conversation && typeof window.openChatThread === 'function') {
            window.openChatThread(
                conversation.counterpart_name,
                conversation.item_title,
                conversation.can_send_message ? 'in_handover' : 'completed',
                null
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
        toggleBookmark: toggleBookmark,
        loadThread: loadThread,
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
