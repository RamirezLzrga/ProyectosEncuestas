<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIEI UAEMex - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-uaemex {
            background-color: #0d5c41; /* Aproximación del verde UAEMex */
        }
        .text-uaemex {
            color: #0d5c41;
        }
        .btn-uaemex {
            background-color: #0d5c41;
            transition: background-color 0.3s;
        }
        .btn-uaemex:hover {
            background-color: #08402d;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    @yield('content')
</body>
</html>
