<?php

require_once APPROOT . '/interfaces/DashboardRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class DashboardRepository extends DBconnection implements DashboardRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllOperators(): array
    {
        return $this->getDB()->readAll('operator');
    }

    public function getAllRoutes(): array
    {
        return $this->getDB()->readAll('view_route_operator');
    }

    public function getAllBookings(): array
    {
        return $this->getDB()->readAll('view_booking');
    }

    public function getAllPayment(): array
    {
        return $this->getDB()->readAll('payments');
    }
}
