<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(auth()->user()->role === 'HOST')
                        <h3 class="text-lg font-bold mb-4">Selamat Datang, {{ auth()->user()->name }}!</h3>
                        <p class="mb-4">Anda login sebagai <span class="font-semibold text-blue-600">HOST</span>.</p>
                        <a href="{{ route('host.laporan.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            📝 Input Laporan Penjualan
                        </a>
                    @elseif(auth()->user()->role === 'MANAJER')
                        <h3 class="text-lg font-bold mb-4">Selamat Datang, {{ auth()->user()->name }}!</h3>
                        <p class="mb-4">Anda login sebagai <span class="font-semibold text-green-600">MANAJER</span>.</p>
                        <a href="{{ route('manager.dashboard') }}" class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            📊 Lihat Dashboard Rekonsiliasi
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>