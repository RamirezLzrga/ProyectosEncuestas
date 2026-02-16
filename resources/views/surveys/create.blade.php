@extends('layouts.app')

@section('title', 'Crear Nueva Encuesta')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Crear Nueva Encuesta</h2>
    <a href="{{ route('surveys.index') }}" class="text-gray-500 hover:text-gray-700 text-xl">
        <i class="fas fa-times"></i>
    </a>
</div>

<form action="{{ route('surveys.store') }}" method="POST" id="surveyForm">
    @csrf
    
    {{-- Campos ocultos requeridos por el controlador con valores por defecto --}}
    <input type="hidden" name="year" value="{{ date('Y') }}">
    <input type="hidden" name="start_date" value="{{ date('Y-m-d') }}">
    <input type="hidden" name="end_date" value="{{ date('Y-m-d', strtotime('+1 month')) }}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Izquierda: Formulario -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Datos Básicos -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                <div>
                    <input type="text" name="title" id="input-title" placeholder="Encuesta sin título" class="text-3xl font-bold w-full border-b-2 border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-2 transition placeholder-gray-400 text-gray-800" required>
                </div>
                <div>
                    <textarea name="description" id="input-description" placeholder="Descripción de la encuesta" class="w-full text-gray-600 border-b-2 border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-2 transition placeholder-gray-400 resize-none" rows="2"></textarea>
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
                        <input type="checkbox" name="settings[anonymous]" value="1" class="w-5 h-5 text-uaemex rounded focus:ring-uaemex">
                    </label>
                    <label class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between cursor-pointer hover:border-uaemex transition shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-user-tag text-purple-400"></i>
                            <span class="text-sm font-bold text-gray-700">Guardar nombres</span>
                        </div>
                        <input type="checkbox" name="settings[collect_names]" value="1" class="w-5 h-5 text-uaemex rounded focus:ring-uaemex">
                    </label>
                    <label class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between cursor-pointer hover:border-uaemex transition shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-blue-400"></i>
                            <span class="text-sm font-bold text-gray-700">Solicitar correo</span>
                        </div>
                        <input type="checkbox" name="settings[collect_emails]" value="1" class="w-5 h-5 text-uaemex rounded focus:ring-uaemex">
                    </label>
                    <label class="bg-white p-4 rounded-xl border border-gray-200 flex items-center justify-between cursor-pointer hover:border-uaemex transition shadow-sm">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-redo text-red-400"></i>
                            <span class="text-sm font-bold text-gray-700">Múltiples respuestas</span>
                        </div>
                        <input type="checkbox" name="settings[allow_multiple]" value="1" class="w-5 h-5 text-uaemex rounded focus:ring-uaemex">
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
                <!-- Las preguntas se agregarán aquí dinámicamente -->
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('surveys.index') }}" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">Cancelar</a>
                <button type="submit" class="bg-uaemex text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-green-900/20 hover:bg-green-800 transition">
                    Guardar Encuesta
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
                            <h1 id="preview-title" class="text-2xl font-bold text-gray-900 break-words">Sin título</h1>
                            <p id="preview-description" class="text-gray-600 mt-2 text-sm break-words">Sin descripción</p>
                        </div>

                        <!-- Preguntas Renderizadas -->
                        <div id="preview-questions" class="space-y-6">
                            <!-- Aquí se renderizarán las preguntas -->
                            <div class="text-center py-10 text-gray-400">
                                <i class="fas fa-pencil-alt text-3xl mb-2 opacity-50"></i>
                                <p class="text-xs">Comienza a editar para ver los cambios aquí</p>
                            </div>
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
            <div class="option-item flex items-center gap-3">
                <i class="far fa-circle text-gray-400"></i>
                <input type="text" name="questions[INDEX][options][]" value="Opción 1" class="question-input-option bg-transparent border-b border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-1 text-sm text-gray-600 w-full transition" placeholder="Opción">
                <button type="button" onclick="removeOption(this); updatePreview();" class="text-gray-300 hover:text-red-500 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
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

    // Inicialización
    document.addEventListener('DOMContentLoaded', () => {
        addQuestion();
        
        // Listeners globales para actualización en tiempo real
        const form = document.getElementById('surveyForm');
        
        // Delegación de eventos para inputs dinámicos
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

        updatePreview(); // Primera carga
    });

    function addQuestion() {
        const container = document.getElementById('questions-container');
        const template = document.getElementById('question-template');
        const clone = template.content.cloneNode(true);
        
        // Reemplazar INDEX con el contador actual
        const html = clone.firstElementChild.outerHTML.replace(/INDEX/g, questionCount);
        
        // Convertir string de vuelta a nodo para insertar
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const newQuestion = tempDiv.firstElementChild;
        
        container.appendChild(newQuestion);
        questionCount++;
        updatePreview();
    }

    function removeQuestion(btn) {
        if (document.querySelectorAll('.question-item').length > 1) {
            const questionItem = btn.closest('.question-item');
            questionItem.remove();
            updatePreview();
        } else {
            alert("La encuesta debe tener al menos una pregunta.");
        }
    }
    
    function duplicateQuestion(btn) {
        const original = btn.closest('.question-item');
        const clone = original.cloneNode(true);
        
        // Actualizar nombres/indices en el clon para que sean únicos
        const inputs = clone.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${questionCount}]`));
            }
        });
        
        document.getElementById('questions-container').appendChild(clone);
        questionCount++;
        updatePreview();
    }

    function toggleOptions(select) {
        const questionItem = select.closest('.question-item');
        const optionsContainer = questionItem.querySelector('.options-container');
        
        if (select.value === 'short_text' || select.value === 'paragraph') {
            optionsContainer.style.display = 'none';
        } else {
            optionsContainer.style.display = 'block';
            // Cambiar iconos según tipo
            const icons = optionsContainer.querySelectorAll('i.far');
            icons.forEach(icon => {
                icon.className = select.value === 'checkboxes' ? 'far fa-square text-gray-400' : 'far fa-circle text-gray-400';
            });
        }
    }

    function addOption(btn, indexPlaceholder) {
        const optionsContainer = btn.closest('.options-container');
        const questionItem = btn.closest('.question-item');
        // Encontrar índice real
        const nameAttr = questionItem.querySelector('input[name^="questions"]').name;
        const realIndex = nameAttr.match(/\[(\d+)\]/)[1];

        const newOption = document.createElement('div');
        newOption.className = 'option-item flex items-center gap-3';
        
        // Determinar icono
        const typeSelect = questionItem.querySelector('select');
        const iconClass = typeSelect.value === 'checkboxes' ? 'far fa-square' : 'far fa-circle';

        newOption.innerHTML = `
            <i class="${iconClass} text-gray-400"></i>
            <input type="text" name="questions[${realIndex}][options][]" class="question-input-option bg-transparent border-b border-transparent hover:border-gray-200 focus:border-uaemex focus:outline-none py-1 text-sm text-gray-600 w-full transition" placeholder="Nueva opción">
            <button type="button" onclick="removeOption(this); updatePreview();" class="text-gray-300 hover:text-red-500 transition">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        optionsContainer.insertBefore(newOption, btn);
    }

    function removeOption(btn) {
        const container = btn.closest('.options-container');
        if (container.querySelectorAll('.option-item').length > 1) {
            btn.closest('.option-item').remove();
        }
    }

    // --- LÓGICA DE VISTA PREVIA ---
    function updatePreview() {
        // Actualizar Título y Descripción
        const titleInput = document.getElementById('input-title');
        const descInput = document.getElementById('input-description');
        const previewTitle = document.getElementById('preview-title');
        const previewDesc = document.getElementById('preview-description');

        previewTitle.textContent = titleInput.value || 'Sin título';
        previewDesc.textContent = descInput.value || 'Sin descripción';

        // Actualizar Preguntas
        const questionsContainer = document.getElementById('questions-container');
        const previewQuestions = document.getElementById('preview-questions');
        previewQuestions.innerHTML = ''; // Limpiar

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
                // Opciones
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