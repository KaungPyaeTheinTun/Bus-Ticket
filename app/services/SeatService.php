<?php

require_once APPROOT . '/repositories/SeatRepository.php';

class SeatService
{
    private $seatRepo;

    public function __construct(SeatRepositoryInterface $seatRepo = null)//dependency injection
    {
        $this->seatRepo = $seatRepo ?: new SeatRepository();
    }

    public function storeBookingSession($route_id, array $selectedSeats, $user_id, $passengers)
    {
        $_SESSION['booking_data'] = [
            'route_id'       => (int)$route_id,
            'selected_seats' => $selectedSeats,
            'user_id'        => (int)$user_id,
            'passengers'     => $passengers
        ];
    }

    public function finalizeBooking(array $bookingData, $payment_id, $imageName): bool
    {
        $seatData = [
            'route_id'      => $bookingData['route_id'],
            'seat_number'   => json_encode($bookingData['selected_seats']),
            'is_booked'     => 1,
            'user_id'       => $bookingData['user_id'],
            'payment_id'    => $payment_id,
            'payment_slip'  => $imageName
        ];

        return $this->seatRepo->createSeat($seatData);
    }
}
