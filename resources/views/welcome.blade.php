<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIEI UAEMex - Sistema de Encuestas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-uaemex-dark { background-color: #1b393b; }
        .bg-uaemex { background-color: #0d5c41; }
        .text-uaemex { color: #0d5c41; }
        .btn-uaemex { background-color: #0d5c41; }
        .text-gold { color: #d4af37; }
        .bg-gold { background-color: #d4af37; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-gold text-uaemex-dark font-bold p-2 rounded-lg h-10 w-10 flex items-center justify-center text-xl">UA</div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide text-gray-800">SIEI <span class="text-gold">UAEMex</span></h1>
                </div>
            </div>
            
            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-uaemex text-white px-6 py-2 rounded-full font-bold hover:bg-green-800 transition shadow-md flex items-center gap-2">
                        <i class="fas fa-chart-pie"></i> Ir al Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 font-bold hover:text-uaemex transition mr-4">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="bg-uaemex text-white px-6 py-2 rounded-full font-bold hover:bg-green-800 transition shadow-md">
                        Registrarse
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-grow flex items-center justify-center relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-50 to-gray-200 -z-10"></div>
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-green-100 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-white to-transparent -z-10"></div>

        <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="space-y-8 animate-fade-in-up">
                <div class="inline-block bg-green-100 text-uaemex px-4 py-1 rounded-full text-sm font-bold tracking-wide uppercase border border-green-200">
                    Sistema Institucional
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight">
                    Plataforma de <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-uaemex to-green-600">Encuestas Digitales</span>
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed max-w-lg">
                    Bienvenido a la página oficial de encuestas. Una herramienta moderna para la recopilación, análisis y gestión de datos institucionales de manera eficiente y segura.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-gold text-uaemex-dark px-8 py-4 rounded-xl font-bold hover:bg-yellow-500 transition shadow-lg transform hover:-translate-y-1 text-center flex items-center justify-center gap-3">
                            <i class="fas fa-tachometer-alt"></i> Panel de Control
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-uaemex text-white px-8 py-4 rounded-xl font-bold hover:bg-green-800 transition shadow-lg transform hover:-translate-y-1 text-center flex items-center justify-center gap-3 text-lg">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                        <a href="#features" class="bg-white text-gray-700 border border-gray-200 px-8 py-4 rounded-xl font-bold hover:bg-gray-50 transition shadow-sm text-center">
                            Conocer más
                        </a>
                    @endauth
                </div>

                <div class="flex items-center gap-8 pt-4 text-gray-400">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Datos en tiempo real</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Seguro y confiable</span>
                    </div>
                </div>
            </div>

            <!-- Right Visual -->
            <div class="relative hidden md:block">
                <div class="relative z-10 bg-white p-2 rounded-2xl shadow-2xl transform rotate-2 hover:rotate-0 transition duration-500 border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Dashboard Preview" class="rounded-xl w-full">
                    
                    <!-- Floating Stats Card -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-lg border border-gray-100 flex items-center gap-4 animate-bounce-slow">
                        <div class="bg-green-100 p-3 rounded-full text-green-600">
                            <i class="fas fa-chart-bar text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">Respuestas hoy</p>
                            <p class="text-2xl font-bold text-gray-800">+1,240</p>
                        </div>
                    </div>
                </div>
                
                <!-- Background Blob -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gold/10 rounded-full blur-3xl -z-10"></div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white py-8 border-t border-gray-100 mt-12">
        <div class="container mx-auto px-6 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} Universidad Autónoma del Estado de México. Todos los derechos reservados.</p>
        </div>
    </footer>

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite ease-in-out;
        }
    </style>
</body>
</html>
