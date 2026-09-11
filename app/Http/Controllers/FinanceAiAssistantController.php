<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FinanceAiAssistantController extends Controller
{
    /**
     * Ubah kalimat natural ATAU rekaman suara jadi USULAN transaksi
     * keuangan (kategori, jumlah, tanggal, keterangan). Tetap draft -
     * user yang konfirmasi & simpan manual lewat form biasa.
     */
    public function parse(Request $request): JsonResponse
    {
        $apiKey = config('services.gemini.api_key');

        if (! $apiKey) {
            return response()->json([
                'error' => 'Fitur AI belum diaktifkan. Isi form manual saja ya.',
            ], 422);
        }

        $teks = $request->input('teks');
        $hasAudio = $request->hasFile('audio');

        if (! $teks && ! $hasAudio) {
            return response()->json(['error' => 'Tulis atau rekam transaksinya dulu.'], 422);
        }

        $categories = $request->user()->financeCategories()->get(['id', 'nama', 'type']);
        $daftarKategori = $categories->map(fn ($c) => "{$c->id} | {$c->nama} | {$c->type}")->implode("\n");

        if ($daftarKategori === '') {
            return response()->json(['error' => 'Belum ada kategori keuangan. Tambah kategori dulu ya.'], 422);
        }

        $instruksi = <<<PROMPT
Kamu asisten pencatat keuangan pribadi. Daftar kategori yang tersedia (format "id | nama | type"):
{$daftarKategori}

Kalau input berupa AUDIO: dengarkan dan pahami kalimatnya dulu (bahasa Indonesia informal, angka seperti "50rb" = 50000, "2jt" = 2000000).
Kalau input berupa TEKS: "{$teks}"

Tugasmu: tentukan SATU kategori id yang paling cocok dari daftar di atas, jumlah uangnya, dan keterangan singkat. Kalau tidak ada kategori yang cocok sama sekali, pilih kategori bertype sama yang paling mendekati.

Balas HANYA dengan JSON valid, tanpa markdown, format PERSIS:
{"finance_category_id": 0, "jumlah": 0, "keterangan": "...", "tanggal": "YYYY-MM-DD"}

Tanggal pakai hari ini kalau tidak disebutkan: {$request->input('tanggal_hari_ini', now()->toDateString())}.
PROMPT;

        $parts = [];

        if ($hasAudio) {
            $audioFile = $request->file('audio');
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $audioFile->getMimeType() ?: 'audio/webm',
                    'data' => base64_encode(file_get_contents($audioFile->getRealPath())),
                ],
            ];
        }

        $parts[] = ['text' => $instruksi];

        $model = config('services.gemini.model', 'gemini-3.6-flash');

        $response = Http::timeout(30)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
            [
                'contents' => [['parts' => $parts]],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'responseMimeType' => 'application/json',
                ],
            ]
        );

        if (! $response->successful()) {
            return response()->json(['error' => 'Gagal menghubungi AI. Coba lagi atau isi manual.'], 422);
        }

        $rawText = $response->json('candidates.0.content.parts.0.text');
        $parsed = json_decode($rawText, true);

        if (! $parsed || empty($parsed['finance_category_id'])) {
            return response()->json(['error' => 'AI tidak berhasil memahami transaksinya. Coba lagi atau isi manual.'], 422);
        }

        $category = $categories->firstWhere('id', $parsed['finance_category_id']);
        if (! $category) {
            return response()->json(['error' => 'Kategori hasil AI tidak valid. Coba lagi atau isi manual.'], 422);
        }

        return response()->json([
            'finance_category_id' => $category->id,
            'kategori_label' => $category->nama.' ('.$category->type.')',
            'jumlah' => $parsed['jumlah'] ?? 0,
            'keterangan' => $parsed['keterangan'] ?? ($teks ?? ''),
            'tanggal' => $parsed['tanggal'] ?? now()->toDateString(),
        ]);
    }
}
