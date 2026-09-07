<?php

namespace App\Http\Controllers;

use App\Models\KalibrasiSertifikat;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function verify($qr_token)
    {
        // Handle Draft Preview Tokens gracefully
        if ($qr_token === 'DRAFT_PREVIEW_TOKEN' || str_starts_with($qr_token, 'DRAFT_')) {
            return view('kalibrasi.coa.verify', [
                'status' => 'DRAFT',
                'message' => 'Dokumen ini merupakan PRATINJAU DRAFT Sertifikat Kalibrasi. Dokumen resmi akan terbit setelah disetujui (Approved) oleh Laboratory Manager.',
                'sertifikat' => null
            ]);
        }

        $sertifikat = KalibrasiSertifikat::with([
            'alat.order',
            'alat.input.evaluasi'
        ])->where('qr_code_token', $qr_token)->first();

        if (!$sertifikat) {
            return view('kalibrasi.coa.verify', [
                'status' => 'INVALID',
                'message' => 'Sertifikat Kalibrasi tidak ditemukan atau kode verifikasi QR tidak sah!',
                'sertifikat' => null
            ]);
        }

        // Otomatis bersihkan jika data rekaman lama di database masih mengandung nama dummy
        if ($sertifikat->verified_by_qms && str_contains($sertifikat->verified_by_qms, 'Dewi Lestari')) {
            $sertifikat->verified_by_qms = 'Tim Penjaminan Mutu (QMS)';
            $sertifikat->save();
        }
        if ($sertifikat->diterbitkan_oleh && str_contains($sertifikat->diterbitkan_oleh, 'Siti Rahma')) {
            $sertifikat->diterbitkan_oleh = 'Admin Master Kalibrasi';
            $sertifikat->save();
        }

        $inputObj = $sertifikat->alat ? $sertifikat->alat->input : null;
        $orderObj = $sertifikat->alat ? $sertifikat->alat->order : null;

        return view('kalibrasi.coa.verify', [
            'status' => 'VALID',
            'message' => 'Sertifikat Kalibrasi Terverifikasi Resmi oleh Laboratorium Kalibrasi PT Envirotama Solusindo (LK-361-IDN)',
            'sertifikat' => $sertifikat,
            'inputObj' => $inputObj,
            'orderObj' => $orderObj
        ]);
    }

    public function search(Request $request)
    {
        $query = trim($request->input('query', ''));
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nomor sertifikat atau nomor job order.'
            ]);
        }

        $sertifikat = KalibrasiSertifikat::with(['alat.order', 'alat.input'])
            ->where('no_sertifikat', 'LIKE', "%{$query}%")
            ->orWhere('qr_code_token', $query)
            ->orWhere('id', $query)
            ->orWhereHas('alat.order', function($q) use ($query) {
                $q->where('no_order', 'LIKE', "%{$query}%");
            })
            ->first();

        if (!$sertifikat) {
            return response()->json([
                'success' => false,
                'message' => "Dokumen dengan nomor '{$query}' tidak ditemukan dalam database resmi. Pastikan format nomor yang dimasukkan sudah sesuai."
            ]);
        }

        $custName = $sertifikat->alat?->order?->nama_pelanggan 
            ?? $sertifikat->alat?->order?->nama_customer 
            ?? 'Customer PT Envirotama Solusindo';

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $sertifikat->id,
                'no_sertifikat' => $sertifikat->no_sertifikat,
                'nama_alat' => $sertifikat->alat?->nama_alat ?? 'Alat Kalibrasi Terakreditasi',
                'merk_tipe' => ($sertifikat->alat?->merk ?? '-') . ' / ' . ($sertifikat->alat?->model ?? '-'),
                'no_seri' => $sertifikat->alat?->no_seri ?? '-',
                'customer' => $custName,
                'tgl_kalibrasi' => $sertifikat->tgl_kalibrasi ? date('d F Y', strtotime($sertifikat->tgl_kalibrasi)) : '-',
                'status_qms' => $sertifikat->status_qms ?? 'Verified',
                'verify_url' => url('/kalibrasi/verify-certificate/' . ($sertifikat->qr_code_token ?? 'DRAFT_PREVIEW_TOKEN')),
                'print_url' => url('/kalibrasi/coa/' . $sertifikat->id . '/print')
            ]
        ]);
    }
}
