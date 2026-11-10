@extends('layouts.admin')

@section('title', 'Detail Laporan — Admin UMBFind')

@section('content')
@php
    $statusTone = match ($report->status->value) {
        'open' => 'bg-[#ecfdf3] text-[#027a48] border-[#abefc6]',
        'in_handover' => 'bg-[#eff8ff] text-[#175cd3] border-[#b2ddff]',
        'resolved' => 'bg-[#f0f9ff] text-[#026aa2] border-[#b9e6fe]',
        'hidden' => 'bg-[#fef3f2] text-[#b42318] border-[#fecdca]',
        'cancelled' => 'bg-[#f9fafb] text-[#475467] border-[#eaecf0]',
        default => 'bg-[#f9fafb] text-[#475467] border-[#eaecf0]',
    };
    $typeTone = $report->isLost() ? 'bg-[#fef3f2] text-[#b42318]' : 'bg-[#ecfdf3] text-[#027a48]';
@endphp

<div class="space-y-5">

    <!-- ================================================================
         HEADER
         ================================================================ -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div class="min-w-0">
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-[#667085] hover:text-[#1572b8] transition-colors mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Dashboard
            </a>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-[6px] text-[11px] font-semibold {{ $typeTone }}">
                    {{ $report->isLost() ? 'Hilang' : 'Temuan' }}
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-[6px] text-[11px] font-semibold border {{ $statusTone }}">
                    {{ $report->statusLabel() }}
                </span>
                <span class="text-xs text-[#98a2b3]">#{{ $report->id }}</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-[#101828] mt-1.5 leading-snug">{{ $report->title }}</h1>
            <p class="text-xs text-[#667085] mt-0.5">
                {{ $report->category?->name ?? 'Tanpa kategori' }}
                &middot; {{ $report->campus_location ?: '—' }}
                @if ($report->location)
                    &middot; {{ $report->location }}
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('report.detail', $report) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-[#eaecf0] bg-white text-[#374151] hover:bg-[#f9fafb] transition-all">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                Lihat sebagai civitas
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="p-3.5 rounded-xl bg-[#ecfdf3] border border-[#abefc6] text-xs text-[#027a48] flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-3.5 rounded-xl bg-[#fef3f2] border border-[#fecdca] text-xs text-[#b42318]">
            <div class="flex items-center gap-2 font-semibold mb-1">
                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0"></i>
                <span>Tindakan gagal diproses</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- ============================================================
             KOLOM KIRI — isi laporan
             ============================================================ -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Foto -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs">
                <h2 class="text-sm font-semibold text-[#101828] mb-3">Foto Barang</h2>
                <div class="rounded-xl overflow-hidden bg-[#f9fafb] border border-[#eaecf0]">
                    <img src="{{ $report->imageUrl() }}" alt="Foto {{ $report->title }}"
                         class="w-full max-h-[380px] object-contain">
                </div>
            </div>

            <!-- Detail -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs">
                <h2 class="text-sm font-semibold text-[#101828] mb-3.5">Detail Barang</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5 text-xs">
                    <div>
                        <dt class="text-[#667085] mb-0.5">Merek / Tipe</dt>
                        <dd class="font-medium text-[#101828]">{{ $report->brand ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#667085] mb-0.5">Kategori</dt>
                        <dd class="font-medium text-[#101828]">{{ $report->category?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#667085] mb-0.5">Tanggal Kejadian</dt>
                        <dd class="font-medium text-[#101828]">
                            {{ $report->incident_date?->translatedFormat('d F Y') ?? '—' }}
                            @if ($report->incident_time)
                                <span class="text-[#667085]">&middot; {{ $report->incident_time }}</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[#667085] mb-0.5">Lokasi Kampus</dt>
                        <dd class="font-medium text-[#101828]">{{ $report->campus_location ?: '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-[#667085] mb-0.5">Lokasi Spesifik</dt>
                        <dd class="font-medium text-[#101828]">{{ $report->location ?: '—' }}</dd>
                    </div>
                    @if ($report->storage_location || $report->storage_note)
                        <div class="sm:col-span-2">
                            <dt class="text-[#667085] mb-0.5">Titik Penyimpanan</dt>
                            <dd class="font-medium text-[#101828]">
                                {{ $report->storage_location ?: '—' }}
                                @if ($report->storage_note)
                                    <span class="text-[#667085]">&middot; {{ $report->storage_note }}</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    @if ($report->reference_report_id)
                        <div class="sm:col-span-2">
                            <dt class="text-[#667085] mb-0.5">Laporan Hilang Terkait (DEC-027)</dt>
                            <dd class="font-medium text-[#101828]">
                                <a href="{{ route('admin.report', $report->reference_report_id) }}"
                                   class="text-[#1572b8] hover:underline">
                                    #{{ $report->reference_report_id }} — lihat laporan
                                </a>
                            </dd>
                        </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="text-[#667085] mb-0.5">Deskripsi</dt>
                        <dd class="text-[#101828] leading-relaxed whitespace-pre-line">{{ $report->description ?: '—' }}</dd>
                    </div>
                    @if ($report->proof_question)
                        <div class="sm:col-span-2">
                            <dt class="text-[#667085] mb-0.5">Pertanyaan Verifikasi Klaim</dt>
                            <dd class="font-medium text-[#101828]">{{ $report->proof_question }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Klaim -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-[#101828]">Klaim Masuk</h2>
                    <span class="text-xs text-[#667085]">{{ $report->claims->count() }} klaim</span>
                </div>

                @forelse ($report->claims as $claim)
                    <div class="py-3 border-t border-[#f2f4f7] first:border-t-0 first:pt-0">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-[#101828]">
                                    {{ $claim->claimant?->name ?? 'Civitas UMB' }}
                                    <span class="font-normal text-[#98a2b3]">
                                        &middot; {{ $claim->claimant?->nim ?? '—' }}
                                    </span>
                                </p>
                                <p class="text-[11px] text-[#667085] mt-0.5">
                                    Diajukan {{ $claim->created_at?->translatedFormat('d M Y, H:i') }} WIB
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-[6px] text-[11px] font-semibold bg-[#f9fafb] text-[#475467] border border-[#eaecf0]">
                                {{ $claim->status->label() }}
                            </span>
                        </div>

                        @if ($claim->proof_answer)
                            <div class="mt-2 p-2.5 rounded-lg bg-[#f9fafb] border border-[#eaecf0]">
                                <p class="text-[11px] text-[#667085] mb-0.5">Jawaban verifikasi</p>
                                <p class="text-xs text-[#101828]">{{ $claim->proof_answer }}</p>
                            </div>
                        @endif

                        @if ($claim->messages->isNotEmpty())
                            <p class="text-[11px] text-[#98a2b3] mt-2">
                                {{ $claim->messages->count() }} pesan chat
                            </p>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-[#98a2b3] py-2">Belum ada klaim pada laporan ini.</p>
                @endforelse
            </div>
        </div>

        <!-- ============================================================
             KOLOM KANAN — pelapor & tindakan moderasi
             ============================================================ -->
        <div class="space-y-5">

            <!-- Pelapor -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs">
                <h2 class="text-sm font-semibold text-[#101828] mb-3">Pelapor</h2>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#eff8ff] text-[#1572b8] flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr($report->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-[#101828] truncate">{{ $report->user?->name ?? 'Civitas UMB' }}</p>
                        <p class="text-[11px] text-[#667085] truncate">{{ $report->user?->email ?? '—' }}</p>
                    </div>
                </div>
                <dl class="mt-3.5 space-y-2 text-xs">
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#667085]">NIM</dt>
                        <dd class="font-medium text-[#101828]">{{ $report->user?->nim ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#667085]">Status akun</dt>
                        <dd class="font-medium text-[#101828]">{{ $report->user?->status->label() ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#667085]">Dibuat</dt>
                        <dd class="font-medium text-[#101828]">{{ $report->created_at?->translatedFormat('d M Y, H:i') }} WIB</dd>
                    </div>
                </dl>
            </div>

            <!-- Tindakan moderasi -->
            <div class="p-5 rounded-2xl bg-white border border-[#eaecf0] shadow-xs">
                <h2 class="text-sm font-semibold text-[#101828] mb-1">Tindakan Moderasi</h2>
                <p class="text-[11px] text-[#667085] mb-3.5">Semua tindakan tersimpan di log audit (DEC-026).</p>

                @if ($report->status->value === 'hidden')
                    <form method="POST" action="{{ route('admin.report.unhide', $report) }}" class="space-y-2.5">
                        @csrf
                        <label class="block text-[11px] font-medium text-[#344054]">Alasan menampilkan kembali</label>
                        <textarea name="reason" rows="2" required maxlength="500"
                                  class="w-full text-xs rounded-lg border border-[#eaecf0] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1572b8]/20 focus:border-[#1572b8]"
                                  placeholder="Contoh: laporan sudah diperiksa dan tidak melanggar aturan.">{{ old('reason') }}</textarea>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-[#1572b8] text-white hover:bg-[#0f5791] transition-all">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            Tampilkan Kembali
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.report.hide', $report) }}" class="space-y-2.5">
                        @csrf
                        <label class="block text-[11px] font-medium text-[#344054]">Alasan menyembunyikan</label>
                        <textarea name="reason" rows="2" required maxlength="500"
                                  class="w-full text-xs rounded-lg border border-[#eaecf0] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1572b8]/20 focus:border-[#1572b8]"
                                  placeholder="Contoh: foto diambil dari internet, barang tidak nyata.">{{ old('reason') }}</textarea>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-white border border-[#fecdca] text-[#b42318] hover:bg-[#fef3f2] transition-all">
                            <i data-lucide="eye-off" class="w-3.5 h-3.5"></i>
                            Sembunyikan Laporan
                        </button>
                    </form>
                @endif

                @if ($report->user)
                    <div class="mt-3.5 pt-3.5 border-t border-[#f2f4f7] space-y-2.5">
                        <label class="block text-[11px] font-medium text-[#344054]">Tindakan pada akun pelapor</label>

                        <form method="POST" action="{{ route('admin.user.warn', $report->user) }}" class="space-y-2">
                            @csrf
                            <input type="text" name="reason" required maxlength="500"
                                   class="w-full text-xs rounded-lg border border-[#eaecf0] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1572b8]/20 focus:border-[#1572b8]"
                                   placeholder="Alasan peringatan">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-white border border-[#fde68a] text-[#b54708] hover:bg-[#fffaeb] transition-all">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                Kirim Peringatan
                            </button>
                        </form>

                        @if ($report->user->status->value === 'suspended')
                            <form method="POST" action="{{ route('admin.user.reactivate', $report->user) }}">
                                @csrf
                                <input type="hidden" name="reason" value="Akun dipulihkan setelah peninjauan etika selesai.">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-[#ecfdf3] border border-[#abefc6] text-[#027a48] hover:bg-[#d1fadf] transition-all">
                                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                                    Aktifkan Akun Pelapor
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.user.suspend', $report->user) }}" class="space-y-2"
                                  onsubmit="return confirm('Tangguhkan akun ini? Seluruh laporan aktifnya akan disembunyikan (DEC-026).');">
                                @csrf
                                <input type="text" name="reason" required maxlength="500"
                                       class="w-full text-xs rounded-lg border border-[#eaecf0] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1572b8]/20 focus:border-[#1572b8]"
                                       placeholder="Alasan penangguhan">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-white border border-[#fecdca] text-[#b42318] hover:bg-[#fef3f2] transition-all">
                                    <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
                                    Tangguhkan Akun
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
    }
</script>
@endpush
