<?php
namespace App\Application\Controllers;

use App\Infrastructure\Database\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class ClientesController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Obtener todos los clientes
    public function getClientes(Request $request, Response $response): Response {
        $sql = "SELECT * FROM clientes";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Obtener un cliente por ID
    public function getCliente(Request $request, Response $response, $args): Response {
        $sql = "SELECT * FROM clientes WHERE cliente_id = :cliente_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cliente_id', $args['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Agregar un nuevo cliente
    public function addCliente(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $sql = "INSERT INTO clientes (nombre, apellidos, correo, telefono) VALUES (:nombre, :apellidos, :correo, :telefono)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellidos', $data['apellidos']);
        $stmt->bindParam(':correo', $data['correo']);
        $stmt->bindParam(':telefono', $data['telefono']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Cliente agregado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al agregar cliente']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Actualizar un cliente por ID
    public function updateCliente(Request $request, Response $response, $args): Response {
        $data = $request->getParsedBody();
        $sql = "UPDATE clientes SET nombre = :nombre, apellidos = :apellidos, correo = :correo, telefono = :telefono WHERE cliente_id = :cliente_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellidos', $data['apellidos']);
        $stmt->bindParam(':correo', $data['correo']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':cliente_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Cliente actualizado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al actualizar cliente']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Eliminar un cliente por ID
    public function deleteCliente(Request $request, Response $response, $args): Response {
        $sql = "DELETE FROM clientes WHERE cliente_id = :cliente_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cliente_id', $args['id']);

        if ($stmt->execute()) {
            $response->getBody()->write(json_encode(['message' => 'Cliente eliminado exitosamente']));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Error al eliminar cliente']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
