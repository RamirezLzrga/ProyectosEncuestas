@extends('layouts.auth')

@section('title', 'Registro')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-uaemex">
    <div class="bg-white p-8 rounded-3xl shadow-lg w-full max-w-md relative">
        
        <div class="text-center mb-8 mt-4">
            <h2 class="text-2xl font-bold text-gray-800">Crear Cuenta</h2>
            <p class="text-gray-500 text-sm">Únete al Sistema Integral de Evaluación</p>
            <div class="w-12 h-1 bg-yellow-500 mx-auto mt-2 rounded"></div>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-xs font-bold text-gray-700 uppercase mb-2">Nombre Completo</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-uaemex">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="name" id="name" required 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-uaemex focus:ring-1 focus:ring-uaemex transition duration-200"
                        placeholder="Juan Pérez" value="{{ old('name') }}">
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase mb-2">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-uaemex">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" required 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-uaemex focus:ring-1 focus:ring-uaemex transition duration-200"
                        placeholder="usuario@uaemex.mx" value="{{ old('email') }}">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase mb-2">Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-yellow-500">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" required 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-uaemex focus:ring-1 focus:ring-uaemex transition duration-200"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase mb-2">Confirmar Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-yellow-500">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-uaemex focus:ring-1 focus:ring-uaemex transition duration-200"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full btn-uaemex text-white font-bold py-3 rounded-lg shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i> REGISTRARSE
            </button>
        </form>

        <div class="mt-6 text-center">
             <p class="text-xs text-gray-500">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-uaemex font-bold hover:underline">Inicia sesión aquí</a></p>
        </div>
    </div>
</div>
@endsection
