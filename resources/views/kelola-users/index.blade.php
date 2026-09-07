@extends('layouts.app')

@section('title', 'Kelola Users & Hak Akses Staf')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">🛡️ Kelola Users & Hak Akses Staf</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Manajemen akun staf laboratorium, teknisi sampling lapangan, analis lab, dan manajer teknis.</p>
    </div>
    <button onclick="document.getElementById('modalTambahUser').style.display='flex'" class="btn-primary" style="background: #0284c7; color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
        ➕ Tambah Staf Baru
    </button>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="data-container">
    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ url()->current() }}" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, username..." style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; width: 280px; font-size: 0.85rem; outline: none;">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Cari
            </button>
        </form>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th>Nama Staf</th>
                    <th>Username / Email</th>
                    <th>Peran / Role</th>
                    <th>No. Telepon</th>
                    <th>Terdaftar Sejak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td><strong style="color: #0f172a;">{{ $u->profile?->full_name ?? $u->name }}</strong></td>
                    <td><span style="font-family: monospace; color: #0284c7;">{{ $u->email }}</span></td>
                    <td>
                        @php $role = $u->profile?->role ?? $u->role ?? 'staff'; @endphp
                        @if($role === 'admin_master')
                            <span class="tag tag-blue">🛡️ Super Admin</span>
                        @elseif($role === 'manager')
                            <span class="tag tag-green">👔 Manajer Teknis</span>
                        @elseif($role === 'analis')
                            <span class="tag tag-orange">🧪 Analis Lab</span>
                        @elseif($role === 'sampling')
                            <span class="tag tag-blue">📍 Petugas Sampling</span>
                        @else
                            <span class="tag" style="background: #f1f5f9; color: #64748b;">{{ strtoupper($role) }}</span>
                        @endif
                    </td>
                    <td>{{ $u->profile?->phone ?? '-' }}</td>
                    <td>{{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}</td>
                    <td>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('pengujian.kelola-users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus akun staf ini?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding: 6px 10px; background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                🗑️
                            </button>
                        </form>
                        @else
                        <span style="font-size: 0.75rem; color: #94a3b8;">(Akun Anda)</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Belum ada staf terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal Tambah User -->
<div id="modalTambahUser" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 500px; padding: 30px;">
        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 20px;">➕ Tambah Akun Staf Baru</h3>

        <form action="{{ url('/pengujian/kelola-users') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Ahmad Fadillah" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Username / Email *</label>
                    <input type="text" name="username" required placeholder="ahmad" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Password *</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Role / Jabatan *</label>
                    <select name="role" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                        <option value="analis">🧪 Analis Laboratorium</option>
                        <option value="sampling">📍 Petugas Sampling Lapangan</option>
                        <option value="manager">👔 Manajer Teknis</option>
                        <option value="admin_ts">📋 Admin Pelayanan / TS</option>
                        <option value="admin_master">🛡️ Super Admin</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">No. WhatsApp</label>
                    <input type="text" name="phone" placeholder="0812..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem;">
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('modalTambahUser').style.display='none'" style="padding: 10px 18px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 20px; background: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Simpan Staf
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
