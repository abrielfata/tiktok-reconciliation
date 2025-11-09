<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-lg sm:text-2xl text-gray-900 leading-tight flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="truncate">Edit Laporan Penjualan</span>
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-gray-600">Perbaiki data laporan yang sudah Anda submit</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <div class="mb-4 sm:mb-6">
                <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Edit Laporan</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Perbarui data penjualan untuk tanggal {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d/m/Y') }}</p>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
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
            <form method="POST" action="{{ route('host.laporan.update', $laporan->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

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
                        value="{{ old('tanggal_laporan', $laporan->tanggal_laporan->format('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition duration-150 ease-in-out @error('tanggal_laporan') border-red-500 @enderror"
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
                            value="{{ old('total_penjualan_host', $laporan->total_penjualan_host) }}"
                            placeholder="0"
                            required
                            class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition duration-150 ease-in-out @error('total_penjualan_host') border-red-500 @enderror"
                        >
                    </div>
                    @error('total_penjualan_host')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">Masukkan total penjualan dalam rupiah (contoh: 1500000)</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 sm:flex-none bg-gray-900 hover:bg-gray-800 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('host.laporan.create') }}" 
                       class="flex-1 sm:flex-none bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                </div>
            </form>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex">
                    <svg class="h-5 w-5 text-gray-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3 text-sm text-gray-600">
                        <p class="font-semibold mb-1 text-gray-900">Catatan:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Data yang diperbarui akan digunakan untuk rekonsiliasi otomatis</li>
                            <li>Pastikan data yang diinput sudah akurat</li>
                            <li>Anda tidak dapat mengubah tanggal menjadi tanggal yang sudah ada laporannya</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

