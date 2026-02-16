@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <!-- Header Section -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Usuarios</h2>
            <p class="text-gray-500 mt-1">Administración de usuarios del sistema</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-green-700 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-800 transition shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-4 items-center mt-6">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-4 items-center w-full">
            <div class="flex items-center gap-3 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                <label class="text-sm font-bold text-gray-600">Rol:</label>
                <select name="role" class="bg-transparent text-sm font-medium focus:outline-none text-gray-800 cursor-pointer">
                    <option value="Todos">Todos</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="editor" {{ request('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                    <option value="viewer" {{ request('role') == 'viewer' ? 'selected' : '' }}>Solo vista</option>
                </select>
            </div>
            
            <div class="flex items-center gap-3 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                <label class="text-sm font-bold text-gray-600">Estado:</label>
                <select name="status" class="bg-transparent text-sm font-medium focus:outline-none text-gray-800 cursor-pointer">
                    <option value="Todos">Todos</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="flex items-center gap-3 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 flex-1">
                <label class="text-sm font-bold text-gray-600">Buscar:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar usuario..." class="bg-transparent text-sm font-medium focus:outline-none text-gray-800 w-full placeholder-gray-400">
            </div>

            <button type="submit" class="bg-uaemex text-white text-sm font-bold px-6 py-2 rounded-lg hover:bg-green-800 transition shadow-md">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-6 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Usuario</th>
                    <th class="px-6 py-4 font-bold">Email</th>
                    <th class="px-6 py-4 font-bold">Rol</th>
                    <th class="px-6 py-4 font-bold">Estado</th>
                    <th class="px-6 py-4 font-bold text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-yellow-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold border border-purple-200">
                                    Administrador
                                </span>
                            @elseif($user->role === 'editor')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-200">
                                    Editor
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold border border-gray-200">
                                    {{ ucfirst($user->role) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="{{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-3 py-1 rounded-full text-xs font-bold">
                                {{ $user->status === 'active' ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex justify-center gap-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="border border-green-600 text-green-700 hover:bg-green-50 px-3 py-1 rounded-lg text-sm font-bold flex items-center gap-1 transition">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border border-red-500 text-red-600 hover:bg-red-50 px-3 py-1 rounded-lg text-sm font-bold flex items-center gap-1 transition">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            No se encontraron usuarios.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->appends(request()->query())->links() }}
    </div>
@endsection
