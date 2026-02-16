@extends('layouts.app')

@section('title', $survey->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('surveys.index') }}" class="text-gray-500 hover:text-gray-700 font-bold flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <div class="flex gap-2">
                <a href="{{ route('surveys.public', $survey->id) }}" target="_blank" class="bg-purple-50 text-purple-600 px-4 py-2 rounded-lg font-bold hover:bg-purple-100 transition flex items-center gap-2">
                    <i class="fas fa-external-link-alt"></i> Responder / Enlace Público
                </a>
                <a href="{{ route('surveys.edit', $survey->id) }}" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg font-bold hover:bg-blue-100 transition">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
            </div>
        </div>

        <!-- Survey Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="h-3 bg-uaemex w-full"></div>
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $survey->title }}</h1>
                <p class="text-gray-600 mb-6">{{ $survey->description }}</p>
                
                <div class="flex flex-wrap gap-4 text-sm text-gray-500 border-t border-gray-100 pt-6">
                    <div class="flex items-center gap-2">
                        <i class="far fa-user text-uaemex"></i>
                        <span>Autor: {{ $survey->user->name ?? 'Desconocido' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="far fa-calendar-alt text-uaemex"></i>
                        <span>Creada: {{ $survey->created_at->format('d/m/Y h:i A') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="far fa-clock text-uaemex"></i>
                        <span>Vigencia: {{ \Carbon\Carbon::parse($survey->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($survey->end_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="{{ $survey->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-xs font-bold px-2 py-1 rounded-full">
                            {{ $survey->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-gray-800 ml-2">Preguntas ({{ count($survey->questions ?? []) }})</h3>
            
            @if(isset($survey->questions) && count($survey->questions) > 0)
                @foreach($survey->questions as $index => $question)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-lg font-bold text-gray-800">
                                {{ $index + 1 }}. {{ $question['text'] }}
                                @if(isset($question['required']) && $question['required'])
                                    <span class="text-red-500 text-sm">*</span>
                                @endif
                            </h4>
                            <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded uppercase">
                                {{ $question['type'] }}
                            </span>
                        </div>

                        <!-- Opciones según el tipo -->
                        <div class="pl-4 border-l-2 border-gray-100">
                            @if($question['type'] === 'short_text')
                                <div class="border-b border-gray-300 border-dashed py-2 text-gray-400 text-sm italic w-1/2">Respuesta corta...</div>
                            @elseif($question['type'] === 'paragraph')
                                <div class="border-b border-gray-300 border-dashed py-2 text-gray-400 text-sm italic w-full">Respuesta larga...</div>
                                <div class="border-b border-gray-300 border-dashed py-2 w-full"></div>
                            @elseif(in_array($question['type'], ['multiple_choice', 'checkboxes', 'dropdown']))
                                @if(isset($question['options']) && is_array($question['options']))
                                    <div class="space-y-2">
                                        @foreach($question['options'] as $option)
                                            <div class="flex items-center gap-2">
                                                @if($question['type'] === 'checkboxes')
                                                    <i class="far fa-square text-gray-400"></i>
                                                @elseif($question['type'] === 'dropdown')
                                                    <span class="text-gray-400 text-xs">▼</span>
                                                @else
                                                    <i class="far fa-circle text-gray-400"></i>
                                                @endif
                                                <span class="text-gray-700">{{ $option }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-10 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500">Esta encuesta no tiene preguntas configuradas.</p>
                </div>
            @endif
        </div>
    </div>
@endsection