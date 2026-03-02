<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Student;
use App\Models\Saving;
use App\Models\Payment;
use App\Models\BillingCategory;
use App\Models\Classes;
use App\Models\Attendance;
use App\Models\AcademicTerm;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function handleWebhook(Request $request)
    {
        try {
            $update = Telegram::getWebhookUpdate();
            // Check if it's a callback query (button click)
            if ($update->isType('callback_query')) {
                $query = $update->getCallbackQuery();
                $chatId = $query->getMessage()->getChat()->getId();
                $messageId = $query->getMessage()->getMessageId();
                $data = $query->getData();
                
                try {
                    Telegram::answerCallbackQuery([
                        'callback_query_id' => $query->getId()
                    ]);
                } catch (\Exception $e) {}
                
                return $this->handleCallbackQuery($chatId, $messageId, $data);
            }

            $message = $update->getMessage();
            if (!$message) return response('OK', 200);

            $chatId = $message->getChat()->getId();
            
            // Handle Force Reply for Nominal Data Input
            if ($message->getReplyToMessage()) {
                $replyToText = $message->getReplyToMessage()->getText();
                $text = trim($message->getText());
                
                $amount = preg_replace('/[^0-9]/', '', $text);
                
                if (empty($amount)) {
                    Telegram::sendMessage(['chat_id' => $chatId, 'text' => "❌ Nominal tidak valid. Harap masukkan angka saja."]);
                    return response('OK', 200);
                }

                if (preg_match('/nominal spp untuk nis:\s*\*?([a-zA-Z0-9]+)\*?/i', $replyToText, $matches)) {
                    return $this->handleFinanceTransaction($chatId, 'SPP', $matches[1], $amount);
                }
                
                if (preg_match('/nominal (?:Setoran\s+)?Tabungan(?:\s+Bebas)?\s+untuk\s+nis:\s*\*?([a-zA-Z0-9]+)\*?/i', $replyToText, $matches)) {
                    $kategori = str_contains(strtolower($replyToText), 'bebas') ? 'Bebas' : 'Wajib';
                    return $this->handleFinanceTransaction($chatId, 'TABUNG', $matches[1], $amount, $kategori);
                }

                if (preg_match('/nominal Penarikan Tabungan untuk nis:\s*\*?([a-zA-Z0-9]+)\*?/i', $replyToText, $matches)) {
                    return $this->handleFinanceTransaction($chatId, 'TARIK', $matches[1], $amount);
                }
            }

            $text = trim($message->getText());

            // Ignore empty text
            if (empty($text)) return response('OK', 200);

            // Log incoming message
            Log::info("Telegram Msg from $chatId: $text");

            // Simple Security: Only allow specific Chat IDs if needed, skipped for prototype
            // We can add logic to verify if $chatId belongs to a registered teacher

            // Commands
            if ($text === '/start' || $text === '/reset') {
                $keyboard = Keyboard::make()->remove();
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "🔄 <b>Bot Di-reset!</b>\n\nHalo! Saya Asisten ERP RA Nurul Hasanah.\nSilakan gunakan perintah berikut:\n\n/absen_kelas - Presensi Kehadiran\n/spp_kelas - Pencatatan SPP\n/tabung_kelas - Setoran Tabungan\n\n<b>Menu Pusat Laporan:</b>\n/rekap_harian - Rekap Hari Ini\n/rekap_mingguan - Rekap 7 Hari\n/rekap_bulanan - Rekap 30 Hari\n/rekap_semesteran - Rekap 6 Bulan",
                    'parse_mode' => 'HTML',
                    'reply_markup' => $keyboard
                ]);
                return response('OK', 200);
            } elseif ($text === '/rekap_harian') {
                return $this->sendRekapOptions($chatId, 'harian');
            } elseif ($text === '/rekap_mingguan') {
                return $this->sendRekapOptions($chatId, 'mingguan');
            } elseif ($text === '/rekap_bulanan') {
                return $this->sendRekapOptions($chatId, 'bulanan');
            } elseif ($text === '/rekap_semesteran') {
                return $this->sendRekapOptions($chatId, 'semesteran');
            } elseif ($text === '/absen_kelas') {
                return $this->handleClassSelectionCommand($chatId, 'absen');
            } elseif ($text === '/spp_kelas') {
                return $this->handleClassSelectionCommand($chatId, 'spp');
            } elseif ($text === '/tabung_kelas') {
                return $this->handleClassSelectionCommand($chatId, 'tabung');
            } elseif ($text === '/rekap_absen') {
                return $this->sendRekapMessage($chatId, 'absen', 0);
            } elseif ($text === '/rekap_spp') {
                return $this->sendRekapMessage($chatId, 'spp', 0);
            } elseif ($text === '/rekap_tabung') {
                return $this->sendRekapMessage($chatId, 'tabung', 0);
            }

            // Regex Parsing for SPP and Tabungan
            // Format: SPP [NIS/Nama] [Amount]
            if (preg_match('/^(SPP|TABUNG)\s+([a-zA-Z0-9]+)\s+(\d+)$/i', $text, $matches)) {
                $action = strtoupper($matches[1]);
                $identifier = $matches[2];
                $amount = $matches[3];

                return $this->handleFinanceTransaction($chatId, $action, $identifier, $amount);
            }

            // Fallback response for unhandled commands (except standard /commands handled by SDK)
            if (!str_starts_with($text, '/')) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Format tidak dikenali.\nGunakan format:\nSPP [NIS/Nama Depan] [Nominal]\nTABUNG [NIS/Nama Depan] [Nominal]"
                ]);
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error("Telegram Webhook Error: " . $e->getMessage());
            return response('OK', 200);
        }
    }

    private function sendRekapOptions($chatId, $period)
    {
        $keyboard = Keyboard::make()->inline();
        $keyboard->row([
            Keyboard::inlineButton(['text' => '📋 Rekap Absen', 'callback_data' => "rekap:$period:absen"]),
            Keyboard::inlineButton(['text' => '💳 Rekap SPP', 'callback_data' => "rekap:$period:spp"]),
        ]);
        $keyboard->row([
            Keyboard::inlineButton(['text' => '💰 Rekap Tabungan', 'callback_data' => "rekap:$period:tabung"]),
        ]);

        $title = ucfirst($period);
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "Pilih jenis Laporan Rekapitulasi ($title):",
            'reply_markup' => $keyboard
        ]);

        return response('OK', 200);
    }

    private function sendRekapMessage($chatId, $type, $days)
    {
        $endDate = now();
        $startDate = $days === 0 ? now()->startOfDay() : now()->subDays($days)->startOfDay();
        
        $dateStr = $days === 0 ? "Hari Ini (" . now()->translatedFormat('d M Y') . ")" : "$days Hari Terakhir";
        if ($days === 30) $dateStr = "Bulan Ini (30 Hari Terakhir)";
        if ($days === 180) $dateStr = "Semester Ini (6 Bulan Terakhir)";

        $message = "📊 <b>Rekapitulasi $dateStr</b>\n\n";

        if ($type === 'absen') {
            $attendances = Attendance::with('student.classroom')
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->get()
                ->groupBy('student.class_id');

            if ($attendances->isEmpty()) {
                $message .= "<i>Belum ada data presensi pada periode ini.</i>";
            } else {
                foreach ($attendances as $classId => $records) {
                    $className = $records->first()->student->classroom->nama_kelas ?? 'Tanpa Kelas';
                    $message .= "<b>$className:</b>\n";
                    
                    // Group by student for aggregate
                    $studentRecords = $records->groupBy('student_id');
                    foreach ($studentRecords as $sId => $sAttendances) {
                        $studentName = $sAttendances->first()->student->nama ?? 'Siswa';
                        
                        if ($days === 0) {
                            // Format harian: Nama: Status
                            $status = $sAttendances->first()->status;
                            $message .= "└ $studentName: <b>$status</b>\n";
                        } else {
                            // Format periodik (mingguan dsb): Nama: Hadir(x) dsb
                            $h = $sAttendances->where('status', 'Hadir')->count();
                            $s = $sAttendances->where('status', 'Sakit')->count();
                            $i = $sAttendances->where('status', 'Izin')->count();
                            $a = $sAttendances->where('status', 'Alpa')->count();
                            $message .= "└ $studentName: Hadir($h) Sakit($s) Izin($i) Alpa($a)\n";
                        }
                    }
                    $message .= "\n";
                }
            }
        } elseif ($type === 'spp') {
            $payments = Payment::with('student.classroom')
                ->whereBetween('payment_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->where('status', 'lunas')
                ->get();

            if ($payments->isEmpty()) {
                $message .= "<i>Belum ada pembayaran SPP pada periode ini.</i>";
            } else {
                $total = 0;
                foreach ($payments as $payment) {
                    $className = $payment->student->classroom->nama_kelas ?? '-';
                    $formatted = number_format($payment->amount, 0, ',', '.');
                    $date = \Carbon\Carbon::parse($payment->payment_date)->format('d/m');
                    $message .= "└ {$payment->student->nama} ($className): Rp $formatted <i>[$date]</i>\n";
                    $total += $payment->amount;
                }
                $message .= "\n<b>Total Pemasukan SPP: Rp " . number_format($total, 0, ',', '.') . "</b>";
            }
        } elseif ($type === 'tabung') {
            $savings = Saving::with('student.classroom')
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->get();

            if ($savings->isEmpty()) {
                $message .= "<i>Belum ada transaksi tabungan pada periode ini.</i>";
            } else {
                $totalSetor = $savings->where('type', 'Setor')->sum('amount');
                $totalTarik = $savings->where('type', 'Tarik')->sum('amount');
                
                $message .= "<b>Ringkasan Transaksi Tabungan:</b>\n";
                $message .= "Total Setoran (Masuk): Rp " . number_format($totalSetor, 0, ',', '.') . "\n";
                $message .= "Total Penarikan (Keluar): Rp " . number_format($totalTarik, 0, ',', '.') . "\n";
            }
        }

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);

        return response('OK', 200);
    }

    private function handleFinanceTransaction($chatId, $action, $identifier, $amount, $kategori = 'Bebas')
    {
        // Cari siswa berdasarkan NIS atau Nama (1 kata pertama)
        $student = Student::where('nis', $identifier)
            ->orWhere('nama', 'ilike', '%' . $identifier . '%')
            ->first();

        if (!$student) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Siswa dengan identitas '$identifier' tidak ditemukan."
            ]);
            return response('OK', 200);
        }

        if ($student->status !== 'Aktif') {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Siswa $student->nama sudah tidak aktif (Status: $student->status)."
            ]);
            return response('OK', 200);
        }

        if ($action === 'TABUNG') {
            Saving::create([
                'student_id' => $student->id,
                'type' => 'Setor',
                'kategori' => $kategori,
                'amount' => $amount,
                'date' => now(),
            ]);

            $balance = $student->savings()->where('type', 'Setor')->sum('amount') 
                     - $student->savings()->where('type', 'Tarik')->sum('amount');

            $formattedAmount = number_format($amount, 0, ',', '.');
            $formattedBalance = number_format($balance, 0, ',', '.');

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "✅ *Berhasil!*\nTelah ditambahkan tabungan atas nama *$student->nama* sebesar Rp $formattedAmount.\n\nSaldo saat ini: Rp $formattedBalance.",
                'parse_mode' => 'Markdown'
            ]);
        } elseif ($action === 'TARIK') {
            $totalSetor = Saving::where('student_id', $student->id)->where('type', 'Setor')->sum('amount');
            $totalTarik = Saving::where('student_id', $student->id)->where('type', 'Tarik')->sum('amount');
            $saldo = $totalSetor - $totalTarik;

            if ($amount > $saldo) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "❌ *Penarikan Gagal!*\nSaldo tabungan *$student->nama* tidak mencukupi.\nSaldo saat ini: Rp " . number_format($saldo, 0, ',', '.'),
                    'parse_mode' => 'Markdown'
                ]);
                return response('OK', 200);
            }

            Saving::create([
                'student_id' => $student->id,
                'type' => 'Tarik',
                'kategori' => 'Bebas', // Tarikan selalu bebas by default
                'amount' => $amount,
                'date' => now(),
            ]);

            $balance = $saldo - $amount;

            $formattedAmount = number_format($amount, 0, ',', '.');
            $formattedBalance = number_format($balance, 0, ',', '.');

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "✅ *Berhasil Penarikan!*\nTelah ditarik tabungan atas nama *$student->nama* sebesar Rp $formattedAmount.\n\nSisa saldo: Rp $formattedBalance.",
                'parse_mode' => 'Markdown'
            ]);
        } elseif ($action === 'SPP') {
            // Asumsi default_amount diambil untuk validasi, tapi kita simpan saja $amount
            $category = BillingCategory::where('name', 'ilike', '%SPP%')->first();
            
            if (!$category) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "❌ Kategori tagihan SPP belum diatur di sistem."
                ]);
                return response('OK', 200);
            }

            Payment::create([
                'student_id' => $student->id,
                'billing_category_id' => $category->id,
                'amount' => $amount,
                'payment_date' => now(),
                'status' => 'lunas'
            ]);

            $formattedAmount = number_format($amount, 0, ',', '.');

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "✅ *Berhasil!*\nPembayaran SPP atas nama *$student->nama* sebesar Rp $formattedAmount telah dicatat lunas.",
                'parse_mode' => 'Markdown'
            ]);
        }

        return response('OK', 200);
    }

    private function handleClassSelectionCommand($chatId, $action)
    {
        $classes = Classes::all();
        
        if ($classes->isEmpty()) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Belum ada kelas yang terdaftar."
            ]);
            return response('OK', 200);
        }

        $keyboard = Keyboard::make()->inline();
        foreach ($classes as $class) {
            $keyboard->row([
                Keyboard::inlineButton(['text' => $class->nama_kelas, 'callback_data' => $action . '_class:' . $class->id])
            ]);
        }

        $titles = [
            'absen' => 'Pilih kelas untuk absensi hari ini:',
            'spp' => 'Pilih kelas untuk pencatatan SPP:',
            'tabung' => 'Pilih kelas untuk setoran Tabungan:'
        ];

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $titles[$action] ?? 'Pilih kelas:',
            'reply_markup' => $keyboard
        ]);

        return response('OK', 200);
    }

    private function handleCallbackQuery($chatId, $messageId, $data)
    {
        // Parse callback data
        // Parse callback data for selecting class
        if (preg_match('/^(absen|spp|tabung)_class:(\d+)$/', $data, $matches)) {
            $action = $matches[1];
            $classId = $matches[2];
            $class = Classes::find($classId);
            if (!$class) return response('OK', 200);

            $students = Student::active()->where('class_id', $classId)->get();
            
            if ($students->isEmpty()) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Tidak ada siswa aktif di " . $class->nama_kelas
                ]);
                return response('OK', 200);
            }

            $actionTexts = [
                'absen' => "📋 Presensi untuk " . $class->nama_kelas . "...",
                'spp' => "💰 Pencatatan SPP untuk " . $class->nama_kelas . "...",
                'tabung' => "🏦 Setoran Tabungan untuk " . $class->nama_kelas . "..."
            ];

            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $actionTexts[$action] ?? "Mempersiapkan data kelas..."
            ]);

            foreach ($students as $student) {
                $keyboard = Keyboard::make()->inline();
                
                if ($action === 'absen') {
                    $keyboard->row([
                        Keyboard::inlineButton(['text' => '✅ Hadir', 'callback_data' => 'abs:Hadir:' . $student->id]),
                        Keyboard::inlineButton(['text' => '🤒 Sakit', 'callback_data' => 'abs:Sakit:' . $student->id]),
                    ]);
                    $keyboard->row([
                        Keyboard::inlineButton(['text' => '✉️ Izin', 'callback_data' => 'abs:Izin:' . $student->id]),
                        Keyboard::inlineButton(['text' => '❌ Alpa', 'callback_data' => 'abs:Alpa:' . $student->id]),
                    ]);
                } elseif ($action === 'spp') {
                    $keyboard->row([
                        Keyboard::inlineButton(['text' => '💳 Bayar SPP (Rp 110.000)', 'callback_data' => 'pay_spp:110000:' . $student->id]),
                    ]);
                } elseif ($action === 'tabung') {
                    $keyboard->row([
                        Keyboard::inlineButton(['text' => '🏦 Pilih untuk Tabungan', 'callback_data' => 'req_tabung:' . $student->id]),
                    ]);
                }

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "👩‍🎓 *$student->nama*\nNIS: $student->nis",
                    'parse_mode' => 'Markdown',
                    'reply_markup' => $keyboard
                ]);
            }
            
            Telegram::deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId
            ]);
        } elseif (str_starts_with($data, 'rekap:')) {
            $parts = explode(':', $data);
            if (count($parts) === 3) {
                $period = $parts[1];
                $type = $parts[2];
                $days = 0;
                if ($period === 'mingguan') $days = 7;
                if ($period === 'bulanan') $days = 30;
                if ($period === 'semesteran') $days = 180;
                
                Telegram::deleteMessage(['chat_id' => $chatId, 'message_id' => $messageId]);
                return $this->sendRekapMessage($chatId, $type, $days);
            }
        } elseif (str_starts_with($data, 'abs:')) {
            // Data format: abs:Status:StudentId -> e.g., abs:Hadir:1
            $parts = explode(':', $data);
            if (count($parts) === 3) {
                $status = $parts[1];
                $studentId = $parts[2];
                $student = Student::find($studentId);
                
                if ($student) {
                    // Insert or update attendance for today
                    Attendance::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'date' => now()->format('Y-m-d')
                        ],
                        [
                            'status' => $status
                        ]
                    );

                    $icon = '✅';
                    if ($status === 'Sakit') $icon = '🤒';
                    if ($status === 'Izin') $icon = '✉️';
                    if ($status === 'Alpa') $icon = '❌';

                    Telegram::editMessageText([
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                        'text' => "👩‍🎓 *$student->nama*\nStatus: $icon *$status* tersimpan.",
                        'parse_mode' => 'Markdown'
                    ]);
                }
            }
        } elseif (str_starts_with($data, 'pay_spp:')) {
            $parts = explode(':', $data);
            if (count($parts) === 3) {
                $amount = $parts[1];
                $studentId = $parts[2];
                $student = Student::find($studentId);
                
                if ($student && $student->status === 'Aktif') {
                    $category = BillingCategory::where('name', 'ilike', '%SPP%')->first();
                    $categoryId = $category ? $category->id : null;
                    
                    if ($category) {
                        Payment::create([
                            'student_id' => $student->id,
                            'billing_category_id' => $categoryId,
                            'amount' => $amount,
                            'payment_date' => now(),
                            'status' => 'lunas'
                        ]);
                        
                        $formattedAmount = number_format($amount, 0, ',', '.');
                        Telegram::editMessageText([
                            'chat_id' => $chatId,
                            'message_id' => $messageId,
                            'text' => "👩‍🎓 *$student->nama*\n✅ *Hore!* SPP Rp $formattedAmount berhasil dicatat Lunas.",
                            'parse_mode' => 'Markdown'
                        ]);
                    } else {
                        Telegram::sendMessage([
                            'chat_id' => $chatId,
                            'text' => "❌ Kategori tagihan SPP belum dbuat di sistem ERP."
                        ]);
                    }
                }
            }
        } elseif (str_starts_with($data, 'req_tabung:')) {
            $studentId = str_replace('req_tabung:', '', $data);
            $student = Student::find($studentId);
            
            if ($student) {
                $keyboard = Keyboard::make()->inline();
                $keyboard->row([
                    Keyboard::inlineButton(['text' => '📥 Setor Wajib (Rp 10.000)', 'callback_data' => 'act_tab_waj:' . $student->id]),
                ]);
                $keyboard->row([
                    Keyboard::inlineButton(['text' => '📥 Setor Bebas (Lainnya)', 'callback_data' => 'act_tab_beb:' . $student->id]),
                ]);
                $keyboard->row([
                    Keyboard::inlineButton(['text' => '📤 Penarikan (Ambil Tabungan)', 'callback_data' => 'act_tarik:' . $student->id]),
                ]);

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "👩‍🎓 Pilih Kategori Transaksi Tabungan untuk *$student->nama* (NIS: $student->nis):",
                    'parse_mode' => 'Markdown',
                    'reply_markup' => $keyboard
                ]);
                Telegram::deleteMessage(['chat_id' => $chatId, 'message_id' => $messageId]);
            }
        } elseif (str_starts_with($data, 'act_tab_waj:')) {
            $studentId = str_replace('act_tab_waj:', '', $data);
            $student = Student::find($studentId);
            
            if ($student) {
                // Lansung proses 10000 Wajib via handleFinanceTransaction
                Telegram::deleteMessage(['chat_id' => $chatId, 'message_id' => $messageId]);
                return $this->handleFinanceTransaction($chatId, 'TABUNG', $student->nis, 10000, 'Wajib');
            }
        } elseif (str_starts_with($data, 'act_tab_beb:')) {
            $studentId = str_replace('act_tab_beb:', '', $data);
            $student = Student::find($studentId);
            
            if ($student) {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Ketikkan nominal Setoran Tabungan Bebas untuk NIS: " . $student->nis . "\n(Kirim angka saja, contoh: 50000)",
                    'reply_markup' => Keyboard::forceReply(['selective' => true])
                ]);
                Telegram::deleteMessage(['chat_id' => $chatId, 'message_id' => $messageId]);
            }
        } elseif (str_starts_with($data, 'act_tarik:')) {
            $studentId = str_replace('act_tarik:', '', $data);
            $student = Student::find($studentId);
            
            if ($student) {
                $totalSetor = Saving::where('student_id', $student->id)->where('type', 'Setor')->sum('amount');
                $totalTarik = Saving::where('student_id', $student->id)->where('type', 'Tarik')->sum('amount');
                $saldo = $totalSetor - $totalTarik;
                $formattedSaldo = number_format($saldo, 0, ',', '.');

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Saldo Tabungan *" . $student->nama . "* saat ini:\n*Rp $formattedSaldo*\n\nKetikkan nominal Penarikan Tabungan untuk NIS: *" . $student->nis . "*\n(Kirim angka saja, contoh: 50000)",
                    'parse_mode' => 'Markdown',
                    'reply_markup' => Keyboard::forceReply(['selective' => true])
                ]);
                Telegram::deleteMessage(['chat_id' => $chatId, 'message_id' => $messageId]);
            }
        }


        return response('OK', 200);
    }
}
