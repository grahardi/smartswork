<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PersonalBotController extends Controller
{
    public function index(): View
    {
        return view('bot');
    }

    /**
     * Deteksi otomatis: kalimat ini soal UANG (masuk ke Keuangan) atau
     * soal AKTIVITAS (masuk ke Aksi Harian)? Tetap draft, user konfirmasi
     * & simpan manual - AI cuma bantu isi form.
     */
    public function parse(Request $request): JsonResponse
    {
        $apiKey = config('services.gemini.api_key');

        if (! $apiKey) {
            return response()->json(['error' => 'Fitur AI belum diaktifkan.'], 422);
        }

        $teks = $request->input('teks');
        $hasAudio = $request->hasFile('audio');

        if (! $teks && ! $hasAudio) {
            return response()->json(['error' => 'Tulis atau rekam dulu.'], 422);
        }

        $user = $request->user();

        $categories = $user->financeCategories()->get(['id', 'nama', 'type']);
        $daftarKategori = $categories->map(fn ($c) => "{$c->id} | {$c->nama} | {$c->type}")->implode("\n") ?: '(belum ada kategori keuangan)';

        $projects = Project::where(function ($q) use ($user) {
                $q->whereHas('workplace.users', fn ($q2) => $q2->where('users.id', $user->id))
                    ->orWhereHas('collaborators', fn ($q2) => $q2->where('users.id', $user->id));
            })
            ->with('workplace')
            ->get(['id', 'nama', 'workplace_id']);
        $daftarProject = $projects->map(fn ($p) => "{$p->id} | {$p->workplace->nama} - {$p->nama}")->implode("\n") ?: '(belum ada project)';

        $instruksi = <<<PROMPT
Kamu asisten pribadi. Tugas pertama: tentukan apakah input ini soal TRANSAKSI UANG (ada jumlah rupiah, pemasukan/pengeluaran) atau soal AKTIVITAS/KEGIATAN sehari-hari (tidak menyebutkan uang, cuma cerita aktivitas seperti "ke toko beli roti", "antar anak sekolah").

Kategori keuangan yang tersedia (format "id | nama | type"):
{$daftarKategori}

Project untuk aksi harian yang tersedia (format "id | nama tempat - nama project"):
{$daftarProject}

Kalau input AUDIO: dengarkan dan pahami dulu isinya (bahasa Indonesia informal, "50rb"=50000, "2jt"=2000000, "jam 3 sore"=15:00).
Kalau input TEKS: "{$teks}"

Kalau ini TRANSAKSI UANG, balas JSON:
{"jenis": "keuangan", "finance_category_id": 0, "jumlah": 0, "keterangan": "...", "tanggal": "YYYY-MM-DD"}

Kalau ini AKTIVITAS/KEGIATAN (bukan soal uang), balas JSON:
{"jenis": "aksi_harian", "project_id": 0, "waktu": "HH:MM atau null kalau tidak disebutkan", "keterangan": "...", "tanggal": "YYYY-MM-DD"}

Kalau tidak ada project yang cocok sama sekali, pilih project pertama yang ada di daftar (biasanya "Pribadi - Kegiatan Harian").

Balas HANYA JSON, tanpa markdown, tanpa penjelasan tambahan.
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

        $model = config('services.gemini.model', 'gemini-2.0-flash');

        $response = Http::timeout(30)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
            [
                'contents' => [['parts' => $parts]],
                'generationConfig' => ['temperature' => 0.2, 'responseMimeType' => 'application/json'],
            ]
        );

        if (! $response->successful()) {
            return response()->json(['error' => 'Gagal menghubungi AI.'], 422);
        }

        $parsed = json_decode($response->json('candidates.0.content.parts.0.text'), true);

        if (! $parsed || empty($parsed['jenis'])) {
            return response()->json(['error' => 'AI tidak berhasil memahami. Coba lagi.'], 422);
        }

        if ($parsed['jenis'] === 'keuangan') {
            $category = $categories->firstWhere('id', $parsed['finance_category_id'] ?? null);
            if (! $category) {
                return response()->json(['error' => 'Kategori keuangan tidak ketemu. Coba lagi atau catat manual.'], 422);
            }

            return response()->json([
                'jenis' => 'keuangan',
                'finance_category_id' => $category->id,
                'kategori_label' => $category->nama.' ('.$category->type.')',
                'jumlah' => $parsed['jumlah'] ?? 0,
                'keterangan' => $parsed['keterangan'] ?? '',
                'tanggal' => $parsed['tanggal'] ?? now()->toDateString(),
            ]);
        }

        // aksi_harian
        $project = $projects->firstWhere('id', $parsed['project_id'] ?? null) ?? $projects->first();
        if (! $project) {
            return response()->json(['error' => 'Belum ada project sama sekali untuk catat aksi harian.'], 422);
        }

        return response()->json([
            'jenis' => 'aksi_harian',
            'project_id' => $project->id,
            'project_label' => $project->workplace->nama.' - '.$project->nama,
            'waktu' => $parsed['waktu'] ?? null,
            'keterangan' => $parsed['keterangan'] ?? '',
            'tanggal' => $parsed['tanggal'] ?? now()->toDateString(),
        ]);
    }
}
