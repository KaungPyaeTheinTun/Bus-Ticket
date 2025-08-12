<?php

require_once APPROOT . '/interfaces/SeatRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class SeatRepository extends DBconnection implements SeatRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createSeat(array $seatData): bool
    {
        return $this->getDB()->create('seats', $seatData);
    }
}
