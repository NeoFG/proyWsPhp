<?php
use Slim\App;
use App\Application\Controllers\ClientesController;
use App\Application\Controllers\InventarioController;
use App\Application\Controllers\OrdenesCompraController;
use App\Application\Controllers\PedidosController;

return function (App $app) {
    // Rutas para clientes
    $app->get('/clientes', [ClientesController::class, 'getClientes']);
    $app->get('/clientes/{id}', [ClientesController::class, 'getCliente']);
    $app->post('/clientes', [ClientesController::class, 'addCliente']);
    $app->put('/clientes/{id}', [ClientesController::class, 'updateCliente']);
    $app->delete('/clientes/{id}', [ClientesController::class, 'deleteCliente']);

    // Rutas para materiales
    $app->get('/materiales', [InventarioController::class, 'getMateriales']);
    $app->get('/materiales/{id}', [InventarioController::class, 'getMaterial']);
    $app->post('/materiales', [InventarioController::class, 'addMaterial']);
    $app->put('/materiales/{id}', [InventarioController::class, 'updateMaterial']);
    $app->delete('/materiales/{id}', [InventarioController::class, 'deleteMaterial']);

    // Rutas para inventario
    $app->get('/inventario', [InventarioController::class, 'getInventario']);
    $app->get('/inventario/{id}', [InventarioController::class, 'getInventarioItem']);
    $app->post('/inventario', [InventarioController::class, 'addInventario']);
    $app->put('/inventario/{id}', [InventarioController::class, 'updateInventario']);
    $app->delete('/inventario/{id}', [InventarioController::class, 'deleteInventario']);

    // Rutas para órdenes de compra
    $app->get('/ordenes_compra', [OrdenesCompraController::class, 'getOrdenesCompra']);
    $app->get('/ordenes_compra/{id}', [OrdenesCompraController::class, 'getOrdenCompra']);
    $app->post('/ordenes_compra', [OrdenesCompraController::class, 'addOrdenCompra']);
    $app->put('/ordenes_compra/{id}', [OrdenesCompraController::class, 'updateOrdenCompra']);
    $app->delete('/ordenes_compra/{id}', [OrdenesCompraController::class, 'deleteOrdenCompra']);

    // Rutas para pedidos 
    $app->get('/pedidos', [PedidosController::class, 'getPedidos']);
    $app->get('/pedidos/{id}', [PedidosController::class, 'getPedido']);
    $app->post('/pedidos', [PedidosController::class, 'addPedido']);
    $app->put('/pedidos/{id}', [PedidosController::class, 'updatePedido']);
    $app->delete('/pedidos/{id}', [PedidosController::class, 'deletePedido']);

    // Rutas para operaciones relacionadas
    $app->get('/pedidos/{pedido_id}/diseno', [PedidosController::class, 'getDesignForPedido']);
    $app->get('/pedidos/{pedido_id}/cotizacion', [PedidosController::class, 'getCotizacionForPedido']);
    $app->post('/pedidos/{pedido_id}/diseno', [PedidosController::class, 'addDesignToPedido']);
    $app->post('/pedidos/{pedido_id}/cotizacion', [PedidosController::class, 'addCotizacionToPedido']);

};
