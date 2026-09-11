<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAssistantController extends Controller
{
    /**
     * Ubah kalimat natural (mis. "beli meja 400rb") jadi USULAN baris jurnal
     * debit/kredit. Ini cuma DRAFT - tetap harus dikonfirmasi manual oleh
     * user sebelum benar-benar tersimpan (AI bisa saja salah klasifikasi).
     */
    public function parse(Request $request, Business $business): JsonResponse
    {
        $request->validate(['teks' => ['required', 'string', 'max:500']]);

        $apiKey = config('services.gemini.api_key');

        if (! $apiKey) {
            return response()->json([
                'error' => 'Fitur AI belum diaktifkan. Isi kolom "teks" secara manual dulu ya, atau minta admin setup GEMINI_API_KEY.',
            ], 422);
        }

        $accounts = $business->accounts()->where('is_active', true)->orderBy('kode')->get(['id', 'kode', 'nama', 'tipe', 'saldo_normal']);

        $daftarAkun = $accounts->map(fn ($a) => "{$a->kode} | {$a->nama} | tipe:{$a->tipe} | normal:{$a->saldo_normal}")->implode("\n");

        $prompt = <<<PROMPT
Kamu adalah asisten akuntansi. User menulis transaksi dalam bahasa natural Indonesia (bisa informal, singkatan angka seperti "400rb" = 400000, "2jt" = 2000000).

Daftar akun (Chart of Account) yang tersedia di business ini, format "kode | nama | tipe | saldo_normal":
{$daftarAkun}

Transaksi dari user: "{$request->input('teks')}"

Tugasmu: buatkan jurnal double-entry (debit = kredit) menggunakan HANYA kode akun dari daftar di atas yang paling sesuai. Kalau transaksi berupa pembelian/pengeluaran tunai, kredit biasanya di "Kas" atau "Bank". Kalau ada akun yang jelas-jelas cocok (misal beli meja -> Peralatan atau Perlengkapan), pilih itu.

Balas HANYA dengan JSON valid, tanpa markdown, tanpa penjelasan tambahan, format PERSIS seperti ini:
{"tanggal": "YYYY-MM-DD", "keterangan": "ringkasan singkat", "lines": [{"kode_akun": "...", "debit": 0, "kredit": 0}, {"kode_akun": "...", "debit": 0, "kredit": 0}]}

Tanggal pakai hari ini kalau tidak disebutkan: {$request->input('tanggal_hari_ini', now()->toDateString())}.
PROMPT;

        $model = config('services.gemini.model', 'gemini-3.6-flash');

        $response = Http::timeout(20)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
            [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
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

        if (! $parsed || empty($parsed['lines'])) {
            return response()->json(['error' => 'AI tidak berhasil memahami transaksinya. Coba tulis lebih jelas atau isi manual.'], 422);
        }

        // Cocokkan kode_akun hasil AI ke id akun yang beneran ada di business ini.
        $lines = [];
        foreach ($parsed['lines'] as $line) {
            $account = $accounts->firstWhere('kode', $line['kode_akun'] ?? null);
            if (! $account) {
                continue;
            }
            $lines[] = [
                'chart_of_account_id' => $account->id,
                'akun_label' => $account->kode.' - '.$account->nama,
                'debit' => $line['debit'] ?? 0,
                'kredit' => $line['kredit'] ?? 0,
            ];
        }

        if (count($lines) < 2) {
            return response()->json(['error' => 'AI tidak menemukan akun yang cocok. Coba isi manual.'], 422);
        }

        return response()->json([
            'tanggal' => $parsed['tanggal'] ?? now()->toDateString(),
            'keterangan' => $parsed['keterangan'] ?? $request->input('teks'),
            'lines' => $lines,
        ]);
    }
}
