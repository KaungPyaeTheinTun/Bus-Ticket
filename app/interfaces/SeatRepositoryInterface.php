<?php

interface SeatRepositoryInterface
{
    public function createSeat(array $seatData): bool;
}
