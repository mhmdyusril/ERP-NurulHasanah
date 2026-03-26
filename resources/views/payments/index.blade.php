<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl md:text-3xl text-gray-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="wallet" class="w-8 h-8 text-sage"></i>
                    {{ __('Keuangan (Tagihan SPP)') }}
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Manajemen pembayaran SPP bulanan siswa.</p>
            </div>
        </div>
    </x-slot>

    <!-- Filter and Summary Section -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 mb-8 p-6">
        <form action="{{ route('payments.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-6">
            <div class="w-full sm:w-1/3">
                <label for="month" class="block text-sm font-bold text-gray-700 mb-2">Pilih Bulan & Tahun Tagihan</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="w-5 h-5 text-gray-400 group-focus-within:text-sage transition-colors"></i>
                    </div>
                    <input type="month" name="month" id="month" value="{{ $month }}" 
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border-gray-200 rounded-xl focus:ring-2 focus:ring-sage/20 focus:border-sage focus:bg-white transition-all shadow-sm font-semibold text-gray-700 cursor-pointer">
                </div>
            </div>
            <div class="w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-sage hover:bg-emerald-800 text-white font-semibold rounded-xl transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 flex justify-center items-center h-[50px]">
                    <i data-lucide="search" class="w-5 h-5 mr-2"></i> Tampilkan
                </button>
            </div>
            
            <div class="sm:ml-auto w-full sm:w-auto mt-4 sm:mt-0">
                @php
                    $terkumpul = 0;
                    $lunas = 0;
                    $belum = 0;
                    foreach($students as $s) {
                        if ($s->payments->count() > 0) {
                            $terkumpul += $s->payments->first()->amount;
                            $lunas++;
                        } else {
                            $belum++;
                        }
                    }
                @endphp
                <div class="bg-gray-50 rounded-xl p-4 flex gap-6 border border-gray-100">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Terkumpul</p>
                        <p class="text-lg font-black text-sage">Rp {{ number_format($terkumpul, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-px bg-gray-200"></div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                        <p class="text-sm font-bold"><span class="text-green-600">{{ $lunas }} Lunas</span> <span class="text-gray-300 mx-1">|</span> <span class="text-red-500">{{ $belum }} Belum</span></p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ── MOBILE CARD LAYOUT ── --}}
    <div class="md:hidden space-y-3">
        @forelse ($students as $student)
            @php
                $isPaid = $student->payments->count() > 0;
                $payment = $isPaid ? $student->payments->first() : null;
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border {{ $isPaid ? 'border-green-100' : 'border-gray-100' }} p-4">
                {{-- Header: nama + status --}}
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-softTeal/20 to-sage/20 flex flex-shrink-0 items-center justify-center text-sage font-bold shadow-sm">
                        {{ substr($student->nama, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-gray-900 truncate">{{ $student->nama }}</div>
                        <div class="text-xs text-gray-400 font-semibold">{{ $student->nis }} &middot; {{ $student->classroom->nama_kelas ?? '-' }}</div>
                    </div>
                    @if($isPaid)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 border border-green-100 rounded-full text-xs font-bold flex-shrink-0">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Lunas
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-600 border border-red-100 rounded-full text-xs font-bold flex-shrink-0">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Belum
                        </span>
                    @endif
                </div>

                {{-- Payment form --}}
                <form action="{{ route('payments.store') }}" method="POST" x-data="{ amount: {{ $billing_categories->first()->default_amount ?? 0 }} }">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="payment_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    <div class="flex gap-2 items-center">
                        <select name="billing_category_id" class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:ring-sage focus:border-sage" @change="amount = $event.target.options[$event.target.selectedIndex].dataset.amount">
                            @foreach($billing_categories as $cat)
                                <option value="{{ $cat->id }}" data-amount="{{ $cat->default_amount }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2 font-semibold text-gray-500 text-xs">Rp</span>
                            <input type="number" name="amount" x-model="amount" class="w-28 pl-7 pr-2 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:ring-sage focus:border-sage" required>
                        </div>
                        <button type="submit" class="px-3 py-2 bg-sage hover:bg-emerald-800 text-white rounded-xl font-bold transition-all text-xs flex items-center gap-1">
                            <i data-lucide="credit-card" class="w-3.5 h-3.5"></i> Bayar
                        </button>
                    </div>
                </form>

                @if($isPaid)
                    <div class="mt-2 flex gap-2 border-t border-gray-50 pt-2">
                        <form action="{{ route('payments.destroy', $student->payments->last()->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan pembayaran terakhir?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-2 py-1 text-red-500 hover:text-white hover:bg-red-500 border border-red-100 rounded-lg text-[10px] font-bold transition-colors flex items-center gap-1">
                                <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Batal (Terakhir)
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm">
                <i data-lucide="users" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
                <p class="font-bold text-gray-900">Data Siswa Kosong</p>
            </div>
        @endforelse
    </div>

    {{-- ── DESKTOP TABLE LAYOUT ── --}}
    <div class="hidden md:block bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider">Anak Didik</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider">Kelas</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center">Periode</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center">Status Tagihan</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right">Aksi Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($students as $student)
                        @php
                            $isPaid = $student->payments->count() > 0;
                            $payment = $isPaid ? $student->payments->first() : null;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-softTeal/20 to-sage/20 flex flex-shrink-0 items-center justify-center text-sage font-bold shadow-sm border border-white">{{ substr($student->nama, 0, 1) }}</div>
                                    <div>
                                        <div class="font-extrabold text-gray-900 text-sm group-hover:text-sage transition-colors">{{ $student->nama }}</div>
                                        <div class="text-xs font-semibold text-gray-400 tracking-wide mt-0.5">{{ $student->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-gray-200 text-gray-700 rounded-lg font-semibold shadow-sm">
                                    <i data-lucide="book-open" class="w-3.5 h-3.5 text-sage"></i>
                                    {{ $student->classroom->nama_kelas ?? 'Belum ada kelas' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600 font-bold">{{ \Carbon\Carbon::parse($month.'-01')->isoFormat('MMMM Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($isPaid)
                                    <div class="flex flex-col gap-1 items-center">
                                        @foreach($student->payments as $payment)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded-full text-[10px] font-bold shadow-sm">
                                                <i data-lucide="check-circle-2" class="w-3 h-3"></i> {{ $payment->billingCategory->name ?? 'SPP' }} LUNAS
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-600 border border-red-200 rounded-full font-bold shadow-sm">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i> BELUM BAYAR
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('payments.store') }}" method="POST" class="inline flex items-center justify-end gap-2" x-data="{ amount: {{ $billing_categories->first()->default_amount ?? 0 }} }">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <input type="hidden" name="payment_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    <select name="billing_category_id" class="w-36 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 focus:ring-sage focus:border-sage shadow-inner" @change="amount = $event.target.options[$event.target.selectedIndex].dataset.amount">
                                        @foreach($billing_categories as $cat)
                                            <option value="{{ $cat->id }}" data-amount="{{ $cat->default_amount }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="relative w-28">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-2 font-semibold text-gray-500 text-xs">Rp</span>
                                        <input type="number" name="amount" x-model="amount" class="w-full pl-7 pr-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-900 focus:ring-sage focus:border-sage shadow-inner" required>
                                    </div>
                                    <button type="submit" class="px-3 py-1.5 bg-sage hover:bg-emerald-800 text-white rounded-lg font-bold transition-all shadow-md shadow-sage/20 hover:-translate-y-0.5 text-xs flex items-center">
                                        Bayar <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
                                    </button>
                                </form>
                                @if($isPaid)
                                    <div class="mt-2 flex justify-end gap-2 border-t border-gray-100 pt-2">
                                        <button class="px-3 py-1 bg-white border border-gray-200 text-gray-700 hover:text-sage rounded-lg font-semibold transition-colors shadow-sm flex items-center text-[10px]" onclick="alert('Fitur cetak invoice segera hadir!')">
                                            <i data-lucide="printer" class="w-3 h-3 mr-1"></i> Cetak Bukti
                                        </button>
                                        <form action="{{ route('payments.destroy', $student->payments->last()->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan pembayaran terakhir?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-2 py-1 text-red-500 hover:text-white hover:bg-red-500 border border-red-100 rounded-lg text-[10px] font-bold transition-colors shadow-sm flex items-center">
                                                <i data-lucide="rotate-ccw" class="w-3 h-3 mr-1"></i> Batal (Terakhir)
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                    <i data-lucide="users" class="w-10 h-10 text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Data Siswa Kosong</h3>
                                <p class="text-gray-500 max-w-sm mx-auto mb-6">Belum ada data siswa untuk diproses pembayarannya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
