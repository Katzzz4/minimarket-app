<x-app-layout>
    <x-slot name="title">Pegawai</x-slot>

    <div class="bg-white rounded-xl border border-gray-200 p-5">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-sm font-semibold text-gray-800">Daftar Pegawai</h2>
                <p class="text-xs text-gray-400 mt-0.5">Total {{ $users->total() }} pegawai terdaftar</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="flex items-center gap-2 text-xs bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fa-solid fa-plus"></i> Tambah Pegawai
            </a>
        </div>

        {{-- Filter & Search --}}
        <form method="GET" class="flex items-center gap-3 mb-5 flex-wrap">
            {{-- Search --}}
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-300 text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="w-full border border-gray-200 rounded-lg pl-8 pr-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            {{-- Filter Cabang --}}
            <select name="branch_id"
                class="border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Role --}}
            <select name="role"
                class="border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300">
                <option value="">Semua Role</option>
                @foreach(['Owner', 'Manajer Toko', 'Supervisor', 'Kasir', 'Pegawai Gudang'] as $role)
                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-blue-700">
                Cari
            </button>

            @if(request()->hasAny(['search', 'role', 'branch_id']))
                <a href="{{ route('users.index') }}" class="text-xs text-gray-400 hover:text-red-500">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </form>

        {{-- Tabel --}}
        <table class="w-full text-xs">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-left pb-3 font-medium px-2">#</th>
                    <th class="text-left pb-3 font-medium px-2">Nama</th>
                    <th class="text-left pb-3 font-medium px-2">Email</th>
                    <th class="text-left pb-3 font-medium px-2">Cabang</th>
                    <th class="text-left pb-3 font-medium px-2">Role</th>
                    <th class="text-center pb-3 font-medium px-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-2 text-gray-400">{{ $users->firstItem() + $i }}</td>
                        <td class="py-3 px-2 font-medium text-gray-800">{{ $user->name }}</td>
                        <td class="py-3 px-2 text-gray-500">{{ $user->email }}</td>
                        <td class="py-3 px-2">{{ $user->branch->name ?? '-' }}</td>
                        <td class="py-3 px-2">
                            @php $roleName = $user->getRoleNames()->first() @endphp
                            @if($roleName === 'Owner')
                                <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">{{ $roleName }}</span>
                            @elseif($roleName === 'Manajer Toko')
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $roleName }}</span>
                            @elseif($roleName === 'Supervisor')
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $roleName }}</span>
                            @elseif($roleName === 'Kasir')
                                <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $roleName }}</span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full">{{ $roleName ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-300">
                            <i class="fa-solid fa-users text-2xl mb-2 block"></i>
                            Tidak ada pegawai ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->appends(request()->query())->links() }}
        </div>

    </div>
</x-app-layout>