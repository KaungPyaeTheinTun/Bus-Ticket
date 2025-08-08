<?php

require_once APPROOT . '/interfaces/SeatRepositoryInterface.php';

class SeatRepository implements SeatRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function createSeat(array $seatData): bool
    {
        return $this->db->create('seats', $seatData);
    }
}
