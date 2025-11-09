<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-lg sm:text-2xl text-gray-900 leading-tight flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="truncate">Tracking Aktivitas</span>
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-gray-600">Riwayat semua aktivitas: menambah, mengubah, dan menghapus laporan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dibuat</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            {{ $stats['created'] }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Diubah</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            {{ $stats['updated'] }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dihapus</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            {{ $stats['deleted'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities List -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Semua Aktivitas</h3>
            
            @if($activities->count() > 0)
                <div class="space-y-4">
                    @foreach($activities as $activity)
                    <div class="border-l-4 {{ $activity->action === 'created' ? 'border-green-500' : ($activity->action === 'updated' ? 'border-blue-500' : 'border-red-500') }} bg-gray-50 p-4 rounded-r-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2 flex-wrap gap-2">
                                    @if($activity->action === 'created')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Dibuat
                                        </span>
                                    @elseif($activity->action === 'updated')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Diubah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Dihapus
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-500">
                                        {{ $activity->created_at->isoFormat('DD MMMM YYYY, HH:mm') }}
                                    </span>
                                    @if($activity->laporanHost)
                                        <a href="{{ route('host.laporan.history', $activity->laporan_host_id) }}" 
                                           class="text-xs text-gray-600 hover:text-gray-900 underline">
                                            Lihat Detail
                                        </a>
                                    @endif
                                </div>

                                @if($activity->action === 'created')
                                    <div class="mt-2 space-y-1 text-sm text-gray-700">
                                        <p><span class="font-medium">Tanggal Laporan:</span> {{ \Carbon\Carbon::parse($activity->tanggal_laporan_new)->format('d/m/Y') }}</p>
                                        <p><span class="font-medium">Total Penjualan:</span> Rp {{ number_format($activity->total_penjualan_host_new, 0, ',', '.') }}</p>
                                    </div>
                                @elseif($activity->action === 'updated')
                                    <div class="mt-2 space-y-2 text-sm">
                                        @if($activity->tanggal_laporan_old != $activity->tanggal_laporan_new)
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-medium text-gray-700">Tanggal:</span>
                                            <span class="text-red-600 line-through">{{ \Carbon\Carbon::parse($activity->tanggal_laporan_old)->format('d/m/Y') }}</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                            <span class="text-green-600 font-medium">{{ \Carbon\Carbon::parse($activity->tanggal_laporan_new)->format('d/m/Y') }}</span>
                                        </div>
                                        @endif
                                        @if($activity->total_penjualan_host_old != $activity->total_penjualan_host_new)
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-medium text-gray-700">Total:</span>
                                            <span class="text-red-600 line-through">Rp {{ number_format($activity->total_penjualan_host_old, 0, ',', '.') }}</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                            <span class="text-green-600 font-medium">Rp {{ number_format($activity->total_penjualan_host_new, 0, ',', '.') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-2 space-y-1 text-sm text-gray-700">
                                        <p><span class="font-medium">Tanggal Laporan:</span> {{ \Carbon\Carbon::parse($activity->tanggal_laporan_old)->format('d/m/Y') }}</p>
                                        <p><span class="font-medium">Total Penjualan:</span> Rp {{ number_format($activity->total_penjualan_host_old, 0, ',', '.') }}</p>
                                    </div>
                                @endif

                                @if($activity->notes)
                                    <p class="mt-2 text-xs text-gray-500 italic">{{ $activity->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $activities->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Aktivitas</h3>
                    <p class="mt-2 text-sm text-gray-500">Aktivitas Anda akan muncul di sini setelah Anda membuat, mengubah, atau menghapus laporan</p>
                    <div class="mt-6">
                        <a href="{{ route('host.laporan.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Laporan Pertama
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

