<?php

interface DashboardRepositoryInterface
{
    public function getAllOperators(): array;
    public function getAllRoutes(): array;
    public function getAllBookings(): array;
    public function getAllPayment(): array;
    public function getFromTo(): array;
}
