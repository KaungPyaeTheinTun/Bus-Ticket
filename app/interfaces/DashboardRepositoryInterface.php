<?php

interface DashboardRepositoryInterface
{
    public function getAllOperators(): array;
    public function getAllRoutes(): array;
    public function getAllBookings(): array;
}
