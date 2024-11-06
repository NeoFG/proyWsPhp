<?php
namespace App\Application\Controllers;

use App\Infrastructure\Database\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class PedidosController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // CRUD para Pedidos
    // Obtener todos los pedidos
    public function getPedidos(Request $request, Response $response): Response {
        $sql = "SELECT * FROM pedidos";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Obtener un pedido específico por ID
    public function getPedido(Request $request, Response $response, $args): Response {
        $sql = "SELECT * FROM pedidos WHERE pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pedido_id', $args['id']);
        $stmt->execute();
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($pedido));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Agregar un nuevo pedido
    public function addPedido(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $sql = "INSERT INTO pedidos (cliente_id, fecha_pedido, estado, numero_pedido) VALUES (:cliente_id, :fecha_pedido, :estado, :numero_pedido)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cliente_id', $data['cliente_id']);
        $stmt->bindParam(':fecha_pedido', $data['fecha_pedido']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':numero_pedido', $data['numero_pedido']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Pedido agregado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al agregar pedido']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Actualizar un pedido por ID
    public function updatePedido(Request $request, Response $response, $args): Response {
        $data = $request->getParsedBody();
        $sql = "UPDATE pedidos SET cliente_id = :cliente_id, fecha_pedido = :fecha_pedido, estado = :estado, numero_pedido = :numero_pedido WHERE pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cliente_id', $data['cliente_id']);
        $stmt->bindParam(':fecha_pedido', $data['fecha_pedido']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':numero_pedido', $data['numero_pedido']);
        $stmt->bindParam(':pedido_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Pedido actualizado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al actualizar pedido']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Eliminar un pedido por ID
    public function deletePedido(Request $request, Response $response, $args): Response {
        $sql = "DELETE FROM pedidos WHERE pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pedido_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Pedido eliminado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al eliminar pedido']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Métodos adicionales para obtener diseños y cotizaciones relacionados con un pedido

    // Obtener el diseño asociado a un pedido
    public function getDesignForPedido(Request $request, Response $response, $args): Response {
        $sql = "SELECT imagen, dimensiones_largo, dimensiones_alto FROM disenos WHERE pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pedido_id', $args['pedido_id']);
        $stmt->execute();
        $design = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($design) {
            // Cambiar 'imagen' para que contenga solo la URL completa de la imagen
            $design['imagen'] = "http://localhost/inventario_nuevo/public/" . $design['imagen'];
            $response->getBody()->write(json_encode($design, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $response->withStatus(404)->write('Diseño no encontrado');
        }
    }
    
    // Obtener la cotización asociada a un pedido
    public function getCotizacionForPedido(Request $request, Response $response, $args): Response {
        $sql = "SELECT * FROM cotizaciones WHERE pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pedido_id', $args['pedido_id']);
        $stmt->execute();
        $cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cotizacion) {
            $response->getBody()->write(json_encode($cotizacion));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $response->withStatus(404)->write('Cotización no encontrada');
        }
    }

    // Agregar un diseño a un pedido
    public function addDesignToPedido(Request $request, Response $response, $args): Response {
        $data = $request->getParsedBody();
        $sql = "INSERT INTO disenos (pedido_id, imagen, dimensiones_largo, dimensiones_alto, material_id) VALUES (:pedido_id, :imagen, :dimensiones_largo, :dimensiones_alto, :material_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pedido_id', $args['pedido_id']);
        $stmt->bindParam(':imagen', $data['imagen']);  // La imagen debe ser enviada en formato binario o como base64 y decodificada
        $stmt->bindParam(':dimensiones_largo', $data['dimensiones_largo']);
        $stmt->bindParam(':dimensiones_alto', $data['dimensiones_alto']);
        $stmt->bindParam(':material_id', $data['material_id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Diseño agregado al pedido exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al agregar diseño al pedido']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Agregar una cotización a un pedido
    public function addCotizacionToPedido(Request $request, Response $response, $args): Response {
        $data = $request->getParsedBody();
        $sql = "INSERT INTO cotizaciones (cliente_id, pedido_id, fecha_cotizacion, total, estado) VALUES (:cliente_id, :pedido_id, :fecha_cotizacion, :total, :estado)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cliente_id', $data['cliente_id']);
        $stmt->bindParam(':pedido_id', $args['pedido_id']);
        $stmt->bindParam(':fecha_cotizacion', $data['fecha_cotizacion']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':estado', $data['estado']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Cotización agregada al pedido exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al agregar cotización al pedido']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
