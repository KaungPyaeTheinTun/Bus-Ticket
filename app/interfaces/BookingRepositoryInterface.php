<?php

interface BookingRepositoryInterface
{
    public function getAllBookings(): array;
    public function deleteSeatByNumber(string $seatNumber): bool;
    public function updateSeatStatus(int $seatId, int $status): bool;
}
