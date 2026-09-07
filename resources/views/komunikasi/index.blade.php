@extends('layouts.app')

@section('title', 'Hub Komunikasi Pelanggan')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">💬 Hub Komunikasi & Tiket Konsultasi Pelanggan</h2>
        <p style="font-size: 0.85rem; color: #64748b;">Layanan konsultasi teknis, pertanyaan regulasi, dan keluhan pelanggan secara real-time.</p>
    </div>
</div>

@if(session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 0.85rem;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="data-container">
    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
        <form method="GET" action="{{ route('pengujian.komunikasi.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perusahaan, perihal..." style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 12px; width: 280px; font-size: 0.85rem; outline: none;">
            <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                🔍 Cari
            </button>
        </form>
    </div>

    <div style="overflow-x: auto; width: 100%;">
        <table>
            <thead>
                <tr>
                    <th>Nama Klien & Pengirim</th>
                    <th>Perihal / Topik</th>
                    <th>Isi Pesan</th>
                    <th>Balasan Staf</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $m)
                <tr>
                    <td>
                        <strong style="color: #0f172a;">{{ $m->company_name }}</strong>
                        <div style="font-size: 0.75rem; color: #64748b;">Pengirim: {{ $m->sender_name ?? '-' }}</div>
                        <div style="font-size: 0.7rem; color: #94a3b8;">{{ $m->created_at ? $m->created_at->diffForHumans() : '-' }}</div>
                    </td>
                    <td><strong>{{ $m->subject }}</strong></td>
                    <td style="max-width: 250px;">
                        <div style="font-size: 0.8rem; color: #334155; white-space: normal;">{{ $m->message }}</div>
                    </td>
                    <td style="max-width: 250px;">
                        @if($m->reply)
                            <div style="font-size: 0.8rem; color: #16a34a; white-space: normal;">{{ $m->reply }}</div>
                            <div style="font-size: 0.7rem; color: #64748b;">Oleh: {{ $m->replied_by }} ({{ \Carbon\Carbon::parse($m->replied_at)->translatedFormat('d M H:i') }})</div>
                        @else
                            <span style="color: #94a3b8; font-size: 0.75rem;">Belum ada balasan</span>
                        @endif
                    </td>
                    <td>
                        @if($m->status === 'Sudah Dibalas')
                            <span class="tag tag-green">✓ Sudah Dibalas</span>
                        @else
                            <span class="tag tag-orange">⏳ Belum Dibalas</span>
                        @endif
                    </td>
                    <td>
                        <button onclick="openModalReply({{ $m->id }}, '{{ addslashes($m->company_name) }}', '{{ addslashes($m->subject) }}', '{{ addslashes($m->message) }}', '{{ addslashes($m->reply ?? '') }}')" style="padding: 6px 12px; background: #0284c7; color: white; border: none; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                            💬 Balas
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Tidak ada pesan konsultasi dari pelanggan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $messages->links() }}
    </div>
</div>

<!-- Modal Balas Pesan -->
<div id="modalBalas" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; border-radius: 20px; width: 100%; max-width: 550px; padding: 30px;">
        <h3 id="modalReplyTitle" style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Balas Pesan Pelanggan</h3>
        <p id="modalReplySubtitle" style="font-size: 0.8rem; color: #64748b; margin-bottom: 15px;"></p>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 20px;">
            <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; margin-bottom: 4px;">Pesan Klien:</div>
            <div id="modalReplyMessage" style="font-size: 0.85rem; color: #1e293b;"></div>
        </div>

        <form id="formReply" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.8rem; font-weight: 700; color: #475569; display: block; margin-bottom: 6px;">Tulis Balasan / Solusi Teknis *</label>
                <textarea name="reply" id="inputReplyText" rows="4" required placeholder="Tuliskan respon resmi laboratorium..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; resize: vertical;"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('modalBalas').style.display='none'" style="padding: 10px 18px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 20px; background: #0284c7; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                    Kirim Balasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModalReply(id, company, subject, msg, existingReply) {
        document.getElementById('modalReplyTitle').innerText = 'Balas Pesan: ' + subject;
        document.getElementById('modalReplySubtitle').innerText = company;
        document.getElementById('modalReplyMessage').innerText = msg;
        document.getElementById('inputReplyText').value = existingReply || '';
        document.getElementById('formReply').action = '/pengujian/komunikasi/' + id + '/reply';
        document.getElementById('modalBalas').style.display = 'flex';
    }
</script>
@endpush
