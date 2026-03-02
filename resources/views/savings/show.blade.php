<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('savings.index') }}" class="inline-flex items-center text-sm font-semibold text-sage hover:text-emerald-800 transition-colors mb-2">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Siswa
                </a>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    Riwayat Tabungan: {{ $student->nama }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Rekapitulasi setoran dan penarikan tabungan siswa.</p>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 shadow-sm">
            <ul class="list-disc pl-5 font-medium text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Input -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Saldo Card -->
            <div class="bg-gradient-to-br from-sage to-emerald-900 rounded-[2rem] shadow-lg shadow-sage/30 p-8 relative overflow-hidden text-white">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <i data-lucide="piggy-bank" class="w-32 h-32 transform rotate-12"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-emerald-100 font-semibold tracking-wide uppercase text-sm mb-2 flex items-center gap-2">
                        <i data-lucide="wallet" class="w-4 h-4"></i> Total Saldo Saat Ini
                    </p>
                    <h3 class="text-4xl font-black tracking-tight">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                    <div class="mt-8 pt-6 border-t border-emerald-600/50 flex items-center justify-between text-sm font-medium text-emerald-50">
                        <div>NIS: {{ $student->nis }}</div>
                        <div>{{ $student->classroom->nama_kelas ?? '' }}</div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-sage"></i>
                    Input Transaksi Baru
                </h3>
                <form action="{{ route('savings.store') }}" method="POST" x-data="{ type: 'Setor', kategori: 'Bebas', amount: '' }">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="date" class="block text-sm font-bold text-gray-700 mb-1.5">Tanggal</label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required class="block w-full px-4 py-2.5 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm font-medium text-gray-700">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Transaksi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="Setor" x-model="type" class="peer sr-only">
                                    <div class="flex items-center justify-center p-3 rounded-xl border border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 text-gray-500 peer-checked:text-emerald-600 font-bold transition-all shadow-sm">
                                        <i data-lucide="arrow-down-circle" class="w-4 h-4 mr-2"></i> Setor
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="Tarik" x-model="type" class="peer sr-only">
                                    <div class="flex items-center justify-center p-3 rounded-xl border border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 text-gray-500 peer-checked:text-amber-600 font-bold transition-all shadow-sm">
                                        <i data-lucide="arrow-up-circle" class="w-4 h-4 mr-2"></i> Tarik
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Kategori (Wajib/Bebas) hanya relevan untuk Setor -->
                        <div x-show="type === 'Setor'" x-transition class="pt-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Setoran</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="kategori" value="Bebas" x-model="kategori" @change="amount = ''" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center p-2 rounded-xl border border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-gray-500 peer-checked:text-blue-600 font-bold transition-all shadow-sm text-sm">
                                        <span>Bebas</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="kategori" value="Wajib" x-model="kategori" @change="amount = '10000'" class="peer sr-only">
                                    <div class="flex flex-col items-center justify-center p-2 rounded-xl border border-gray-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 text-gray-500 peer-checked:text-purple-600 font-bold transition-all shadow-sm text-sm">
                                        <span>Wajib Mingguan</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-bold text-gray-700 mb-1.5">Nominal (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold text-sm">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount" x-model="amount" :readonly="type === 'Setor' && kategori === 'Wajib'" placeholder="0" required min="1000" class="block w-full pl-11 pr-4 py-2.5 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm font-bold text-gray-900 text-lg" :class="{'bg-gray-100 cursor-not-allowed': type === 'Setor' && kategori === 'Wajib'}">
                            </div>
                            <p x-show="type === 'Setor' && kategori === 'Wajib'" class="text-xs text-purple-600 font-medium mt-1">Nominal dikunci Rp 10.000 untuk setoran Wajib.</p>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-bold text-gray-700 mb-1.5">Catatan (Opsi)</label>
                            <textarea name="notes" id="notes" rows="2" class="block w-full px-4 py-2.5 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm text-sm text-gray-700" placeholder="Contoh: Titipan Ayah"></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="mt-6 w-full flex items-center justify-center px-4 py-3 bg-sage hover:bg-emerald-800 text-white font-semibold rounded-xl transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5">
                        <i data-lucide="save" class="w-5 h-5 mr-2"></i> Proses Transaksi
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                        <i data-lucide="history" class="w-5 h-5 text-gray-400"></i>
                        Riwayat Transaksi
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold">Tanggal</th>
                                <th scope="col" class="px-6 py-4 font-bold">Jenis</th>
                                <th scope="col" class="px-6 py-4 font-bold text-right">Nominal</th>
                                <th scope="col" class="px-6 py-4 font-bold">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $txn)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($txn->date)->format('d M Y') }}
                                        <div class="text-[10px] text-gray-400 font-normal">{{ $txn->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1 items-start">
                                            @if($txn->type === 'Setor')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold shadow-sm w-max">
                                                    <i data-lucide="arrow-down-circle" class="w-3.5 h-3.5"></i> Setoran
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold shadow-sm w-max">
                                                    <i data-lucide="arrow-up-circle" class="w-3.5 h-3.5"></i> Penarikan
                                                </span>
                                            @endif
                                            
                                            @if($txn->kategori === 'Wajib')
                                                <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-[10px] font-bold w-max uppercase tracking-wider border border-purple-200">Wajib</span>
                                            @elseif($txn->type === 'Setor' && $txn->kategori === 'Bebas')
                                                <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-bold w-max uppercase tracking-wider border border-blue-200">Bebas</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-right {{ $txn->type === 'Setor' ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $txn->type === 'Setor' ? '+' : '-' }} Rp {{ number_format($txn->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">
                                        {{ $txn->notes ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-100 text-gray-300">
                                            <i data-lucide="file-x" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada transaksi tabungan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
