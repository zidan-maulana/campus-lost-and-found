@extends('layouts.app')

@section('title', 'Dashboard Admin — FindUMB')

@section('content')
<div class="admin-page">
    <header class="admin-head">
        <div>
            <h1>Dashboard Moderasi</h1>
            <p>Administrator tidak menentukan kepemilikan barang. Tugas moderasi terbatas pada penegakan aturan konten dan status akun.</p>
        </div>
        <a href="{{ route('home') }}" class="admin-link">← Kembali ke katalog</a>
    </header>

    @if (session('status'))
        <div class="admin-alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">{{ $errors->first() }}</div>
    @endif

    <section class="admin-stats">
        <div class="admin-stat"><span class="v">{{ $stats['users'] }}</span><span class="k">Total Pengguna</span></div>
        <div class="admin-stat"><span class="v">{{ $stats['reports_open'] }}</span><span class="k">Laporan Terbuka</span></div>
        <div class="admin-stat"><span class="v">{{ $stats['claims_active'] }}</span><span class="k">Klaim Aktif</span></div>
        <div class="admin-stat"><span class="v">{{ $stats['abuse_pending'] }}</span><span class="k">Laporan Penyalahgunaan</span></div>
        <div class="admin-stat"><span class="v">{{ $stats['reports_hidden'] }}</span><span class="k">Laporan Disembunyikan</span></div>
        <div class="admin-stat"><span class="v">{{ $stats['suspended'] }}</span><span class="k">Akun Ditangguhkan</span></div>
    </section>

    <section class="admin-card">
        <h2>Laporan Penyalahgunaan Menunggu Tinjauan</h2>

        @forelse ($abuseReports as $abuse)
            <article class="admin-item">
                <div class="admin-item-main">
                    <div class="admin-item-title">
                        {{ ucfirst(str_replace('_', ' ', $abuse->target_type)) }} #{{ $abuse->target_id }}
                        <span class="pill pill-{{ $abuse->status->value }}">{{ $abuse->status->label() }}</span>
                    </div>
                    <div class="admin-item-meta">
                        Dilaporkan oleh {{ $abuse->reporter?->name ?? 'Pengguna' }}
                        • {{ $abuse->created_at?->diffForHumans() }}
                    </div>
                    <p class="admin-item-body"><strong>{{ $abuse->reason }}</strong>@if ($abuse->details) — {{ $abuse->details }}@endif</p>
                </div>

                <form method="POST" action="{{ route('admin.abuse.resolve', $abuse->id) }}" class="admin-form">
                    @csrf
                    <input type="text" name="reason" placeholder="Catatan keputusan" required>
                    <div class="admin-form-actions">
                        <button type="submit" name="decision" value="resolved" class="btn-sm btn-primary">Tindak</button>
                        <button type="submit" name="decision" value="dismissed" class="btn-sm btn-ghost">Abaikan</button>
                    </div>
                </form>
            </article>
        @empty
            <p class="admin-empty">Tidak ada laporan penyalahgunaan yang menunggu tinjauan.</p>
        @endforelse
    </section>

    <section class="admin-card">
        <h2>Laporan Disembunyikan</h2>

        @forelse ($hiddenReports as $report)
            <article class="admin-item">
                <div class="admin-item-main">
                    <div class="admin-item-title">{{ $report->title }}</div>
                    <div class="admin-item-meta">
                        {{ $report->type->label() }} • {{ $report->user?->name }} • {{ $report->created_at?->format('d M Y') }}
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.report.unhide', $report->id) }}" class="admin-form">
                    @csrf
                    <input type="text" name="reason" placeholder="Alasan ditampilkan kembali" required>
                    <div class="admin-form-actions">
                        <button type="submit" class="btn-sm btn-primary">Tampilkan</button>
                    </div>
                </form>
            </article>
        @empty
            <p class="admin-empty">Tidak ada laporan yang disembunyikan.</p>
        @endforelse
    </section>

    <section class="admin-card">
        <h2>Akun Ditangguhkan</h2>

        @forelse ($suspendedUsers as $user)
            <article class="admin-item">
                <div class="admin-item-main">
                    <div class="admin-item-title">{{ $user->name }}</div>
                    <div class="admin-item-meta">{{ $user->email }}</div>
                </div>
                <form method="POST" action="{{ route('admin.user.reactivate', $user->id) }}" class="admin-form">
                    @csrf
                    <input type="text" name="reason" placeholder="Alasan reaktivasi" required>
                    <div class="admin-form-actions">
                        <button type="submit" class="btn-sm btn-primary">Aktifkan</button>
                    </div>
                </form>
            </article>
        @empty
            <p class="admin-empty">Tidak ada akun yang ditangguhkan.</p>
        @endforelse
    </section>

    <section class="admin-card">
        <h2>Riwayat Tindakan Moderasi</h2>

        @forelse ($recentActions as $action)
            <article class="admin-item admin-item-plain">
                <div class="admin-item-main">
                    <div class="admin-item-title">{{ $action->action_type->label() }}</div>
                    <div class="admin-item-meta">
                        Oleh {{ $action->admin?->name }} • {{ $action->created_at?->format('d M Y H:i') }}
                        @if ($action->targetUser) • Sasaran: {{ $action->targetUser->name }} @endif
                    </div>
                    <p class="admin-item-body">{{ $action->reason }}</p>
                </div>
            </article>
        @empty
            <p class="admin-empty">Belum ada tindakan moderasi.</p>
        @endforelse
    </section>
