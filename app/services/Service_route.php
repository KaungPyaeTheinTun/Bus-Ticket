<?php
require_once APPROOT . '/repositories/RouteRepository.php';

class RouteService
{
    private $routeRepository;

    public function __construct(RouteRepositoryInterface $routeRepository = null) // depedency injection
    {
        // Allow dependency injection, or create default instance
        $this->routeRepository = $routeRepository ?: new RouteRepository();
    }

    public function getRoutes(array $filters = [])
    {
        return $this->routeRepository->getAll($filters);
    }

    public function getRouteById(int $id)
    {
        return $this->routeRepository->getById($id);
    }

    public function createRoute(array $data)
    {
        $operatorId = intval($data['operator_id']);
        $db = new Database();
        $operator = $db->getById('operator', $operatorId);

        if (!$operator) {
            throw new Exception("Operator not found.");
        }

        $bus_type_id = $operator['bus_type_id'];

        $imageName = null;
        if (!empty($_FILES['image']['tmp_name'])) {
            $targetDir = dirname(APPROOT) . '/public/uploads/routes_images/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $originalName = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $uniqueName = uniqid('route_', true) . '.' . $ext;
            $targetFile = $targetDir . $uniqueName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imageName = $uniqueName;
            }
        }

        $params = [
            $operatorId,
            $bus_type_id,
            floatval($data['price']),
            $data['from'],
            $data['to'],
            $data['departure_time'],
            $data['arrival_time'],
            $imageName
        ];

        return $this->routeRepository->create($params);
    }

    public function deleteRoute(int $id)
    {
        return $this->routeRepository->delete($id);
    }
}
