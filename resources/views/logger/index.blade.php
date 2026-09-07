@extends('layouts.app')

@section('title', 'Audit Logger & Log Aktivitas')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">📝 Audit Logger & Riwayat Aktivitas Sistem</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Rekam jejak audit keamanan, login staf, modifikasi data sampel, dan sertifikat pengujian.</p>
    </div>
</div>

<div class="data-container">
    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th>Waktu / Timestamp</th>
                    <th>Nama Pengguna / Staf</th>
                    <th>Role / Jabatan</th>
                    <th>Status Akun</th>
                    <th>Tipe Aktivitas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>{{ $u->updated_at ? $u->updated_at->translatedFormat('d M Y H:i:s') : ($u->created_at ? $u->created_at->translatedFormat('d M Y H:i:s') : '-') }}</td>
                    <td>
                        <strong style="color: #0f172a;">{{ $u->profile?->full_name ?? $u->name }}</strong>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $u->email }}</div>
                    </td>
                    <td>
                        @php $role = $u->profile?->role ?? $u->role ?? 'staff'; @endphp
                        <span class="tag tag-blue">{{ strtoupper($role) }}</span>
                    </td>
                    <td>
                        <span class="tag tag-green">✓ Active Session</span>
                    </td>
                    <td>
                        <span class="tag" style="background: #f1f5f9; color: #475569;">Staff Activity Authenticated</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Belum ada riwayat aktivitas yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
