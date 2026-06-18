<x-app-layout>
    <x-slot name="title">Cabang</x-slot>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-sm font-semibold text-gray-800">Daftar Cabang</h2>
            <a href="{{ route('branches.create') }}"
               class="text-xs font-medium bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                + Tambah Cabang
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($branches as $branch)
            <div class="border border-gray-200 rounded-xl p-4 hover:border-indigo-200 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">{{ $branch->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $branch->city }}</p>
                    </div>
                    <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-medium">Aktif</span>
                </div>
                <p class="text-xs text-gray-400 mb-3">{{ $branch->address ?? '-' }}</p>
                <div class="flex gap-2 justify-end pt-2 border-t border-gray-100">
                    <a href="{{ route('branches.edit', $branch->id) }}"
                       class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md hover:bg-indigo-100 transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Hapus cabang ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-xs font-medium text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md hover:bg-rose-100 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 py-8 text-center text-gray-400">Belum ada cabang</div>
            @endforelse
        </div>
        <div class="mt-4">{{ $branches->links() }}</div>
    </div>
</x-app-layout>