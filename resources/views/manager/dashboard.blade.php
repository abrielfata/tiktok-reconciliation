<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight flex items-center">
                    <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Dashboard Rekonsiliasi
                </h2>
                <p class="mt-1 text-sm text-gray-600">Monitoring dan analisis hasil rekonsiliasi penjualan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card 1: Total Data -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Data</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Rekonsiliasi</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Cocok -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Cocok</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['cocok'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['total'] > 0 ? round(($stats['cocok'] / $stats['total']) * 100, 1) : 0 }}% Akurat
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Selisih -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Selisih</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['selisih'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['total'] > 0 ? round(($stats['selisih'] / $stats['total']) * 100, 1) : 0 }}% Data
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Belum Lapor -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Belum Lapor</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['belum_lapor'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Pending</p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100">
            <form method="GET" action="{{ route('manager.dashboard') }}" class="space-y-4">
                <div class="flex items-center space-x-2 mb-4">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900">Filter Data</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Tanggal Dari -->
                    <div>
                        <label for="tanggal_dari" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Dari
                        </label>
                        <input 
                            type="date" 
                            name="tanggal_dari" 
                            id="tanggal_dari"
                            value="{{ request('tanggal_dari') }}"
                            class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                        >
                    </div>

                    <!-- Tanggal Sampai -->
                    <div>
                        <label for="tanggal_sampai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Sampai
                        </label>
                        <input 
                            type="date" 
                            name="tanggal_sampai" 
                            id="tanggal_sampai"
                            value="{{ request('tanggal_sampai') }}"
                            class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                        >
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end space-x-2">
                        <button 
                            type="submit"
                            class="flex-1 bg-gradient-to-r from-blue-600 to-teal-500 hover:from-blue-700 hover:to-teal-600 text-white font-semibold py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        <a 
                            href="{{ route('manager.dashboard') }}"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </a>
                    </div>
                </div>

                @if(request('tanggal_dari') || request('tanggal_sampai'))
                <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-700 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <strong>Filter Aktif:</strong>
                        @if(request('tanggal_dari')) 
                            Dari {{ \Carbon\Carbon::parse(request('tanggal_dari'))->isoFormat('DD MMMM YYYY') }}
                        @endif
                        @if(request('tanggal_sampai'))
                            sampai {{ \Carbon\Carbon::parse(request('tanggal_sampai'))->isoFormat('DD MMMM YYYY') }}
                        @endif
                    </p>
                </div>
                @endif
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Data Rekonsiliasi
                </h3>
            </div>

            @if($data->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Host</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total API</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total Host</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Selisih</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data as $index => $rekonsiliasi)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $data->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($rekonsiliasi->tanggal)->isoFormat('DD MMM YYYY') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-teal-500 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-3">
                                            {{ strtoupper(substr($rekonsiliasi->user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $rekonsiliasi->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($rekonsiliasi->total_api, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                    @if($rekonsiliasi->total_host)
                                        Rp {{ number_format($rekonsiliasi->total_host, 0, ',', '.') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $rekonsiliasi->selisih != 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    Rp {{ number_format($rekonsiliasi->selisih, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($rekonsiliasi->status === 'COCOK')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 shadow-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            COCOK
                                        </span>
                                    @elseif($rekonsiliasi->status === 'SELISIH')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 shadow-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            SELISIH
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 shadow-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            BELUM LAPOR
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $data->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak Ada Data</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        @if(request('tanggal_dari') || request('tanggal_sampai'))
                            Tidak ada data rekonsiliasi untuk filter yang dipilih. Coba ubah filter atau reset.
                        @else
                            Belum ada data rekonsiliasi. Data akan muncul setelah HOST melaporkan penjualan.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>