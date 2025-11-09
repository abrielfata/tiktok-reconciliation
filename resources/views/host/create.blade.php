<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight flex items-center">
                    <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Input Laporan Penjualan
                </h2>
                <p class="mt-1 text-sm text-gray-600">Laporkan hasil penjualan harian Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Input (Kiri) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-teal-500 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Form Laporan Baru</h3>
                        <p class="text-sm text-gray-600 mt-1">Isi data penjualan Anda hari ini</p>
                    </div>

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg animate-pulse">
                            <div class="flex">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="ml-3 text-sm text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="ml-3 text-sm text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('host.laporan.store') }}" class="space-y-6">
                        @csrf

                        <!-- Tanggal Laporan -->
                        <div>
                            <label for="tanggal_laporan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Laporan <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="tanggal_laporan" 
                                id="tanggal_laporan"
                                max="{{ date('Y-m-d') }}"
                                value="{{ old('tanggal_laporan', date('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150 ease-in-out @error('tanggal_laporan') border-red-500 @enderror"
                            >
                            @error('tanggal_laporan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total Penjualan -->
                        <div>
                            <label for="total_penjualan_host" class="block text-sm font-semibold text-gray-700 mb-2">
                                Total Penjualan (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">
                                    Rp
                                </span>
                                <input 
                                    type="number" 
                                    name="total_penjualan_host" 
                                    id="total_penjualan_host"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('total_penjualan_host') }}"
                                    placeholder="0"
                                    required
                                    class="w-full pl-12 pr-4 py-3 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-150 ease-in-out @error('total_penjualan_host') border-red-500 @enderror"
                                >
                            </div>
                            @error('total_penjualan_host')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Masukkan total penjualan dalam rupiah (contoh: 1500000)</p>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-teal-500 hover:from-blue-700 hover:to-teal-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Laporan
                        </button>
                    </form>

                    <!-- Info Box -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3 text-sm text-blue-700">
                                <p class="font-semibold mb-1">Tips:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Lapor penjualan setiap hari</li>
                                    <li>Pastikan data akurat</li>
                                    <li>Hanya 1 laporan per tanggal</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Table (Kanan) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Riwayat Laporan (7 Terakhir)
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">History laporan yang sudah Anda submit</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                            {{ $laporans->count() }} Laporan
                        </span>
                    </div>

                    @if($laporans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Total Penjualan
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Waktu Submit
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($laporans as $index => $laporan)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->isoFormat('DD MMMM YYYY') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="text-sm font-bold text-gray-900">
                                                Rp {{ number_format($laporan->total_penjualan_host, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($laporan->created_at)->diffForHumans() }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Laporan</h3>
                            <p class="mt-2 text-sm text-gray-500">Mulai dengan membuat laporan pertama Anda di form sebelah kiri</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>