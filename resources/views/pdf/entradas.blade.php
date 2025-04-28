<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $titulo ?? 'Reporte de Entradas' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.6;
            padding: 40px;
            color: #333;
        }
        h1, h2, h3, h4 {
            margin: 0 0 10px;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            color: #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }
        h2 {
            font-size: 18px;
            text-align: center;
            color: #333;
            margin-top: 30px;
        }
        p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            color: #2c3e50;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .img-producto {
            text-align: center;
        }
        .imagen-producto {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
        .responsable {
            margin-top: 120px;
            text-align: center;
            font-size: 12px;
        }
        .line {
            width: 300px;
            height: 1px;
            background-color: #333;
            margin: 20px auto 5px auto;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #aaa;
        }
    </style>
</head>
<body>
    @foreach($entradas as $entrada)
    <h1>{{ $titulo ?? 'Reporte de Entrada' }}</h1>
    
    <h2>Detalle de Entrada al Almacén</h2>
    
    <p><strong>Número de Orden:</strong> {{ $entrada->no_orden ?? 'N/A' }}</p>
    <p><strong>Fecha de Compra:</strong> {{ $entrada->fecha_compra ? date('d/m/Y', strtotime($entrada->fecha_compra)) : 'N/A' }}</p>
    <p><strong>Fecha de Entrada:</strong> {{ date('d/m/Y', strtotime($entrada->fecha_entrada)) }}</p>
    <p><strong>Área Solicitante:</strong> {{ $entrada->area_solicitante ?? 'No especificada' }}</p>
    <p><strong>Encargado del Area: </strong> {{ $entrada->encargado_area ?? 'No especificado' }}</p>
    <p><strong>Proveedor:</strong> {{ $entrada->proveedor ?? 'No especificado' }}</p>
    
    <h3 style="margin-top: 30px; color: #333;">Datos del Producto</h3>
    
    <table>
        <thead>
            <tr>
                <th>Nombre del Producto</th>
                <th>Cantidad</th>
                <th>Precio Total</th>
                <th>Partida Presupuestal</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $entrada->producto ?? 'No especificado' }}</td>
                <td>{{ $entrada->cantidad_piezas ?? '0' }}</td>
                <td>${{ number_format($entrada->total ?? 0, 2) }}</td>
                <td>{{ $entrada->partida_presupuestal ?? 'No especificada' }}</td>
                <td>
                    <div class="img-producto">
                        @if(isset($entrada->img) && !empty($entrada->img))
                            <img src="{{ storage_path('app/public/productos/'.$entrada->img) }}" class="imagen-producto" alt="Imagen del producto">
                        @else
                            Sin imagen
                        @endif
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="responsable">
        <div class="line"></div>
        <h4>Nombre y Firma de quien recibe</h4>
    </div>
    
    <div class="footer">
        © {{ date('Y') }} - Reporte Sistema de Inventarios
    </div>
    
    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
    @endforeach
</body>
</html>