<?php
namespace App\Application\Controllers;

use App\Infrastructure\Database\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class InventarioController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // CRUD para materiales
    // Obtener todos los materiales
    public function getMateriales(Request $request, Response $response): Response {
        $sql = "SELECT * FROM materiales";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Obtener un material por ID
    public function getMaterial(Request $request, Response $response, $args): Response {
        $sql = "SELECT * FROM materiales WHERE material_id = :material_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':material_id', $args['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Agregar un nuevo material
    public function addMaterial(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $sql = "INSERT INTO materiales (nombre, descripcion, precio_por_metro) VALUES (:nombre, :descripcion, :precio)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio_por_metro']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Material agregado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al agregar material']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Actualizar un material por ID
    public function updateMaterial(Request $request, Response $response, $args): Response {
        $data = $request->getParsedBody();
        $sql = "UPDATE materiales SET nombre = :nombre, descripcion = :descripcion, precio_por_metro = :precio WHERE material_id = :material_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio_por_metro']);
        $stmt->bindParam(':material_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Material actualizado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al actualizar material']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Eliminar un material por ID
    public function deleteMaterial(Request $request, Response $response, $args): Response {
        $sql = "DELETE FROM materiales WHERE material_id = :material_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':material_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Material eliminado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al eliminar material']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // CRUD para inventario
    // Obtener todo el inventario
    public function getInventario(Request $request, Response $response): Response {
        $sql = "SELECT * FROM inventario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Obtener un elemento del inventario por ID
    public function getInventarioItem(Request $request, Response $response, $args): Response {
        $sql = "SELECT * FROM inventario WHERE inventario_id = :inventario_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':inventario_id', $args['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Agregar un nuevo elemento al inventario
    public function addInventario(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $sql = "INSERT INTO inventario (material_id, cantidad_disponible, ultima_actualizacion) VALUES (:material_id, :cantidad_disponible, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':material_id', $data['material_id']);
        $stmt->bindParam(':cantidad_disponible', $data['cantidad_disponible']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Elemento de inventario agregado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al agregar elemento de inventario']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Actualizar un elemento del inventario por ID
    public function updateInventario(Request $request, Response $response, $args): Response {
        $data = $request->getParsedBody();
        $sql = "UPDATE inventario SET cantidad_disponible = :cantidad_disponible, ultima_actualizacion = NOW() WHERE inventario_id = :inventario_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cantidad_disponible', $data['cantidad_disponible']);
        $stmt->bindParam(':inventario_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Inventario actualizado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al actualizar inventario']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Eliminar un elemento del inventario por ID
    public function deleteInventario(Request $request, Response $response, $args): Response {
        $sql = "DELETE FROM inventario WHERE inventario_id = :inventario_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':inventario_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Elemento de inventario eliminado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al eliminar elemento de inventario']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
