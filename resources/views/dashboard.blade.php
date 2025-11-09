<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-900 leading-tight">
                    Selamat Datang, {{ auth()->user()->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Role: <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                        {{ auth()->user()->role }}
                    </span>
                </p>
            </div>
            <div class="text-xs sm:text-sm text-gray-500">
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-1">Sistem Rekonsiliasi Penjualan TikTok</h3>
                    <p class="text-gray-600 text-xs sm:text-sm">
                        Otomasi proses rekonsiliasi untuk meningkatkan akurasi dan efisiensi
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 sm:mb-8">
            <!-- Stat Card 1 -->
            <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors duration-200 p-4 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status Sistem</p>
                        <p class="text-lg sm:text-xl font-semibold text-gray-900 mt-1">Aktif</p>
                    </div>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors duration-200 p-4 sm:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Auto Sync</p>
                        <p class="text-lg sm:text-xl font-semibold text-gray-900 mt-1">02:00 WIB</p>
                    </div>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors duration-200 p-4 sm:p-5 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Role Anda</p>
                        <p class="text-lg sm:text-xl font-semibold text-gray-900 mt-1">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards Based on Role -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            @if(auth()->user()->role === 'HOST')
            <!-- Host - Input Laporan -->
            <a href="{{ route('host.laporan.create') }}" class="group">
                <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-900 transition-all duration-200 p-4 sm:p-6 h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Input Laporan Penjualan</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mb-4">
                        Laporkan hasil penjualan harian Anda untuk proses rekonsiliasi otomatis
                    </p>
                    <div class="flex items-center text-sm sm:text-base text-gray-700 font-medium group-hover:text-gray-900">
                        Buat Laporan Baru
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Host - Riwayat -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Riwayat Laporan</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Lihat history laporan yang sudah Anda submit sebelumnya
                </p>
                <div class="text-sm text-gray-500">
                    Tersedia di halaman Input Laporan
                </div>
            </div>

            @elseif(auth()->user()->role === 'MANAJER')
            <!-- Manager - Dashboard Rekonsiliasi -->
            <a href="{{ route('manager.dashboard') }}" class="group">
                <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-900 transition-all duration-200 p-6 h-full">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Dashboard Rekonsiliasi</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Monitor dan analisis hasil rekonsiliasi dari semua HOST
                    </p>
                    <div class="flex items-center text-gray-700 font-medium group-hover:text-gray-900">
                        Lihat Dashboard
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Manager - Reports -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Otomatis</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Sistem secara otomatis menghasilkan laporan rekonsiliasi setiap hari
                </p>
                <div class="text-sm text-gray-600 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Sinkronisasi otomatis setiap hari pukul 02:00 WIB
                </div>
            </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="mt-6 sm:mt-8 bg-gray-50 border border-gray-200 rounded-lg p-4 sm:p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-900">Informasi Sistem</h3>
                    <div class="mt-2 text-xs sm:text-sm text-gray-600 space-y-1">
                        <p>• Sistem akan melakukan rekonsiliasi otomatis setiap hari pukul 02:00 WIB</p>
                        <p>• Data akan dibandingkan dengan TikTok API secara real-time</p>
                        <p>• Status rekonsiliasi: COCOK, SELISIH, atau HOST_BELUM_LAPOR</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>