<?php 
namespace App\Interfaces;

interface RouteRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getById(int $id);
    public function create(array $params);
    public function delete(int $id);
    public function resetSeatsByRoute(int $route_id);
}
