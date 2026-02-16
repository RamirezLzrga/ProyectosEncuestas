<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta No Disponible - SIEI UAEMex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md w-full text-center border-t-4 border-red-500">
        <div class="mb-6">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Encuesta No Disponible</h1>
            <p class="text-gray-600">{{ $message ?? 'Esta encuesta ha sido inhabilitada o no existe.' }}</p>
        </div>
    </div>

</body>
</html>
