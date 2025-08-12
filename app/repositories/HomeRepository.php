<?php

require_once APPROOT . '/interfaces/HomeRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class HomeRepository extends DBconnection implements HomeRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllRecords()
    {
        return $this->getDB()->readAll('view_history');
    }

    public function getUserById($id)
    {
        return $this->getDB()->getById('users', $id);
    }

    public function getRouteById($routeId)
    {
        return $this->getDB()->getById('view_route_operator', $routeId);
    }

    public function getOperatorById($operatorId)
    {
        return $this->getDB()->getById('operator', $operatorId);
    }

    public function getBusTypeById($busTypeId)
    {
        return $this->getDB()->getById('bus_type', $busTypeId);
    }

    public function getAllSeats()
    {
        return $this->getDB()->readAll('seats');
    }

    public function getAllPayments()
    {
        return $this->getDB()->readAll('payments');
    }

    public function getAllRoutes()
    {
        return $this->getDB()->readAll('view_route_operator');
    }
}
