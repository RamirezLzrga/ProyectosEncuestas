<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $survey->title }} - SIEI UAEMex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-uaemex { background-color: #0d5c41; }
        .text-uaemex { color: #0d5c41; }
        .btn-uaemex { background-color: #0d5c41; }
        .bg-gold { background-color: #d4af37; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-10">

    <div class="max-w-3xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="h-3 bg-uaemex w-full"></div>
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $survey->title }}</h1>
                <p class="text-gray-600">{{ $survey->description }}</p>
                <div class="mt-4 text-sm text-gray-500 italic">
                    <span class="text-red-500">* Obligatorio</span>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('surveys.store-answer', $survey->id) }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                @foreach($survey->questions as $index => $question)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <div class="mb-4">
                            <label class="font-bold text-gray-800 text-lg block">
                                {{ $question['text'] }}
                                @if(isset($question['required']) && $question['required'])
                                    <span class="text-red-500 ml-1">*</span>
                                @endif
                            </label>
                        </div>

                        <div class="text-gray-700">
                            @if($question['type'] === 'short_text')
                                <input type="text" name="answers[{{ $question['text'] }}]" 
                                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-uaemex focus:bg-white transition" 
                                    placeholder="Tu respuesta"
                                    {{ isset($question['required']) && $question['required'] ? 'required' : '' }}>
                            
                            @elseif($question['type'] === 'paragraph')
                                <textarea name="answers[{{ $question['text'] }}]" 
                                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-uaemex focus:bg-white transition resize-none" 
                                    rows="4"
                                    placeholder="Tu respuesta"
                                    {{ isset($question['required']) && $question['required'] ? 'required' : '' }}></textarea>
                            
                            @elseif($question['type'] === 'multiple_choice')
                                <div class="space-y-3">
                                    @if(isset($question['options']) && is_array($question['options']))
                                        @foreach($question['options'] as $option)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="radio" name="answers[{{ $question['text'] }}]" value="{{ $option }}" 
                                                    class="w-5 h-5 text-uaemex border-gray-300 focus:ring-uaemex"
                                                    {{ isset($question['required']) && $question['required'] ? 'required' : '' }}>
                                                <span class="group-hover:text-uaemex transition">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>

                            @elseif($question['type'] === 'checkboxes')
                                <div class="space-y-3">
                                    @if(isset($question['options']) && is_array($question['options']))
                                        @foreach($question['options'] as $option)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="checkbox" name="answers[{{ $question['text'] }}][]" value="{{ $option }}" 
                                                    class="w-5 h-5 text-uaemex border-gray-300 rounded focus:ring-uaemex">
                                                <span class="group-hover:text-uaemex transition">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer con Botón Enviar -->
            <div class="mt-8 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-shield-alt text-uaemex"></i> Tus datos están protegidos.
                </div>
                <button type="submit" class="bg-uaemex text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-green-900/20 hover:bg-green-800 transition transform hover:-translate-y-1">
                    Enviar Respuesta
                </button>
            </div>
        </form>
    </div>

    <div class="text-center mt-10 mb-6 text-gray-400 text-sm">
        &copy; {{ date('Y') }} SIEI UAEMex - Sistema Integral de Evaluación Institucional
    </div>

</body>
</html>
