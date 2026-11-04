@extends('layouts.admin')

@section('title', 'Dashboard Administrator — FindUMB')

@section('content')
<div class="space-y-6">

    <!-- ===========================================================================
         1. TOP HEADER & GLOBAL ACTIONS
         =========================================================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-border">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Dashboard Administrator</h1>
            <p class="text-xs sm:text-sm text-muted-foreground mt-1">Pusat komando moderasi pelaporan, penanganan aduan civitas, dan tata kelola platform Lost &amp; Found Universitas Mercu Buana.</p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0">
            <button type="button" onclick="exportAdminReportsCSV()" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg border border-border bg-white text-slate-700 hover:bg-slate-50 hover:text-slate-900 shadow-2xs transition-all">
                <i data-lucide="download" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Unduh Rekap</span>
            </button>
            <button type="button" onclick="openAdminCreateReportModal()" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg bg-slate-900 text-white hover:bg-slate-800 shadow-xs transition-all">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah Laporan</span>
            </button>
        </div>
    </div>

    <!-- ===========================================================================
         2. FOUR METRIC STAT CARDS (Shadcn Card Style)
         =========================================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Laporan Aktif -->
        <div class="p-5 rounded-xl bg-white border border-border shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-muted-foreground">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Laporan Aktif</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#1572b8] flex items-center justify-center">
                    <i data-lucide="package" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-bold tracking-tight text-slate-900" id="stat-active-reports">28</div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1 inline"></i> +14%
                    </span>
                    <span class="text-xs text-muted-foreground">19 Temuan, 9 Hilang</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Laporan Selesai & Kembali -->
        <div class="p-5 rounded-xl bg-white border border-border shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-muted-foreground">
                <span class="text-xs font-semibold uppercase tracking-wider">Barang Selesai Serah Terima</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-bold tracking-tight text-slate-900" id="stat-resolved-reports">94</div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">
                        78.3%
                    </span>
                    <span class="text-xs text-muted-foreground">Tingkat keberhasilan temu</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Aduan Penyalahgunaan (Abuse Reports) -->
        <div class="p-5 rounded-xl bg-white border border-border shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-muted-foreground">
                <span class="text-xs font-semibold uppercase tracking-wider">Aduan Menunggu Review</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="shield-alert" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-bold tracking-tight text-slate-900" id="stat-pending-abuse">3</div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center text-[11px] font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded-full">
                        2 Prioritas Tinggi
                    </span>
                    <span class="text-xs text-muted-foreground">Perlu moderasi</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Akun Ditangguhkan -->
        <div class="p-5 rounded-xl bg-white border border-border shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-muted-foreground">
                <span class="text-xs font-semibold uppercase tracking-wider">Akun Ditangguhkan</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="user-x" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-bold tracking-tight text-slate-900" id="stat-suspended-users">4</div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center text-[11px] font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">
                        Sanksi Etik
                    </span>
                    <span class="text-xs text-muted-foreground">Sesuai aturan DEC-026</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         3. SHADCN TABS SEGMENTED BAR
         =========================================================================== -->
    <div class="flex items-center overflow-x-auto p-1 bg-slate-200/60 rounded-xl max-w-full text-xs font-semibold gap-1 shrink-0" role="tablist">
        <button type="button" onclick="switchAdminTab('overview')" id="tab-btn-overview" class="admin-tab-btn flex-1 min-w-fit px-4 py-2 rounded-lg transition-all text-slate-900 bg-white shadow-xs font-semibold">
            Ringkasan
        </button>
        <button type="button" onclick="switchAdminTab('moderation')" id="tab-btn-moderation" class="admin-tab-btn flex-1 min-w-fit px-4 py-2 rounded-lg transition-all text-slate-600 hover:text-slate-900 hover:bg-white/50 font-medium flex items-center justify-center gap-1.5">
            <span>Aduan &amp; Moderasi</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-rose-500 text-white" id="tab-abuse-count-badge">3</span>
        </button>
        <button type="button" onclick="switchAdminTab('reports')" id="tab-btn-reports" class="admin-tab-btn flex-1 min-w-fit px-4 py-2 rounded-lg transition-all text-slate-600 hover:text-slate-900 hover:bg-white/50 font-medium">
            Kelola Laporan Barang
        </button>
        <button type="button" onclick="switchAdminTab('users')" id="tab-btn-users" class="admin-tab-btn flex-1 min-w-fit px-4 py-2 rounded-lg transition-all text-slate-600 hover:text-slate-900 hover:bg-white/50 font-medium">
            Manajemen Pengguna
        </button>
        <button type="button" onclick="switchAdminTab('audit')" id="tab-btn-audit" class="admin-tab-btn flex-1 min-w-fit px-4 py-2 rounded-lg transition-all text-slate-600 hover:text-slate-900 hover:bg-white/50 font-medium">
            Log Audit Moderasi
        </button>
        <button type="button" onclick="switchAdminTab('categories')" id="tab-btn-categories" class="admin-tab-btn flex-1 min-w-fit px-4 py-2 rounded-lg transition-all text-slate-600 hover:text-slate-900 hover:bg-white/50 font-medium">
            Kategori Barang
        </button>
    </div>

    <!-- ===========================================================================
         TAB PANEL 1: RINGKASAN (OVERVIEW)
         =========================================================================== -->
    <div id="tab-panel-overview" class="admin-tab-panel space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left (7 Cols): Aduan Mendesak -->
            <div class="lg:col-span-7 space-y-4">
                <div class="p-5 rounded-xl bg-white border border-border shadow-xs">
                    <div class="flex items-center justify-between pb-4 border-b border-border">
                        <div>
                            <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                                <i data-lucide="alert-octagon" class="w-4 h-4 text-rose-500"></i>
                                <span>Aduan Perlu Tindakan Segera</span>
                            </h3>
                            <p class="text-xs text-muted-foreground mt-0.5">Laporan dugaan pelanggaran etik, konten penipuan, atau chat tidak pantas.</p>
                        </div>
                        <button type="button" onclick="switchAdminTab('moderation')" class="text-xs text-[#1572b8] hover:underline font-semibold flex items-center gap-1">
                            <span>Lihat Semua</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                    <!-- List of Urgent Abuse Reports -->
                    <div class="divide-y divide-border" id="overview-urgent-abuse-list">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>

            <!-- Right (5 Cols): Aktivitas Moderasi Terbaru & Statistik -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Log Aktivitas Terkini -->
                <div class="p-5 rounded-xl bg-white border border-border shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-border">
                        <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                            <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                            <span>Aktivitas Moderasi Terkini</span>
                        </h3>
                        <span class="text-[11px] text-muted-foreground">Real-time</span>
                    </div>
                    <div class="divide-y divide-border" id="overview-recent-audit-feed">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Sebaran Kategori Barang -->
                <div class="p-5 rounded-xl bg-white border border-border shadow-xs space-y-3.5">
                    <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-[#1572b8]"></i>
                        <span>Sebaran Kategori Barang Civitas</span>
                    </h3>
                    <div class="space-y-2.5 text-xs">
                        <div>
                            <div class="flex justify-between font-medium text-slate-700 mb-1">
                                <span>Elektronik &amp; HP</span>
                                <span class="font-bold text-slate-900">42% (51 laporan)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-[#1572b8] h-2 rounded-full" style="width: 42%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between font-medium text-slate-700 mb-1">
                                <span>Dokumen &amp; KTM</span>
                                <span class="font-bold text-slate-900">28% (34 laporan)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: 28%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between font-medium text-slate-700 mb-1">
                                <span>Tas &amp; Dompet</span>
                                <span class="font-bold text-slate-900">18% (22 laporan)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: 18%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between font-medium text-slate-700 mb-1">
                                <span>Lain-lain &amp; Aksesori</span>
                                <span class="font-bold text-slate-900">12% (15 laporan)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: 12%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sebaran Lokasi Kampus -->
                <div class="p-5 rounded-xl bg-white border border-border shadow-xs">
                    <h3 class="font-bold text-sm text-slate-900 mb-2 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-emerald-600"></i>
                        <span>Cakupan Wilayah Kampus UMB</span>
                    </h3>
                    <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-border">
                            <div class="font-bold text-slate-900">Kampus Meruya</div>
                            <div class="text-slate-500 text-[11px]">84 Laporan • Pos Barat &amp; Tower</div>
                        </div>
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-border">
                            <div class="font-bold text-slate-900">Kampus Menteng</div>
                            <div class="text-slate-500 text-[11px]">22 Laporan • Gedung Utama</div>
                        </div>
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-border">
                            <div class="font-bold text-slate-900">Kampus Pejaten</div>
                            <div class="text-slate-500 text-[11px]">16 Laporan • Pos Keamanan</div>
                        </div>
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-border">
                            <div class="font-bold text-slate-900">Kampus Kranggan</div>
                            <div class="text-slate-500 text-[11px]">6 Laporan • Gedung A</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 2: ADUAN & MODERASI (ABUSE REPORTS)
         =========================================================================== -->
    <div id="tab-panel-moderation" class="admin-tab-panel hidden space-y-4">
        <!-- Control Bar -->
        <div class="p-4 rounded-xl bg-white border border-border shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="abuse-search-input" oninput="renderAbuseReportsTable()" placeholder="Cari pelapor, objek, atau nomor aduan..." class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-border rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                </div>
                <select id="abuse-status-filter" onchange="renderAbuseReportsTable()" class="text-xs bg-slate-50 border border-border rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
                    <option value="all">Semua Status Aduan</option>
                    <option value="pending" selected>Menunggu Review</option>
                    <option value="resolved">Selesai Ditindak</option>
                    <option value="dismissed">Diabaikan / Ditolak</option>
                </select>
                <select id="abuse-reason-filter" onchange="renderAbuseReportsTable()" class="text-xs bg-slate-50 border border-border rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
                    <option value="all">Semua Jenis Pelanggaran</option>
                    <option value="fake_report">Laporan Palsu / Spam</option>
                    <option value="fraud_claim">Klaim Fiktif / Penipuan</option>
                    <option value="inappropriate_chat">Pelecehan / Kata Kasar</option>
                </select>
            </div>
            <div class="text-xs text-muted-foreground text-right shrink-0">
                Total Aduan: <span class="font-bold text-slate-900" id="abuse-total-count">3</span>
            </div>
        </div>

        <!-- Shadcn Table Container -->
        <div class="rounded-xl border border-border bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs shadcn-table">
                    <thead class="bg-slate-50/80 border-b border-border text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="py-3 px-4">No. Aduan</th>
                            <th class="py-3 px-4">Pelapor</th>
                            <th class="py-3 px-4">Objek Dilaporkan</th>
                            <th class="py-3 px-4">Jenis Pelanggaran</th>
                            <th class="py-3 px-4">Keterangan / Bukti</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border text-slate-700" id="abuse-reports-table-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
            <div id="abuse-table-empty" class="hidden p-8 text-center text-slate-500 text-xs">
                Tidak ada aduan penyalahgunaan yang cocok dengan filter.
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 3: KELOLA LAPORAN BARANG
         =========================================================================== -->
    <div id="tab-panel-reports" class="admin-tab-panel hidden space-y-4">
        <!-- Control Bar -->
        <div class="p-4 rounded-xl bg-white border border-border shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="report-search-input" oninput="renderReportsManagementTable()" placeholder="Cari judul barang, pelapor, atau lokasi..." class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-border rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                </div>
                <select id="report-type-filter" onchange="renderReportsManagementTable()" class="text-xs bg-slate-50 border border-border rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
                    <option value="all">Semua Jenis (Hilang &amp; Temuan)</option>
                    <option value="lost">Barang Hilang</option>
                    <option value="found">Barang Temuan</option>
                </select>
                <select id="report-status-filter" onchange="renderReportsManagementTable()" class="text-xs bg-slate-50 border border-border rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
                    <option value="all">Semua Status</option>
                    <option value="open">Open (Aktif Terbuka)</option>
                    <option value="in_handover">In Handover (Proses Serah Terima)</option>
                    <option value="resolved">Resolved (Terselesaikan)</option>
                    <option value="hidden">Hidden (Disembunyikan Admin)</option>
                </select>
            </div>
            <div class="text-xs text-muted-foreground text-right shrink-0">
                Menampilkan: <span class="font-bold text-slate-900" id="reports-total-count">0</span> Laporan
            </div>
        </div>

        <!-- Shadcn Table Container -->
        <div class="rounded-xl border border-border bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs shadcn-table">
                    <thead class="bg-slate-50/80 border-b border-border text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="py-3 px-4">Barang</th>
                            <th class="py-3 px-4">Jenis</th>
                            <th class="py-3 px-4">Pelapor</th>
                            <th class="py-3 px-4">Lokasi &amp; Ruangan</th>
                            <th class="py-3 px-4">Tanggal Kejadian</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border text-slate-700" id="reports-management-table-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
            <div id="reports-table-empty" class="hidden p-8 text-center text-slate-500 text-xs">
                Tidak ada laporan barang yang sesuai filter.
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 4: MANAJEMEN PENGGUNA (USER MANAGEMENT)
         =========================================================================== -->
    <div id="tab-panel-users" class="admin-tab-panel hidden space-y-4">
        <!-- Callout Banner: Admin Email Flexibility & Access Policy -->
        <div class="p-4 rounded-xl bg-sky-50 border border-sky-200 flex items-start gap-3 text-xs text-sky-900">
            <div class="w-6 h-6 rounded-md bg-[#1572b8] text-white flex items-center justify-center shrink-0 mt-0.5">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
            </div>
            <div class="flex-1 leading-relaxed">
                <span class="font-bold text-sky-950">Kebijakan Akun Administrator &amp; Email:</span>
                Civitas mahasiswa wajib mendaftar menggunakan email kampus, sedangkan akun Administrator dapat menggunakan <strong>email institusi (@mercubuana.ac.id)</strong> maupun <strong>email pribadi terpercaya (@gmail.com, dll.)</strong> yang didaftarkan melalui seeder atau ditambahkan langsung oleh Super Admin.
            </div>
            <button type="button" onclick="openCreateAdminModal()" class="px-3 py-1.5 rounded-lg bg-[#1572b8] text-white font-semibold text-xs hover:bg-[#0f5791] shrink-0 transition-colors shadow-2xs flex items-center gap-1.5">
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                <span>+ Tambah Admin</span>
            </button>
        </div>

        <!-- Control Bar -->
        <div class="p-4 rounded-xl bg-white border border-border shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="user-search-input" oninput="renderUsersTable()" placeholder="Cari nama, NIM, email, atau fakultas..." class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-border rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                </div>
                <select id="user-role-filter" onchange="renderUsersTable()" class="text-xs bg-slate-50 border border-border rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
                    <option value="all">Semua Peran (Civitas &amp; Admin)</option>
                    <option value="user">Civitas Mahasiswa</option>
                    <option value="admin">Administrator Kampus</option>
                </select>
                <select id="user-status-filter" onchange="renderUsersTable()" class="text-xs bg-slate-50 border border-border rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
                    <option value="all">Semua Status Akun</option>
                    <option value="active">Aktif Normal</option>
                    <option value="suspended">Ditangguhkan (Suspended)</option>
                </select>
            </div>
            <div class="text-xs text-muted-foreground text-right shrink-0">
                Total Akun: <span class="font-bold text-slate-900" id="users-total-count">0</span>
            </div>
        </div>

        <!-- Shadcn Table Container -->
        <div class="rounded-xl border border-border bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs shadcn-table">
                    <thead class="bg-slate-50/80 border-b border-border text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="py-3 px-4">Pengguna</th>
                            <th class="py-3 px-4">NIM / NIK</th>
                            <th class="py-3 px-4">Fakultas &amp; Program Studi</th>
                            <th class="py-3 px-4">Alamat Email</th>
                            <th class="py-3 px-4">Peran (Role)</th>
                            <th class="py-3 px-4">Status Akun</th>
                            <th class="py-3 px-4 text-right">Aksi Akun</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border text-slate-700" id="users-table-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
            <div id="users-table-empty" class="hidden p-8 text-center text-slate-500 text-xs">
                Tidak ada pengguna yang cocok dengan kriteria filter.
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 5: LOG AUDIT MODERASI (AUDIT TRAIL)
         =========================================================================== -->
    <div id="tab-panel-audit" class="admin-tab-panel hidden space-y-4">
        <!-- Control Bar -->
        <div class="p-4 rounded-xl bg-white border border-border shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="audit-search-input" oninput="renderAuditTable()" placeholder="Cari aktor admin, target, atau alasan tindakan..." class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-border rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                </div>
                <select id="audit-action-filter" onchange="renderAuditTable()" class="text-xs bg-slate-50 border border-border rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900">
                    <option value="all">Semua Jenis Tindakan</option>
                    <option value="hide_report">Sembunyikan Laporan</option>
                    <option value="suspend_user">Penangguhan Akun</option>
                    <option value="warn_user">Peringatan Formal</option>
                    <option value="reactivate_user">Reaktivasi Akun</option>
                    <option value="dismiss_abuse">Abaikan Aduan</option>
                </select>
            </div>
            <div class="text-xs text-muted-foreground text-right shrink-0">
                Total Riwayat: <span class="font-bold text-slate-900" id="audit-total-count">0</span> Tindakan
            </div>
        </div>

        <!-- Shadcn Table Container -->
        <div class="rounded-xl border border-border bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs shadcn-table">
                    <thead class="bg-slate-50/80 border-b border-border text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="py-3 px-4">Waktu &amp; Tanggal</th>
                            <th class="py-3 px-4">Admin Pelaksana</th>
                            <th class="py-3 px-4">Jenis Tindakan</th>
                            <th class="py-3 px-4">Target Objek / Pengguna</th>
                            <th class="py-3 px-4">Alasan &amp; Dasar Keputusan</th>
                            <th class="py-3 px-4 text-right">Dampak Sistem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border text-slate-700" id="audit-table-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
            <div id="audit-table-empty" class="hidden p-8 text-center text-slate-500 text-xs">
                Belum ada catatan log audit yang terekam.
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 6: KATEGORI BARANG
         =========================================================================== -->
    <div id="tab-panel-categories" class="admin-tab-panel hidden space-y-4">
        <div class="p-5 rounded-xl bg-white border border-border shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-border mb-4">
                <div>
                    <h3 class="font-bold text-base text-slate-900">Kelola Kategori Barang Kampus</h3>
                    <p class="text-xs text-muted-foreground mt-0.5">Daftar taksonomi resmi kategori barang hilang dan temuan civitas Universitas Mercu Buana.</p>
                </div>
                <button type="button" onclick="showAdminToast('Fitur penambahan kategori master aktif.')" class="px-3.5 py-2 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition-colors flex items-center gap-1.5 shadow-xs">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="categories-grid-container">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>

</div>

<!-- ===========================================================================
     MODAL DIALOGS (SHADCN DIALOG STYLES)
     =========================================================================== -->

<!-- Modal 1: Tindakan Moderasi & Wajib Input Alasan (DEC-013, DEC-026) -->
<div id="modal-moderation-action" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-border overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-6 border-b border-border flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div id="modal-mod-icon-wrap" class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base text-slate-900" id="modal-mod-title">Tindakan Moderasi</h3>
                    <p class="text-xs text-muted-foreground" id="modal-mod-sub">Eksekusi sanksi dan catat dasar keputusan admin.</p>
                </div>
            </div>
            <button type="button" onclick="closeModerationActionModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-moderation-action" onsubmit="executeModerationCommit(event)" class="p-6 space-y-4">
            <input type="hidden" id="mod-target-type">
            <input type="hidden" id="mod-target-id">
            <input type="hidden" id="mod-action-type">

            <!-- Detail Target Info Box -->
            <div class="p-3.5 rounded-xl bg-slate-50 border border-border space-y-1.5 text-xs">
                <div class="text-slate-500 font-medium">Target Objek:</div>
                <div class="font-bold text-slate-900 text-sm" id="mod-target-summary">-</div>
                <div class="text-slate-600 text-[11px]" id="mod-target-subinfo">-</div>
            </div>

            <!-- Impact Info Callout Box (DEC-026) -->
            <div id="mod-impact-callout" class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs space-y-1">
                <span class="font-bold flex items-center gap-1.5">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Dampak Sistem Otomatis (DEC-026):</span>
                </span>
                <p id="mod-impact-text" class="text-[11px] leading-relaxed text-amber-800">
                    Penonaktifan akun akan membatalkan klaim aktif terkait dan mengunci percakapan menjadi hanya-baca.
                </p>
            </div>

            <!-- Form: Alasan Tindakan (Mandatory) -->
            <div class="space-y-1.5">
                <label for="mod-reason-input" class="text-xs font-bold text-slate-900 flex items-center justify-between">
                    <span>Alasan &amp; Dasar Keputusan Moderasi <span class="text-rose-500">*</span></span>
                    <span class="text-[11px] font-normal text-muted-foreground">Wajib Dicatat</span>
                </label>
                <textarea id="mod-reason-input" rows="3" required placeholder="Tuliskan pelanggaran yang terjadi secara spesifik demi transparansi audit kampus..." class="w-full p-3 text-xs bg-white border border-border rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900 leading-relaxed"></textarea>
            </div>

            <div class="pt-3 border-t border-border flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModerationActionModal()" class="px-4 py-2 rounded-lg border border-border text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-colors">
                    Batal
                </button>
                <button type="submit" id="btn-mod-submit-confirm" class="px-4 py-2 rounded-lg bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-colors shadow-xs">
                    Eksekusi Tindakan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Detail Aduan & Konten -->
<div id="modal-abuse-detail" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl border border-border overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-5 border-b border-border flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-sky-100 text-[#1572b8] flex items-center justify-center">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900">Rincian Aduan Penyalahgunaan</h3>
            </div>
            <button type="button" onclick="closeAbuseDetailModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
            <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 rounded-xl border border-border">
                <div>
                    <span class="text-slate-500 block text-[11px]">ID Aduan:</span>
                    <span class="font-bold text-slate-900" id="detail-abuse-id">-</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">Waktu Lapor:</span>
                    <span class="font-bold text-slate-900" id="detail-abuse-date">-</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">Identitas Pelapor:</span>
                    <span class="font-bold text-slate-900" id="detail-abuse-reporter">-</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">Status Saat Ini:</span>
                    <span class="font-bold text-slate-900" id="detail-abuse-status">-</span>
                </div>
            </div>

            <div>
                <span class="font-bold text-slate-900 block mb-1">Objek Yang Dilaporkan:</span>
                <div class="p-3 bg-white border border-border rounded-xl" id="detail-abuse-target-box">-</div>
            </div>

            <div>
                <span class="font-bold text-slate-900 block mb-1">Jenis Pelanggaran:</span>
                <div class="p-2.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg font-semibold" id="detail-abuse-reason-badge">-</div>
            </div>

            <div>
                <span class="font-bold text-slate-900 block mb-1">Uraian / Kronologi Bukti Pelapor:</span>
                <p class="p-3.5 bg-slate-50 border border-border rounded-xl text-slate-700 leading-relaxed" id="detail-abuse-description">-</p>
            </div>
        </div>

        <div class="p-4 border-t border-border flex items-center justify-end gap-2.5 bg-slate-50/50">
            <button type="button" onclick="closeAbuseDetailModal()" class="px-4 py-2 rounded-lg border border-border text-xs font-semibold text-slate-700 hover:bg-slate-100">
                Tutup
            </button>
            <button type="button" id="btn-detail-action-dismiss" class="px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-100">
                Abaikan Aduan
            </button>
            <button type="button" id="btn-detail-action-hide" class="px-3.5 py-2 rounded-lg bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700">
                Sembunyikan Laporan
            </button>
        </div>
    </div>
</div>

<!-- Modal 3: Tambah Akun Administrator Baru (Mendukung Email Pribadi / Kampus) -->
<div id="modal-create-admin" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-border overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-5 border-b border-border flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900">Tambah Akun Administrator</h3>
                    <p class="text-[11px] text-muted-foreground">Mendukung email institusi maupun email pribadi.</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateAdminModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="form-create-admin" onsubmit="handleCreateAdminSubmit(event)" class="p-6 space-y-3.5 text-xs">
            <div class="space-y-1">
                <label for="admin-new-name" class="font-bold text-slate-900">Nama Lengkap Petugas Admin <span class="text-rose-500">*</span></label>
                <input type="text" id="admin-new-name" required placeholder="Contoh: Petugas Biro Kemahasiswaan UMB" class="w-full p-2.5 bg-slate-50 border border-border rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
            </div>

            <div class="space-y-1">
                <label for="admin-new-email" class="font-bold text-slate-900">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" id="admin-new-email" required placeholder="admin.umb@mercubuana.ac.id atau email pribadi" class="w-full p-2.5 bg-slate-50 border border-border rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                <span class="text-[10px] text-slate-500 block">Dapat menggunakan email kampus maupun email pribadi (@gmail.com, dll.).</span>
            </div>

            <div class="space-y-1">
                <label for="admin-new-unit" class="font-bold text-slate-900">Unit / Satuan Kerja</label>
                <input type="text" id="admin-new-unit" placeholder="Contoh: Pos Satpam Tower Meruya / Biro Kemahasiswaan" class="w-full p-2.5 bg-slate-50 border border-border rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
            </div>

            <div class="space-y-1">
                <label for="admin-new-role" class="font-bold text-slate-900">Tingkat Hak Akses</label>
                <select id="admin-new-role" class="w-full p-2.5 bg-slate-50 border border-border rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                    <option value="admin">Administrator Moderasi</option>
                    <option value="superadmin">Super Administrator</option>
                </select>
            </div>

            <div class="pt-3 border-t border-border flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeCreateAdminModal()" class="px-4 py-2 rounded-lg border border-border text-xs font-semibold text-slate-700 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 shadow-xs">
                    Simpan Admin Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 4: Pengaturan Akun Admin -->
<div id="modal-admin-settings" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-border overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-5 border-b border-border flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-sm text-slate-900">Pengaturan Akun Administrator</h3>
            <button type="button" onclick="closeAdminSettingsModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form onsubmit="saveAdminSettings(event)" class="p-6 space-y-3.5 text-xs">
            <div class="space-y-1">
                <label for="settings-admin-name" class="font-bold text-slate-900">Nama Tampilan Admin</label>
                <input type="text" id="settings-admin-name" required value="Biro Kemahasiswaan UMB" class="w-full p-2.5 bg-slate-50 border border-border rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
            </div>
            <div class="space-y-1">
                <label for="settings-admin-email" class="font-bold text-slate-900">Email Akun Admin</label>
                <input type="email" id="settings-admin-email" required value="admin.lostfound@mercubuana.ac.id" class="w-full p-2.5 bg-slate-50 border border-border rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                <span class="text-[10px] text-slate-500">Bisa diubah ke email pribadi Anda jika diperlukan.</span>
            </div>
            <div class="pt-3 border-t border-border flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeAdminSettingsModal()" class="px-4 py-2 rounded-lg border border-border text-xs font-semibold text-slate-700 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /* ===========================================================================
       ADMIN DASHBOARD DATA STORE & LOGIC (Mock Engine with LocalStorage Sync)
       =========================================================================== */

    // 1. Initial Sample Abuse Reports (Aduan Penyalahgunaan)
    const DEFAULT_ABUSE_REPORTS = [
        {
            id: 'ABUSE-2026-001',
            reporter_name: 'Muhammad Farhan',
            reporter_nim: '41521010088',
            target_type: 'report', // 'report', 'chat', 'user'
            target_id: 'rep_fake_iphone',
            target_title: 'iPhone 15 Pro Max Deep Blue',
            reason_code: 'fake_report',
            reason_label: 'Laporan Palsu / Spam',
            severity: 'high',
            description: 'Foto diambil dari Google Images dan tidak ada barang aslinya. Pelapor mencoba memancing chat transaksi fiktif di grup kampus.',
            date: '04 Nov 2026, 14:20 WIB',
            status: 'pending' // 'pending', 'resolved', 'dismissed'
        },
        {
            id: 'ABUSE-2026-002',
            reporter_name: 'Siti Nurhaliza',
            reporter_nim: '41521010012',
            target_type: 'chat',
            target_id: 'chat_tws_anker',
            target_title: 'Percakapan Klaim Earphone TWS Anker',
            reason_code: 'inappropriate_chat',
            reason_label: 'Pelecehan / Kata Kasar',
            severity: 'high',
            description: 'Pengklaim menggunakan kata-kata kasar dan mengancam saat diminta membuktikan ciri nomor seri pada case TWS.',
            date: '04 Nov 2026, 09:15 WIB',
            status: 'pending'
        },
        {
            id: 'ABUSE-2026-003',
            reporter_name: 'Dwi Prasetyo',
            reporter_nim: '41521010034',
            target_type: 'user',
            target_id: 'user_41521010099',
            target_title: 'Akun Civitas: Bagus Triyanto (41521010099)',
            reason_code: 'fraud_claim',
            reason_label: 'Klaim Fiktif Berulang',
            severity: 'medium',
            description: 'Mengajukan klaim serah terima pada 5 laporan barang temuan berbeda sekaligus dalam 1 hari dengan ciri asal-asalan.',
            date: '03 Nov 2026, 19:40 WIB',
            status: 'pending'
        }
    ];

    // 2. Initial Sample Users
    const DEFAULT_ADMIN_USERS = [
        {
            id: '1',
            name: 'Ahmad Fauzi',
            nim: '41521010045',
            faculty: 'Fakultas Ilmu Komputer',
            prodi: 'S1 Teknik Informatika',
            email: '41521010045@student.mercubuana.ac.id',
            role: 'user',
            status: 'active'
        },
        {
            id: '2',
            name: 'Biro Kemahasiswaan & Admin UMB',
            nim: 'NIK-UMB-8821',
            faculty: 'Biro Kemahasiswaan',
            prodi: 'Staf Administrasi',
            email: 'admin.lostfound@mercubuana.ac.id',
            role: 'admin',
            status: 'active'
        },
        {
            id: '3',
            name: 'Zidan Maulana (Admin Pengembang)',
            nim: 'NIK-DEV-001',
            faculty: 'Pengembang Platform',
            prodi: 'IT Campus Center',
            email: 'zidan.personal@gmail.com', // Contoh Email Pribadi Sah untuk Admin
            role: 'admin',
            status: 'active'
        },
        {
            id: '4',
            name: 'Bagus Triyanto',
            nim: '41521010099',
            faculty: 'Fakultas Teknik',
            prodi: 'S1 Teknik Sipil',
            email: '41521010099@student.mercubuana.ac.id',
            role: 'user',
            status: 'suspended' // Contoh akun terkena sanksi moderasi
        },
        {
            id: '5',
            name: 'Siti Nurhaliza',
            nim: '41521010012',
            faculty: 'Fakultas Ekonomi & Bisnis',
            prodi: 'S1 Manajemen',
            email: '41521010012@student.mercubuana.ac.id',
            role: 'user',
            status: 'active'
        }
    ];

    // 3. Initial Sample Audit Trail Logs
    const DEFAULT_AUDIT_LOGS = [
        {
            id: 'AUD-901',
            timestamp: '04 Nov 2026, 15:10 WIB',
            admin_name: 'Biro Kemahasiswaan UMB',
            admin_email: 'admin.lostfound@mercubuana.ac.id',
            action_type: 'suspend_user',
            action_label: 'Penangguhan Akun Pengguna',
            target: 'Bagus Triyanto (41521010099)',
            reason: 'Spam klaim fiktif berulang kali tanpa bukti kepemilikan yang sah.',
            impact: 'Seluruh klaim dibatalkan, hak klaim diblokir (DEC-026).'
        },
        {
            id: 'AUD-902',
            timestamp: '03 Nov 2026, 11:25 WIB',
            admin_name: 'Zidan Maulana',
            admin_email: 'zidan.personal@gmail.com',
            action_type: 'hide_report',
            action_label: 'Sembunyikan Laporan',
            target: 'Laporan #rep_spam_99 (Cincin Emas Palsu)',
            reason: 'Foto diambil dari web komersial dan menyertakan nomor WA penipu.',
            impact: 'Laporan diubah ke status hidden, dihilangkan dari pencarian publik.'
        },
        {
            id: 'AUD-903',
            timestamp: '02 Nov 2026, 09:00 WIB',
            admin_name: 'Biro Kemahasiswaan UMB',
            admin_email: 'admin.lostfound@mercubuana.ac.id',
            action_type: 'warn_user',
            action_label: 'Peringatan Formal Civitas',
            target: 'Rian Pratama (41521010022)',
            reason: 'Laporan barang hilang menggunakan kata provokatif menuduh civitas lain.',
            impact: 'Peringatan terkirim ke notifikasi pengguna.'
        }
    ];

    // 4. Initial Sample Categories
    const DEFAULT_CATEGORIES = [
        { id: '1', name: 'Elektronik & HP', count: 51, icon: 'smartphone', desc: 'Handphone, laptop, charger, TWS, powerbank.' },
        { id: '2', name: 'Dokumen & KTM', count: 34, icon: 'file-text', desc: 'Kartu Mahasiswa, KTP, SIM, paspor, berkas ijazah.' },
        { id: '3', name: 'Tas & Dompet', count: 22, icon: 'briefcase', desc: 'Dompet kartu, tas ransel, totebag, pouch.' },
        { id: '4', name: 'Kunci & Kendaraan', count: 18, icon: 'key', desc: 'Kunci motor/mobil, smart key, STNK kendaraan.' },
        { id: '5', name: 'Jam & Aksesori', count: 15, icon: 'watch', desc: 'Jam tangan, cincin, kacamata, gelang.' },
        { id: '6', name: 'Perlengkapan Pribadi', count: 12, icon: 'coffee', desc: 'Tumbler, payung, botol minum, jaket.' },
        { id: '7', name: 'Buku & ATK', count: 9, icon: 'book', desc: 'Buku perkuliahan, binder catatan, kalkulator ilmiah.' },
        { id: '8', name: 'Lainnya', count: 6, icon: 'layers', desc: 'Barang serbaguna yang tidak masuk kategori utama.' }
    ];

    /* ===========================================================================
       INITIALIZATION & DATA ACCESSORS
       =========================================================================== */

    function getAdminAbuseReports() {
        try {
            const raw = localStorage.getItem('findumb_admin_abuse_reports');
            if (raw) return JSON.parse(raw);
        } catch(e) {}
        saveAdminAbuseReports(DEFAULT_ABUSE_REPORTS);
        return DEFAULT_ABUSE_REPORTS;
    }

    function saveAdminAbuseReports(data) {
        try {
            localStorage.setItem('findumb_admin_abuse_reports', JSON.stringify(data));
        } catch(e) {}
    }

    function getAdminUsers() {
        try {
            const raw = localStorage.getItem('findumb_admin_users');
            if (raw) return JSON.parse(raw);
        } catch(e) {}
        saveAdminUsers(DEFAULT_ADMIN_USERS);
        return DEFAULT_ADMIN_USERS;
    }

    function saveAdminUsers(data) {
        try {
            localStorage.setItem('findumb_admin_users', JSON.stringify(data));
        } catch(e) {}
    }

    function getAdminAuditLogs() {
        try {
            const raw = localStorage.getItem('findumb_admin_audit_logs');
            if (raw) return JSON.parse(raw);
        } catch(e) {}
        saveAdminAuditLogs(DEFAULT_AUDIT_LOGS);
        return DEFAULT_AUDIT_LOGS;
    }

    function saveAdminAuditLogs(data) {
        try {
            localStorage.setItem('findumb_admin_audit_logs', JSON.stringify(data));
        } catch(e) {}
    }

    function getAllReportsCatalog() {
        // Gabungan katalog statis & laporan custom civitas
        const catalog = [
            { id: '1', title: 'Samsung SM-A135F', type: 'lost', reporter: 'Ahmad Fauzi (41521010045)', location: 'Kampus Meruya — Tower Lt. 7', date: '20 Jun 2026', status: 'open', image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300' },
            { id: '2', title: 'Earphone TWS Anker Soundcore', type: 'found', reporter: 'Siti Nurhaliza (41521010012)', location: 'Kampus Meruya — Perpustakaan Lt. 2', date: '18 Okt 2026', status: 'in_handover', image: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=300' },
            { id: '3', title: 'Tas Laptop Lenovo ThinkPad', type: 'found', reporter: 'Pos Satpam Tower', location: 'Kampus Meruya — Pos Satpam Lt. 1', date: '19 Jan 2026', status: 'open', image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=300' },
            { id: '4', title: 'Kunci Motor Honda Vario', type: 'lost', reporter: 'Muhammad Farhan (41521010088)', location: 'Kampus Meruya — Parkir Barat', date: '19 Jan 2026', status: 'open', image: 'https://images.unsplash.com/photo-1582139329536-e7284fece509?w=300' },
            { id: '5', title: 'Cincin Emas Berlian', type: 'lost', reporter: 'Ahmad Fauzi (41521010045)', location: 'Kampus Meruya — Parkir Barat', date: '15 Jan 2026', status: 'resolved', image: 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=300' },
            { id: '6', title: 'Jaket Hoodie Hitam Uniqlo', type: 'found', reporter: 'Petugas Lab Komputer', location: 'Kampus Meruya — Lab Fasilkom', date: '12 Jan 2026', status: 'open', image: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=300' },
            { id: '7', title: 'KTM & Dompet Kulit Cokelat', type: 'found', reporter: 'Dwi Prasetyo (41521010034)', location: 'Kampus Meruya — Kantin Blok D', date: '22 Okt 2026', status: 'open', image: 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=300' },
            { id: '8', title: 'Tumbler Corkcicle Putih 16oz', type: 'lost', reporter: 'Nabila Putri (41521010055)', location: 'Kampus Meruya — Gazebo Rektorat', date: '18 Jan 2026', status: 'open', image: 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=300' },
            { id: 'rep_fake_iphone', title: 'iPhone 15 Pro Max Deep Blue', type: 'lost', reporter: 'Akun Palsu (41521010000)', location: 'Kampus Menteng — Ruang Serbaguna', date: '04 Nov 2026', status: 'hidden', image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300' }
        ];

        // Merge custom reports from localStorage if exists
        try {
            const rawCustom = localStorage.getItem('findumb_custom_reports');
            if (rawCustom) {
                const parsed = JSON.parse(rawCustom);
                if (Array.isArray(parsed)) {
                    parsed.forEach(c => {
                        if (!catalog.some(item => String(item.id) === String(c.id))) {
                            catalog.push({
                                id: c.id,
                                title: c.title,
                                type: c.type || 'lost',
                                reporter: c.reporter_name || 'Ahmad Fauzi (41521010045)',
                                location: c.location_full || c.location_campus || 'Kampus Meruya',
                                date: c.date_display || c.date || 'Hari Ini',
                                status: c.status || 'open',
                                image: c.image || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=300'
                            });
                        }
                    });
                }
            }
        } catch(e) {}

        return catalog;
    }

    /* ===========================================================================
       TAB SWITCHER & ROUTING
       =========================================================================== */

    function switchAdminTab(tabId) {
        document.querySelectorAll('.admin-tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        const activePanel = document.getElementById(`tab-panel-${tabId}`);
        if (activePanel) activePanel.classList.remove('hidden');

        // Update Top Breadcrumb
        const breadcrumbMap = {
            'overview': 'Ringkasan Dashboard',
            'moderation': 'Aduan & Moderasi',
            'reports': 'Kelola Laporan Barang',
            'users': 'Manajemen Pengguna',
            'audit': 'Log Audit Moderasi',
            'categories': 'Kategori Barang'
        };
        const titleEl = document.getElementById('top-breadcrumb-title');
        if (titleEl) titleEl.textContent = breadcrumbMap[tabId] || 'Dashboard Administrator';

        // Update Tab Segmented Button Styles
        document.querySelectorAll('.admin-tab-btn').forEach(btn => {
            btn.className = 'admin-tab-btn flex-1 min-w-fit px-4 py-2 rounded-lg transition-all text-slate-600 hover:text-slate-900 hover:bg-white/50 font-medium';
        });
        const activeBtn = document.getElementById(`tab-btn-${tabId}`);
        if (activeBtn) {
            activeBtn.className = 'admin-tab-btn flex-1 min-w-fit px-4 py-2 rounded-lg transition-all text-slate-900 bg-white shadow-xs font-semibold';
        }

        // Update Sidebar Active Links
        document.querySelectorAll('.admin-nav-item').forEach(item => {
            item.className = 'admin-nav-item flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-slate-700 hover:text-slate-900 hover:bg-slate-100';
        });
        const activeNavItem = document.getElementById(`nav-tab-${tabId}`);
        if (activeNavItem) {
            activeNavItem.className = 'admin-nav-item flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors bg-slate-900 text-white shadow-xs';
        }

        // Re-render corresponding data view
        if (tabId === 'overview') renderOverviewDashboard();
        if (tabId === 'moderation') renderAbuseReportsTable();
        if (tabId === 'reports') renderReportsManagementTable();
        if (tabId === 'users') renderUsersTable();
        if (tabId === 'audit') renderAuditTable();
        if (tabId === 'categories') renderCategoriesGrid();

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    /* ===========================================================================
       RENDERING LOGIC
       =========================================================================== */

    function refreshDashboardMetrics() {
        const abuseReports = getAdminAbuseReports();
        const pendingAbuse = abuseReports.filter(a => a.status === 'pending');
        const users = getAdminUsers();
        const suspendedUsers = users.filter(u => u.status === 'suspended');
        const reports = getAllReportsCatalog();
        const activeReports = reports.filter(r => r.status === 'open' || r.status === 'in_handover');
        const resolvedReports = reports.filter(r => r.status === 'resolved');

        document.getElementById('stat-active-reports').textContent = activeReports.length;
        document.getElementById('stat-resolved-reports').textContent = resolvedReports.length + 94; // Baseline
        document.getElementById('stat-pending-abuse').textContent = pendingAbuse.length;
        document.getElementById('stat-suspended-users').textContent = suspendedUsers.length;

        // Update Badges
        const sidebarBadge = document.getElementById('sidebar-abuse-badge');
        if (sidebarBadge) sidebarBadge.textContent = pendingAbuse.length;
        const tabBadge = document.getElementById('tab-abuse-count-badge');
        if (tabBadge) tabBadge.textContent = pendingAbuse.length;

        const notifDot = document.getElementById('header-notif-indicator');
        if (notifDot) notifDot.style.display = pendingAbuse.length > 0 ? 'block' : 'none';

        const reportsCount = document.getElementById('sidebar-reports-count');
        if (reportsCount) reportsCount.textContent = reports.length;
    }

    function renderOverviewDashboard() {
        refreshDashboardMetrics();
        const abuseReports = getAdminAbuseReports();
        const pending = abuseReports.filter(a => a.status === 'pending');
        const urgentContainer = document.getElementById('overview-urgent-abuse-list');

        if (!urgentContainer) return;

        if (pending.length === 0) {
            urgentContainer.innerHTML = `
                <div class="py-8 text-center text-slate-500 text-xs">
                    <i data-lucide="shield-check" class="w-8 h-8 mx-auto text-emerald-500 mb-2"></i>
                    <p class="font-semibold text-slate-800">Tidak ada aduan penyalahgunaan pending.</p>
                    <p class="text-[11px] text-slate-400">Platform Lost &amp; Found dalam kondisi aman dan tertib.</p>
                </div>
            `;
        } else {
            urgentContainer.innerHTML = pending.map(item => `
                <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="${item.reason_code === 'inappropriate_chat' ? 'message-square-off' : 'alert-circle'}" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 truncate">${escapeHtml(item.target_title)}</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">${escapeHtml(item.reason_label)}</span>
                            </div>
                            <p class="text-slate-600 text-[11px] mt-0.5 line-clamp-1">${escapeHtml(item.description)}</p>
                            <div class="flex items-center gap-3 text-[10px] text-muted-foreground mt-1">
                                <span>Pelapor: <strong class="text-slate-700">${escapeHtml(item.reporter_name)}</strong></span>
                                <span>•</span>
                                <span>${item.date}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                        <button type="button" onclick="openAbuseDetailModal('${item.id}')" class="px-2.5 py-1.5 rounded-lg border border-border hover:bg-slate-100 text-slate-700 font-semibold text-[11px] transition-colors">
                            Tinjau
                        </button>
                        <button type="button" onclick="openModerationActionModal('hide_report', 'report', '${item.target_id}', '${escapeHtml(item.target_title)}', 'Sembunyikan laporan dari publik')" class="px-2.5 py-1.5 rounded-lg bg-rose-600 text-white font-semibold text-[11px] hover:bg-rose-700 transition-colors">
                            Sembunyikan
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Recent Audit Feed
        const audits = getAdminAuditLogs().slice(0, 4);
        const auditFeed = document.getElementById('overview-recent-audit-feed');
        if (auditFeed) {
            auditFeed.innerHTML = audits.map(log => `
                <div class="py-2.5 text-xs flex items-start gap-2.5">
                    <div class="w-2 h-2 rounded-full bg-slate-900 mt-1.5 shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-slate-900 truncate">${escapeHtml(log.action_label)}</div>
                        <div class="text-[11px] text-slate-600 truncate">Target: ${escapeHtml(log.target)}</div>
                        <div class="text-[10px] text-muted-foreground mt-0.5">${log.timestamp} • Oleh: ${escapeHtml(log.admin_name)}</div>
                    </div>
                </div>
            `).join('');
        }

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function renderAbuseReportsTable() {
        const query = (document.getElementById('abuse-search-input')?.value || '').toLowerCase();
        const statusFilter = document.getElementById('abuse-status-filter')?.value || 'all';
        const reasonFilter = document.getElementById('abuse-reason-filter')?.value || 'all';

        const reports = getAdminAbuseReports();
        const tbody = document.getElementById('abuse-reports-table-body');
        const emptyEl = document.getElementById('abuse-table-empty');

        const filtered = reports.filter(r => {
            const matchQuery = !query || r.id.toLowerCase().includes(query) || r.reporter_name.toLowerCase().includes(query) || r.target_title.toLowerCase().includes(query);
            const matchStatus = statusFilter === 'all' || r.status === statusFilter;
            const matchReason = reasonFilter === 'all' || r.reason_code === reasonFilter;
            return matchQuery && matchStatus && matchReason;
        });

        document.getElementById('abuse-total-count').textContent = filtered.length;

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            emptyEl.classList.remove('hidden');
            return;
        }

        emptyEl.classList.add('hidden');
        tbody.innerHTML = filtered.map(r => {
            let statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Menunggu Review</span>`;
            if (r.status === 'resolved') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Selesai Ditindak</span>`;
            } else if (r.status === 'dismissed') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Diabaikan</span>`;
            }

            return `
                <tr class="border-b border-border">
                    <td class="py-3 px-4 font-mono font-bold text-slate-900">${r.id}</td>
                    <td class="py-3 px-4">
                        <div class="font-semibold text-slate-900">${escapeHtml(r.reporter_name)}</div>
                        <div class="text-[11px] text-muted-foreground">${r.reporter_nim}</div>
                    </td>
                    <td class="py-3 px-4 max-w-[200px]">
                        <div class="font-medium text-slate-900 truncate" title="${escapeHtml(r.target_title)}">${escapeHtml(r.target_title)}</div>
                        <div class="text-[10px] text-slate-500 uppercase">${r.target_type}</div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            ${escapeHtml(r.reason_label)}
                        </span>
                    </td>
                    <td class="py-3 px-4 max-w-[240px]">
                        <p class="text-[11px] text-slate-600 line-clamp-2" title="${escapeHtml(r.description)}">${escapeHtml(r.description)}</p>
                    </td>
                    <td class="py-3 px-4 text-[11px] text-slate-500 whitespace-nowrap">${r.date}</td>
                    <td class="py-3 px-4 whitespace-nowrap">${statusBadge}</td>
                    <td class="py-3 px-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button" onclick="openAbuseDetailModal('${r.id}')" class="p-1.5 rounded-md hover:bg-slate-100 text-slate-600 hover:text-slate-900" title="Lihat Rincian Aduan">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="openModerationActionModal('hide_report', 'report', '${r.target_id}', '${escapeHtml(r.target_title)}', 'Sembunyikan laporan dari pencarian publik')" class="p-1.5 rounded-md hover:bg-rose-50 text-rose-600" title="Sembunyikan Konten">
                                <i data-lucide="eye-off" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="openModerationActionModal('suspend_user', 'user', '${r.reporter_nim}', '${escapeHtml(r.target_title)}', 'Tangguhkan akun pelanggar')" class="p-1.5 rounded-md hover:bg-amber-50 text-amber-600" title="Tangguhkan Akun Pelanggar">
                                <i data-lucide="user-x" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="dismissAbuseReportDirect('${r.id}')" class="p-1.5 rounded-md hover:bg-emerald-50 text-emerald-600" title="Tandai Selesai / Abaikan">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function renderReportsManagementTable() {
        const query = (document.getElementById('report-search-input')?.value || '').toLowerCase();
        const typeFilter = document.getElementById('report-type-filter')?.value || 'all';
        const statusFilter = document.getElementById('report-status-filter')?.value || 'all';

        const reports = getAllReportsCatalog();
        const tbody = document.getElementById('reports-management-table-body');
        const emptyEl = document.getElementById('reports-table-empty');

        const filtered = reports.filter(r => {
            const matchQuery = !query || r.title.toLowerCase().includes(query) || r.reporter.toLowerCase().includes(query) || r.location.toLowerCase().includes(query);
            const matchType = typeFilter === 'all' || r.type === typeFilter;
            const matchStatus = statusFilter === 'all' || r.status === statusFilter;
            return matchQuery && matchType && matchStatus;
        });

        document.getElementById('reports-total-count').textContent = filtered.length;

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            emptyEl.classList.remove('hidden');
            return;
        }

        emptyEl.classList.add('hidden');
        tbody.innerHTML = filtered.map(item => {
            const isFound = item.type === 'found';
            const typeBadge = isFound ? 
                `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Temuan</span>` :
                `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Hilang</span>`;

            let statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800">Open</span>`;
            if (item.status === 'in_handover') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">In Handover</span>`;
            if (item.status === 'resolved') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Resolved</span>`;
            if (item.status === 'hidden') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Hidden</span>`;

            return `
                <tr class="border-b border-border">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <img src="${item.image}" alt="${escapeHtml(item.title)}" class="w-10 h-10 rounded-lg object-cover bg-slate-100 shrink-0 border border-border">
                            <div class="min-w-0">
                                <div class="font-bold text-slate-900 truncate max-w-[220px]" title="${escapeHtml(item.title)}">${escapeHtml(item.title)}</div>
                                <div class="text-[10px] font-mono text-muted-foreground">ID: ${item.id}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 whitespace-nowrap">${typeBadge}</td>
                    <td class="py-3 px-4 text-slate-800 font-medium">${escapeHtml(item.reporter)}</td>
                    <td class="py-3 px-4 max-w-[200px] text-slate-600 truncate" title="${escapeHtml(item.location)}">${escapeHtml(item.location)}</td>
                    <td class="py-3 px-4 text-slate-500 whitespace-nowrap">${item.date}</td>
                    <td class="py-3 px-4 whitespace-nowrap">${statusBadge}</td>
                    <td class="py-3 px-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ url('/report') }}/${item.id}" target="_blank" class="p-1.5 rounded-md hover:bg-slate-100 text-slate-600 hover:text-slate-900" title="Buka di Portal Civitas">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                            ${item.status === 'hidden' ? `
                                <button type="button" onclick="toggleReportVisibility('${item.id}', 'unhide')" class="p-1.5 rounded-md hover:bg-emerald-50 text-emerald-600" title="Tampilkan Kembali (Unhide)">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            ` : `
                                <button type="button" onclick="openModerationActionModal('hide_report', 'report', '${item.id}', '${escapeHtml(item.title)}', 'Laporan melanggar aturan publikasi')" class="p-1.5 rounded-md hover:bg-rose-50 text-rose-600" title="Sembunyikan Laporan (Hide)">
                                    <i data-lucide="eye-off" class="w-4 h-4"></i>
                                </button>
                            `}
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function renderUsersTable() {
        const query = (document.getElementById('user-search-input')?.value || '').toLowerCase();
        const roleFilter = document.getElementById('user-role-filter')?.value || 'all';
        const statusFilter = document.getElementById('user-status-filter')?.value || 'all';

        const users = getAdminUsers();
        const tbody = document.getElementById('users-table-body');
        const emptyEl = document.getElementById('users-table-empty');

        const filtered = users.filter(u => {
            const matchQuery = !query || u.name.toLowerCase().includes(query) || u.nim.toLowerCase().includes(query) || u.email.toLowerCase().includes(query) || u.faculty.toLowerCase().includes(query);
            const matchRole = roleFilter === 'all' || u.role === roleFilter;
            const matchStatus = statusFilter === 'all' || u.status === statusFilter;
            return matchQuery && matchRole && matchStatus;
        });

        document.getElementById('users-total-count').textContent = filtered.length;

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            emptyEl.classList.remove('hidden');
            return;
        }

        emptyEl.classList.add('hidden');
        tbody.innerHTML = filtered.map(u => {
            const isAdmin = u.role === 'admin';
            const roleBadge = isAdmin ? 
                `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-900 text-white">Administrator</span>` :
                `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">Civitas Mahasiswa</span>`;

            const isSuspended = u.status === 'suspended';
            const statusBadge = isSuspended ?
                `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Ditangguhkan</span>` :
                `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif Normal</span>`;

            return `
                <tr class="border-b border-border">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full ${isAdmin ? 'bg-slate-900 text-white' : 'bg-[#e0f2fe] text-[#0284c7]'} flex items-center justify-center font-bold text-xs shrink-0">
                                ${u.name.substring(0, 2).toUpperCase()}
                            </div>
                            <div class="font-bold text-slate-900">${escapeHtml(u.name)}</div>
                        </div>
                    </td>
                    <td class="py-3 px-4 font-mono text-slate-700">${u.nim}</td>
                    <td class="py-3 px-4">
                        <div class="text-slate-900 font-medium">${escapeHtml(u.faculty)}</div>
                        <div class="text-[11px] text-muted-foreground">${escapeHtml(u.prodi)}</div>
                    </td>
                    <td class="py-3 px-4 font-mono text-slate-700">
                        ${escapeHtml(u.email)}
                        ${u.email.includes('@gmail.com') ? '<span class="ml-1 text-[10px] bg-amber-50 text-amber-700 px-1 py-0.2 rounded border border-amber-200">Personal</span>' : ''}
                    </td>
                    <td class="py-3 px-4 whitespace-nowrap">${roleBadge}</td>
                    <td class="py-3 px-4 whitespace-nowrap">${statusBadge}</td>
                    <td class="py-3 px-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            ${isSuspended ? `
                                <button type="button" onclick="reactivateUserDirect('${u.id}', '${escapeHtml(u.name)}')" class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold text-xs border border-emerald-200 transition-colors" title="Pulihkan Akun">
                                    Pulihkan
                                </button>
                            ` : `
                                <button type="button" onclick="openModerationActionModal('suspend_user', 'user', '${u.id}', '${escapeHtml(u.name)} (${u.nim})', 'Pelanggaran ketentuan etika civitas kampus')" class="px-2.5 py-1 rounded-md bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold text-xs border border-rose-200 transition-colors" title="Tangguhkan Akun">
                                    Tangguhkan
                                </button>
                            `}
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function renderAuditTable() {
        const query = (document.getElementById('audit-search-input')?.value || '').toLowerCase();
        const actionFilter = document.getElementById('audit-action-filter')?.value || 'all';

        const logs = getAdminAuditLogs();
        const tbody = document.getElementById('audit-table-body');
        const emptyEl = document.getElementById('audit-table-empty');

        const filtered = logs.filter(l => {
            const matchQuery = !query || l.admin_name.toLowerCase().includes(query) || l.target.toLowerCase().includes(query) || l.reason.toLowerCase().includes(query);
            const matchAction = actionFilter === 'all' || l.action_type === actionFilter;
            return matchQuery && matchAction;
        });

        document.getElementById('audit-total-count').textContent = filtered.length;

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            emptyEl.classList.remove('hidden');
            return;
        }

        emptyEl.classList.add('hidden');
        tbody.innerHTML = filtered.map(log => `
            <tr class="border-b border-border">
                <td class="py-3 px-4 font-mono text-[11px] text-slate-500 whitespace-nowrap">${log.timestamp}</td>
                <td class="py-3 px-4">
                    <div class="font-bold text-slate-900">${escapeHtml(log.admin_name)}</div>
                    <div class="text-[10px] text-muted-foreground font-mono">${escapeHtml(log.admin_email)}</div>
                </td>
                <td class="py-3 px-4">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-900 text-white">
                        ${escapeHtml(log.action_label)}
                    </span>
                </td>
                <td class="py-3 px-4 font-medium text-slate-800">${escapeHtml(log.target)}</td>
                <td class="py-3 px-4 max-w-[260px]">
                    <div class="text-slate-700 text-[11px] leading-relaxed">${escapeHtml(log.reason)}</div>
                </td>
                <td class="py-3 px-4 text-right text-[11px] text-slate-500 max-w-[200px]">
                    ${escapeHtml(log.impact)}
                </td>
            </tr>
        `).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function renderCategoriesGrid() {
        const container = document.getElementById('categories-grid-container');
        if (!container) return;

        container.innerHTML = DEFAULT_CATEGORIES.map(cat => `
            <div class="p-4 rounded-xl border border-border bg-slate-50/60 hover:bg-white hover:border-slate-300 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-lg bg-[#e0f2fe] text-[#0284c7] flex items-center justify-center mb-2.5">
                        <i data-lucide="${cat.icon}" class="w-4 h-4"></i>
                    </div>
                    <div class="font-bold text-slate-900 text-sm">${escapeHtml(cat.name)}</div>
                    <p class="text-slate-500 text-[11px] mt-1 leading-relaxed">${escapeHtml(cat.desc)}</p>
                </div>
                <div class="pt-3 border-t border-border mt-3 flex items-center justify-between text-xs">
                    <span class="font-semibold text-slate-700">${cat.count} Laporan</span>
                    <span class="text-emerald-600 font-bold text-[10px] bg-emerald-50 px-1.5 py-0.5 rounded">Aktif</span>
                </div>
            </div>
        `).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    /* ===========================================================================
       ACTION HANDLERS & MODALS
       =========================================================================== */

    function openModerationActionModal(actionType, targetType, targetId, targetSummary, defaultReason) {
        document.getElementById('mod-action-type').value = actionType;
        document.getElementById('mod-target-type').value = targetType;
        document.getElementById('mod-target-id').value = targetId;
        document.getElementById('mod-target-summary').textContent = targetSummary;
        document.getElementById('mod-reason-input').value = defaultReason || '';

        const titleEl = document.getElementById('modal-mod-title');
        const submitBtn = document.getElementById('btn-mod-submit-confirm');
        const impactText = document.getElementById('mod-impact-text');

        if (actionType === 'hide_report') {
            titleEl.textContent = 'Sembunyikan Laporan dari Publik';
            submitBtn.textContent = 'Konfirmasi Sembunyikan';
            submitBtn.className = 'px-4 py-2 rounded-lg bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-colors shadow-xs';
            impactText.textContent = 'Laporan akan diubah ke status "hidden" dan tidak akan muncul di hasil pencarian civitas kampus.';
        } else if (actionType === 'suspend_user') {
            titleEl.textContent = 'Tangguhkan Akun Pengguna';
            submitBtn.textContent = 'Tangguhkan Akun';
            submitBtn.className = 'px-4 py-2 rounded-lg bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-colors shadow-xs';
            impactText.textContent = 'Sesuai DEC-026: Seluruh klaim aktif pengguna akan dibatalkan otomatis dan hak klaim baru di seluruh sistem diblokir.';
        } else if (actionType === 'warn_user') {
            titleEl.textContent = 'Kirim Peringatan Formal';
            submitBtn.textContent = 'Kirim Peringatan';
            submitBtn.className = 'px-4 py-2 rounded-lg bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs';
            impactText.textContent = 'Peringatan resmi akan dicatat dan dikirimkan ke pusat notifikasi pengguna terkait.';
        }

        document.getElementById('modal-moderation-action').classList.remove('hidden');
    }

    function closeModerationActionModal() {
        document.getElementById('modal-moderation-action').classList.add('hidden');
    }

    function executeModerationCommit(e) {
        e.preventDefault();
        const actionType = document.getElementById('mod-action-type').value;
        const targetType = document.getElementById('mod-target-type').value;
        const targetId = document.getElementById('mod-target-id').value;
        const targetSummary = document.getElementById('mod-target-summary').textContent;
        const reason = document.getElementById('mod-reason-input').value.trim();

        if (!reason) {
            alert('Alasan tindakan moderasi wajib diisi.');
            return;
        }

        const now = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) + ' WIB';
        const adminName = document.getElementById('admin-user-display-name')?.textContent || 'Biro Kemahasiswaan';
        const adminEmail = document.getElementById('admin-user-display-email')?.textContent || 'admin.lostfound@mercubuana.ac.id';

        // 1. Eksekusi Perubahan Data Sesuai DEC-013 & DEC-026
        if (actionType === 'hide_report') {
            // Update report status in local storage catalog
            let customReports = [];
            try {
                const raw = localStorage.getItem('findumb_custom_reports');
                if (raw) customReports = JSON.parse(raw);
            } catch(e) {}
            const reportIdx = customReports.findIndex(r => String(r.id) === String(targetId));
            if (reportIdx !== -1) {
                customReports[reportIdx].status = 'hidden';
                localStorage.setItem('findumb_custom_reports', JSON.stringify(customReports));
            }

            // Update abuse report if related
            let abuseReports = getAdminAbuseReports();
            abuseReports.forEach(a => {
                if (a.target_id === targetId) a.status = 'resolved';
            });
            saveAdminAbuseReports(abuseReports);
        } else if (actionType === 'suspend_user') {
            let users = getAdminUsers();
            users.forEach(u => {
                if (u.id === targetId || u.nim === targetId) {
                    u.status = 'suspended';
                }
            });
            saveAdminUsers(users);

            // Update abuse report if related
            let abuseReports = getAdminAbuseReports();
            abuseReports.forEach(a => {
                if (a.reporter_nim === targetId || a.target_id === targetId) a.status = 'resolved';
            });
            saveAdminAbuseReports(abuseReports);
        }

        // 2. Catat ke Audit Trail Log
        const newLog = {
            id: `AUD-${Date.now().toString().slice(-4)}`,
            timestamp: now,
            admin_name: adminName,
            admin_email: adminEmail,
            action_type: actionType,
            action_label: actionType === 'hide_report' ? 'Sembunyikan Laporan' : (actionType === 'suspend_user' ? 'Penangguhan Akun' : 'Peringatan Formal'),
            target: targetSummary,
            reason: reason,
            impact: actionType === 'suspend_user' ? 'Klaim dibatalkan, hak klaim diblokir (DEC-026).' : 'Konten disembunyikan dari publik.'
        };

        const logs = getAdminAuditLogs();
        logs.unshift(newLog);
        saveAdminAuditLogs(logs);

        closeModerationActionModal();
        showAdminToast(`✓ Tindakan moderasi berhasil dieksekusi dan dicatat di log audit.`);
        refreshDashboardMetrics();
        renderOverviewDashboard();
        renderAbuseReportsTable();
        renderReportsManagementTable();
        renderUsersTable();
        renderAuditTable();
    }

    function dismissAbuseReportDirect(abuseId) {
        if (!confirm('Abaikan aduan ini karena tidak ditemukan pelanggaran valid?')) return;
        let abuseReports = getAdminAbuseReports();
        const idx = abuseReports.findIndex(a => a.id === abuseId);
        if (idx !== -1) {
            abuseReports[idx].status = 'dismissed';
            saveAdminAbuseReports(abuseReports);

            // Add Audit log
            const logs = getAdminAuditLogs();
            logs.unshift({
                id: `AUD-${Date.now().toString().slice(-4)}`,
                timestamp: new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) + ' WIB',
                admin_name: document.getElementById('admin-user-display-name')?.textContent || 'Biro Kemahasiswaan',
                admin_email: document.getElementById('admin-user-display-email')?.textContent || 'admin.lostfound@mercubuana.ac.id',
                action_type: 'dismiss_abuse',
                action_label: 'Abaikan Aduan',
                target: `Aduan ${abuseId}`,
                reason: 'Tidak ditemukan bukti pelanggaran yang cukup.',
                impact: 'Status aduan diubah menjadi diabaikan.'
            });
            saveAdminAuditLogs(logs);

            showAdminToast('Aduan telah diabaikan.');
            refreshDashboardMetrics();
            renderOverviewDashboard();
            renderAbuseReportsTable();
        }
    }

    function reactivateUserDirect(userId, userName) {
        if (!confirm(`Aktifkan kembali akun civitas ${userName}?`)) return;
        let users = getAdminUsers();
        const user = users.find(u => String(u.id) === String(userId));
        if (user) {
            user.status = 'active';
            saveAdminUsers(users);

            // Audit log
            const logs = getAdminAuditLogs();
            logs.unshift({
                id: `AUD-${Date.now().toString().slice(-4)}`,
                timestamp: new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) + ' WIB',
                admin_name: document.getElementById('admin-user-display-name')?.textContent || 'Biro Kemahasiswaan',
                admin_email: document.getElementById('admin-user-display-email')?.textContent || 'admin.lostfound@mercubuana.ac.id',
                action_type: 'reactivate_user',
                action_label: 'Reaktivasi Akun Pengguna',
                target: `${userName} (${user.nim})`,
                reason: 'Masa penangguhan selesai dan civitas telah mengonfirmasi pemahaman aturan.',
                impact: 'Akun kembali aktif. Sesuai DEC-026 transaksi masa lalu yang telah terminal tetap tersimpan.'
            });
            saveAdminAuditLogs(logs);

            showAdminToast(`✓ Akun ${userName} berhasil diaktifkan kembali.`);
            refreshDashboardMetrics();
            renderUsersTable();
        }
    }

    function toggleReportVisibility(reportId, action) {
        let customReports = [];
        try {
            const raw = localStorage.getItem('findumb_custom_reports');
            if (raw) customReports = JSON.parse(raw);
        } catch(e) {}

        const reportIdx = customReports.findIndex(r => String(r.id) === String(reportId));
        if (reportIdx !== -1) {
            customReports[reportIdx].status = action === 'unhide' ? 'open' : 'hidden';
            localStorage.setItem('findumb_custom_reports', JSON.stringify(customReports));
        }

        // Add audit log
        const logs = getAdminAuditLogs();
        logs.unshift({
            id: `AUD-${Date.now().toString().slice(-4)}`,
            timestamp: new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) + ' WIB',
            admin_name: document.getElementById('admin-user-display-name')?.textContent || 'Biro Kemahasiswaan',
            admin_email: document.getElementById('admin-user-display-email')?.textContent || 'admin.lostfound@mercubuana.ac.id',
            action_type: action === 'unhide' ? 'unhide_report' : 'hide_report',
            action_label: action === 'unhide' ? 'Buka Kembali Laporan' : 'Sembunyikan Laporan',
            target: `Laporan #${reportId}`,
            reason: action === 'unhide' ? 'Verifikasi klarifikasi laporan telah valid' : 'Disembunyikan oleh admin',
            impact: action === 'unhide' ? 'Laporan kembali tampil di pencarian publik.' : 'Laporan disembunyikan dari publik.'
        });
        saveAdminAuditLogs(logs);

        showAdminToast(action === 'unhide' ? 'Laporan berhasil dipublikasikan kembali.' : 'Laporan disembunyikan.');
        renderReportsManagementTable();
    }

    function openAbuseDetailModal(abuseId) {
        const abuseReports = getAdminAbuseReports();
        const item = abuseReports.find(a => a.id === abuseId);
        if (!item) return;

        document.getElementById('detail-abuse-id').textContent = item.id;
        document.getElementById('detail-abuse-date').textContent = item.date;
        document.getElementById('detail-abuse-reporter').textContent = `${item.reporter_name} (${item.reporter_nim})`;
        document.getElementById('detail-abuse-status').textContent = item.status === 'pending' ? 'Menunggu Review' : (item.status === 'resolved' ? 'Selesai' : 'Diabaikan');
        document.getElementById('detail-abuse-target-box').innerHTML = `<strong>${escapeHtml(item.target_title)}</strong> <span class="text-slate-400">(${item.target_type} ID: ${item.target_id})</span>`;
        document.getElementById('detail-abuse-reason-badge').textContent = item.reason_label;
        document.getElementById('detail-abuse-description').textContent = item.description;

        document.getElementById('btn-detail-action-dismiss').onclick = () => {
            closeAbuseDetailModal();
            dismissAbuseReportDirect(item.id);
        };

        document.getElementById('btn-detail-action-hide').onclick = () => {
            closeAbuseDetailModal();
            openModerationActionModal('hide_report', 'report', item.target_id, item.target_title, 'Laporan melanggar aturan publikasi kampus');
        };

        document.getElementById('modal-abuse-detail').classList.remove('hidden');
    }

    function closeAbuseDetailModal() {
        document.getElementById('modal-abuse-detail').classList.add('hidden');
    }

    function openCreateAdminModal() {
        document.getElementById('modal-create-admin').classList.remove('hidden');
    }

    function closeCreateAdminModal() {
        document.getElementById('modal-create-admin').classList.add('hidden');
    }

    function handleCreateAdminSubmit(e) {
        e.preventDefault();
        const name = document.getElementById('admin-new-name').value.trim();
        const email = document.getElementById('admin-new-email').value.trim();
        const unit = document.getElementById('admin-new-unit').value.trim() || 'Staf Operasional Admin';
        const role = document.getElementById('admin-new-role').value;

        if (!name || !email) return;

        const users = getAdminUsers();
        users.push({
            id: `adm_${Date.now()}`,
            name: name,
            nim: `ADMIN-${Math.floor(1000 + Math.random() * 9000)}`,
            faculty: unit,
            prodi: role === 'superadmin' ? 'Super Administrator' : 'Administrator Moderasi',
            email: email,
            role: 'admin',
            status: 'active'
        });
        saveAdminUsers(users);

        closeCreateAdminModal();
        showAdminToast(`✓ Akun admin ${name} (${email}) berhasil didaftarkan!`);
        renderUsersTable();
    }

    function openAdminSettingsModal() {
        document.getElementById('modal-admin-settings').classList.remove('hidden');
    }

    function closeAdminSettingsModal() {
        document.getElementById('modal-admin-settings').classList.add('hidden');
    }

    function saveAdminSettings(e) {
        e.preventDefault();
        const name = document.getElementById('settings-admin-name').value.trim();
        const email = document.getElementById('settings-admin-email').value.trim();
        if (!name || !email) return;

        document.getElementById('admin-user-display-name').textContent = name;
        document.getElementById('admin-user-display-email').textContent = email;
        document.getElementById('dropdown-admin-name').textContent = name;
        document.getElementById('dropdown-admin-email').textContent = email;

        closeAdminSettingsModal();
        showAdminToast('✓ Profil administrator berhasil diperbarui!');
    }

    function openAdminCreateReportModal() {
        window.location.href = "{{ route('report.found') }}";
    }

    function exportAdminReportsCSV() {
        const reports = getAllReportsCatalog();
        let csv = "ID,Judul,Tipe,Pelapor,Lokasi,Tanggal,Status\n";
        reports.forEach(r => {
            csv += `"${r.id}","${r.title}","${r.type}","${r.reporter}","${r.location}","${r.date}","${r.status}"\n`;
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `findumb-rekap-laporan-${new Date().toISOString().slice(0, 10)}.csv`;
        a.click();
        URL.revokeObjectURL(url);
        showAdminToast('✓ Berkas rekap laporan (CSV) berhasil diunduh!');
    }

    function handleAdminGlobalSearch(val) {
        if (!val) return;
        const lower = val.toLowerCase();
        // Auto-switch to relevant tab if matches pattern
        if (lower.startsWith('abu') || lower.includes('palsu') || lower.includes('spam')) {
            switchAdminTab('moderation');
            const input = document.getElementById('abuse-search-input');
            if (input) { input.value = val; renderAbuseReportsTable(); }
        } else if (lower.startsWith('415') || lower.includes('@') || lower.includes('admin')) {
            switchAdminTab('users');
            const input = document.getElementById('user-search-input');
            if (input) { input.value = val; renderUsersTable(); }
        } else {
            switchAdminTab('reports');
            const input = document.getElementById('report-search-input');
            if (input) { input.value = val; renderReportsManagementTable(); }
        }
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

    // On Load Initialization
    document.addEventListener('DOMContentLoaded', () => {
        // Read hash if exists (e.g. #moderation, #users)
        const hash = window.location.hash.replace('#', '');
        if (hash && ['overview', 'moderation', 'reports', 'users', 'audit', 'categories'].includes(hash)) {
            switchAdminTab(hash);
        } else {
            switchAdminTab('overview');
        }
    });
</script>
@endpush
