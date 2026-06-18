<x-app-layout>
    <x-slot name="title">Akses Ditolak</x-slot>

    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
            <i class="fa-solid fa-lock text-red-500 text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Akses Ditolak</h1>
        <p class="text-sm text-gray-500 mb-6">
            Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm px-5 py-2.5 rounded-lg hover:bg-blue-700">
            <i class="fa-solid fa-house"></i> Kembali ke Dashboard
        </a>
    </div>
</x-app-layout>