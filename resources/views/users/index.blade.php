<x-app-layout>
    <x-slot name="title">Pegawai</x-slot>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-sm font-semibold">Daftar Pegawai</h2>
            <a href="{{ route('users.create') }}" class="text-xs bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Pegawai</a>
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-3 font-normal">Nama</th>
                    <th class="text-left pb-3 font-normal">Email</th>
                    <th class="text-left pb-3 font-normal">Cabang</th>
                    <th class="text-left pb-3 font-normal">Role</th>
                    <th class="text-left pb-3 font-normal">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3 font-medium">{{ $user->name }}</td>
                    <td class="py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="py-3">{{ $user->branch->name ?? '-' }}</td>
                    <td class="py-3">
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $user->getRoleNames()->first() ?? '-' }}</span>
                    </td>
                    <td class="py-3 flex gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-400">Belum ada pegawai</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>