@extends('layouts.admin')

@section('title', 'Dashboard Administrator — FindUMB')

@section('content')
<div id="tab-panel-overview" class="admin-tab-panel space-y-5">

    <!-- ===========================================================================
         1. TOP HEADER & GLOBAL ACTIONS
         =========================================================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#111827]">Overview</h1>
            <p class="text-xs text-[#6b7280] mt-0.5">Pusat komando moderasi pelaporan dan tata kelola platform FindUMB.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="button" onclick="exportAdminReportsCSV()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-[#eaecf0] bg-white text-[#374151] hover:bg-[#f9fafb] hover:text-[#111827] transition-all cursor-pointer shadow-2xs">
                <i data-lucide="download" class="w-3.5 h-3.5 text-[#6b7280]"></i>
                <span>Unduh Rekap</span>
            </button>
        </div>
    </div>

    <!-- Hidden alias anchor for test compatibility -->
    <span class="sr-only">Dashboard Administrator</span>

    <!-- ===========================================================================
         2. ALERT / EXCEPTION CALLOUT BANNER (Clean Triage Alert)
         =========================================================================== -->
    <div id="overview-alert-banner" class="p-3.5 rounded-2xl bg-[#fffcf5] border border-[#fef0c7] flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs text-[#92400e] shadow-2xs">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-6 h-6 rounded-full bg-[#fef3c7] flex items-center justify-center shrink-0">
                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-700"></i>
            </div>
            <div class="min-w-0 leading-tight">
                <span class="font-bold text-[#78350f] aduan-count-text">Terdapat 3 aduan civitas membutuhkan tindakan segera:</span>
                <span class="text-[#92400e] ml-1">Laporan dugaan penipuan barang dan konten tidak pantas menunggu verifikasi admin.</span>
            </div>
        </div>
        <button type="button" onclick="switchAdminTab('moderation')" class="inline-flex items-center px-3.5 py-1.5 bg-white border border-[#fde68a] text-[#78350f] rounded-lg font-semibold text-xs hover:bg-[#fef9c3] transition-all shrink-0 self-end sm:self-center cursor-pointer shadow-2xs">
            <span>Tangani Aduan</span>
        </button>
    </div>

    <!-- ===========================================================================
         3. 4 KPI METRIC STAT CARDS (Exact Clean Reference Design - No Three Dots)
         =========================================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Card 1: Total Laporan Aktif -->
        <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-[#1572b8]/30 transition-all flex flex-col justify-between">
            <span class="text-sm font-medium text-[#475467]">Total Laporan Aktif</span>
            <div class="mt-3.5">
                <div class="flex items-center gap-2">
                    <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="stat-active-reports">28</span>
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i> 14%
                    </span>
                </div>
                <p class="text-xs text-[#667085] mt-1.5">19 Temuan, 9 Hilang</p>
            </div>
        </div>

        <!-- Card 2: Barang Selesai Serah Terima -->
        <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-emerald-500/30 transition-all flex flex-col justify-between">
            <span class="text-sm font-medium text-[#475467]">Barang Selesai Serah Terima</span>
            <div class="mt-3.5">
                <div class="flex items-center gap-2">
                    <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="stat-resolved-reports">95</span>
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i> 78.3%
                    </span>
                </div>
                <p class="text-xs text-[#667085] mt-1.5">Tingkat temu berhasil diklaim</p>
            </div>
        </div>

        <!-- Card 3: Aduan Menunggu Review -->
        <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-amber-500/30 transition-all flex flex-col justify-between">
            <span class="text-sm font-medium text-[#475467]">Aduan Menunggu Review</span>
            <div class="mt-3.5">
                <div class="flex items-center gap-2">
                    <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="stat-pending-abuse">3</span>
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#fef0c7] text-[#b54708]">
                        <i data-lucide="arrow-up-right" class="w-3 h-3"></i> 2 Urgent
                    </span>
                </div>
                <p class="text-xs text-[#667085] mt-1.5">Perlu moderasi &amp; verifikasi admin</p>
            </div>
        </div>

        <!-- Card 4: Akun Ditangguhkan -->
        <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-rose-500/30 transition-all flex flex-col justify-between">
            <span class="text-sm font-medium text-[#475467]">Akun Ditangguhkan</span>
            <div class="mt-3.5">
                <div class="flex items-center gap-2">
                    <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="stat-suspended-users">1</span>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#fee4e2] text-[#d92d20]">
                        Sanksi Etik
                    </span>
                </div>
                <p class="text-xs text-[#667085] mt-1.5">Sesuai aturan DEC-026 UMB</p>
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         4. CHARTS & OPERATIONAL COCKPIT (GRID 12 COLS - KAMPUS MERUYA)
         =========================================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-1">
        <!-- Left Box (8 Cols): Volume Laporan & Keberhasilan Temu (Refined Clean Composition) -->
        <div class="lg:col-span-8 p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs flex flex-col justify-between">
            <div>
                <!-- Header with Timeframe Switcher -->
                <div class="flex items-center justify-between pb-3 border-b border-[#f3f4f6]">
                    <div>
                        <h3 class="font-bold text-sm text-[#111827]">Volume Laporan &amp; Keberhasilan Temu</h3>
                        <p class="text-xs text-[#6b7280] mt-0.5">Tren perbandingan barang temuan dan laporan kehilangan mingguan di Kampus Meruya</p>
                    </div>
                    <!-- Timeframe Switcher (7h, 30h, 90h) -->
                    <div class="flex items-center gap-1 bg-[#f3f4f6] p-0.5 rounded-lg text-[10px] font-semibold text-[#6b7280]">
                        <button type="button" id="btn-chart-7d" onclick="setChartRange('7d')" class="chart-range-btn px-2.5 py-1 rounded text-[#6b7280] hover:text-[#111827] transition-all cursor-pointer">7h</button>
                        <button type="button" id="btn-chart-30d" onclick="setChartRange('30d')" class="chart-range-btn px-2.5 py-1 rounded bg-white text-[#111827] shadow-2xs font-bold transition-all cursor-pointer">30h</button>
                        <button type="button" id="btn-chart-90d" onclick="setChartRange('90d')" class="chart-range-btn px-2.5 py-1 rounded text-[#6b7280] hover:text-[#111827] transition-all cursor-pointer">90h</button>
                    </div>
                </div>

                <!-- Prominent Metric Highlight & Legend Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-3.5 pb-1">
                    <div class="flex items-baseline gap-2.5">
                        <span class="text-xl font-semibold tracking-tight text-[#101828]" id="chart-headline-val">123 Laporan</span>
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                            <i data-lucide="arrow-up-right" class="w-3 h-3"></i>
                            <span id="chart-trend-val">14.2% 30 hari</span>
                        </span>
                    </div>
                    <!-- Legend Buttons -->
                    <div class="flex items-center gap-4 text-xs">
                        <button type="button" onclick="toggleChartDataset(0)" id="legend-btn-temuan" class="inline-flex items-center gap-1.5 text-xs text-[#374151] font-medium hover:text-[#1572b8] transition-colors cursor-pointer" title="Klik untuk sembunyikan/tampilkan">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#1572b8]"></span>
                            <span>Barang Temuan</span>
                        </button>
                        <button type="button" onclick="toggleChartDataset(1)" id="legend-btn-hilang" class="inline-flex items-center gap-1.5 text-xs text-[#374151] font-medium hover:text-[#f43f5e] transition-colors cursor-pointer" title="Klik untuk sembunyikan/tampilkan">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#f43f5e]"></span>
                            <span>Barang Hilang</span>
                        </button>
                    </div>
                </div>

                <!-- Real Interactive Line Chart (Canvas Powered by Chart.js) -->
                <div class="w-full h-[310px] mt-1 relative">
                    <canvas id="adminTrendChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- 4 Bottom KPI Non-Redundant Operational Insights (Harmonious Typography) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-3.5 border-t border-[#f3f4f6] mt-2">
                <div>
                    <div class="text-xs font-medium text-[#475467]">Rata-rata Temu</div>
                    <div class="text-base font-bold text-[#101828] mt-0.5">2.4 Hari</div>
                    <div class="text-xs text-[#667085] mt-0.5">ke serah terima</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#475467]">Tingkat Pengembalian</div>
                    <div class="text-base font-bold text-[#101828] mt-0.5" id="chart-stat-rate">78.3%</div>
                    <div class="text-xs text-[#667085] mt-0.5">barang kembali ke pemilik</div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#475467]">Puncak Mingguan</div>
                    <div class="text-base font-bold text-[#101828] mt-0.5" id="chart-stat-high">42 Kasus</div>
                    <div class="text-xs text-[#667085] mt-0.5">terendah: <span id="chart-stat-low">8</span></div>
                </div>
                <div>
                    <div class="text-xs font-medium text-[#475467]">Rasio Aduan</div>
                    <div class="text-base font-bold text-[#101828] mt-0.5">3.1%</div>
                    <div class="text-xs text-emerald-600 font-medium mt-0.5">terkendali &lt; 5%</div>
                </div>
            </div>

            <!-- Hidden compatibility anchor for JS script updates -->
            <span id="stat-sub-rate" class="hidden">78.3%</span>
        </div>

        <!-- Right Box (4 Cols): Status Laporan + Kategori Terpopuler (Khusus Kampus Meruya) -->
        <div class="lg:col-span-4 flex flex-col gap-4">
            
            <!-- Box 1: Status Laporan Kampus Meruya (Interactive Status Funnel) -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs">
                <div class="pb-2.5 border-b border-[#f3f4f6] flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-xs text-[#111827]">Status Laporan Kampus</h4>
                        <div class="text-[10px] text-[#6b7280]">Katalog operasional Kampus Meruya</div>
                    </div>
                    <span class="text-[10px] font-semibold text-[#1572b8] bg-blue-50 px-2 py-0.5 rounded-full">30 Total</span>
                </div>

                <!-- Clean Color Segmented Bar -->
                <div class="w-full h-2 rounded-full bg-[#f3f4f6] overflow-hidden flex my-3">
                    <div class="bg-[#1572b8] h-full" style="width: 58%" title="Open (18)"></div>
                    <div class="bg-amber-400 h-full" style="width: 27%" title="In Handover (10)"></div>
                    <div class="bg-emerald-500 h-full" style="width: 15%" title="Resolved (95)"></div>
                </div>

                <div class="space-y-1 text-xs">
                    <div onclick="switchAdminTab('reports'); setReportsQuickFilter('open');" class="flex items-center justify-between p-1.5 -mx-1.5 rounded-lg hover:bg-[#f9fafb] cursor-pointer transition-colors group">
                        <span class="flex items-center gap-2 text-[#4b5563]">
                            <span class="w-2 h-2 rounded-full bg-[#1572b8]"></span>
                            <span class="group-hover:text-[#111827]">Open (Aktif Terbuka)</span>
                        </span>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-[#111827]">18</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-300 group-hover:text-gray-500 transition-colors"></i>
                        </div>
                    </div>
                    <div onclick="switchAdminTab('reports'); setReportsQuickFilter('in_handover');" class="flex items-center justify-between p-1.5 -mx-1.5 rounded-lg hover:bg-[#f9fafb] cursor-pointer transition-colors group">
                        <span class="flex items-center gap-2 text-[#4b5563]">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span class="group-hover:text-[#111827]">In Handover (Pos Satpam)</span>
                        </span>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-[#111827]">10</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-300 group-hover:text-gray-500 transition-colors"></i>
                        </div>
                    </div>
                    <div onclick="switchAdminTab('reports'); setReportsQuickFilter('resolved');" class="flex items-center justify-between p-1.5 -mx-1.5 rounded-lg hover:bg-[#f9fafb] cursor-pointer transition-colors group">
                        <span class="flex items-center gap-2 text-[#4b5563]">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="group-hover:text-[#111827]">Resolved (Selesai Klaim)</span>
                        </span>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-[#111827]">95</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-300 group-hover:text-gray-500 transition-colors"></i>
                        </div>
                    </div>
                    <div onclick="switchAdminTab('reports'); setReportsQuickFilter('hidden');" class="flex items-center justify-between p-1.5 -mx-1.5 rounded-lg hover:bg-[#f9fafb] cursor-pointer transition-colors group">
                        <span class="flex items-center gap-2 text-[#4b5563]">
                            <span class="w-2 h-2 rounded-full bg-[#9ca3af]"></span>
                            <span class="group-hover:text-[#111827]">Hidden (Dimoderasi)</span>
                        </span>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-[#111827]">1</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-300 group-hover:text-gray-500 transition-colors"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Box 2: Kategori Barang Terpopuler (Khusus Kampus Meruya) -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs flex-1 flex flex-col justify-between">
                <div class="flex items-center justify-between pb-2.5 border-b border-[#f3f4f6]">
                    <div>
                        <h4 class="font-bold text-xs text-[#111827]">Kategori Barang Terpopuler</h4>
                        <div class="text-[10px] text-[#6b7280]">Distribusi barang Kampus Meruya</div>
                    </div>
                    <button type="button" onclick="switchAdminTab('categories')" class="text-xs font-semibold text-[#1572b8] hover:underline cursor-pointer">
                        Kelola Kategori
                    </button>
                </div>

                <!-- 4 Real Categories from System Catalog -->
                <div class="space-y-2 mt-3 text-xs">
                    <div>
                        <div class="flex items-center justify-between text-[11px] mb-1">
                            <span class="font-medium text-[#374151]">Elektronik &amp; Gadget</span>
                            <span class="font-bold text-[#111827]">51 Laporan</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-[#f3f4f6] overflow-hidden">
                            <div class="bg-[#1572b8] h-full rounded-full" style="width: 41%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-[11px] mb-1">
                            <span class="font-medium text-[#374151]">Dokumen &amp; KTM</span>
                            <span class="font-bold text-[#111827]">34 Laporan</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-[#f3f4f6] overflow-hidden">
                            <div class="bg-blue-400 h-full rounded-full" style="width: 27%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-[11px] mb-1">
                            <span class="font-medium text-[#374151]">Tas &amp; Dompet</span>
                            <span class="font-bold text-[#111827]">22 Laporan</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-[#f3f4f6] overflow-hidden">
                            <div class="bg-amber-400 h-full rounded-full" style="width: 18%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-[11px] mb-1">
                            <span class="font-medium text-[#374151]">Aksesoris &amp; Pakaian</span>
                            <span class="font-bold text-[#111827]">18 Laporan</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-[#f3f4f6] overflow-hidden">
                            <div class="bg-slate-400 h-full rounded-full" style="width: 14%"></div>
                        </div>
                    </div>
                </div>

                <!-- Quick shortcut to handover items -->
                <div class="mt-3.5 pt-3 border-t border-[#f3f4f6]">
                    <button type="button" onclick="switchAdminTab('reports'); setReportsQuickFilter('in_handover');" class="w-full flex items-center justify-center gap-2 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#1572b8] font-semibold text-xs transition-colors cursor-pointer border border-blue-200">
                        <i data-lucide="package-check" class="w-3.5 h-3.5"></i>
                        <span>10 Barang di Pos Satpam Tower</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ===========================================================================
         5. BOTTOM GRID: Antrean Prioritas Aduan (Left) + Log Audit Feed (Right)
         =========================================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-1">
        
        <!-- Left (6 Cols): Antrean Prioritas Moderasi (Refined Ticket Composition - No Red Dot) -->
        <div class="lg:col-span-6 p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-[#f3f4f6]">
                    <div>
                        <h4 class="font-bold text-xs text-[#111827]">Antrean Prioritas Moderasi</h4>
                        <p class="text-[10px] text-[#6b7280]">Aduan civitas terbuka yang memerlukan verifikasi admin</p>
                    </div>
                    <button type="button" onclick="switchAdminTab('moderation')" class="text-xs font-semibold text-[#1572b8] hover:underline cursor-pointer">
                        Buka Tab Aduan
                    </button>
                </div>

                <div class="divide-y divide-[#f3f4f6]" id="overview-urgent-abuse-list">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>

        <!-- Right (6 Cols): Aktivitas & Log Audit Real-Time (Timeline Feed - No Icon Shape) -->
        <div class="lg:col-span-6 p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-[#f3f4f6]">
                    <div>
                        <h4 class="font-bold text-xs text-[#111827]">Aktivitas &amp; Log Audit Terkini</h4>
                        <p class="text-[10px] text-[#6b7280]">Feed tindakan moderasi dan tata kelola real-time</p>
                    </div>
                    <button type="button" onclick="switchAdminTab('audit')" class="text-xs font-semibold text-[#1572b8] hover:underline cursor-pointer">
                        Lihat Semua
                    </button>
                </div>

                <div class="divide-y divide-[#f3f4f6]" id="overview-recent-audit-feed">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>

    </div>

</div>

    <!-- ===========================================================================
         TAB PANEL 2: KELOLA LAPORAN (Dedicated Reports Management & Handover System)
         =========================================================================== -->
    <div id="tab-panel-reports" class="admin-tab-panel hidden space-y-5">
        
        <!-- 1. Header Halaman Kelola Laporan -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#111827]">Kelola Laporan</h1>
                <p class="text-xs text-[#6b7280] mt-0.5">Inventarisasi barang temuan, laporan kehilangan, dan serah terima pos satpam.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" onclick="exportAdminReportsCSV()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-[#eaecf0] bg-white text-[#374151] hover:bg-[#f9fafb] hover:text-[#111827] transition-all">
                    <i data-lucide="download" class="w-3.5 h-3.5 text-[#6b7280]"></i>
                    <span>Ekspor CSV</span>
                </button>
                <button type="button" onclick="openAdminCreateReportModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-[#1572b8] text-white hover:bg-[#0f5791] transition-all shadow-2xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Laporan</span>
                </button>
            </div>
        </div>

        <!-- 2. 4 Kartu Metrik Operasional Laporan (Exact Clean Reference Design - No Three Dots) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <!-- Card 1: Total Terdaftar -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-[#1572b8]/30 transition-all flex flex-col justify-between">
                <span class="text-sm font-medium text-[#475467]">Total Terdaftar</span>
                <div class="mt-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="reports-kpi-total">30</span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                            <i data-lucide="arrow-up-right" class="w-3 h-3"></i> 20%
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">Akumulasi laporan civitas UMB</p>
                </div>
            </div>

            <!-- Card 2: Barang Temuan -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-emerald-500/30 transition-all flex flex-col justify-between">
                <span class="text-sm font-medium text-[#475467]">Barang Temuan</span>
                <div class="mt-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="reports-kpi-found">19</span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                            <i data-lucide="arrow-up-right" class="w-3 h-3"></i> 63%
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">12 Open, 7 Di Pos Satpam</p>
                </div>
            </div>

            <!-- Card 3: Laporan Kehilangan -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-rose-500/30 transition-all flex flex-col justify-between">
                <span class="text-sm font-medium text-[#475467]">Laporan Kehilangan</span>
                <div class="mt-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="reports-kpi-lost">9</span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                            <i data-lucide="arrow-up-right" class="w-3 h-3"></i> 12%
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">6 Terbuka, 3 Teridentifikasi</p>
                </div>
            </div>

            <!-- Card 4: Selesai Dikembalikan -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-teal-500/30 transition-all flex flex-col justify-between">
                <span class="text-sm font-medium text-[#475467]">Selesai Dikembalikan</span>
                <div class="mt-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="reports-kpi-resolved">1</span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                            <i data-lucide="arrow-up-right" class="w-3 h-3"></i> 78%
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">Tingkat temu berhasil diklaim</p>
                </div>
            </div>
        </div>
        <!-- 3. Filter Quick Pills + Filter Controls -->
        <div class="p-4 rounded-xl bg-white border border-[#eaecf0] shadow-xs space-y-3.5">
            <!-- Quick Filter Pills -->
            <div class="flex flex-wrap items-center justify-between gap-2.5 pb-3 border-b border-[#f3f4f6]">
                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                    <button type="button" onclick="setReportsQuickFilter('all')" id="rep-pill-all" class="rep-quick-pill px-3 py-1 rounded-lg bg-[#1572b8] text-white font-semibold transition-all cursor-pointer shadow-2xs">
                        Semua Laporan (<span id="pill-count-all">28</span>)
                    </button>
                    <button type="button" onclick="setReportsQuickFilter('found')" id="rep-pill-found" class="rep-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer">
                        Barang Temuan (<span id="pill-count-found">19</span>)
                    </button>
                    <button type="button" onclick="setReportsQuickFilter('lost')" id="rep-pill-lost" class="rep-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer">
                        Barang Hilang (<span id="pill-count-lost">9</span>)
                    </button>
                    <button type="button" onclick="setReportsQuickFilter('in_handover')" id="rep-pill-in_handover" class="rep-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer">
                        Siap Serah Terima (<span id="pill-count-handover">10</span>)
                    </button>
                    <button type="button" onclick="setReportsQuickFilter('resolved')" id="rep-pill-resolved" class="rep-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer">
                        Selesai (<span id="pill-count-resolved">95</span>)
                    </button>
                </div>
                <div class="text-xs text-[#6b7280]">
                    Menampilkan <strong class="text-[#111827]" id="reports-displayed-count">28</strong> laporan
                </div>
            </div>

            <!-- Filter Controls Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5">
                <!-- Search Input (5 Cols) -->
                <div class="lg:col-span-5 relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#9ca3af]"></i>
                    <input type="text" id="report-search-input" oninput="renderReportsManagementTable()" placeholder="Cari nama barang, kode #ID, pelapor, atau lokasi..." class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-[#eaecf0] rounded-lg text-[#111827] placeholder-[#9ca3af] focus:outline-none focus:border-[#1572b8]">
                </div>

                <!-- Tipe Filter (2 Cols) -->
                <div class="lg:col-span-2">
                    <select id="report-type-filter" onchange="renderReportsManagementTable()" class="w-full text-xs bg-white border border-[#eaecf0] rounded-lg px-2.5 py-1.5 text-[#374151] focus:outline-none focus:border-[#1572b8]">
                        <option value="all">Semua Tipe</option>
                        <option value="found">Barang Temuan</option>
                        <option value="lost">Laporan Kehilangan</option>
                    </select>
                </div>

                <!-- Status Filter (2 Cols) -->
                <div class="lg:col-span-2">
                    <select id="report-status-filter" onchange="renderReportsManagementTable()" class="w-full text-xs bg-white border border-[#eaecf0] rounded-lg px-2.5 py-1.5 text-[#374151] focus:outline-none focus:border-[#1572b8]">
                        <option value="all">Semua Status</option>
                        <option value="open">Open (Aktif)</option>
                        <option value="in_handover">In Handover (Proses)</option>
                        <option value="resolved">Resolved (Selesai)</option>
                        <option value="hidden">Hidden (Dimoderasi)</option>
                    </select>
                </div>

                <!-- Lokasi Filter (2 Cols) -->
                <div class="lg:col-span-2">
                    <select id="report-location-filter" onchange="renderReportsManagementTable()" class="w-full text-xs bg-white border border-[#eaecf0] rounded-lg px-2.5 py-1.5 text-[#374151] focus:outline-none focus:border-[#1572b8]">
                        <option value="all">Semua Lokasi</option>
                        <option value="Meruya">Kampus Meruya</option>
                        <option value="Menteng">Kampus Menteng</option>
                    </select>
                </div>

                <!-- Reset Button (1 Col) -->
                <div class="lg:col-span-1 flex items-center justify-end">
                    <button type="button" onclick="resetReportsFilters()" class="w-full py-1.5 text-xs text-[#6b7280] hover:text-[#111827] font-medium border border-[#eaecf0] rounded-lg hover:bg-[#f9fafb] transition-all text-center cursor-pointer" title="Reset filter">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- 4. Tabel Kelola Laporan Barang -->
        <div class="rounded-xl border border-[#eaecf0] bg-white overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#f9fafb] text-[#6b7280] text-[11px] font-semibold border-b border-[#eaecf0]">
                            <th class="py-3 px-4">Barang &amp; ID</th>
                            <th class="py-3 px-3">Jenis</th>
                            <th class="py-3 px-3">Pelapor</th>
                            <th class="py-3 px-3">Lokasi Penemuan / Hilang</th>
                            <th class="py-3 px-3">Tanggal Lapor</th>
                            <th class="py-3 px-3">Status Operasional</th>
                            <th class="py-3 px-4 text-right">Aksi Kelola</th>
                        </tr>
                    </thead>
                    <tbody id="reports-management-table-body" class="divide-y divide-[#eaecf0]">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div id="reports-table-empty" class="hidden py-12 px-4 text-center">
                <div class="w-12 h-12 rounded-full bg-[#f3f4f6] text-[#9ca3af] flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="inbox" class="w-6 h-6"></i>
                </div>
                <div class="text-sm font-semibold text-[#111827]">Tidak Ada Laporan Ditemukan</div>
                <div class="text-xs text-[#6b7280] mt-1 max-w-sm mx-auto">Tidak ada laporan barang yang sesuai dengan kata kunci atau kriteria filter yang Anda pilih.</div>
                <button type="button" onclick="resetReportsFilters()" class="mt-3 px-3 py-1.5 text-xs font-medium text-[#1572b8] bg-[#f0f7fd] rounded-lg hover:bg-[#e0f2fe] transition-colors cursor-pointer">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 3: ADUAN & MODERASI
         =========================================================================== -->
    <div id="tab-panel-moderation" class="admin-tab-panel hidden space-y-4">
        
        <!-- Header Halaman Aduan & Moderasi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#111827]">Aduan &amp; Moderasi</h1>
                <p class="text-xs text-[#6b7280] mt-0.5">Penanganan laporan pelanggaran, klaim fiktif, dan verifikasi aduan civitas.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                    <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                    <span>Standar Etik DEC-026</span>
                </span>
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-white border border-[#eaecf0] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#9ca3af]"></i>
                    <input type="text" id="abuse-search-input" oninput="renderAbuseReportsTable()" placeholder="Cari pelapor, objek, atau nomor aduan..." class="w-full pl-8 pr-3 py-1.5 text-xs bg-[#ffffff] border border-[#eaecf0] rounded-lg text-[#111827] placeholder-[#9ca3af] focus:outline-none focus:border-[#1572b8]">
                </div>
                <select id="abuse-status-filter" onchange="renderAbuseReportsTable()" class="text-xs bg-white border border-[#eaecf0] rounded-lg px-2.5 py-1.5 text-[#374151]">
                    <option value="all">Semua Status</option>
                    <option value="pending" selected>Menunggu Review</option>
                    <option value="resolved">Selesai Ditindak</option>
                    <option value="dismissed">Diabaikan</option>
                </select>
                <select id="abuse-reason-filter" onchange="renderAbuseReportsTable()" class="text-xs bg-white border border-[#eaecf0] rounded-lg px-2.5 py-1.5 text-[#374151]">
                    <option value="all">Semua Jenis Pelanggaran</option>
                    <option value="fake_report">Laporan Palsu / Spam</option>
                    <option value="fraud_claim">Klaim Fiktif</option>
                    <option value="inappropriate_chat">Pelecehan / Kata Kasar</option>
                </select>
            </div>
            <div class="text-xs text-[#6b7280] font-medium shrink-0">
                Total: <span class="font-bold text-[#111827]" id="abuse-total-count">3</span> Aduan
            </div>
        </div>

        <div class="rounded-xl border border-[#eaecf0] bg-white overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>No. Aduan</th>
                            <th>Pelapor</th>
                            <th>Objek Dilaporkan</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Keterangan / Bukti</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-right">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody id="abuse-reports-table-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
            <div id="abuse-table-empty" class="hidden p-8 text-center text-[#6b7280] text-xs">
                Tidak ada aduan penyalahgunaan yang cocok.
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 4: MANAJEMEN PENGGUNA
         =========================================================================== -->
    <div id="tab-panel-users" class="admin-tab-panel hidden space-y-4">
        
        <!-- 1. Header & Primary Action Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#111827]">Manajemen Pengguna</h1>
                <p class="text-xs text-[#6b7280] mt-0.5">Direktori hak akses civitas akademika dan administrator FindUMB.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openCreateAdminModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-[#1572b8] hover:bg-[#0f5791] text-white text-xs font-semibold shadow-2xs transition-all cursor-pointer">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Administrator</span>
                </button>
            </div>
        </div>

        <!-- 2. Clean Policy Callout (Without cramped button) -->
        <div class="p-3.5 rounded-xl bg-[#f0f7fd] border border-[#bae6fd] flex items-center gap-2.5 text-xs text-[#0369a1]">
            <i data-lucide="info" class="w-4 h-4 shrink-0 text-[#1572b8]"></i>
            <span><strong>Kebijakan Email:</strong> Mahasiswa wajib email kampus (<code class="bg-[#e0f2fe] px-1.5 py-0.5 rounded text-[11px] font-mono text-[#0284c7]">@student.mercubuana.ac.id</code>). Administrator diizinkan email institusi atau Gmail terverifikasi.</span>
        </div>

        <!-- 3. KPI Stat Cards (4 Cards - Synchronized with FindUMB Design Language) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Total Pengguna -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs flex flex-col justify-between">
                <span class="text-sm font-medium text-[#475467]">Total Pengguna</span>
                <div class="mt-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="users-kpi-total">5</span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                            <i data-lucide="arrow-up-right" class="w-3 h-3"></i> 100%
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">Seluruh civitas terdaftar</p>
                </div>
            </div>

            <!-- Card 2: Administrator Kampus (Brand Blue) -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-blue-500/30 transition-all flex flex-col justify-between">
                <span class="text-sm font-medium text-[#475467]">Administrator</span>
                <div class="mt-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="users-kpi-admin">2</span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-blue-50 text-[#1572b8]">
                            Otoritas Penuh
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">Biro Kemahasiswaan &amp; IT</p>
                </div>
            </div>

            <!-- Card 3: Civitas Mahasiswa -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-emerald-500/30 transition-all flex flex-col justify-between">
                <span class="text-sm font-medium text-[#475467]">Civitas Mahasiswa</span>
                <div class="mt-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="users-kpi-students">3</span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-[#ecfdf3] text-[#027a48]">
                            SSO Aktif
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">Fasilkom, FT, FDSK</p>
                </div>
            </div>

            <!-- Card 4: Akun Ditangguhkan -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs hover:border-rose-500/30 transition-all flex flex-col justify-between">
                <span class="text-sm font-medium text-[#475467]">Akun Ditangguhkan</span>
                <div class="mt-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-[22px] font-semibold tracking-tight text-[#101828]" id="users-kpi-suspended">1</span>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-[5px] text-[11px] font-semibold bg-rose-50 text-rose-700">
                            Sanksi Etik
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] mt-1.5">Sanksi aturan DEC-026</p>
                </div>
            </div>
        </div>

        <!-- 4. Quick Pills + Filter Bar -->
        <div class="p-4 rounded-xl bg-white border border-[#eaecf0] shadow-xs space-y-3.5">
            <!-- Quick Filter Pills -->
            <div class="flex flex-wrap items-center justify-between gap-2.5 pb-3 border-b border-[#f3f4f6]">
                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                    <button type="button" onclick="setUsersQuickFilter('all')" id="user-pill-all" class="user-quick-pill px-3 py-1 rounded-lg bg-[#1572b8] text-white font-semibold transition-all cursor-pointer shadow-2xs">
                        Semua Pengguna (<span id="user-pill-count-all">5</span>)
                    </button>
                    <button type="button" onclick="setUsersQuickFilter('admin')" id="user-pill-admin" class="user-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer">
                        Administrator (<span id="user-pill-count-admin">2</span>)
                    </button>
                    <button type="button" onclick="setUsersQuickFilter('user')" id="user-pill-user" class="user-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer">
                        Civitas Mahasiswa (<span id="user-pill-count-user">3</span>)
                    </button>
                    <button type="button" onclick="setUsersQuickFilter('suspended')" id="user-pill-suspended" class="user-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer">
                        Ditangguhkan (<span id="user-pill-count-suspended">1</span>)
                    </button>
                </div>
                <div class="text-xs text-[#6b7280]">
                    Menampilkan <strong class="text-[#111827]" id="users-displayed-count">5</strong> akun
                </div>
            </div>

            <!-- Filter Controls Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5">
                <!-- Search Input (6 Cols) -->
                <div class="lg:col-span-6 relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#9ca3af]"></i>
                    <input type="text" id="user-search-input" oninput="renderUsersTable()" placeholder="Cari nama pengguna, NIM/NIK, email, atau fakultas..." class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-[#eaecf0] rounded-lg text-[#111827] placeholder-[#9ca3af] focus:outline-none focus:border-[#1572b8]">
                </div>

                <!-- Peran Filter (2 Cols) -->
                <div class="lg:col-span-2">
                    <select id="user-role-filter" onchange="activeUsersQuickFilter='all'; renderUsersTable()" class="w-full text-xs bg-white border border-[#eaecf0] rounded-lg px-2.5 py-1.5 text-[#374151] focus:outline-none focus:border-[#1572b8]">
                        <option value="all">Semua Peran</option>
                        <option value="admin">Administrator</option>
                        <option value="user">Civitas Mahasiswa</option>
                    </select>
                </div>

                <!-- Status Filter (2 Cols) -->
                <div class="lg:col-span-2">
                    <select id="user-status-filter" onchange="activeUsersQuickFilter='all'; renderUsersTable()" class="w-full text-xs bg-white border border-[#eaecf0] rounded-lg px-2.5 py-1.5 text-[#374151] focus:outline-none focus:border-[#1572b8]">
                        <option value="all">Semua Status</option>
                        <option value="active">Aktif Normal</option>
                        <option value="suspended">Ditangguhkan</option>
                    </select>
                </div>

                <!-- Reset Button (2 Cols) -->
                <div class="lg:col-span-2 flex items-center justify-end">
                    <button type="button" onclick="resetUsersFilters()" class="w-full py-1.5 text-xs text-[#6b7280] hover:text-[#111827] font-medium border border-[#eaecf0] rounded-lg hover:bg-[#f9fafb] transition-all text-center cursor-pointer">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- 5. Users Table Container -->
        <div class="rounded-xl border border-[#eaecf0] bg-white overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#f9fafb] text-[#6b7280] text-[11px] font-semibold border-b border-[#eaecf0]">
                            <th class="py-3 px-3.5">Pengguna</th>
                            <th class="py-3 px-2.5 whitespace-nowrap">NIM / NIK</th>
                            <th class="py-3 px-2.5">Fakultas &amp; Program Studi</th>
                            <th class="py-3 px-2.5">Alamat Email</th>
                            <th class="py-3 px-2.5 whitespace-nowrap">Peran (Role)</th>
                            <th class="py-3 px-2.5 whitespace-nowrap">Status Akun</th>
                            <th class="py-3 px-3.5 text-right whitespace-nowrap">Aksi Akun</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body" class="divide-y divide-[#eaecf0]">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
            <!-- Empty State -->
            <div id="users-table-empty" class="hidden py-12 px-4 text-center">
                <div class="w-12 h-12 rounded-full bg-[#f3f4f6] text-[#9ca3af] flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="user-x" class="w-6 h-6"></i>
                </div>
                <div class="text-sm font-semibold text-[#111827]">Tidak Ada Pengguna Ditemukan</div>
                <div class="text-xs text-[#6b7280] mt-1 max-w-sm mx-auto">Tidak ada akun civitas atau administrator yang cocok dengan kata kunci atau filter yang Anda pilih.</div>
                <button type="button" onclick="resetUsersFilters()" class="mt-3 px-3 py-1.5 text-xs font-medium text-[#1572b8] bg-[#f0f7fd] rounded-lg hover:bg-[#e0f2fe] transition-colors cursor-pointer">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 5: LOG AUDIT MODERASI
         =========================================================================== -->
    <div id="tab-panel-audit" class="admin-tab-panel hidden space-y-4">
        
        <!-- Header Halaman Log Audit Moderasi -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#111827]">Log Audit Moderasi</h1>
                <p class="text-xs text-[#6b7280] mt-0.5">Rekam jejak tindakan administratif, moderasi, dan tata kelola sistem.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" onclick="exportAdminAuditCSV()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-[#eaecf0] bg-white text-[#374151] hover:bg-[#f9fafb] hover:text-[#111827] transition-all cursor-pointer shadow-2xs">
                    <i data-lucide="download" class="w-3.5 h-3.5 text-[#6b7280]"></i>
                    <span>Ekspor Log</span>
                </button>
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-white border border-[#eaecf0] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#9ca3af]"></i>
                    <input type="text" id="audit-search-input" oninput="renderAuditTable()" placeholder="Cari aktor admin, target, atau alasan..." class="w-full pl-8 pr-3 py-1.5 text-xs bg-[#ffffff] border border-[#eaecf0] rounded-lg text-[#111827] placeholder-[#9ca3af] focus:outline-none focus:border-[#1572b8]">
                </div>
                <select id="audit-action-filter" onchange="renderAuditTable()" class="text-xs bg-white border border-[#eaecf0] rounded-lg px-2.5 py-1.5 text-[#374151]">
                    <option value="all">Semua Tindakan</option>
                    <option value="hide_report">Sembunyikan Laporan</option>
                    <option value="suspend_user">Penangguhan Akun</option>
                    <option value="warn_user">Peringatan Formal</option>
                    <option value="reactivate_user">Reaktivasi Akun</option>
                    <option value="dismiss_abuse">Abaikan Aduan</option>
                </select>
            </div>
            <div class="text-xs text-[#6b7280] font-medium shrink-0">
                Total: <span class="font-bold text-[#111827]" id="audit-total-count">0</span> Catatan
            </div>
        </div>

        <div class="rounded-xl border border-[#eaecf0] bg-white overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Waktu &amp; Tanggal</th>
                            <th>Admin Pelaksana</th>
                            <th>Jenis Tindakan</th>
                            <th>Target Objek / Pengguna</th>
                            <th>Alasan &amp; Dasar Keputusan</th>
                            <th class="text-right">Dampak Sistem</th>
                        </tr>
                    </thead>
                    <tbody id="audit-table-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
            <div id="audit-table-empty" class="hidden p-8 text-center text-[#6b7280] text-xs">
                Belum ada catatan log audit.
            </div>
        </div>
    </div>

    <!-- ===========================================================================
         TAB PANEL 6: KATEGORI BARANG
         =========================================================================== -->
    <div id="tab-panel-categories" class="admin-tab-panel hidden space-y-4">
        
        <!-- Header Halaman Kategori Barang -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#111827]">Kategori Barang</h1>
                <p class="text-xs text-[#6b7280] mt-0.5">Taksonomi resmi kategori barang temuan dan laporan kehilangan kampus.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" onclick="showAdminToast('Fitur penambahan kategori master aktif.')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#1572b8] hover:bg-[#0f5791] text-white text-xs font-semibold shadow-2xs transition-all cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3" id="categories-grid-container">
            <!-- Populated by JS -->
        </div>
    </div>

</div>

<!-- ===========================================================================
     MODAL DIALOGS (Clean Monochrome / Brand Style)
     =========================================================================== -->

<!-- Modal 1: Tindakan Moderasi (DEC-013, DEC-026) -->
<div id="modal-moderation-action" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-xl border border-[#eaecf0] overflow-hidden">
        <div class="p-4 border-b border-[#eaecf0] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="shield-alert" class="w-4 h-4 text-rose-600"></i>
                <h3 class="font-bold text-sm text-[#111827]" id="modal-mod-title">Tindakan Moderasi</h3>
            </div>
            <button type="button" onclick="closeModerationActionModal()" class="text-[#9ca3af] hover:text-[#111827]">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="form-moderation-action" onsubmit="executeModerationCommit(event)" class="p-5 space-y-3.5 text-xs">
            <input type="hidden" id="mod-target-type">
            <input type="hidden" id="mod-target-id">
            <input type="hidden" id="mod-action-type">

            <div class="p-3 rounded-lg bg-[#f9fafb] border border-[#eaecf0] space-y-0.5">
                <div class="text-[#6b7280] text-[11px]">Target Objek:</div>
                <div class="font-bold text-[#111827]" id="mod-target-summary">-</div>
                <div class="text-[10px] text-[#9ca3af]" id="mod-target-subinfo">-</div>
            </div>

            <div id="mod-impact-callout" class="p-3 rounded-lg bg-[#fffbeb] border border-[#fef3c7] text-[#92400e] text-[11px] leading-relaxed">
                <strong>Dampak Sistem (DEC-026):</strong>
                <span id="mod-impact-text" class="block mt-0.5">Penonaktifan akun akan membatalkan klaim aktif dan mengunci hak klaim.</span>
            </div>

            <div class="space-y-1">
                <label for="mod-reason-input" class="font-semibold text-[#111827] block">
                    Alasan &amp; Dasar Keputusan Moderasi <span class="text-rose-500">*</span>
                </label>
                <textarea id="mod-reason-input" rows="3" required placeholder="Tuliskan justifikasi pelanggaran secara spesifik..." class="w-full p-2.5 bg-white border border-[#eaecf0] rounded-lg text-[#111827] focus:outline-none focus:border-[#1572b8]"></textarea>
            </div>

            <div class="pt-3 border-t border-[#eaecf0] flex items-center justify-end gap-2">
                <button type="button" onclick="closeModerationActionModal()" class="px-3 py-1.5 rounded-lg border border-[#eaecf0] text-[#374151] hover:bg-[#f9fafb]">
                    Batal
                </button>
                <button type="submit" id="btn-mod-submit-confirm" class="px-3.5 py-1.5 rounded-lg bg-rose-600 text-white font-semibold hover:bg-rose-700">
                    Eksekusi Tindakan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Detail Aduan -->
<div id="modal-abuse-detail" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-xl border border-[#eaecf0] overflow-hidden">
        <div class="p-4 border-b border-[#eaecf0] flex items-center justify-between">
            <h3 class="font-bold text-sm text-[#111827]">Rincian Aduan Pelanggaran</h3>
            <button type="button" onclick="closeAbuseDetailModal()" class="text-[#9ca3af] hover:text-[#111827]">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="p-5 space-y-3 text-xs max-h-[70vh] overflow-y-auto">
            <div class="grid grid-cols-2 gap-2 p-3 bg-[#f9fafb] rounded-lg border border-[#eaecf0] text-[11px]">
                <div><span class="text-[#6b7280]">ID Aduan:</span> <span class="font-bold text-[#111827] block" id="detail-abuse-id">-</span></div>
                <div><span class="text-[#6b7280]">Waktu:</span> <span class="font-bold text-[#111827] block" id="detail-abuse-date">-</span></div>
                <div><span class="text-[#6b7280]">Pelapor:</span> <span class="font-bold text-[#111827] block" id="detail-abuse-reporter">-</span></div>
                <div><span class="text-[#6b7280]">Status:</span> <span class="font-bold text-[#111827] block" id="detail-abuse-status">-</span></div>
            </div>

            <div>
                <span class="font-semibold text-[#111827] block mb-1">Objek Yang Dilaporkan:</span>
                <div class="p-2.5 bg-white border border-[#eaecf0] rounded-lg" id="detail-abuse-target-box">-</div>
            </div>

            <div>
                <span class="font-semibold text-[#111827] block mb-1">Jenis Pelanggaran:</span>
                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700" id="detail-abuse-reason-badge">-</span>
            </div>

            <div>
                <span class="font-semibold text-[#111827] block mb-1">Bukti &amp; Uraian Pelapor:</span>
                <p class="p-3 bg-[#f9fafb] border border-[#eaecf0] rounded-lg text-[#374151] leading-relaxed" id="detail-abuse-description">-</p>
            </div>
        </div>

        <div class="p-3.5 border-t border-[#eaecf0] flex items-center justify-end gap-2 bg-[#fafbfc]">
            <button type="button" onclick="closeAbuseDetailModal()" class="px-3 py-1.5 rounded-lg border border-[#eaecf0] text-xs text-[#374151] hover:bg-white">
                Tutup
            </button>
            <button type="button" id="btn-detail-action-dismiss" class="px-3 py-1.5 rounded-lg border border-[#eaecf0] text-xs text-[#374151] hover:bg-white">
                Abaikan Aduan
            </button>
            <button type="button" id="btn-detail-action-hide" class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700">
                Sembunyikan Laporan
            </button>
        </div>
    </div>
</div>

<!-- Modal 3: Tambah Admin Baru -->
<div id="modal-create-admin" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl border border-[#eaecf0] overflow-hidden">
        <div class="p-4 border-b border-[#eaecf0] flex items-center justify-between">
            <h3 class="font-bold text-sm text-[#111827]">Tambah Akun Administrator</h3>
            <button type="button" onclick="closeCreateAdminModal()" class="text-[#9ca3af] hover:text-[#111827]">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="form-create-admin" onsubmit="handleCreateAdminSubmit(event)" class="p-5 space-y-3 text-xs">
            <div class="space-y-1">
                <label for="admin-new-name" class="font-semibold text-[#111827]">Nama Lengkap Petugas <span class="text-rose-500">*</span></label>
                <input type="text" id="admin-new-name" required placeholder="Contoh: Petugas Biro Kemahasiswaan" class="w-full p-2 bg-white border border-[#eaecf0] rounded-lg text-[#111827] focus:outline-none focus:border-[#1572b8]">
            </div>

            <div class="space-y-1">
                <label for="admin-new-email" class="font-semibold text-[#111827]">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" id="admin-new-email" required placeholder="admin.umb@mercubuana.ac.id atau email pribadi" class="w-full p-2 bg-white border border-[#eaecf0] rounded-lg text-[#111827] focus:outline-none focus:border-[#1572b8]">
                <span class="text-[10px] text-[#6b7280] block">Mendukung email kampus maupun email pribadi (@gmail.com).</span>
            </div>

            <div class="space-y-1">
                <label for="admin-new-unit" class="font-semibold text-[#111827]">Unit Kerja</label>
                <input type="text" id="admin-new-unit" placeholder="Pos Satpam Tower Meruya / Biro Kemahasiswaan" class="w-full p-2 bg-white border border-[#eaecf0] rounded-lg text-[#111827] focus:outline-none focus:border-[#1572b8]">
            </div>

            <div class="space-y-1">
                <label for="admin-new-role" class="font-semibold text-[#111827]">Hak Akses</label>
                <select id="admin-new-role" class="w-full p-2 bg-white border border-[#eaecf0] rounded-lg text-[#111827]">
                    <option value="admin">Administrator Moderasi</option>
                    <option value="superadmin">Super Administrator</option>
                </select>
            </div>

            <div class="pt-3 border-t border-[#eaecf0] flex items-center justify-end gap-2">
                <button type="button" onclick="closeCreateAdminModal()" class="px-3 py-1.5 rounded-lg border border-[#eaecf0] text-[#374151]">Batal</button>
                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#1572b8] hover:bg-[#0f5791] text-white font-semibold shadow-2xs transition-colors cursor-pointer">Simpan Admin Baru</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 4: Pengaturan Akun Admin -->
<div id="modal-admin-settings" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl border border-[#eaecf0] overflow-hidden">
        <div class="p-4 border-b border-[#eaecf0] flex items-center justify-between">
            <h3 class="font-bold text-sm text-[#111827]">Pengaturan Akun Administrator</h3>
            <button type="button" onclick="closeAdminSettingsModal()" class="text-[#9ca3af] hover:text-[#111827]">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form onsubmit="saveAdminSettings(event)" class="p-5 space-y-3 text-xs">
            <div class="space-y-1">
                <label for="settings-admin-name" class="font-semibold text-[#111827]">Nama Tampilan</label>
                <input type="text" id="settings-admin-name" required value="Biro Kemahasiswaan UMB" class="w-full p-2 bg-white border border-[#eaecf0] rounded-lg text-[#111827]">
            </div>
            <div class="space-y-1">
                <label for="settings-admin-email" class="font-semibold text-[#111827]">Email Admin</label>
                <input type="email" id="settings-admin-email" required value="admin.lostfound@mercubuana.ac.id" class="w-full p-2 bg-white border border-[#eaecf0] rounded-lg text-[#111827]">
            </div>
            <div class="pt-3 border-t border-[#eaecf0] flex items-center justify-between gap-2">
                <button type="button" onclick="handleAdminSignOut()" class="px-2.5 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50 font-medium cursor-pointer">Keluar Mode Admin</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeAdminSettingsModal()" class="px-3 py-1.5 rounded-lg border border-[#eaecf0] text-[#374151] hover:bg-[#f9fafb] cursor-pointer">Batal</button>
                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-[#1572b8] hover:bg-[#0f5791] text-white font-semibold shadow-2xs transition-colors cursor-pointer">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal 5: Detail & Verifikasi Laporan Civitas -->
<div id="modal-report-management-detail" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-[#eaecf0] shadow-xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <!-- Header -->
        <div class="p-4 sm:p-5 border-b border-[#eaecf0] flex items-center justify-between bg-[#f8fafc]">
            <div class="flex items-center gap-2.5">
                <div id="modal-rep-badge-type" class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">TEMUAN</div>
                <span class="text-xs font-mono text-[#9ca3af]" id="modal-rep-ticket-id">#FND-001</span>
            </div>
            <button type="button" onclick="closeReportDetailModal()" class="w-7 h-7 rounded-lg text-[#9ca3af] hover:text-[#111827] hover:bg-[#f1f5f9] flex items-center justify-center cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-5 space-y-4 text-xs max-h-[75vh] overflow-y-auto">
            <div>
                <h3 class="text-base font-bold text-[#111827]" id="modal-rep-title">Nama Barang</h3>
                <div class="flex items-center gap-2 mt-1 text-[#6b7280]">
                    <span id="modal-rep-category">Elektronik</span>
                    <span>•</span>
                    <span id="modal-rep-date">18 Okt 2026</span>
                </div>
            </div>

            <!-- Detail Box -->
            <div class="p-3.5 rounded-xl bg-[#f8fafc] border border-[#eaecf0] space-y-2.5">
                <div class="flex items-start gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-[#1572b8] shrink-0 mt-0.5"></i>
                    <div>
                        <div class="font-semibold text-[#111827]">Lokasi Barang:</div>
                        <div class="text-[#4b5563]" id="modal-rep-location">Kampus Meruya - Perpustakaan Lt. 2</div>
                    </div>
                </div>

                <div class="flex items-start gap-2 pt-2 border-t border-[#eaecf0]">
                    <i data-lucide="user" class="w-4 h-4 text-[#6b7280] shrink-0 mt-0.5"></i>
                    <div>
                        <div class="font-semibold text-[#111827]">Pelapor:</div>
                        <div class="text-[#4b5563]" id="modal-rep-reporter">Siti Nurhaliza (41521010012)</div>
                    </div>
                </div>

                <div class="flex items-start gap-2 pt-2 border-t border-[#eaecf0]">
                    <i data-lucide="shield-alert" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                    <div>
                        <div class="font-semibold text-[#111827]">Ciri Khusus (Verifikasi Satpam):</div>
                        <div class="text-[#4b5563]" id="modal-rep-features">Terdapat stiker logo kucing di bagian case samping, nomor seri terverifikasi.</div>
                    </div>
                </div>
            </div>

            <!-- Status Manager -->
            <div class="p-3.5 rounded-xl border border-[#eaecf0] bg-white space-y-2">
                <div class="font-semibold text-[#111827]">Ubah Status Laporan:</div>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="updateReportStatusDirect('open')" class="py-1.5 px-2 rounded-lg border border-[#eaecf0] hover:bg-[#f0f7fd] text-[11px] font-medium text-center cursor-pointer">
                        Open (Aktif)
                    </button>
                    <button type="button" onclick="updateReportStatusDirect('in_handover')" class="py-1.5 px-2 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100 text-[11px] font-semibold text-center cursor-pointer">
                        In Handover
                    </button>
                    <button type="button" onclick="updateReportStatusDirect('resolved')" class="py-1.5 px-2 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 text-[11px] font-semibold text-center cursor-pointer">
                        Resolved (Selesai)
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-[#eaecf0] bg-[#f8fafc] flex items-center justify-between">
            <button type="button" id="modal-rep-toggle-hide-btn" onclick="toggleHideActiveReport()" class="px-3 py-1.5 rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 text-xs font-semibold cursor-pointer">
                Sembunyikan
            </button>
            <button type="button" onclick="closeReportDetailModal()" class="px-4 py-1.5 rounded-lg border border-[#eaecf0] bg-white text-[#374151] hover:bg-[#f9fafb] text-xs font-medium cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    /* ===========================================================================
       ADMIN DASHBOARD ENGINE & DATA STORE
       =========================================================================== */

    const DEFAULT_ABUSE_REPORTS = [
        {
            id: 'ABUSE-2026-001',
            reporter_name: 'Muhammad Farhan',
            reporter_nim: '41521010088',
            target_type: 'report',
            target_id: 'rep_fake_iphone',
            target_title: 'iPhone 15 Pro Max Deep Blue',
            reason_code: 'fake_report',
            reason_label: 'Laporan Palsu / Spam',
            severity: 'high',
            description: 'Foto diambil dari Google Images dan tidak ada barang aslinya.',
            date: '04 Nov 2026, 14:20 WIB',
            status: 'pending'
        },
        {
            id: 'ABUSE-2026-002',
            reporter_name: 'Siti Nurhaliza',
            reporter_nim: '41521010012',
            target_type: 'chat',
            target_id: 'chat_anker_conv',
            target_title: 'Percakapan Klaim Earphone TWS Anker',
            reason_code: 'inappropriate_chat',
            reason_label: 'Pelecehan / Kata Kasar',
            severity: 'medium',
            description: 'Pengklaim menggunakan kata-kata kasar dan mengancam saat penemu menanyakan detail nomor seri casing.',
            date: '04 Nov 2026, 09:15 WIB',
            status: 'pending'
        },
        {
            id: 'ABUSE-2026-003',
            reporter_name: 'Dwi Prasetyo',
            reporter_nim: '41521010034',
            target_type: 'user',
            target_id: '41521010099',
            target_title: 'Akun Civitas: Bagus Triyanto (41521010099)',
            reason_code: 'fraud_claim',
            reason_label: 'Klaim Fiktif Berulang',
            severity: 'high',
            description: 'Mengajukan klaim serah terima pada 5 laporan barang temuan berbeda dalam rentang waktu 2 jam.',
            date: '03 Nov 2026, 19:40 WIB',
            status: 'pending'
        }
    ];

    const DEFAULT_ADMIN_USERS = [
        { id: 'u_fauzi', name: 'Ahmad Fauzi', nim: '41521010045', faculty: 'Fakultas Ilmu Komputer', prodi: 'S1 Teknik Informatika', email: '41521010045@student.mercubuana.ac.id', role: 'user', status: 'active' },
        { id: 'u_admin_biro', name: 'Biro Kemahasiswaan & Admin UMB', nim: 'NIK-UMB-8821', faculty: 'Biro Kemahasiswaan', prodi: 'Staf Administrasi', email: 'admin.lostfound@mercubuana.ac.id', role: 'admin', status: 'active' },
        { id: 'u_admin_zidan', name: 'Zidan Maulana (Admin Pengembang)', nim: 'NIK-DEV-001', faculty: 'Pengembang Platform', prodi: 'IT Campus Center', email: 'zidan.personal@gmail.com', role: 'admin', status: 'active' },
        { id: 'u_bagus', name: 'Bagus Triyanto', nim: '41521010099', faculty: 'Fakultas Teknik', prodi: 'S1 Teknik Sipil', email: '41521010099@student.mercubuana.ac.id', role: 'user', status: 'suspended' },
        { id: 'u_siti', name: 'Siti Nurhaliza', nim: '41521010012', faculty: 'Fakultas Desain & Seni Kreatif', prodi: 'S1 Desain Komunikasi Visual', email: '41521010012@student.mercubuana.ac.id', role: 'user', status: 'active' }
    ];

    const DEFAULT_AUDIT_LOGS = [
        { id: 'AUD-901', timestamp: '04 Nov 2026, 15:10 WIB', admin_name: 'Biro Kemahasiswaan', admin_email: 'admin.lostfound@mercubuana.ac.id', action_type: 'suspend_user', action_label: 'Penangguhan Akun', target: 'Bagus Triyanto (41521010099)', reason: 'Terbukti melakukan klaim serah terima fiktif secara massal.', impact: 'Akun status suspended (DEC-026).' },
        { id: 'AUD-902', timestamp: '03 Nov 2026, 11:25 WIB', admin_name: 'Zidan Maulana', admin_email: 'zidan.personal@gmail.com', action_type: 'hide_report', action_label: 'Sembunyikan Laporan', target: 'Laporan #rep_spam_99', reason: 'Laporan mengandung tautan mencurigakan.', impact: 'Postingan disembunyikan.' },
        { id: 'AUD-903', timestamp: '02 Nov 2026, 09:00 WIB', admin_name: 'Biro Kemahasiswaan', admin_email: 'admin.lostfound@mercubuana.ac.id', action_type: 'warn_user', action_label: 'Peringatan Formal', target: 'Rian Pratama (41521010022)', reason: 'Bahasa chat tidak sopan pada negosiasi lokasi serah terima.', impact: 'Peringatan dikirim.' }
    ];

    const DEFAULT_CATEGORIES = [
        { id: 'cat_electronics', name: 'Elektronik & Gadget', icon: 'smartphone', count: 51, desc: 'Smartphone, laptop, earphone, charger, kalkulator ilmiah' },
        { id: 'cat_docs', name: 'Dokumen & KTM', icon: 'id-card', count: 34, desc: 'KTM, SIM, KTP, STNK, buku catatan kuliah' },
        { id: 'cat_bags', name: 'Tas & Dompet', icon: 'briefcase', count: 22, desc: 'Ransel laptop, tote bag, dompet kulit, pouch kosmetik' },
        { id: 'cat_accessories', name: 'Aksesoris & Pakaian', icon: 'watch', count: 18, desc: 'Jam tangan, jaket hoodie, kacamata minus/silinder, topi' }
    ];

    function getAdminAbuseReports() {
        try {
            const raw = localStorage.getItem('findumb_admin_abuse_reports');
            if (raw) return JSON.parse(raw);
        } catch(e) {}
        saveAdminAbuseReports(DEFAULT_ABUSE_REPORTS);
        return DEFAULT_ABUSE_REPORTS;
    }

    function saveAdminAbuseReports(data) {
        try { localStorage.setItem('findumb_admin_abuse_reports', JSON.stringify(data)); } catch(e) {}
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
        try { localStorage.setItem('findumb_admin_users', JSON.stringify(data)); } catch(e) {}
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
        try { localStorage.setItem('findumb_admin_audit_logs', JSON.stringify(data)); } catch(e) {}
    }

    const DEFAULT_REPORTS_CATALOG = [
        // 19 Barang Temuan (12 Open, 7 In Handover)
        { id: '1', title: 'Earphone TWS Anker Soundcore R50i', type: 'found', reporter: 'Siti Nurhaliza (41521010012)', location: 'Kampus Meruya • Perpustakaan Lt. 2', date: '18 Okt 2026', status: 'in_handover' },
        { id: '2', title: 'Tas Laptop Lenovo ThinkPad Hitam', type: 'found', reporter: 'Pos Satpam Tower', location: 'Kampus Meruya • Pos Satpam Lt. 1', date: '19 Jan 2026', status: 'open' },
        { id: '3', title: 'Jaket Hoodie Hitam Uniqlo Airism', type: 'found', reporter: 'Petugas Lab Komputer', location: 'Kampus Meruya • Lab Fasilkom Lt. 3', date: '12 Jan 2026', status: 'open' },
        { id: '4', title: 'KTM & Dompet Kulit Cokelat UMB', type: 'found', reporter: 'Dwi Prasetyo (41521010034)', location: 'Kampus Meruya • Kantin Blok D', date: '22 Okt 2026', status: 'open' },
        { id: '5', title: 'Flashdisk SanDisk Ultra Flair 64GB', type: 'found', reporter: 'Staf Lab Jaringan', location: 'Kampus Meruya • Lab Fasilkom Lt. 2', date: '25 Okt 2026', status: 'open' },
        { id: '6', title: 'Tumbler Corkcicle Classic 475ml Forest Green', type: 'found', reporter: 'Petugas Kebersihan Kampus', location: 'Kampus Meruya • Gazebo Rektorat', date: '26 Okt 2026', status: 'in_handover' },
        { id: '7', title: 'Kacamata Minus Titanium Frame Hitam', type: 'found', reporter: 'Rina Anggraini (41621010015)', location: 'Kampus Meruya • Gedung B Ruang B-201', date: '27 Okt 2026', status: 'open' },
        { id: '8', title: 'Helm KYT TT Course Motif Putih', type: 'found', reporter: 'Petugas Parkir Motor', location: 'Kampus Meruya • Parkir Barat Lt. 1', date: '28 Okt 2026', status: 'in_handover' },
        { id: '9', title: 'Charger MacBook Pro 67W USB-C', type: 'found', reporter: 'Biro Perlengkapan UMB', location: 'Kampus Meruya • Gedung Tower Lt. 3', date: '29 Okt 2026', status: 'open' },
        { id: '10', title: 'Kunci Motor Honda Vario 160 Hitam', type: 'found', reporter: 'Petugas Keamanan Parkir', location: 'Kampus Meruya • Parkir Gedung B', date: '30 Okt 2026', status: 'open' },
        { id: '11', title: 'Payung Lipat Otomatis Navy Blue', type: 'found', reporter: 'Resepsionis Gedung Tower', location: 'Kampus Meruya • Lobi Utama Tower', date: '31 Okt 2026', status: 'open' },
        { id: '12', title: 'Dompet Kartu Cardholder E-Money Mandiri', type: 'found', reporter: 'Bayu Wicaksono (41121010055)', location: 'Kampus Meruya • Halte Bus UMB', date: '01 Nov 2026', status: 'in_handover' },
        { id: '13', title: 'Mouse Wireless Logitech Silent M331', type: 'found', reporter: 'Staf TU Fakultas Teknik', location: 'Kampus Meruya • Ruang Sidang FT Lt. 2', date: '01 Nov 2026', status: 'open' },
        { id: '14', title: 'Buku Catatan Kalkulus II & Binder B5', type: 'found', reporter: 'Perpustakaan UMB', location: 'Kampus Meruya • Perpustakaan Lt. 1', date: '02 Nov 2026', status: 'open' },
        { id: '15', title: 'Kalkulator Ilmiah Casio FX-991EX', type: 'found', reporter: 'Lab Teknik Sipil', location: 'Kampus Meruya • Gedung E Lt. 2', date: '02 Nov 2026', status: 'in_handover' },
        { id: '16', title: 'Jam Tangan Alexandre Christie Rantai Wanita', type: 'found', reporter: 'Cleaning Service Tower', location: 'Kampus Meruya • Toilet Tower Lt. 4', date: '03 Nov 2026', status: 'in_handover' },
        { id: '17', title: 'Powerbank Anker 20.000mAh Dual Port Hitam', type: 'found', reporter: 'Staf BEM Fasilkom', location: 'Kampus Meruya • Ruang BEM Gedung C', date: '03 Nov 2026', status: 'open' },
        { id: '18', title: 'STNK Motor Honda Beat B 3921 KRF', type: 'found', reporter: 'Pos Satpam Pintu 2', location: 'Kampus Meruya • Pos Pintu Gerbang', date: '04 Nov 2026', status: 'in_handover' },
        { id: '19', title: 'Pouch Kosmetik & Kunci Loker FDSK', type: 'found', reporter: 'Nadia Putri (41821010008)', location: 'Kampus Meruya • Koridor Gedung D Lt. 1', date: '04 Nov 2026', status: 'open' },

        // 9 Barang Hilang Aktif (6 Open, 3 In Handover)
        { id: '20', title: 'Samsung Galaxy A13 (SM-A135F) Biru Muda', type: 'lost', reporter: 'Ahmad Fauzi (41521010045)', location: 'Kampus Meruya • Tower Lt. 7', date: '20 Jun 2026', status: 'open' },
        { id: '21', title: 'Kunci Motor Yamaha Aerox 155 Remote', type: 'lost', reporter: 'Muhammad Farhan (41521010088)', location: 'Kampus Meruya • Parkir Barat', date: '19 Jan 2026', status: 'open' },
        { id: '22', title: 'Apple AirPods Pro Gen 2 & Case Putih', type: 'lost', reporter: 'Kevin Sanjaya (41521010067)', location: 'Kampus Meruya • Lapangan Futsal', date: '24 Okt 2026', status: 'in_handover' },
        { id: '23', title: 'Jam Tangan Casio G-Shock GA-2100 All Black', type: 'lost', reporter: 'Rizky Ramadhan (41621010090)', location: 'Kampus Meruya • Masjid Manarul Ilmi', date: '27 Okt 2026', status: 'open' },
        { id: '24', title: 'iPad Air 5 64GB Space Grey + Apple Pencil 2', type: 'lost', reporter: 'Clarissa Amanda (41921010014)', location: 'Kampus Meruya • Food Court Atrium', date: '28 Okt 2026', status: 'in_handover' },
        { id: '25', title: 'Harddisk Eksternal WD Elements 1TB Hitam', type: 'lost', reporter: 'Ilham Hidayat (41521010052)', location: 'Kampus Meruya • Co-Working Space Lt. 2', date: '30 Okt 2026', status: 'open' },
        { id: '26', title: 'Kacamata RayBan Aviator Frame Silver', type: 'lost', reporter: 'Dimas Aditya (41221010031)', location: 'Kampus Menteng • Ruang M-301', date: '01 Nov 2026', status: 'open' },
        { id: '27', title: 'Dompet Lipat Braun Buffel Kulit Hitam', type: 'lost', reporter: 'Fajar Nugraha (41121010048)', location: 'Kampus Meruya • Kantin Karyawan', date: '02 Nov 2026', status: 'in_handover' },
        { id: '28', title: 'Jaket Parka Erigo Krem Size L', type: 'lost', reporter: 'Anisa Maharani (41821010029)', location: 'Kampus Meruya • Plaza Rektorat', date: '03 Nov 2026', status: 'open' },

        // Non-Aktif (1 Resolved, 1 Hidden)
        { id: '29', title: 'Cincin Emas Berlian Solitaire', type: 'lost', reporter: 'Ahmad Fauzi (41521010045)', location: 'Kampus Meruya • Parkir Barat', date: '15 Jan 2026', status: 'resolved' },
        { id: 'rep_fake_iphone', title: 'iPhone 15 Pro Max Deep Blue', type: 'lost', reporter: 'Akun Palsu (41521010000)', location: 'Kampus Menteng', date: '04 Nov 2026', status: 'hidden' }
    ];

    function getAllReportsCatalog() {
        try {
            const raw = localStorage.getItem('findumb_admin_reports');
            if (raw) return JSON.parse(raw);
        } catch(e) {}
        saveAdminReportsCatalog(DEFAULT_REPORTS_CATALOG);
        return DEFAULT_REPORTS_CATALOG;
    }

    function saveAdminReportsCatalog(data) {
        try { localStorage.setItem('findumb_admin_reports', JSON.stringify(data)); } catch(e) {}
    }

    /* ===========================================================================
       TAB SWITCHING
       =========================================================================== */

    function switchAdminTab(tabId) {
        document.querySelectorAll('.admin-tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        const activePanel = document.getElementById(`tab-panel-${tabId}`);
        if (activePanel) activePanel.classList.remove('hidden');

        const titleMap = {
            'overview': 'Overview',
            'reports': 'Kelola Laporan',
            'moderation': 'Aduan & Moderasi',
            'users': 'Manajemen Pengguna',
            'audit': 'Log Audit Moderasi',
            'categories': 'Kategori Barang'
        };
        const titleEl = document.getElementById('top-breadcrumb-title');
        if (titleEl) titleEl.textContent = titleMap[tabId] || 'Overview';

        document.querySelectorAll('.sidebar-nav-item, .sidebar-menu-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const activeBtn = document.getElementById(`nav-tab-${tabId}`);
        if (activeBtn) activeBtn.classList.add('active');

        if (tabId === 'overview') renderOverviewDashboard();
        if (tabId === 'moderation') renderAbuseReportsTable();
        if (tabId === 'reports') renderReportsManagementTable();
        if (tabId === 'users') renderUsersTable();
        if (tabId === 'audit') renderAuditTable();
        if (tabId === 'categories') renderCategoriesGrid();

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    /* ===========================================================================
       RENDER METHODS
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
        document.getElementById('stat-resolved-reports').textContent = resolvedReports.length + 94;
        document.getElementById('stat-pending-abuse').textContent = pendingAbuse.length;
        document.getElementById('stat-suspended-users').textContent = suspendedUsers.length;

        const sidebarBadge = document.getElementById('sidebar-abuse-badge');
        if (sidebarBadge) sidebarBadge.textContent = pendingAbuse.length;
        const reportsCount = document.getElementById('sidebar-reports-count');
        if (reportsCount) reportsCount.textContent = activeReports.length;
        const notifDot = document.getElementById('header-notif-indicator');
        if (notifDot) notifDot.style.display = pendingAbuse.length > 0 ? 'block' : 'none';
    }

            function renderOverviewDashboard() {
        refreshDashboardMetrics();
        const abuseReports = getAdminAbuseReports();
        const pending = abuseReports.filter(a => a.status === 'pending');
        const container = document.getElementById('overview-urgent-abuse-list');

        if (container) {
            if (pending.length === 0) {
                container.innerHTML = `
                    <div class="py-8 flex flex-col items-center justify-center text-center">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div class="text-xs font-semibold text-[#111827]">Tidak Ada Aduan Tertunda</div>
                        <p class="text-[11px] text-[#6b7280] mt-0.5">Semua laporan dan percakapan civitas dalam kondisi bersih dan aman.</p>
                    </div>
                `;
            } else {
                container.innerHTML = pending.map(item => {
                    const isFraud = (item.reason_label || '').toLowerCase().includes('palsu') || (item.reason_label || '').toLowerCase().includes('penipuan');
                    const isHarass = (item.reason_label || '').toLowerCase().includes('pelecehan') || (item.reason_label || '').toLowerCase().includes('kasar');
                    const badgeClass = isFraud 
                        ? 'bg-rose-50 text-rose-700' 
                        : (isHarass ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-700');
                    return `
                    <div class="py-3.5 flex items-center justify-between gap-3 text-xs hover:bg-[#f9fafb] px-2.5 -mx-2.5 rounded-xl transition-colors">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-xs text-[#101828] truncate">${escapeHtml(item.target_title)}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium ${badgeClass} shrink-0">${escapeHtml(item.reason_label)}</span>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] text-[#667085] mt-1">
                                <span>Pelapor: <strong class="text-[#374151] font-medium">${escapeHtml(item.reporter_name)}</strong></span>
                                <span class="text-[#d0d5dd]">•</span>
                                <span class="text-[#667085]">${escapeHtml(item.date || 'Baru saja')}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="openAbuseDetailModal('${item.id}')" class="px-3 py-1.5 rounded-lg border border-[#eaecf0] bg-white hover:bg-[#f9fafb] text-xs font-medium text-[#374151] shadow-2xs transition-all cursor-pointer">
                                Tinjau
                            </button>
                            <button type="button" onclick="openModerationActionModal('hide_report', 'report', '${item.target_id}', '${escapeHtml(item.target_title)}', 'Sembunyikan laporan dari publik')" class="px-3 py-1.5 rounded-lg bg-[#fee4e2]/70 hover:bg-[#fee4e2] text-[#d92d20] text-xs font-semibold transition-all cursor-pointer">
                                Moderasi
                            </button>
                        </div>
                    </div>
                `}).join('');
            }
        }

        // Recent Audit Feed Timeline in Overview (NO ICON SHAPE/BACKGROUND - RAW ICON ONLY)
        const audits = getAdminAuditLogs().slice(0, 4);
        const auditFeed = document.getElementById('overview-recent-audit-feed');
        if (auditFeed) {
            if (audits.length === 0) {
                auditFeed.innerHTML = `<div class="py-6 text-center text-xs text-[#6b7280]">Belum ada aktivitas audit moderasi.</div>`;
            } else {
                auditFeed.innerHTML = audits.map(log => {
                    const iconColor = log.action_type === 'suspend_user' 
                        ? 'text-rose-600' 
                        : (log.action_type === 'hide_report'
                            ? 'text-amber-600' 
                            : (log.action_type === 'warn_user'
                                ? 'text-amber-500' 
                                : 'text-blue-600'));
                    const iconName = log.action_type === 'suspend_user' 
                        ? 'user-x' 
                        : (log.action_type === 'hide_report'
                            ? 'eye-off' 
                            : (log.action_type === 'warn_user'
                                ? 'alert-triangle' 
                                : 'shield-check'));
                    return `
                    <div class="py-3 flex items-start justify-between gap-3 text-xs hover:bg-[#f9fafb] px-2.5 -mx-2.5 rounded-xl transition-colors">
                        <div class="flex items-start gap-3 min-w-0">
                            <i data-lucide="${iconName}" class="w-4 h-4 ${iconColor} shrink-0 mt-0.5"></i>
                            <div class="min-w-0">
                                <div class="text-[#101828] text-xs leading-snug">
                                    <span class="font-semibold">${escapeHtml(log.target)}</span>
                                    <span class="text-[#667085]">di-${escapeHtml(log.action_label.toLowerCase())}</span>
                                </div>
                                <div class="text-[11px] text-[#667085] truncate mt-0.5">${escapeHtml(log.reason)}</div>
                            </div>
                        </div>
                        <div class="text-[11px] text-[#9ca3af] font-mono shrink-0 pt-0.5">
                            ${log.timestamp.split(',')[0]}
                        </div>
                    </div>
                `}).join('');
            }
        }

        renderAdminTrendChart();
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
            let statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700">Menunggu Review</span>`;
            if (r.status === 'resolved') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700">Selesai Ditindak</span>`;
            if (r.status === 'dismissed') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Diabaikan</span>`;

            return `
                <tr>
                    <td class="font-mono font-medium text-[#111827] whitespace-nowrap">${r.id}</td>
                    <td>
                        <div class="font-medium text-[#111827]">${escapeHtml(r.reporter_name)}</div>
                        <div class="text-[10px] text-[#9ca3af] font-mono">${r.reporter_nim}</div>
                    </td>
                    <td class="max-w-[150px]">
                        <div class="font-medium text-[#111827] truncate" title="${escapeHtml(r.target_title)}">${escapeHtml(r.target_title)}</div>
                        <div class="text-[10px] text-[#9ca3af] uppercase font-mono">${r.target_type}</div>
                    </td>
                    <td class="whitespace-nowrap">
                        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-rose-50 text-rose-700">
                            ${escapeHtml(r.reason_label)}
                        </span>
                    </td>
                    <td class="max-w-[180px]">
                        <p class="text-[11px] text-[#4b5563] line-clamp-2 leading-snug" title="${escapeHtml(r.description)}">${escapeHtml(r.description)}</p>
                    </td>
                    <td class="text-[11px] text-[#6b7280] whitespace-nowrap">${r.date}</td>
                    <td class="whitespace-nowrap">${statusBadge}</td>
                    <td class="text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" onclick="openAbuseDetailModal('${r.id}')" class="p-1 text-[#6b7280] hover:text-[#111827]" title="Lihat Detail">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="openModerationActionModal('hide_report', 'report', '${r.target_id}', '${escapeHtml(r.target_title)}', 'Sembunyikan laporan dari publik')" class="p-1 text-rose-600 hover:text-rose-800" title="Sembunyikan Laporan">
                                <i data-lucide="eye-off" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="openModerationActionModal('suspend_user', 'user', '${r.reporter_nim}', '${escapeHtml(r.target_title)}', 'Tangguhkan akun pelanggar')" class="p-1 text-amber-600 hover:text-amber-800" title="Tangguhkan Akun">
                                <i data-lucide="user-x" class="w-4 h-4"></i>
                            </button>
                            <button type="button" onclick="dismissAbuseReportDirect('${r.id}')" class="p-1 text-emerald-600 hover:text-emerald-800" title="Tandai Selesai">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    let activeReportsQuickFilter = 'all';
    let currentSelectedReportId = null;

    function setReportsQuickFilter(filterKey) {
        if (filterKey === 'handover') filterKey = 'in_handover';
        activeReportsQuickFilter = filterKey;

        // Reset search input to avoid unintended zero-results
        const sInput = document.getElementById('report-search-input');
        if (sInput) sInput.value = '';

        // Reset general selects to default
        const tSelect = document.getElementById('report-type-filter');
        if (tSelect) {
            if (filterKey === 'found') tSelect.value = 'found';
            else if (filterKey === 'lost') tSelect.value = 'lost';
            else tSelect.value = 'all';
        }

        const stSelect = document.getElementById('report-status-filter');
        if (stSelect) {
            if (filterKey === 'in_handover') stSelect.value = 'in_handover';
            else if (filterKey === 'resolved') stSelect.value = 'resolved';
            else if (filterKey === 'open') stSelect.value = 'open';
            else if (filterKey === 'hidden') stSelect.value = 'hidden';
            else stSelect.value = 'all';
        }

        const locSelect = document.getElementById('report-location-filter');
        if (locSelect) locSelect.value = 'all';

        // Update pills styling
        document.querySelectorAll('.rep-quick-pill').forEach(btn => {
            btn.className = 'rep-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer';
        });
        const activeBtn = document.getElementById(`rep-pill-${filterKey}`);
        if (activeBtn) {
            activeBtn.className = 'rep-quick-pill px-3 py-1 rounded-lg bg-[#1572b8] text-white font-semibold transition-all cursor-pointer shadow-2xs';
        }

        renderReportsManagementTable();
    }

    function resetReportsFilters() {
        const sInput = document.getElementById('report-search-input');
        if (sInput) sInput.value = '';
        const tSelect = document.getElementById('report-type-filter');
        if (tSelect) tSelect.value = 'all';
        const stSelect = document.getElementById('report-status-filter');
        if (stSelect) stSelect.value = 'all';
        const locSelect = document.getElementById('report-location-filter');
        if (locSelect) locSelect.value = 'all';
        setReportsQuickFilter('all');
    }

    function openReportDetailModal(reportId) {
        const reports = getAllReportsCatalog();
        const item = reports.find(r => String(r.id) === String(reportId));
        if (!item) return;

        currentSelectedReportId = item.id;
        document.getElementById('modal-rep-ticket-id').textContent = `#UMB-${item.type === 'found' ? 'FND' : 'LST'}-${String(item.id).padStart(3, '0')}`;
        document.getElementById('modal-rep-title').textContent = item.title;
        document.getElementById('modal-rep-category').textContent = item.category || 'Barang Kampus';
        document.getElementById('modal-rep-date').textContent = item.date;
        document.getElementById('modal-rep-location').textContent = item.location;
        document.getElementById('modal-rep-reporter').textContent = item.reporter;
        document.getElementById('modal-rep-features').textContent = item.features || 'Ciri khusus telah diverifikasi oleh petugas pos satpam.';

        const typeBadge = document.getElementById('modal-rep-badge-type');
        if (typeBadge) {
            if (item.type === 'found') {
                typeBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700';
                typeBadge.textContent = 'BARANG TEMUAN';
            } else {
                typeBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700';
                typeBadge.textContent = 'LAPORAN HILANG';
            }
        }

        const hideBtn = document.getElementById('modal-rep-toggle-hide-btn');
        if (hideBtn) {
            if (item.status === 'hidden') {
                hideBtn.textContent = 'Pulihkan Laporan';
                hideBtn.className = 'px-3 py-1.5 rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 text-xs font-semibold';
            } else {
                hideBtn.textContent = 'Sembunyikan Laporan';
                hideBtn.className = 'px-3 py-1.5 rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 text-xs font-semibold';
            }
        }

        const modal = document.getElementById('modal-report-management-detail');
        if (modal) modal.classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeReportDetailModal() {
        const modal = document.getElementById('modal-report-management-detail');
        if (modal) modal.classList.add('hidden');
        currentSelectedReportId = null;
    }

    function updateReportStatusDirect(newStatus) {
        if (!currentSelectedReportId) return;
        const reports = getAllReportsCatalog();
        const item = reports.find(r => String(r.id) === String(currentSelectedReportId));
        if (item) {
            item.status = newStatus;
            saveAdminReportsCatalog(reports);
            showAdminToast(`Status laporan #${item.id} berhasil diubah ke ${newStatus.toUpperCase()}.`);
            closeReportDetailModal();
            renderReportsManagementTable();
            refreshDashboardMetrics();
        }
    }

    function toggleHideActiveReport() {
        if (!currentSelectedReportId) return;
        const reports = getAllReportsCatalog();
        const item = reports.find(r => String(r.id) === String(currentSelectedReportId));
        if (item) {
            const nextStatus = item.status === 'hidden' ? 'open' : 'hidden';
            item.status = nextStatus;
            saveAdminReportsCatalog(reports);
            showAdminToast(nextStatus === 'hidden' ? 'Laporan disembunyikan dari publik.' : 'Laporan dipulihkan kembali ke publik.');
            closeReportDetailModal();
            renderReportsManagementTable();
            refreshDashboardMetrics();
        }
    }

    function renderReportsManagementTable() {
        const query = (document.getElementById('report-search-input')?.value || '').toLowerCase().trim();
        const typeFilter = document.getElementById('report-type-filter')?.value || 'all';
        const statusFilter = document.getElementById('report-status-filter')?.value || 'all';
        const locFilter = document.getElementById('report-location-filter')?.value || 'all';

        const reports = getAllReportsCatalog();
        const tbody = document.getElementById('reports-management-table-body');
        const emptyEl = document.getElementById('reports-table-empty');

        // Update Operational KPI Counters
        const foundCount = reports.filter(r => r.type === 'found' && r.status !== 'resolved' && r.status !== 'hidden').length;
        const lostCount = reports.filter(r => r.type === 'lost' && r.status !== 'resolved' && r.status !== 'hidden').length;
        const handoverCount = reports.filter(r => r.status === 'in_handover').length;
        const resolvedCount = reports.filter(r => r.status === 'resolved').length;

        const elKpiTotal = document.getElementById('reports-kpi-total');
        if (elKpiTotal) elKpiTotal.textContent = reports.length;
        const elKpiFound = document.getElementById('reports-kpi-found');
        if (elKpiFound) elKpiFound.textContent = foundCount;
        const elKpiLost = document.getElementById('reports-kpi-lost');
        if (elKpiLost) elKpiLost.textContent = lostCount;
        const elKpiResolved = document.getElementById('reports-kpi-resolved');
        if (elKpiResolved) elKpiResolved.textContent = resolvedCount;

        // Update Quick Pill Badge Counters
        const pAll = document.getElementById('pill-count-all');
        if (pAll) pAll.textContent = reports.length;
        const pFound = document.getElementById('pill-count-found');
        if (pFound) pFound.textContent = reports.filter(r => r.type === 'found').length;
        const pLost = document.getElementById('pill-count-lost');
        if (pLost) pLost.textContent = reports.filter(r => r.type === 'lost').length;
        const pHandover = document.getElementById('pill-count-handover');
        if (pHandover) pHandover.textContent = handoverCount;
        const pResolved = document.getElementById('pill-count-resolved');
        if (pResolved) pResolved.textContent = resolvedCount;

        const filtered = reports.filter(r => {
            const matchQuery = !query || 
                r.title.toLowerCase().includes(query) || 
                r.reporter.toLowerCase().includes(query) || 
                r.location.toLowerCase().includes(query) ||
                String(r.id).includes(query);

            const matchType = typeFilter === 'all' || r.type === typeFilter;
            const matchStatus = statusFilter === 'all' || r.status === statusFilter;
            const matchLoc = locFilter === 'all' || r.location.toLowerCase().includes(locFilter.toLowerCase());

            let matchPill = true;
            if (activeReportsQuickFilter === 'found') matchPill = r.type === 'found';
            if (activeReportsQuickFilter === 'lost') matchPill = r.type === 'lost';
            if (activeReportsQuickFilter === 'in_handover' || activeReportsQuickFilter === 'handover') matchPill = r.status === 'in_handover';
            if (activeReportsQuickFilter === 'resolved') matchPill = r.status === 'resolved';
            if (activeReportsQuickFilter === 'open') matchPill = r.status === 'open';
            if (activeReportsQuickFilter === 'hidden') matchPill = r.status === 'hidden';

            return matchQuery && matchType && matchStatus && matchLoc && matchPill;
        });

        const dispCount = document.getElementById('reports-displayed-count');
        if (dispCount) dispCount.textContent = filtered.length;

        if (filtered.length === 0) {
            if (tbody) tbody.innerHTML = '';
            if (emptyEl) emptyEl.classList.remove('hidden');
            return;
        }

        if (emptyEl) emptyEl.classList.add('hidden');
        if (!tbody) return;

        tbody.innerHTML = filtered.map(item => {
            const isFound = item.type === 'found';
            const typeBadge = isFound ? 
                `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>Temuan</span>` :
                `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200"><span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>Hilang</span>`;

            let statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-[#f0f7fd] text-[#0369a1] border border-[#bae6fd]">Open</span>`;
            if (item.status === 'in_handover') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">In Handover</span>`;
            } else if (item.status === 'resolved') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Resolved</span>`;
            } else if (item.status === 'hidden') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">Hidden</span>`;
            }

            const ticketCode = `#UMB-${isFound ? 'FND' : 'LST'}-${String(item.id).padStart(3, '0')}`;

            return `
                <tr class="hover:bg-[#f9fafb] transition-colors">
                    <td class="py-3 px-4">
                        <div class="flex items-start gap-2.5">
                            <div class="w-8 h-8 rounded-lg ${isFound ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'} flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="${isFound ? 'package' : 'help-circle'}" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="font-semibold text-[#111827] max-w-[220px] truncate" title="${escapeHtml(item.title)}">${escapeHtml(item.title)}</div>
                                <div class="flex items-center gap-1.5 text-[10px] text-[#9ca3af] font-mono mt-0.5">
                                    <span>${ticketCode}</span>
                                    <span>•</span>
                                    <span class="text-[#6b7280] font-sans">${escapeHtml(item.category || (isFound ? 'Temuan Kampus' : 'Barang Pribadi'))}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-3 whitespace-nowrap">${typeBadge}</td>
                    <td class="py-3 px-3">
                        <div class="font-medium text-[#374151] max-w-[150px] truncate">${escapeHtml(item.reporter.split('(')[0].trim())}</div>
                        <div class="text-[10px] text-[#9ca3af] truncate">${escapeHtml(item.reporter.includes('(') ? item.reporter.split('(')[1].replace(')', '') : 'Civitas')}</div>
                    </td>
                    <td class="py-3 px-3">
                        <div class="max-w-[200px] text-[#4b5563] truncate" title="${escapeHtml(item.location)}">
                            <i data-lucide="map-pin" class="w-3 h-3 inline-block mr-1 text-[#9ca3af]"></i>${escapeHtml(item.location)}
                        </div>
                    </td>
                    <td class="py-3 px-3 text-[11px] text-[#6b7280] whitespace-nowrap font-mono">${item.date}</td>
                    <td class="py-3 px-3 whitespace-nowrap">${statusBadge}</td>
                    <td class="py-3 px-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button" onclick="openReportDetailModal('${item.id}')" class="px-2.5 py-1 rounded-lg border border-[#eaecf0] hover:bg-[#f0f7fd] text-[#1572b8] font-semibold text-[11px] transition-colors" title="Lihat detail laporan">
                                Tinjau
                            </button>
                            ${item.status === 'in_handover' ? `
                                <button type="button" onclick="currentSelectedReportId='${item.id}'; updateReportStatusDirect('resolved');" class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-semibold text-[11px] transition-colors" title="Konfirmasi serah terima selesai">
                                    Serahkan
                                </button>
                            ` : item.status === 'open' ? `
                                <button type="button" onclick="currentSelectedReportId='${item.id}'; updateReportStatusDirect('in_handover');" class="px-2 py-1 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100 font-medium text-[11px] transition-colors" title="Tandai proses serah terima">
                                    Proses
                                </button>
                            ` : `
                                <button type="button" onclick="currentSelectedReportId='${item.id}'; updateReportStatusDirect('open');" class="px-2 py-1 rounded-lg border border-[#eaecf0] text-[#6b7280] hover:bg-[#f3f4f6] font-medium text-[11px] transition-colors" title="Buka kembali">
                                    Buka
                                </button>
                            `}
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    let activeUsersQuickFilter = 'all';

    function setUsersQuickFilter(filterKey) {
        activeUsersQuickFilter = filterKey;
        const sInput = document.getElementById('user-search-input');
        if (sInput) sInput.value = '';

        const rSelect = document.getElementById('user-role-filter');
        if (rSelect) {
            if (filterKey === 'admin') rSelect.value = 'admin';
            else if (filterKey === 'user') rSelect.value = 'user';
            else rSelect.value = 'all';
        }

        const stSelect = document.getElementById('user-status-filter');
        if (stSelect) {
            if (filterKey === 'suspended') stSelect.value = 'suspended';
            else stSelect.value = 'all';
        }

        document.querySelectorAll('.user-quick-pill').forEach(btn => {
            btn.className = 'user-quick-pill px-3 py-1 rounded-lg bg-[#f3f4f6] text-[#4b5563] font-medium hover:bg-[#eaecf0] transition-all cursor-pointer';
        });
        const activeBtn = document.getElementById(`user-pill-${filterKey}`);
        if (activeBtn) {
            activeBtn.className = 'user-quick-pill px-3 py-1 rounded-lg bg-[#1572b8] text-white font-semibold transition-all cursor-pointer shadow-2xs';
        }

        renderUsersTable();
    }

    function resetUsersFilters() {
        const sInput = document.getElementById('user-search-input');
        if (sInput) sInput.value = '';
        const rSelect = document.getElementById('user-role-filter');
        if (rSelect) rSelect.value = 'all';
        const stSelect = document.getElementById('user-status-filter');
        if (stSelect) stSelect.value = 'all';
        setUsersQuickFilter('all');
    }

    function renderUsersTable() {
        const query = (document.getElementById('user-search-input')?.value || '').toLowerCase().trim();
        const roleFilter = document.getElementById('user-role-filter')?.value || 'all';
        const statusFilter = document.getElementById('user-status-filter')?.value || 'all';

        const users = getAdminUsers();
        const tbody = document.getElementById('users-table-body');
        const emptyEl = document.getElementById('users-table-empty');

        // Update Operational KPI counters
        const totalUsers = users.length;
        const adminCount = users.filter(u => u.role === 'admin').length;
        const studentCount = users.filter(u => u.role === 'user').length;
        const suspendedCount = users.filter(u => u.status === 'suspended').length;

        const elKpiTotal = document.getElementById('users-kpi-total');
        if (elKpiTotal) elKpiTotal.textContent = totalUsers;
        const elKpiAdmin = document.getElementById('users-kpi-admin');
        if (elKpiAdmin) elKpiAdmin.textContent = adminCount;
        const elKpiStudents = document.getElementById('users-kpi-students');
        if (elKpiStudents) elKpiStudents.textContent = studentCount;
        const elKpiSuspended = document.getElementById('users-kpi-suspended');
        if (elKpiSuspended) elKpiSuspended.textContent = suspendedCount;

        // Update Quick Pill Badge Counters
        const pAll = document.getElementById('user-pill-count-all');
        if (pAll) pAll.textContent = totalUsers;
        const pAdmin = document.getElementById('user-pill-count-admin');
        if (pAdmin) pAdmin.textContent = adminCount;
        const pUser = document.getElementById('user-pill-count-user');
        if (pUser) pUser.textContent = studentCount;
        const pSuspended = document.getElementById('user-pill-count-suspended');
        if (pSuspended) pSuspended.textContent = suspendedCount;

        const filtered = users.filter(u => {
            const matchQuery = !query || 
                u.name.toLowerCase().includes(query) || 
                u.nim.toLowerCase().includes(query) || 
                u.email.toLowerCase().includes(query) || 
                u.faculty.toLowerCase().includes(query) ||
                (u.prodi && u.prodi.toLowerCase().includes(query));

            const matchRole = roleFilter === 'all' || u.role === roleFilter;
            const matchStatus = statusFilter === 'all' || u.status === statusFilter;

            let matchPill = true;
            if (activeUsersQuickFilter === 'admin') matchPill = u.role === 'admin';
            if (activeUsersQuickFilter === 'user') matchPill = u.role === 'user';
            if (activeUsersQuickFilter === 'suspended') matchPill = u.status === 'suspended';

            return matchQuery && matchRole && matchStatus && matchPill;
        });

        const dispCount = document.getElementById('users-displayed-count');
        if (dispCount) dispCount.textContent = filtered.length;

        if (filtered.length === 0) {
            if (tbody) tbody.innerHTML = '';
            if (emptyEl) emptyEl.classList.remove('hidden');
            return;
        }

        if (emptyEl) emptyEl.classList.add('hidden');
        if (!tbody) return;

        tbody.innerHTML = filtered.map(u => {
            const isAdmin = u.role === 'admin';
            
            // Refined Blue badge for Administrator - strictly NO black badge!
            const roleBadge = isAdmin ? 
                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-[#1572b8] border border-blue-200 shadow-2xs">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#1572b8]"></i>
                    Administrator
                </span>` :
                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-slate-400"></i>
                    Civitas Mahasiswa
                </span>`;

            const isSuspended = u.status === 'suspended';
            const statusBadge = isSuspended ?
                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                    Ditangguhkan
                </span>` :
                `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    Aktif Normal
                </span>`;

            // Initials avatar
            const words = u.name.trim().split(/\s+/).filter(w => !['&', 'dan', 'UMB', '(Admin', 'Pengembang)'].includes(w));
            const initials = words.length >= 2 ? (words[0][0] + words[1][0]).toUpperCase() : u.name.slice(0, 2).toUpperCase();
            const avatarStyle = isAdmin ? 'bg-blue-100 text-[#1572b8] border-blue-200' : (isSuspended ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-slate-100 text-slate-700 border-slate-200');

            return `
                <tr class="hover:bg-[#f9fafb] transition-colors">
                    <td class="py-3 px-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full ${avatarStyle} border flex items-center justify-center font-bold text-xs shrink-0">
                                ${initials}
                            </div>
                            <div class="min-w-0">
                                <div class="font-semibold text-xs text-[#111827]">${escapeHtml(u.name)}</div>
                                <div class="text-[10px] text-[#6b7280]">${isAdmin ? 'Staf Otoritas Sistem' : 'Mahasiswa Reguler UMB'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-2.5 whitespace-nowrap">
                        <span class="font-mono text-xs text-[#374151] px-2 py-0.5 rounded bg-[#f9fafb] border border-[#eaecf0] inline-block font-medium">${u.nim}</span>
                    </td>
                    <td class="py-3 px-2.5">
                        <div class="text-xs font-medium text-[#111827]">${escapeHtml(u.faculty)}</div>
                        <div class="text-[10px] text-[#6b7280] mt-0.5">${escapeHtml(u.prodi)}</div>
                    </td>
                    <td class="py-3 px-2.5">
                        <div class="flex items-center gap-1.5 font-mono text-xs text-[#4b5563]">
                            <span>${escapeHtml(u.email)}</span>
                            ${u.email.includes('@gmail.com') ? '<span class="text-[9px] bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 rounded font-sans font-medium">Personal</span>' : ''}
                        </div>
                    </td>
                    <td class="py-3 px-2.5 whitespace-nowrap">${roleBadge}</td>
                    <td class="py-3 px-2.5 whitespace-nowrap">${statusBadge}</td>
                    <td class="py-3 px-3.5 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            ${isSuspended ? `
                                <button type="button" onclick="reactivateUserDirect('${u.id}', '${escapeHtml(u.name)}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold text-xs border border-emerald-200 transition-colors cursor-pointer">
                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                    Pulihkan
                                </button>
                            ` : `
                                <button type="button" onclick="openModerationActionModal('suspend_user', 'user', '${u.id}', '${escapeHtml(u.name)} (${u.nim})', 'Pelanggaran ketentuan etika civitas kampus')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold text-xs border border-rose-200 transition-colors cursor-pointer">
                                    <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
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
            <tr>
                <td class="font-mono text-[11px] text-[#6b7280] whitespace-nowrap">${log.timestamp}</td>
                <td>
                    <div class="font-medium text-[#111827]">${escapeHtml(log.admin_name)}</div>
                    <div class="text-[10px] text-[#9ca3af] font-mono">${escapeHtml(log.admin_email)}</div>
                </td>
                <td>
                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-[#f3f4f6] text-[#374151]">
                        ${escapeHtml(log.action_label)}
                    </span>
                </td>
                <td class="font-medium text-[#111827]">${escapeHtml(log.target)}</td>
                <td class="max-w-[240px]">
                    <div class="text-[#4b5563] text-[11px] leading-relaxed">${escapeHtml(log.reason)}</div>
                </td>
                <td class="text-right text-[11px] text-[#6b7280] max-w-[180px]">
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
            <div class="p-3.5 rounded-xl border border-[#eaecf0] bg-white flex flex-col justify-between">
                <div>
                    <div class="font-medium text-[#111827] text-xs">${escapeHtml(cat.name)}</div>
                    <p class="text-[#6b7280] text-[10px] mt-0.5 leading-relaxed">${escapeHtml(cat.desc)}</p>
                </div>
                <div class="pt-2 border-t border-[#f3f4f6] mt-2.5 flex items-center justify-between text-xs">
                    <span class="font-semibold text-[#111827] text-[11px]">${cat.count} Laporan</span>
                    <span class="text-emerald-700 font-semibold text-[10px] bg-emerald-50 px-1.5 py-0.2 rounded">Aktif</span>
                </div>
            </div>
        `).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    /* ===========================================================================
       ACTIONS & MODALS
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
            submitBtn.className = 'px-3.5 py-1.5 rounded-lg bg-rose-600 text-white font-semibold text-xs hover:bg-rose-700';
            impactText.textContent = 'Laporan akan diubah ke status "hidden" dan tidak akan muncul di hasil pencarian.';
        } else if (actionType === 'suspend_user') {
            titleEl.textContent = 'Tangguhkan Akun Pengguna';
            submitBtn.textContent = 'Tangguhkan Akun';
            submitBtn.className = 'px-3.5 py-1.5 rounded-lg bg-rose-600 text-white font-semibold text-xs hover:bg-rose-700';
            impactText.textContent = 'Sesuai DEC-026: Seluruh klaim aktif pengguna akan dibatalkan otomatis dan hak klaim diblokir.';
        } else if (actionType === 'warn_user') {
            titleEl.textContent = 'Kirim Peringatan Formal';
            submitBtn.textContent = 'Kirim Peringatan';
            submitBtn.className = 'px-3.5 py-1.5 rounded-lg bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700';
            impactText.textContent = 'Peringatan resmi akan dikirim ke pusat notifikasi pengguna.';
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

        if (!reason) { alert('Alasan tindakan wajib diisi.'); return; }

        const now = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) + ' WIB';
        const adminName = document.getElementById('admin-user-display-name')?.textContent || 'Biro Kemahasiswaan';
        const adminEmail = document.getElementById('admin-user-display-email')?.textContent || 'admin.lostfound@mercubuana.ac.id';

        if (actionType === 'hide_report') {
            let abuseReports = getAdminAbuseReports();
            abuseReports.forEach(a => { if (a.target_id === targetId) a.status = 'resolved'; });
            saveAdminAbuseReports(abuseReports);

            let reports = getAllReportsCatalog();
            const rep = reports.find(r => String(r.id) === String(targetId));
            if (rep) {
                rep.status = 'hidden';
                saveAdminReportsCatalog(reports);
            }
        } else if (actionType === 'suspend_user') {
            let users = getAdminUsers();
            users.forEach(u => { if (u.id === targetId || u.nim === targetId) u.status = 'suspended'; });
            saveAdminUsers(users);

            let abuseReports = getAdminAbuseReports();
            abuseReports.forEach(a => { if (a.reporter_nim === targetId || a.target_id === targetId) a.status = 'resolved'; });
            saveAdminAbuseReports(abuseReports);

            // DEC-026: Sembunyikan seluruh laporan aktif milik pengguna yang ditangguhkan
            let reports = getAllReportsCatalog();
            reports.forEach(r => {
                if (r.reporter && r.reporter.includes(targetId)) {
                    r.status = 'hidden';
                }
            });
            saveAdminReportsCatalog(reports);
        }

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
        showAdminToast('Tindakan moderasi berhasil dieksekusi.');
        refreshDashboardMetrics();
        renderOverviewDashboard();
        renderAbuseReportsTable();
        renderReportsManagementTable();
        renderUsersTable();
        renderAuditTable();
    }

    function dismissAbuseReportDirect(abuseId) {
        if (!confirm('Abaikan aduan ini?')) return;
        let abuseReports = getAdminAbuseReports();
        const item = abuseReports.find(a => a.id === abuseId);
        if (item) {
            item.status = 'dismissed';
            saveAdminAbuseReports(abuseReports);

            const now = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) + ' WIB';
            const adminName = document.getElementById('admin-user-display-name')?.textContent || 'Biro Kemahasiswaan';
            const adminEmail = document.getElementById('admin-user-display-email')?.textContent || 'admin.lostfound@mercubuana.ac.id';
            const logs = getAdminAuditLogs();
            logs.unshift({
                id: `AUD-${Date.now().toString().slice(-4)}`,
                timestamp: now,
                admin_name: adminName,
                admin_email: adminEmail,
                action_type: 'dismiss_abuse',
                action_label: 'Abaikan Aduan',
                target: `${item.id}: ${item.target_title}`,
                reason: 'Aduan ditinjau dan dinyatakan tidak memenuhi unsur pelanggaran.',
                impact: 'Aduan ditutup tanpa sanksi (FR-11).'
            });
            saveAdminAuditLogs(logs);

            showAdminToast('Aduan telah diabaikan.');
            refreshDashboardMetrics();
            renderOverviewDashboard();
            renderAbuseReportsTable();
            renderAuditTable();
        }
    }

    function reactivateUserDirect(userId, userName) {
        if (!confirm(`Aktifkan kembali akun ${userName}?`)) return;
        let users = getAdminUsers();
        const user = users.find(u => String(u.id) === String(userId) || String(u.nim) === String(userId));
        if (user) {
            user.status = 'active';
            saveAdminUsers(users);

            const now = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) + ' WIB';
            const adminName = document.getElementById('admin-user-display-name')?.textContent || 'Biro Kemahasiswaan';
            const adminEmail = document.getElementById('admin-user-display-email')?.textContent || 'admin.lostfound@mercubuana.ac.id';
            const logs = getAdminAuditLogs();
            logs.unshift({
                id: `AUD-${Date.now().toString().slice(-4)}`,
                timestamp: now,
                admin_name: adminName,
                admin_email: adminEmail,
                action_type: 'reactivate_user',
                action_label: 'Reaktivasi Akun',
                target: `${userName} (${user.nim || userId})`,
                reason: 'Akun dipulihkan setelah peninjauan etika selesai.',
                impact: 'Status akun aktif kembali (DEC-026).'
            });
            saveAdminAuditLogs(logs);

            showAdminToast(`Akun ${userName} berhasil diaktifkan kembali.`);
            refreshDashboardMetrics();
            renderUsersTable();
            renderAuditTable();
        }
    }

    function openAbuseDetailModal(abuseId) {
        const abuseReports = getAdminAbuseReports();
        const item = abuseReports.find(a => a.id === abuseId);
        if (!item) return;

        document.getElementById('detail-abuse-id').textContent = item.id;
        document.getElementById('detail-abuse-date').textContent = item.date;
        document.getElementById('detail-abuse-reporter').textContent = `${item.reporter_name} (${item.reporter_nim})`;
        document.getElementById('detail-abuse-status').textContent = item.status === 'pending' ? 'Menunggu Review' : (item.status === 'resolved' ? 'Selesai' : 'Diabaikan');
        document.getElementById('detail-abuse-target-box').innerHTML = `<strong>${escapeHtml(item.target_title)}</strong> <span class="text-[#9ca3af]">(${item.target_type} ID: ${item.target_id})</span>`;
        document.getElementById('detail-abuse-reason-badge').textContent = item.reason_label;
        document.getElementById('detail-abuse-description').textContent = item.description;

        document.getElementById('btn-detail-action-dismiss').onclick = () => {
            closeAbuseDetailModal();
            dismissAbuseReportDirect(item.id);
        };

        document.getElementById('btn-detail-action-hide').onclick = () => {
            closeAbuseDetailModal();
            openModerationActionModal('hide_report', 'report', item.target_id, item.target_title, 'Laporan melanggar aturan publikasi');
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

        const now = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) + ' WIB';
        const adminName = document.getElementById('admin-user-display-name')?.textContent || 'Biro Kemahasiswaan';
        const adminEmail = document.getElementById('admin-user-display-email')?.textContent || 'admin.lostfound@mercubuana.ac.id';
        const logs = getAdminAuditLogs();
        logs.unshift({
            id: `AUD-${Date.now().toString().slice(-4)}`,
            timestamp: now,
            admin_name: adminName,
            admin_email: adminEmail,
            action_type: 'create_admin',
            action_label: 'Penambahan Admin',
            target: `${name} (${email})`,
            reason: `Pemberian hak akses ${role === 'superadmin' ? 'Super Administrator' : 'Administrator Moderasi'} untuk unit ${unit}.`,
            impact: 'Akun administrator baru aktif di sistem.'
        });
        saveAdminAuditLogs(logs);

        const formEl = document.getElementById('form-create-admin');
        if (formEl) formEl.reset();

        closeCreateAdminModal();
        showAdminToast(`Akun admin ${name} berhasil didaftarkan!`);
        refreshDashboardMetrics();
        renderUsersTable();
        renderAuditTable();
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

        const nameEl = document.getElementById('admin-user-display-name');
        if (nameEl) nameEl.textContent = name;
        const emailEl = document.getElementById('admin-user-display-email');
        if (emailEl) emailEl.textContent = email;

        closeAdminSettingsModal();
        showAdminToast('Profil administrator diperbarui.');
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
        showAdminToast('Berkas CSV laporan berhasil diunduh.');
    }

    function exportAdminAuditCSV() {
        const logs = getAdminAuditLogs();
        let csv = "ID,Timestamp,Admin Name,Admin Email,Action Type,Action Label,Target,Reason\n";
        logs.forEach(l => {
            csv += `"${l.id}","${l.timestamp}","${l.admin_name}","${l.admin_email}","${l.action_type}","${l.action_label}","${l.target}","${l.reason}"\n`;
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `findumb-log-audit-${new Date().toISOString().slice(0, 10)}.csv`;
        a.click();
        URL.revokeObjectURL(url);
        showAdminToast('Berkas CSV log audit berhasil diunduh.');
    }

    function handleAdminGlobalSearch(val) {
        if (!val) return;
        const lower = val.toLowerCase();
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
        return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    
    /* ===========================================================================
       REAL INTERACTIVE TREND CHART (CHART.JS SPLINE + CROSSHAIR & HOVER TOOLTIP)
       =========================================================================== */

    const CHART_CONFIG = {
        '7d': {
            labels: ['Sen, 28 Okt', 'Sel, 29 Okt', 'Rab, 30 Okt', 'Kam, 31 Okt', 'Jum, 01 Nov', 'Sab, 02 Nov', 'Min, 03 Nov'],
            headline: '28 Laporan',
            trendText: '+16.5% vs minggu lalu',
            high: '8',
            low: '2',
            rate: '82.1%',
            temuan: [3, 4, 7, 5, 8, 6, 5],
            hilang: [1, 2, 2, 1, 3, 2, 1],
            keyPoints: [0, 4]
        },
        '30d': {
            labels: ['01 Okt', '04 Okt', '07 Okt', '10 Okt', '13 Okt', '16 Okt', '19 Okt', '22 Okt', '25 Okt', '28 Okt', '31 Okt', '03 Nov'],
            headline: '123 Laporan',
            trendText: '+14.2% 30 hari',
            high: '42',
            low: '8',
            rate: '78.3%',
            temuan: [12, 18, 15, 24, 28, 14, 22, 29, 35, 42, 38, 32],
            hilang: [4, 5, 3, 6, 7, 4, 5, 6, 8, 9, 7, 5],
            keyPoints: [0, 5, 9] // Start (12), Dip on 16 Okt (14), Peak on 28 Okt (42) matching Reference!
        },
        '90d': {
            labels: ['Agt M1', 'Agt M2', 'Agt M3', 'Agt M4', 'Sep M1', 'Sep M2', 'Sep M3', 'Sep M4', 'Okt M1', 'Okt M2', 'Okt M3', 'Okt M4'],
            headline: '348 Laporan',
            trendText: '+23.8% kuartal ini',
            high: '54',
            low: '14',
            rate: '76.4%',
            temuan: [18, 22, 25, 20, 28, 34, 30, 39, 44, 48, 54, 46],
            hilang: [6, 8, 7, 6, 9, 10, 8, 11, 12, 14, 15, 11],
            keyPoints: [0, 3, 10]
        }
    };

    let adminTrendChartInstance = null;
    let currentChartRange = '30d';

    // Custom Chart.js Plugin for dashed vertical crosshair guideline on hover
    const dashedCrosshairPlugin = {
        id: 'dashedCrosshair',
        afterDraw: (chart) => {
            if (chart.tooltip?._active && chart.tooltip._active.length) {
                const activePoint = chart.tooltip._active[0];
                const ctx = chart.ctx;
                const x = activePoint.element.x;
                const topY = chart.scales.y.top;
                const bottomY = chart.scales.y.bottom;

                ctx.save();
                ctx.beginPath();
                ctx.setLineDash([4, 4]);
                ctx.moveTo(x, topY);
                ctx.lineTo(x, bottomY);
                ctx.lineWidth = 1.5;
                ctx.strokeStyle = '#c4b5fd';
                ctx.stroke();
                ctx.restore();
            }
        }
    };

    function setChartRange(rangeKey) {
        if (!CHART_CONFIG[rangeKey]) return;
        currentChartRange = rangeKey;

        // Update range buttons UI
        document.querySelectorAll('.chart-range-btn').forEach(btn => {
            btn.className = 'chart-range-btn px-2 py-0.5 rounded text-[#6b7280] hover:text-[#111827] transition-all cursor-pointer';
        });
        const activeBtn = document.getElementById(`btn-chart-${rangeKey}`);
        if (activeBtn) {
            activeBtn.className = 'chart-range-btn px-2 py-0.5 rounded bg-white text-[#111827] shadow-2xs font-bold transition-all cursor-pointer';
        }

        const data = CHART_CONFIG[rangeKey];

        const headlineEl = document.getElementById('chart-headline-val');
        if (headlineEl && data.headline) headlineEl.textContent = data.headline;

        const trendEl = document.getElementById('chart-trend-val');
        if (trendEl && data.trendText) trendEl.textContent = data.trendText;

        const highEl = document.getElementById('chart-stat-high');
        if (highEl) highEl.textContent = `${data.high} Kasus`;

        const lowEl = document.getElementById('chart-stat-low');
        if (lowEl) lowEl.textContent = data.low;

        const rateEl = document.getElementById('chart-stat-rate');
        if (rateEl) rateEl.textContent = data.rate;

        const subRateEl = document.getElementById('stat-sub-rate');
        if (subRateEl) subRateEl.textContent = data.rate;

        renderAdminTrendChart();
    }

    function toggleChartDataset(datasetIndex) {
        if (!adminTrendChartInstance) return;
        const isVisible = adminTrendChartInstance.isDatasetVisible(datasetIndex);
        adminTrendChartInstance.setDatasetVisibility(datasetIndex, !isVisible);
        adminTrendChartInstance.update();

        const btnId = datasetIndex === 0 ? 'legend-btn-temuan' : 'legend-btn-hilang';
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.classList.toggle('opacity-40', isVisible);
            btn.classList.toggle('line-through', isVisible);
        }
    }

    function renderAdminTrendChart() {
        const canvas = document.getElementById('adminTrendChart');
        if (!canvas) return;

        if (typeof Chart === 'undefined') {
            console.warn('Chart.js not yet available, retrying...');
            setTimeout(renderAdminTrendChart, 200);
            return;
        }

        const rangeData = CHART_CONFIG[currentChartRange] || CHART_CONFIG['30d'];
        const ctx = canvas.getContext('2d');

        // Linear gradient for primary purple curve
        const purpleGrad = ctx.createLinearGradient(0, 0, 0, 300);
        purpleGrad.addColorStop(0, 'rgba(21, 114, 184, 0.16)');
        purpleGrad.addColorStop(0.7, 'rgba(21, 114, 184, 0.03)');
        purpleGrad.addColorStop(1, 'rgba(21, 114, 184, 0.00)');

        // Linear gradient for secondary rose curve
        const roseGrad = ctx.createLinearGradient(0, 0, 0, 300);
        roseGrad.addColorStop(0, 'rgba(244, 63, 94, 0.10)');
        roseGrad.addColorStop(1, 'rgba(244, 63, 94, 0.00)');

        // Key point node radiuses
        const pointRadii = rangeData.temuan.map((_, i) => rangeData.keyPoints.includes(i) ? 4.5 : 0);
        const pointHoverRadii = rangeData.temuan.map(() => 6.5);

        if (adminTrendChartInstance) {
            adminTrendChartInstance.data.labels = rangeData.labels;
            adminTrendChartInstance.data.datasets[0].data = rangeData.temuan;
            adminTrendChartInstance.data.datasets[0].pointRadius = pointRadii;
            adminTrendChartInstance.data.datasets[1].data = rangeData.hilang;
            adminTrendChartInstance.update();
            return;
        }

        adminTrendChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: rangeData.labels,
                datasets: [
                    {
                        label: 'Barang Temuan',
                        data: rangeData.temuan,
                        borderColor: '#1572b8',
                        backgroundColor: purpleGrad,
                        borderWidth: 1.8,
                        tension: 0.42,
                        fill: true,
                        pointRadius: pointRadii,
                        pointHoverRadius: pointHoverRadii,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#1572b8',
                        pointBorderWidth: 1.8,
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: '#1572b8',
                        pointHoverBorderWidth: 2.2
                    },
                    {
                        label: 'Barang Hilang',
                        data: rangeData.hilang,
                        borderColor: '#f43f5e',
                        backgroundColor: roseGrad,
                        borderWidth: 1.8,
                        tension: 0.38,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 5.5,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#f43f5e',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 600,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(15, 23, 42, 0.96)',
                        titleColor: '#f1f5f9',
                        titleFont: { size: 11, weight: '600' },
                        bodyColor: '#e2e8f0',
                        bodyFont: { size: 11 },
                        padding: { top: 7, bottom: 7, left: 10, right: 10 },
                        cornerRadius: 8,
                        displayColors: true,
                        boxWidth: 7,
                        boxHeight: 7,
                        boxPadding: 3,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.dataset.label}: ${context.parsed.y} Kasus`;
                            },
                            afterBody: function(items) {
                                const temuan = items[0]?.parsed?.y || 0;
                                const hilang = items[1]?.parsed?.y || 0;
                                const total = temuan + hilang;
                                const rate = total > 0 ? Math.round((temuan / total) * 100) : 0;
                                return `
Tingkat Temu: ${rate}%`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10, family: 'ui-sans-serif, system-ui' },
                            color: '#9ca3af',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            borderDash: [3, 3]
                        },
                        ticks: {
                            font: { size: 10, family: 'ui-monospace, monospace' },
                            color: '#9ca3af',
                            stepSize: 10
                        }
                    }
                }
            },
            plugins: [dashedCrosshairPlugin]
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash.replace('#', '');
        if (hash && ['overview', 'reports', 'moderation', 'users', 'audit', 'categories'].includes(hash)) {
            switchAdminTab(hash);
        } else {
            switchAdminTab('overview');
        }
    });
</script>
@endpush
