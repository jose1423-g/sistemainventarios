<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de salida</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            padding: 40px;
            color: #333;
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
            margin-top: 0px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            /* margin-top: 20px; */
            font-size: 12px;
        }
        th, td {
            border: 1px solid #bbb;
            padding: .5rem;
            text-align: center;            
        }
        th {
            background-color: #f2f2f2;
            color: #2c3e50;
        }
        .imagen-producto {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
        .pt-4 {
            padding-top: 5rem;
        }
        .font_bold {
            font-weight: 600;
        }
        .mb-10 {
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
     
    {{-- table datos generales --}}
    <div>
        <h2>Reporte de salidas</h2>
        <table>
            <thead>
                <tr>
                    <th scope="col">
                        No° de Salida
                    </th>
                    <th scope="col">
                        No° de Orden
                    </th>
                    <th scope="col">
                        Fecha de Salida
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">
                        {{ $salida->no_salida }}
                    </th>
                    <td>
                        {{ $salida->no_orden }}
                    </td>
                    <td>
                        {{ $salida->fecha_salida }}                        
                    </td>
                </tr>
            </tbody>
        </table>         
    </div>

    {{-- table productos --}}
    <div class="mb-10">
        <h2>Productos</h2>
        <table>
            <thead>
                <tr>
                    <th scope="col">
                        Nombre del producto
                    </th>
                    <th scope="col">
                        Cantidad
                    </th>
                    <th scope="col">
                        Imagen
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos_salida as $item)            
                    <tr class="">
                        <th scope="row">
                            {{ $item->nombre }}
                        </th>
                        <td>
                            {{ $item->cantidad }}
                        </td>
                        <td>
                            <img src="{{ $item->img }}" class="imagen-producto" alt="img produto">                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- nombre y frima --}}
    <div>
        <table>
            <tr>
                <td class="font_bold">Area solcitante: <span>{{ $salida->area }}</span></td>
                <td class="font_bold">Encargado: <span>{{ $salida->personal }}</span></td>
            </tr>
            <tr>
                <td colspan="2" class="pt-4">firma del encargado.</td>                
            </tr>
            <tr>
                <td colspan="2" class="pt-4">Nombre y firma de quien recibe.</td>
            </tr>            
        </table>
    </div>

</body>
</html>