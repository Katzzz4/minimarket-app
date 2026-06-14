<x-app-layout>
    <x-slot name="title">Tambah Pegawai</x-slot>
    <div class="max-w-lg">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold mb-5">Tambah Pegawai Baru</h2>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-xs text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-xs text-gray-600 mb-1">Password</label>
                    <input type="password" name="password" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-xs text-gray-600 mb-1">Cabang</label>
                    <select name="branch_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                        <option value="">-- Pilih Cabang --</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }} - {{ $branch->city }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-6">
                    <label class="block text-xs text-gray-600 mb-1">Role</label>
                    <select name="role" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 text-white text-sm px-5 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                    <a href="{{ route('users.index') }}" class="text-sm text-gray-500 px-5 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>