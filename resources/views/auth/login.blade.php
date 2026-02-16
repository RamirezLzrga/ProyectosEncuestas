@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-uaemex">
    <div class="bg-white p-8 rounded-3xl shadow-lg w-full max-w-md relative">
        <!-- Logo (Placeholder) -->
        <div class="flex justify-center -mt-16 mb-4">
            <div class="bg-white p-2 rounded-xl shadow-md">
                <div class="bg-uaemex text-white font-bold text-2xl h-16 w-16 flex items-center justify-center rounded-lg">
                    UA
                </div>
            </div>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">SIEI UAEMex</h2>
            <p class="text-gray-500 text-sm">Sistema Integral de Evaluación Institucional</p>
            <div class="w-12 h-1 bg-yellow-500 mx-auto mt-2 rounded"></div>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase mb-2">Usuario Institucional</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-uaemex">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="email" name="email" id="email" required 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-uaemex focus:ring-1 focus:ring-uaemex transition duration-200"
                        placeholder="admin@uaemex.mx" value="{{ old('email') }}">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
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

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="form-checkbox text-uaemex rounded h-4 w-4 mr-2 border-gray-300 focus:ring-uaemex">
                    Recordar acceso
                </label>
                <a href="#" class="text-sm text-uaemex font-bold hover:underline">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="w-full btn-uaemex text-white font-bold py-3 rounded-lg shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-lock"></i> INICIAR SESIÓN
            </button>
        </form>

        <div class="mt-8 bg-gray-50 p-4 rounded-lg border-l-4 border-yellow-500">
            <div class="flex items-start gap-3">
                <div class="mt-1">
                    <i class="fas fa-info-circle text-yellow-600"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-uaemex">Acceso demo</h4>
                    <p class="text-xs text-gray-600">admin@uaemex.mx</p>
                    <p class="text-xs text-gray-600">password</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center">
             <p class="text-xs text-gray-500">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-uaemex font-bold hover:underline">Regístrate aquí</a></p>
        </div>

        <div class="mt-8 text-center flex items-center justify-center gap-2 text-xs text-gray-400">
            <i class="fas fa-bolt text-orange-400"></i>
            <span>Sistema oficial de la <span class="font-bold text-yellow-600">UAEMex</span></span>
            <i class="fas fa-bolt text-orange-400"></i>
        </div>
    </div>
</div>
@endsection
