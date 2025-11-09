<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-lg sm:text-2xl text-gray-900 leading-tight flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="truncate">History Perubahan Laporan</span>
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-gray-600">
                    Tanggal: {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d/m/Y') }}
                </p>
            </div>
            <a href="{{ route('host.laporan.create') }}" 
               class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Current Data Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6 mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">
                @if(isset($laporan->deleted) && $laporan->deleted)
                    Data Laporan (Sudah Dihapus)
                @else
                    Data Saat Ini
                @endif
            </h3>
            @if(isset($laporan->deleted) && $laporan->deleted)
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700">Laporan ini sudah dihapus. History masih tersimpan untuk keperluan audit.</p>
                </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal Laporan</p>
                    <p class="mt-1 text-sm sm:text-base font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Penjualan</p>
                    <p class="mt-1 text-sm sm:text-base font-semibold text-gray-900">
                        Rp {{ number_format($laporan->total_penjualan_host, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- History List -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Riwayat Perubahan</h3>
            
            @if($histories->count() > 0)
                <div class="space-y-4">
                    @foreach($histories as $history)
                    <div class="border-l-4 {{ $history->action === 'created' ? 'border-green-500' : ($history->action === 'updated' ? 'border-blue-500' : 'border-red-500') }} bg-gray-50 p-4 rounded-r-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    @if($history->action === 'created')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Dibuat
                                        </span>
                                    @elseif($history->action === 'updated')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            Diperbarui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Dihapus
                                        </span>
                                    @endif
                                    <span class="ml-3 text-xs text-gray-500">
                                        {{ $history->created_at->isoFormat('DD MMMM YYYY, HH:mm') }}
                                    </span>
                                </div>

                                @if($history->action === 'created')
                                    <div class="mt-2 space-y-1 text-sm text-gray-700">
                                        <p><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($history->tanggal_laporan_new)->format('d/m/Y') }}</p>
                                        <p><span class="font-medium">Total Penjualan:</span> Rp {{ number_format($history->total_penjualan_host_new, 0, ',', '.') }}</p>
                                    </div>
                                @elseif($history->action === 'updated')
                                    <div class="mt-2 space-y-2 text-sm">
                                        @if($history->tanggal_laporan_old != $history->tanggal_laporan_new)
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-700 w-24">Tanggal:</span>
                                            <span class="text-red-600 line-through mr-2">{{ \Carbon\Carbon::parse($history->tanggal_laporan_old)->format('d/m/Y') }}</span>
                                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                            <span class="text-green-600 font-medium">{{ \Carbon\Carbon::parse($history->tanggal_laporan_new)->format('d/m/Y') }}</span>
                                        </div>
                                        @endif
                                        @if($history->total_penjualan_host_old != $history->total_penjualan_host_new)
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-700 w-24">Total:</span>
                                            <span class="text-red-600 line-through mr-2">Rp {{ number_format($history->total_penjualan_host_old, 0, ',', '.') }}</span>
                                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                            <span class="text-green-600 font-medium">Rp {{ number_format($history->total_penjualan_host_new, 0, ',', '.') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-2 space-y-1 text-sm text-gray-700">
                                        <p><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($history->tanggal_laporan_old)->format('d/m/Y') }}</p>
                                        <p><span class="font-medium">Total Penjualan:</span> Rp {{ number_format($history->total_penjualan_host_old, 0, ',', '.') }}</p>
                                    </div>
                                @endif

                                @if($history->notes)
                                    <p class="mt-2 text-xs text-gray-500 italic">{{ $history->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-sm font-medium text-gray-900">Belum Ada History</h3>
                    <p class="mt-2 text-xs text-gray-500">History perubahan akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

