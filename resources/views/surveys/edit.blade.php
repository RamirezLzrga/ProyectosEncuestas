@extends('layouts.app')

@section('title', 'Editar Encuesta')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Editar Encuesta</h2>
    <a href="{{ route('surveys.index') }}" class="text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </a>
</div>

<form action="{{ route('surveys.update', $survey->id) }}" method="POST" id="surveyForm">
    @csrf
    @method('PUT')
    
    {{-- Campos ocultos requeridos por el controlador --}}
    <input type="hidden" name="year" value="{{ $survey->year }}">
    <input type="hidden" name="start_date" value="{{ $survey->start_date }}">
    <input type="hidden" name="end_date" value="{{ $survey->end_date }}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Izquierda: Formulario -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Datos Básicos -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <div>
                    <input type="text" name="title" id="input-title" value="{{ $survey->title }}" placeholder="Encuesta sin título" class="text-3xl font-bold w-full border-b-2 border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-2 transition placeholder-gray-400 text-gray-800" required>
                </div>
                <div>
                    <textarea name="description" id="input-description" placeholder="Descripción de la encuesta" class="w-full text-gray-600 border-b-2 border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-2 transition placeholder-gray-400 resize-none" rows="2">{{ $survey->description }}</textarea>
                </div>
            </div>

            <!-- Configuración -->
            <div class="bg-orange-50/50 p-6 rounded-2xl border border-orange-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-cog text-gray-400"></i> Configuración de la Encuesta
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between cursor-pointer hover:border-uaemex transition shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-lock text-orange-400"></i>
                            <span class="text-sm font-bold text-gray-700">Evaluación anónima</span>
                        </div>
                        <input type="checkbox" name="settings[anonymous]" value="1" {{ isset($survey->settings['anonymous']) && $survey->settings['anonymous'] ? 'checked' : '' }} class="w-5 h-5 text-uaemex rounded focus:ring-uaemex">
                    </label>
                    <label class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between cursor-pointer hover:border-uaemex transition shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-user-tag text-purple-400"></i>
                            <span class="text-sm font-bold text-gray-700">Guardar nombres</span>
                        </div>
                        <input type="checkbox" name="settings[collect_names]" value="1" {{ isset($survey->settings['collect_names']) && $survey->settings['collect_names'] ? 'checked' : '' }} class="w-5 h-5 text-uaemex rounded focus:ring-uaemex">
                    </label>
                    <label class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between cursor-pointer hover:border-uaemex transition shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-blue-400"></i>
                            <span class="text-sm font-bold text-gray-700">Solicitar correo</span>
                        </div>
                        <input type="checkbox" name="settings[collect_emails]" value="1" {{ isset($survey->settings['collect_emails']) && $survey->settings['collect_emails'] ? 'checked' : '' }} class="w-5 h-5 text-uaemex rounded focus:ring-uaemex">
                    </label>
                    <label class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between cursor-pointer hover:border-uaemex transition shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-redo text-red-400"></i>
                            <span class="text-sm font-bold text-gray-700">Múltiples respuestas</span>
                        </div>
                        <input type="checkbox" name="settings[allow_multiple]" value="1" {{ isset($survey->settings['allow_multiple']) && $survey->settings['allow_multiple'] ? 'checked' : '' }} class="w-5 h-5 text-uaemex rounded focus:ring-uaemex">
                    </label>
                </div>
            </div>

            <!-- Preguntas -->
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-xl text-gray-800 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-gray-400"></i> Preguntas
                </h3>
                <button type="button" onclick="addQuestion()" class="bg-uaemex text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-green-800 transition flex items-center gap-2 shadow-lg shadow-green-900/20">
                    <i class="fas fa-plus"></i> Agregar pregunta
                </button>
            </div>

            <div id="questions-container" class="space-y-6">
                <!-- Las preguntas se cargarán vía JS -->
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('surveys.index') }}" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" class="bg-uaemex text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-green-900/20 hover:bg-green-800 transition">
                    Actualizar Encuesta
                </button>
            </div>
        </div>

        <!-- Columna Derecha: Vista Previa -->
        <div class="lg:col-span-1">
            <div class="sticky top-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-eye text-gray-400"></i> Vista Previa
                </h3>
                
                <!-- Contenedor Vista Previa -->
                <div id="preview-container" class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xl min-h-[500px] max-h-[calc(100vh-100px)] overflow-y-auto relative">
                    <!-- Cabecera de la encuesta (color uaemex) -->
                    <div class="absolute top-0 left-0 w-full h-3 bg-uaemex rounded-t-2xl"></div>
                    
                    <div class="mt-4 space-y-6">
                        <!-- Título y Descripción -->
                        <div class="border-b border-gray-100 pb-4">
                            <h1 id="preview-title" class="text-2xl font-bold text-gray-900 break-words">{{ $survey->title }}</h1>
                            <p id="preview-description" class="text-gray-600 mt-2 text-sm break-words">{{ $survey->description }}</p>
                        </div>

                        <!-- Preguntas Renderizadas -->
                        <div id="preview-questions" class="space-y-6">
                            <!-- Aquí se renderizarán las preguntas -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Template para Preguntas (Formulario) -->
