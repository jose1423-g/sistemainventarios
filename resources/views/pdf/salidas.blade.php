<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Salidas</title>
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
    <h1>Reporte de Salidas</h1>

    

    <div class="responsable">
        <div class="line"></div>
        <h4>Nombre y Firma de quien recibe</h4>
    </div>

    <div class="footer">
        © {{ date('Y') }} - Reporte Sistema de Inventarios
    </div>
</body>
</html>
