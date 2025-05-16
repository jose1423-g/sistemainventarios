<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de entrada</title>
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
            margin-top: 20px;
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

    </style>
</head>
<body>
     
    {{-- table datos generales --}}
    <div>
        <h2>Reporte de entradas</h2>
        <table>
            <thead>
                <tr>
                    <th scope="col">
                        No° de Orden
                    </th>
                    <th scope="col">
                        Proveedor
                    </th>
                    <th scope="col">
                        Fecha de entrada
                    </th>
                    <th scope="col">
                        Fecha de compra
                    </th>                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">
                        {{ $entrada->no_orden }}
                    </th>
                    <td>
                        {{ $entrada->proveedor }}
                    </td>
                    <td>
                        {{ $entrada->fecha_compra }}
                    </td>
                    <td>
                        {{ $entrada->fecha_entrada }}
                    </td>                    
                </tr>         
            </tbody>
        </table> 

        <table>
            <thead>
                <tr>
                    <th scope="col">
                        No° de requisicion
                    </th>
                    <th scope="col">
                        Total de piezas
                    </th>
                    <th scope="col">
                        Precio unitario
                    </th>
                    <th scope="col">
                        Iva
                    </th>
                    <th scope="col">
                        Total
                    </th>                    
                </tr>
            </thead>
            <tbody>                
                <tr>
                    <td>
                        {{ $entrada->numero_requisicion }}
                    </td>
                    <td scope="row">
                        {{ $entrada->cantidad_piezas }}
                    </td>
                    <td>
                        ${{ $entrada->precio_unitario }}
                    </td>
                    <td>
                        {{ $entrada->iva }}%
                    </td>
                    <td>
                        ${{ $entrada->total }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- table productos --}}
    <div>
        <h2>Productos</h2>

        <table>
            <thead>
                <tr>
                    <th scope="col">
                        Nombre del producto
                    </th>
                    <th scope="col">
                        stock
                    </th>
                    <th scope="col">
                        Unidad de Medida
                    </th>
                    <th scope="col">
                        Precio
                    </th>
                    <th scope="col">
                        Imagen
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos_entrada as $item)            
                    <tr class="">
                        <th scope="row">
                            {{ $item->nombre }}
                        </th>
                        <td>
                            {{ $item->stock }}
                        </td>
                        <td>
                            {{ $item->unidad }}
                        </td>
                        <td>
                            ${{ $item->precio }}
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
    {{-- <div>
        <table class="w-full text-sm text-center rtl:text-right text-gray-900">

            <tr>
                <td class="font_bold">Area solcitante: <span>{{ $entrada->area }}</span></td>
                <td class="font_bold">Encargado: <span>{{ $entrada->nombre }}</span></td>
            </tr>            

            <tr>
                <td colspan="2" class="pt-4">firma del encargado.</td>
            </tr>

            <tr>
                <td colspan="2" class="pt-4">Nombre y firma de quien recibe.</td>
            </tr>
            
        </table>
    </div> --}}

</body>
</html>