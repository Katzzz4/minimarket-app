<x-app-layout>
    <x-slot name="title">Cabang</x-slot>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-sm font-semibold">Daftar Cabang</h2>
            <a href="{{ route('branches.create') }}" class="text-xs bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Cabang</a>
        </div>

        <div class="grid grid-cols-3 gap-4">
            @forelse($branches as $branch)
            <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-200 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-sm font-semibold">{{ $branch->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $branch->city }}</p>
                    </div>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Aktif</span>
                </div>
                <p class="text-xs text-gray-400 mb-3">{{ $branch->address ?? '-' }}</p>
                <div class="flex gap-2 justify-end">
                    <a href="{{ route('branches.edit', $branch->id) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Hapus cabang ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-3 py-8 text-center text-gray-400">Belum ada cabang</div>
            @endforelse
        </div>
        <div class="mt-4">{{ $branches->links() }}</div>
    </div>
</x-app-layout>