</div>

<style>
    .admin-page { padding: 18px 16px 90px; max-width: 980px; margin: 0 auto; }
    .admin-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; margin-bottom: 16px; flex-wrap: wrap; }
    .admin-head h1 { margin: 0 0 4px; font-size: 20px; font-weight: 800; color: var(--text-main); }
    .admin-head p { margin: 0; font-size: 12.5px; color: var(--text-secondary); max-width: 560px; line-height: 1.55; }
    .admin-link { font-size: 12.5px; font-weight: 700; color: var(--primary); text-decoration: none; white-space: nowrap; }
    .admin-alert { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; padding: 11px 14px; border-radius: 12px; font-size: 12.5px; margin-bottom: 14px; }
    .admin-alert-error { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
    .admin-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 10px; margin-bottom: 16px; }
    .admin-stat { background: #fff; border: 1px solid var(--border-subtle); border-radius: 14px; padding: 13px 14px; display: flex; flex-direction: column; gap: 3px; }
    .admin-stat .v { font-size: 20px; font-weight: 800; color: var(--primary); }
    .admin-stat .k { font-size: 11.5px; color: var(--text-muted); font-weight: 600; }
    .admin-card { background: #fff; border: 1px solid var(--border-subtle); border-radius: 16px; padding: 16px; margin-bottom: 14px; }
    .admin-card h2 { margin: 0 0 12px; font-size: 14.5px; font-weight: 800; color: var(--text-main); }
    .admin-item { display: flex; gap: 12px; justify-content: space-between; align-items: flex-start; padding: 12px 0; border-top: 1px solid var(--border-light); flex-wrap: wrap; }
    .admin-item:first-of-type { border-top: none; }
    .admin-item-main { flex: 1; min-width: 220px; }
    .admin-item-title { font-size: 13.5px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .admin-item-meta { font-size: 11.5px; color: var(--text-muted); margin-top: 3px; }
    .admin-item-body { font-size: 12.5px; color: var(--text-secondary); margin: 7px 0 0; line-height: 1.55; }
    .admin-form { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .admin-form input[type="text"] {
        padding: 9px 11px; font-size: 12.5px; font-family: inherit; border: 1.5px solid var(--border-subtle);
        border-radius: 10px; min-width: 180px;
    }
    .admin-form input:focus { outline: none; border-color: var(--primary); }
    .admin-form-actions { display: flex; gap: 6px; }
    .btn-sm { padding: 9px 13px; font-size: 12px; font-weight: 700; font-family: inherit; border-radius: 10px; border: none; cursor: pointer; }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-ghost { background: #f1f5f9; color: var(--text-secondary); }
    .admin-empty { font-size: 12.5px; color: var(--text-muted); margin: 6px 0; }
    .pill { font-size: 10.5px; font-weight: 700; padding: 3px 8px; border-radius: 999px; }
    .pill-pending { background: #fef3c7; color: #b45309; }
    .pill-reviewing { background: #e0f2fe; color: #0369a1; }
    .pill-resolved { background: #dcfce7; color: #15803d; }
    .pill-dismissed { background: #f1f5f9; color: #64748b; }
</style>
@endsection
