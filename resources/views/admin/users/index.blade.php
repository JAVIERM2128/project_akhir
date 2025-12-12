@extends('layouts.admin')

@section('title', __('Manajemen Pengguna'))
@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Daftar Pengguna</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->trashed())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Dihapus
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($user->trashed())
                                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900 mr-3" 
                                                            onclick="return confirm('Yakin ingin memulihkan pengguna ini?')">
                                                        Pulihkan
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                                
                                                <div class="relative inline-block text-left">
                                                    <button type="button" 
                                                            class="text-gray-600 hover:text-gray-900 mr-3" 
                                                            onclick="toggleRoleMenu({{ $user->id }})">
                                                        Role
                                                    </button>
                                                    
                                                    <div id="roleMenu{{ $user->id }}" 
                                                         class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1" role="none">
                                                            <form method="POST" action="{{ route('admin.users.changeRole', $user) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" name="role" value="user" class="w-full text-left">Jadikan Customer</button>
                                                            </form>
                                                            <form method="POST" action="{{ route('admin.users.changeRole', $user) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" name="role" value="admin" class="w-full text-left">Jadikan Admin</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Yakin ingin menghapus pengguna ini? Data akan dihapus sementara.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @else
                        <p class="text-gray-600">Tidak ada pengguna ditemukan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleRoleMenu(userId) {
            const menu = document.getElementById('roleMenu' + userId);
            menu.classList.toggle('hidden');
        }
        
        // Tutup menu saat klik di luar
        document.addEventListener('click', function(event) {
            const menus = document.querySelectorAll('[id^="roleMenu"]');
            menus.forEach(function(menu) {
                const button = menu.previousElementSibling;
                if (!menu.contains(event.target) && !button.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
    </script>
@endsection