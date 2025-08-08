<?php
require_once APPROOT . '/interfaces/BookingRepositoryInterface.php';

class BookingRepository implements BookingRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllBookings(): array
    {
        return $this->db->readAll('view_booking');
    }

    public function deleteSeatByNumber(string $seatNumber): bool
    {
        return $this->db->deleteByColumn('seats', 'seat_number', $seatNumber);
    }

    public function updateSeatStatus(int $seatId, int $status): bool
    {
        return $this->db->update('seats', $seatId, ['is_booked' => $status]);
    }
}
