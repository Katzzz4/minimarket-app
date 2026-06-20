<x-app-layout>
    <x-slot name="title">Cabang</x-slot>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-sm font-semibold text-gray-800">Daftar Cabang</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $branches->total() }} cabang terdaftar</p>
            </div>
            <a href="{{ route('branches.create') }}"
                class="flex items-center gap-2 text-xs bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fa-solid fa-plus"></i> Tambah Cabang
            </a>
        </div>

        <div class="grid grid-cols-3 gap-4">
            @forelse($branches as $branch)
                <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-sm transition-all group cursor-pointer"
                    onclick="window.location='{{ route('branches.show', $branch->id) }}'">

                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                {{ $branch->name }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $branch->city }}</p>
                        </div>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full shrink-0">Aktif</span>
                    </div>

                    <p class="text-xs text-gray-400 mb-4">{{ $branch->address ?? 'Alamat belum diisi' }}</p>

                    <div class="flex justify-end gap-3" onclick="event.stopPropagation()">
                        <a href="{{ route('branches.edit', $branch->id) }}"
                            class="text-xs text-gray-400 hover:text-blue-600">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST"
                            onsubmit="return confirm('Hapus cabang {{ $branch->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-gray-400 hover:text-red-500">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-10 text-center text-gray-300">
                    <i class="fa-solid fa-store text-2xl mb-2 block"></i>
                    Belum ada cabang
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $branches->links() }}</div>
    </div>
</x-app-layout>