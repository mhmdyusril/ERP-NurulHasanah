<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class GeminiController extends Controller
{
    /**
     * Handle chat request via Groq API (Llama 3.3 70B — free, no billing needed).
     *
     * POST /gemini/chat
     * Body: { "message": "...", "history": [...] }
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'nullable|array',
            'history.*.role' => 'in:user,model',
            'history.*.text' => 'string|max:2000',
        ]);

        $apiKey = config('services.groq.key');
        if (!$apiKey) {
            return response()->json([
                'error' => 'GROQ_API_KEY belum dikonfigurasi di .env server.'
            ], 500);
        }

        $user = Auth::user();
        $schoolName = 'RA Nurul Hasanah';

        $systemPrompt = <<<PROMPT
Kamu adalah asisten AI cerdas khusus untuk sistem ERP {$schoolName}, sebuah Raudhatul Athfal (TK/RA) Islam untuk anak usia 4-6 tahun.

Tugas utamamu:
- Membantu staf admin dan guru dalam menggunakan sistem ERP
- Menjawab pertanyaan tentang manajemen siswa, absensi, SPP & keuangan, nilai akademik, dan laporan
- Memberikan tips pengisian data yang benar di sistem
- Merespons dalam Bahasa Indonesia yang ramah, singkat, dan profesional

Pengguna yang sedang login: {$user->name} (Role: {$user->role})

Panduan modul ERP ini:
1. Dashboard - Ringkasan statistik siswa, guru, dan keuangan
2. Manajemen Guru - Data guru dan staf (hanya admin)
3. Data Kesiswaan - Pendaftaran dan data lengkap siswa
4. Manajemen Kelas - Pengelolaan kelas dan penempatan siswa
5. Kasir SPP & Tagihan - Pencatatan pembayaran SPP bulanan
6. Pusat Rekap Laporan - Laporan keuangan dan ekspor Excel
7. Akademik & Nilai - Penilaian perkembangan anak
8. Absensi Harian - Pencatatan kehadiran siswa
9. Tabungan Siswa - Pengelolaan tabungan anak

Jika ditanya hal di luar konteks sekolah/ERP, tetap jawab dengan sopan namun fokuskan ke topik ERP.
PROMPT;

        // Build messages array for Groq (OpenAI-compatible format)
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Add conversation history (max 10 last messages)
        $history = array_slice($request->input('history', []), -10);
        foreach ($history as $entry) {
            $role = $entry['role'] === 'model' ? 'assistant' : 'user';
            $messages[] = ['role' => $role, 'content' => $entry['text']];
        }

        // Add current user message
        $messages[] = ['role' => 'user', 'content' => $request->message];

        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false]) // bypass SSL for local dev
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => 'llama-3.3-70b-versatile',
                    'messages'    => $messages,
                    'temperature' => 0.7,
                    'max_tokens'  => 1024,
                ]);

            if ($response->failed()) {
                \Log::error('Groq API Error: ' . $response->body());
                return response()->json([
                    'error' => 'API Error (' . $response->status() . '): ' . mb_strimwidth($response->body(), 0, 200, '...')
                ], 502);
            }

            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? null;

            if (!$text) {
                return response()->json([
                    'error' => 'AI tidak memberikan respons. Coba ulangi pertanyaanmu.'
                ], 500);
            }

            return response()->json([
                'reply' => $text,
                'model' => $data['model'] ?? 'llama-3.3-70b-versatile',
            ]);

        } catch (\Exception $e) {
            \Log::error('Groq Exception: ' . $e->getMessage());
            return response()->json([
                'error' => 'Koneksi gagal: ' . mb_strimwidth($e->getMessage(), 0, 100, '...')
            ], 500);
        }
    }
}
