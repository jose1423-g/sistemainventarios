<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de entrada</title>
    @vite('resources/css/app.css')
</head>
<body>
    
    @php
        // dd($entrada, true)
        // dd($productos_entrada, true)
    @endphp        

    {{-- table datos generales --}}
    <div class="container mx-auto px-5 my-10">
        <h2 class="text-center font-bold text-xl my-5">Reporte de entradas</h2>

        <table class="w-full text-sm text-left rtl:text-right text-gray-500 mb-5">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-2">
                        No° de Entrada
                    </th>
                    <th scope="col" class="px-6 py-2">
                        Proveedor
                    </th>
                    <th scope="col" class="px-6 py-2">
                        Fecha de entrada
                    </th>
                    <th scope="col" class="px-6 py-2">
                        Fecha de compra
                    </th>                    
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 border-gray-200">
                    <th scope="row" class="px-6 py-2 font-medium text-blue-900 whitespace-nowrap">
                        {{ $entrada->no_orden }}
                    </th>
                    <td class="px-6 py-2">
                        {{ $entrada->proveedor }}
                    </td>
                    <td class="px-6 py-2">
                        {{ $entrada->fecha_compra }}
                    </td>
                    <td class="px-6 py-2">
                        {{ $entrada->fecha_entrada }}
                    </td>                    
                </tr>         
            </tbody>
        </table> 

        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-2">
                        No° de requisicion
                    </th>
                    <th scope="col" class="px-6 py-2">
                        Total de piezas
                    </th>
                    <th scope="col" class="px-6 py-2">
                        Precio unitario
                    </th>
                    <th scope="col" class="px-6 py-2">
                        Iva
                    </th>
                    <th scope="col" class="px-6 py-2">
                        Total
                    </th>                    
                </tr>
            </thead>
            <tbody>                
                <tr class="bg-white border-b dark:bg-gray-800 border-gray-200">
                    <td class="px-6 py-2">
                        {{ $entrada->numero_requisicion }}
                    </td>
                    <td scope="row" class="px-6 py-2 font-medium text-blue-900 whitespace-nowrap">
                        {{ $entrada->cantidad_piezas }}
                    </td>
                    <td class="px-6 py-2">
                        {{ $entrada->precio_unitario }}
                    </td>
                    <td class="px-6 py-2">
                        {{ $entrada->iva }}
                    </td>
                    <td class="px-6 py-2">
                        {{ $entrada->total }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- table productos --}}
    <div class="container mx-auto px-5 mb-10">
        <h2 class="text-center font-semibold text-xl my-5">Productos</h2>

        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nombre del producto
                    </th>
                    <th scope="col" class="px-6 py-3">
                        stock
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Unidad
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precio
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Imagen
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos_entrada as $item)            
                    <tr class="bg-white border-b dark:bg-gray-800 border-gray-200">
                        <th scope="row" class="px-6 py-2 font-medium text-blue-900 whitespace-nowrap">
                            {{ $item->nombre }}
                        </th>
                        <td class="px-6 py-2">
                            {{ $item->stock }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $item->unidad }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $item->precio }}
                        </td>
                        <td class="px-6 py-2">
                            <img src="{{ $item->img }}" class="w-[5rem] max-h-full object-cover" alt="Apple Watch">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- nombre y frima --}}
    <div class="container mx-auto px-5">
        <table class="w-full text-sm text-center rtl:text-right text-gray-900">

            <tr class="bg-white mb-[35rem]">
                <td class="px-5 py-0 font-semibold">Area solcitante: <span class="uppercase">{{ $entrada->area }}</span></td>
                <td class="px-5 py-0 uppercase font-semibold">Encargado: <span class="uppercase">{{ $entrada->nombre }}</span></td>
            </tr>

            <tr>
                <td colspan="2" class="px-2 py-10 border-black border-b"></td>
            </tr>

            <tr class="bg-white">
                <td colspan="2" class="text-center py-2 pb-12">firma del encargado.</td>                
            </tr>

            <tr>
                <td colspan="2" class="px-2 py-10 border-black border-b"></td>
            </tr>

            <tr class="bg-white">
                <td colspan="2" class="text-center py-2">Nombre y firma de quien recibe.</td>                
            </tr>

            
        </table>
    </div>


</body>
</html>