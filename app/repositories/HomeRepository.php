<?php

require_once APPROOT . '/interfaces/HomeRepositoryInterface.php';

class HomeRepository implements HomeRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllRecords()
    {
        return $this->db->readAll('view_history');
    }

    public function getUserById($id)
    {
        return $this->db->getById('users', $id);
    }

    public function getRouteById($routeId)
    {
        return $this->db->getById('view_route_operator', $routeId);
    }

    public function getOperatorById($operatorId)
    {
        return $this->db->getById('operator', $operatorId);
    }

    public function getBusTypeById($busTypeId)
    {
        return $this->db->getById('bus_type', $busTypeId);
    }

    public function getAllSeats()
    {
        return $this->db->readAll('seats');
    }

    public function getAllPayments()
    {
        return $this->db->readAll('payments');
    }

    public function getAllRoutes()
    {
        return $this->db->readAll('view_route_operator');
    }
}
