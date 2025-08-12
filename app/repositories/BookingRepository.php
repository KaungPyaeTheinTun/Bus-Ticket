<?php

require_once APPROOT . '/interfaces/BookingRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class BookingRepository extends DBconnection implements BookingRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllBookings(): array
    {
        return $this->getDB()->readAll('view_booking');
    }

    public function deleteSeatByNumber(string $seatNumber): bool
    {
        return $this->getDB()->deleteByColumn('seats', 'seat_number', $seatNumber);
    }

    public function updateSeatStatus(int $seatId, int $status): bool
    {
        return $this->getDB()->update('seats', $seatId, ['is_booked' => $status]);
    }
}
