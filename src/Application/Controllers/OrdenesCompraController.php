<?php
namespace App\Application\Controllers;

use App\Infrastructure\Database\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class OrdenesCompraController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Obtener todas las órdenes de compra
    public function getOrdenesCompra(Request $request, Response $response): Response {
        $sql = "SELECT * FROM ordenes_compra";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Obtener una orden de compra por ID
    public function getOrdenCompra(Request $request, Response $response, $args): Response {
        $sql = "SELECT * FROM ordenes_compra WHERE orden_id = :orden_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orden_id', $args['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Agregar una nueva orden de compra
    public function addOrdenCompra(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $sql = "INSERT INTO ordenes_compra (material_id, cantidad, fecha_orden, proveedor, estado) VALUES (:material_id, :cantidad, NOW(), :proveedor, :estado)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':material_id', $data['material_id']);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        $stmt->bindParam(':proveedor', $data['proveedor']);
        $stmt->bindParam(':estado', $data['estado']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Orden de compra agregada exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al agregar orden de compra']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Actualizar una orden de compra por ID
    public function updateOrdenCompra(Request $request, Response $response, $args): Response {
        $data = $request->getParsedBody();
        $sql = "UPDATE ordenes_compra SET cantidad = :cantidad, proveedor = :proveedor, estado = :estado WHERE orden_id = :orden_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        $stmt->bindParam(':proveedor', $data['proveedor']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':orden_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Orden de compra actualizada exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al actualizar orden de compra']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Eliminar una orden de compra por ID
    public function deleteOrdenCompra(Request $request, Response $response, $args): Response {
        $sql = "DELETE FROM ordenes_compra WHERE orden_id = :orden_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orden_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Orden de compra eliminada exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al eliminar orden de compra']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
