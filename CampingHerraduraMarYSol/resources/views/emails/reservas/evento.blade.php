<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de reserva</title>
</head>
<body>
    <h2>Su reserva ha sido {{ $accion }}</h2>

    <p>Hola {{ $reserva->usuario?->name ?? 'cliente' }},</p>
    <p>Le compartimos el detalle de la reserva {{ $accion }}.</p>

    <ul>
        <li><strong>ID de reserva:</strong> {{ $reserva->id }}</li>
        <li><strong>Cliente:</strong> {{ $reserva->usuario?->name ?? 'N/A' }}</li>
        <li><strong>Correo:</strong> {{ $reserva->usuario?->email ?? 'N/A' }}</li>
        <li><strong>Hospedaje:</strong> {{ $reserva->hospedaje?->tipo ?? 'N/A' }} {{ $reserva->hospedaje?->numeros ?? '' }}</li>
        <li><strong>Fecha de entrada:</strong> {{ optional($reserva->fecha_entrada)->format('d/m/Y H:i') }}</li>
        <li><strong>Fecha de salida:</strong> {{ optional($reserva->fecha_salida)->format('d/m/Y H:i') }}</li>
        <li><strong>Precio:</strong> {{ number_format((float) $reserva->precio, 2) }}</li>
        <li><strong>Espacios de parqueo:</strong> {{ $reserva->espacios_de_parqueo }}</li>
        <li><strong>Desayuno:</strong> {{ $reserva->desayuno ? 'Si' : 'No' }}</li>
        <li><strong>Estado:</strong> {{ $reserva->estado }}</li>
    </ul>

    <p>Gracias por preferirnos.</p>
</body>
</html>
