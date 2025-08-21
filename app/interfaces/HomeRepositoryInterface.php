<?php

interface HomeRepositoryInterface
{
    public function getAllRecords();
    public function getAllOperator();
    public function getUserById($id);
    public function getRouteById($routeId);
    public function getOperatorById($operatorId);
    public function getBusTypeById($busTypeId);
    public function getAllSeats();
    public function getAllPayments();
    public function getAllRoutes();
}