<template id="question-template">
    <div class="question-item bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-uaemex transition group relative">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="md:col-span-2">
                <input type="text" name="questions[INDEX][text]" placeholder="Escribe tu pregunta" class="question-input-text w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-800 font-bold focus:outline-none focus:border-uaemex focus:bg-white transition" required>
            </div>
            <div>
                <select name="questions[INDEX][type]" onchange="toggleOptions(this); updatePreview();" class="question-input-type w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-gray-700 font-medium focus:outline-none focus:border-uaemex cursor-pointer">
                    <option value="multiple_choice">Opción múltiple</option>
                    <option value="checkboxes">Casillas de verificación</option>
                    <option value="short_text">Texto corto</option>
                    <option value="paragraph">Párrafo</option>
                </select>
            </div>
        </div>

        <!-- Opciones (Visible solo para multiple_choice y checkboxes) -->
        <div class="options-container space-y-3 pl-4 border-l-2 border-gray-100">
            <!-- Las opciones se insertarán aquí dinámicamente o por defecto -->
            <button type="button" onclick="addOption(this, 'INDEX'); updatePreview();" class="text-uaemex text-sm font-bold hover:underline flex items-center gap-2 mt-2">
                <i class="fas fa-plus"></i> Agregar opción
            </button>
        </div>

        <!-- Footer de la pregunta -->
        <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="questions[INDEX][required]" onchange="updatePreview()" class="question-input-required w-4 h-4 text-uaemex rounded focus:ring-uaemex">
                <span class="text-sm text-gray-500 font-medium">Obligatoria</span>
            </label>
            <div class="flex gap-2">
                <button type="button" onclick="duplicateQuestion(this)" class="text-gray-400 hover:text-uaemex p-2 rounded-lg transition" title="Duplicar">
                    <i class="far fa-copy"></i>
                </button>
                <button type="button" onclick="removeQuestion(this)" class="text-gray-400 hover:text-red-500 p-2 rounded-lg transition" title="Eliminar">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
    let questionCount = 0;
    const existingQuestions = @json($survey->questions);

    // Inicialización
    document.addEventListener('DOMContentLoaded', () => {
        // Cargar preguntas existentes
        if (existingQuestions && existingQuestions.length > 0) {
            existingQuestions.forEach(q => {
                loadQuestion(q);
            });
        } else {
            addQuestion();
        }
        
        // Listeners globales
        const form = document.getElementById('surveyForm');
        form.addEventListener('input', (e) => {
            if (e.target.matches('input, textarea')) {
                updatePreview();
            }
        });
        
        form.addEventListener('change', (e) => {
            if (e.target.matches('select, input[type="checkbox"]')) {
                updatePreview();
            }
        });
        
        updatePreview();
    });

    // Función para cargar pregunta existente
    function loadQuestion(data) {
        const container = document.getElementById('questions-container');
        const template = document.getElementById('question-template');
        const clone = template.content.cloneNode(true);
        const index = questionCount++;

        // Reemplazar INDEX en nombres
        clone.querySelectorAll('[name*="INDEX"]').forEach(el => {
            el.name = el.name.replace('INDEX', index);
        });

        // Setear valores
        const item = clone.querySelector('.question-item');
        item.querySelector('.question-input-text').value = data.text;
        item.querySelector('.question-input-type').value = data.type;
        if (data.required) {
            item.querySelector('.question-input-required').checked = true;
        }

        // Manejar opciones
        const optionsContainer = item.querySelector('.options-container');
        // Remover botón de agregar para reinsertarlo al final
        const addBtn = optionsContainer.querySelector('button');
        
        if (data.options && Array.isArray(data.options)) {
            data.options.forEach(optVal => {
                const optHtml = `
                    <div class="option-item flex items-center gap-3">
                        <i class="far fa-circle text-gray-400"></i>
                        <input type="text" name="questions[${index}][options][]" value="${optVal}" class="question-input-option bg-transparent border-b border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-1 text-sm text-gray-600 w-full transition" placeholder="Opción">
                        <button type="button" onclick="removeOption(this); updatePreview();" class="text-gray-300 hover:text-red-500 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                addBtn.insertAdjacentHTML('beforebegin', optHtml);
            });
        } else if (['multiple_choice', 'checkboxes'].includes(data.type)) {
             // Si no hay opciones pero el tipo las requiere, agregar una por defecto
             const optHtml = `
                    <div class="option-item flex items-center gap-3">
                        <i class="far fa-circle text-gray-400"></i>
                        <input type="text" name="questions[${index}][options][]" value="Opción 1" class="question-input-option bg-transparent border-b border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-1 text-sm text-gray-600 w-full transition" placeholder="Opción">
                        <button type="button" onclick="removeOption(this); updatePreview();" class="text-gray-300 hover:text-red-500 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
             addBtn.insertAdjacentHTML('beforebegin', optHtml);
        }

        // Mostrar/Ocultar opciones según tipo
        const typeSelect = item.querySelector('.question-input-type');
        toggleOptions(typeSelect);

        container.appendChild(clone);
    }

    // --- Funciones Core (Iguales a create.blade.php pero adaptadas si es necesario) ---

    function addQuestion() {
        const container = document.getElementById('questions-container');
        const template = document.getElementById('question-template');
        const clone = template.content.cloneNode(true);
        const index = questionCount++;

        clone.querySelectorAll('[name*="INDEX"]').forEach(el => {
            el.name = el.name.replace('INDEX', index);
        });

        // Agregar opción por defecto
        const optionsContainer = clone.querySelector('.options-container');
        const addBtn = optionsContainer.querySelector('button');
        const defaultOption = `
            <div class="option-item flex items-center gap-3">
                <i class="far fa-circle text-gray-400"></i>
                <input type="text" name="questions[${index}][options][]" value="Opción 1" class="question-input-option bg-transparent border-b border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-1 text-sm text-gray-600 w-full transition" placeholder="Opción">
                <button type="button" onclick="removeOption(this); updatePreview();" class="text-gray-300 hover:text-red-500 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        addBtn.insertAdjacentHTML('beforebegin', defaultOption);

        container.appendChild(clone);
        updatePreview();
    }

    function removeQuestion(btn) {
        if (document.querySelectorAll('.question-item').length > 1) {
            btn.closest('.question-item').remove();
            updatePreview();
        } else {
            alert('La encuesta debe tener al menos una pregunta.');
        }
    }

    function duplicateQuestion(btn) {
        const original = btn.closest('.question-item');
        const clone = original.cloneNode(true);
        const index = questionCount++;

        // Actualizar índices
        clone.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/questions\[\d+\]/, `questions[${index}]`);
        });

        // Insertar después del original
        original.after(clone);
        updatePreview();
    }

    function toggleOptions(select) {
        const container = select.closest('.question-item').querySelector('.options-container');
        if (['multiple_choice', 'checkboxes', 'dropdown'].includes(select.value)) {
            container.style.display = 'block';
            // Actualizar iconos
            const iconClass = select.value === 'checkboxes' ? 'far fa-square' : 'far fa-circle';
            container.querySelectorAll('i').forEach(icon => {
                if (!icon.parentElement.classList.contains('text-uaemex')) { // Ignorar icono de botón agregar
                     icon.className = `${iconClass} text-gray-400`;
                }
            });
        } else {
            container.style.display = 'none';
        }
    }

    function addOption(btn, indexPlaceholder) {
        // En modo edición, el indexPlaceholder ya no es válido, necesitamos encontrar el índice real
        const questionItem = btn.closest('.question-item');
        // Buscar un input dentro para sacar el índice del nombre
        const nameAttr = questionItem.querySelector('input, select').name;
        const indexMatch = nameAttr.match(/questions\[(\d+)\]/);
        const index = indexMatch ? indexMatch[1] : 0;

        const container = btn.parentElement;
        const typeSelect = questionItem.querySelector('.question-input-type');
        const iconClass = typeSelect.value === 'checkboxes' ? 'far fa-square' : 'far fa-circle';

        const optionHtml = `
            <div class="option-item flex items-center gap-3">
                <i class="${iconClass} text-gray-400"></i>
                <input type="text" name="questions[${index}][options][]" class="question-input-option bg-transparent border-b border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-1 text-sm text-gray-600 w-full transition" placeholder="Opción">
                <button type="button" onclick="removeOption(this); updatePreview();" class="text-gray-300 hover:text-red-500 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        btn.insertAdjacentHTML('beforebegin', optionHtml);
    }

    function removeOption(btn) {
        const container = btn.closest('.options-container');
        if (container.querySelectorAll('.option-item').length > 1) {
            btn.closest('.option-item').remove();
        } else {
            // Limpiar valor en lugar de eliminar si es la última
            btn.previousElementSibling.value = '';
        }
    }

    // --- LÓGICA DE VISTA PREVIA (Idéntica a create) ---
    function updatePreview() {
        const titleInput = document.getElementById('input-title');
        const descInput = document.getElementById('input-description');
        const previewTitle = document.getElementById('preview-title');
        const previewDesc = document.getElementById('preview-description');

        previewTitle.textContent = titleInput.value || 'Sin título';
        previewDesc.textContent = descInput.value || 'Sin descripción';

        const questionsContainer = document.getElementById('questions-container');
        const previewQuestions = document.getElementById('preview-questions');
        previewQuestions.innerHTML = '';

        const questionItems = questionsContainer.querySelectorAll('.question-item');
        
        if (questionItems.length === 0) {
            previewQuestions.innerHTML = '<p class="text-center text-gray-400 text-sm py-4">Agrega preguntas para verlas aquí</p>';
            return;
        }

        questionItems.forEach((item, index) => {
            const text = item.querySelector('.question-input-text').value || 'Pregunta sin título';
            const type = item.querySelector('.question-input-type').value;
            const required = item.querySelector('.question-input-required').checked;
            
            const previewItem = document.createElement('div');
            previewItem.className = 'bg-gray-50 p-4 rounded-xl border border-gray-100';
            
            let contentHtml = '';
            
            if (type === 'short_text') {
                contentHtml = `<div class="border-b border-gray-300 border-dashed py-2 text-gray-400 text-sm">Texto de respuesta corta</div>`;
            } else if (type === 'paragraph') {
                contentHtml = `<div class="border-b border-gray-300 border-dashed py-2 text-gray-400 text-sm">Texto de respuesta larga</div><div class="border-b border-gray-300 border-dashed py-2"></div>`;
            } else {
                const options = item.querySelectorAll('.question-input-option');
                options.forEach(opt => {
                    const optText = opt.value || 'Opción';
                    const iconClass = type === 'checkboxes' ? 'far fa-square' : 'far fa-circle';
                    contentHtml += `
                        <div class="flex items-center gap-2 py-1">
                            <i class="${iconClass} text-gray-400 text-sm"></i>
                            <span class="text-sm text-gray-600">${optText}</span>
                        </div>
                    `;
                });
            }

            previewItem.innerHTML = `
                <div class="mb-2">
                    <span class="font-bold text-gray-800 text-sm">${index + 1}. ${text}</span>
                    ${required ? '<span class="text-red-500 ml-1">*</span>' : ''}
                </div>
                <div class="pl-2">
                    ${contentHtml}
                </div>
            `;
            
            previewQuestions.appendChild(previewItem);
        });
    }
</script>
@endpush