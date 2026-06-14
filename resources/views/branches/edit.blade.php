<x-app-layout>
    <x-slot name="title">Edit Cabang</x-slot>
    <div class="max-w-lg">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold mb-5">Edit Cabang</h2>
            <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-xs text-gray-600 mb-1">Nama Cabang</label>
                    <input type="text" name="name" value="{{ old('name', $branch->name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-xs text-gray-600 mb-1">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $branch->city) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                    @error('city')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-6">
                    <label class="block text-xs text-gray-600 mb-1">Alamat</label>
                    <textarea name="address" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ old('address', $branch->address) }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 text-white text-sm px-5 py-2 rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
                    <a href="{{ route('branches.index') }}" class="text-sm text-gray-500 px-5 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>