<?php

require_once APPROOT . '/repositories/RouteRepository.php';

class RouteService
{
    private $routeRepository;

    public function __construct(RouteRepository $routeRepository)
    {
        $this->routeRepository = $routeRepository;
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
        $operatorId = (int) $data['operator_id'];
        $db = new Database();
        $operator = $db->getById('operator', $operatorId);

        if (!$operator) {
            $_SESSION['error'] = "Operator not found.";
        }

        $imageName = $this->handleImageUpload();

        $params = [
            $operatorId,
            $operator['bus_type_id'],
            (float) $data['price'],
            $data['from'],
            $data['to'],
            $data['departure_time'],
            $data['arrival_time'],
            $imageName
        ];

        return $this->routeRepository->create($params);
    }

    /*private function handleImageUpload(): ?string
    {
        if (empty($_FILES['image']['tmp_name'])) {
            return null;
        }

        $dir = dirname(APPROOT) . '/public/uploads/routes_images/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $filename = uniqid('route_', true) . '.' . $ext;
        $path = $dir . $filename;

        return move_uploaded_file($_FILES['image']['tmp_name'], $path) ? $filename : null;
    }*/
    private function handleImageUpload(): ?string
    {
        if (empty($_FILES['image']['tmp_name'])) {
            return null;
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimeTypes)) {
            $_SESSION['error'] = "Invalid image type. Only JPG, PNG, GIF allowed.";
        }

        $maxFileSize = 2 * 1024 * 1024; // 2MB
        if ($_FILES['image']['size'] > $maxFileSize) {
            $_SESSION['error'] = "Image size exceeds 2MB.";
        }

        $dir = dirname(APPROOT) . '/public/uploads/routes_images/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $ext = strtolower(pathinfo(basename($_FILES['image']['name']), PATHINFO_EXTENSION));
        $filename = uniqid('route_', true) . '.' . $ext;
        $path = $dir . $filename;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
            $_SESSION['error'] = "Failed to move uploaded file.";
        }

        return $filename;
    }

    public function deleteRoute(int $id)
    {
        return $this->routeRepository->delete($id);
    }

    public function resetSeats(int $route_id)
    {
        return $this->routeRepository->resetSeatsByRoute($route_id) > 0;
    }
}
