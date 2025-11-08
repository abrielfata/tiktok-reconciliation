<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Laporan Penjualan Harian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Message Sukses --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Form Input Laporan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">📝 Buat Laporan Baru</h3>
                    
                    <form method="POST" action="{{ route('host.laporan.store') }}" class="space-y-4">
                        @csrf

                        {{-- Input Tanggal Laporan --}}
                        <div>
                            <label for="tanggal_laporan" class="block font-medium text-sm text-gray-700">
                                Tanggal Laporan <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="tanggal_laporan" 
                                name="tanggal_laporan" 
                                value="{{ old('tanggal_laporan') }}"
                                max="{{ date('Y-m-d') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tanggal_laporan') border-red-500 @enderror"
                                required
                            >
                            @error('tanggal_laporan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                💡 Pilih tanggal transaksi yang ingin dilaporkan (maksimal hari ini)
                            </p>
                        </div>

                        {{-- Input Total Penjualan --}}
                        <div>
                            <label for="total_penjualan_host" class="block font-medium text-sm text-gray-700">
                                Total Penjualan (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="total_penjualan_host" 
                                name="total_penjualan_host" 
                                value="{{ old('total_penjualan_host') }}"
                                step="0.01"
                                min="0"
                                placeholder="Contoh: 1500000"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('total_penjualan_host') border-red-500 @enderror"
                                required
                            >
                            @error('total_penjualan_host')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                💡 Masukkan total penjualan dalam format angka (tanpa titik/koma pemisah ribuan)
                            </p>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center gap-4">
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                💾 Simpan Laporan
                            </button>
                            
                            <a 
                                href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                ← Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- History Laporan (7 Terakhir) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">📊 History Laporan (7 Terakhir)</h3>
                    
                    @if($laporans->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                            <p>Belum ada laporan. Silakan buat laporan pertama Anda!</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Laporan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Penjualan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dibuat Pada
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($laporans as $index => $laporan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($laporan->total_penjualan_host, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $laporan->created_at->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>