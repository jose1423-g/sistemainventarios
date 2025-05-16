<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PartidaPresupuestalController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\Admin;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/productos', [ProductosController::class, 'Showview'])->middleware(['auth', 'verified'])->name('productos');

Route::get('/entrada', [EntradaController::class, 'Showview'])->middleware(['auth', 'verified'])->name('entrada');

Route::get('/salida', [SalidaController::class, 'Showview'])->middleware(['auth', 'verified'])->name('salida');

Route::get('/area', [AreaController::class, 'Showview'])->middleware(['auth', 'verified'])->name('area');

Route::get('/personal', [PersonalController::class, 'Showview'])->middleware(['auth', 'verified'])->name('personal');

Route::get('/proveedores', [ProveedoresController::class, 'Index'])->middleware(['auth', 'verified'])->name('proveedores');

Route::get('/partidapresupuestal', [PartidaPresupuestalController::class, 'Showview'])->middleware(['auth', 'verified'])->name('partidapresupuestal');

Route::get('/usuarios', [UsuariosController::class, 'Showview'])->middleware(['auth', 'verified', Admin::class])->name('usuarios');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    /* partida */
    Route::post('/storepartida', [PartidaPresupuestalController::class, 'Store'])->name('store.partida');
    Route::get('/getpartida/{id}', [PartidaPresupuestalController::class, 'Edit'])->name('get.partida');
    Route::delete('/deletepartida/{id}', [PartidaPresupuestalController::class, 'Delete'])->name('delete.partida');
    /* area */
    Route::post('/storearea', [AreaController::class, 'Store'])->name('store.area');
    Route::get('/editearea/{id}', [AreaController::class, 'Edit'])->name('edit.area');
    Route::delete('/deletearea/{id}', [AreaController::class, 'Delete'])->name('delete.area');
    /* personal */
    Route::post('/storepersonal', [PersonalController::class, 'Store'])->name('store.personal');
    Route::get('/editpersonal/{id}', [PersonalController::class, 'Edit'])->name('edit.personal');
    Route::delete('/deletepersonal/{id}', [PersonalController::class, 'Delete'])->name('delete.personal'); 
    /* Salida */
    Route::post('/storesalida', [SalidaController::class, 'Store'])->name('store.salida');
    Route::get('/editsalida/{id}', [SalidaController::class, 'Edit'])->name('edit.salida');
    Route::delete('/deletesalida/{id}', [SalidaController::class, 'Delete'])->name('delete.salida');
    /* Entrada */
    Route::post('/storeentrada', [EntradaController::class, 'Store'])->name('store.entrada');
    Route::get('/editentrada/{id}', [EntradaController::class, 'Edit'])->name('edit.entrada');
    Route::delete('/deleteentrada/{id}', [EntradaController::class, 'Delete'])->name('delete.entrada');
    /* Productos */
    Route::post('/storeproducto', [ProductosController::class, 'Store'])->name('store.producto');
    Route::get('/editproducto/{id}', [ProductosController::class, 'Edit'])->name('edit.producto');
    Route::delete('/deleteproducto/{id}', [ProductosController::class, 'Delete'])->name('delete.producto');
    /* usuarios */
    Route::post('/storeusuario', [UsuariosController::class, 'Store'])->name('store.usuario');
    Route::get('/editusuario/{id}', [UsuariosController::class, 'Edit'])->name('edit.usuario');
    Route::delete('/deleteusuario/{id}', [UsuariosController::class, 'Delete'])->name('delete.usuario');
    /* Proveedores */
    Route::post('/storeproveedor', [ProveedoresController::class, 'Store'])->name('store.proveedor');
    Route::get('/editproveedor/{id}', [ProveedoresController::class, 'Edit'])->name('edit.proveedor');
    Route::delete('/deleteproveedor/{id}', [ProveedoresController::class, 'Delete'])->name('delete.proveedor');

    /* buscador */
    Route::get('/searcharea/{name}', [SearchController::class, 'SearchArea'])->name('search.area');
    Route::get('/searchentradas/{name}', [SearchController::class, 'SearchEntradas'])->name('search.entradas');
    Route::get('/searchpartidas/{name}', [SearchController::class, 'SearchPartidas'])->name('search.partidas');
    Route::get('/searchproductos/{name}', [SearchController::class, 'SearchProductos'])->name('search.productos');
    Route::get('/searchproveedor/{name}', [SearchController::class, 'SearchProveedor'])->name('search.proveedor');

    /* buscador dashboard */
    Route::get('/searchdashboardentradas/{name}', [SearchController::class, 'SearchDashboardEntradas'])->name('search.dashboard.entrada');
    Route::get('/searchdashboardsalidas/{name}', [SearchController::class, 'SearchSalidasEntradas'])->name('search.dashboard.salida');

    /* buscador de las tablas */
    Route::post('/searchentradatable', [SearchController::class, 'SearchEntradasTable'])->name('search.entrada.table');
    Route::post('/searchsalidatable', [SearchController::class, 'SearchSalidasTable'])->name('search.salida.table');
    Route::post('/searchpartidatable', [SearchController::class, 'SearchPartidaTable'])->name('search.partida.table');
    Route::post('/searchpersonaltable', [SearchController::class, 'SearchPersonalTable'])->name('search.personal.table');
    Route::post('/searchusertable', [SearchController::class, 'SearchUsersTable'])->name('search.users.table');
    Route::post('/searchareatable', [SearchController::class, 'SearchAreaTable'])->name('search.area.table');
    Route::post('/searchproducttable', [SearchController::class, 'SearchProductTable'])->name('search.product.table');
    Route::post('/searchproveedortable', [SearchController::class, 'SearchProveedorTable'])->name('search.proveedor.table');

    /* para los pdf */
    Route::get('/pdf-entrada/{id}', [PdfController::class, 'ViewPdfEntrada'])->name('view.pdf.entrada');
    Route::get('/pdf-salida/{id}', [PdfController::class, 'ViewPdfSalida'])->name('view.pdf.salida');

});

require __DIR__.'/auth.php';
