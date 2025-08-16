<?php

require_once APPROOT . '/repositories/BookingRepository.php';

class BookingService
{
    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function getAllBookings(): array
    {
        return $this->bookingRepository->getAllBookings();
    }

    public function deleteSeat(string $encodedSeat): bool
    {
        $seat = base64_decode($encodedSeat);
        return $this->bookingRepository->deleteSeatByNumber($seat);
    }

    public function updateBookingStatus(int $id, int $status): bool
    {
        return $this->bookingRepository->updateSeatStatus($id, $status);
    }
}
