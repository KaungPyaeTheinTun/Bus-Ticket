<?php

use PHPUnit\Framework\TestCase;

require_once APPROOT . '/services/BookingService.php';

require_once APPROOT . '/repositories/BookingRepository.php';

class BookingServiceTest extends TestCase
{
    private $bookingRepositoryMock;
    private $bookingService;

    protected function setUp(): void
    {
        $this->bookingRepositoryMock = $this->createMock(BookingRepository::class);

        $this->bookingService = new BookingService($this->bookingRepositoryMock);
    }

    public function testGetAllBookingsReturnsArray()
    {
        $fakeBookings = [
            ['id' => 1, 'seat_number' => 'A1', 'status' => 1],
            ['id' => 2, 'seat_number' => 'A2', 'status' => 0],
        ];
        
        $this->bookingRepositoryMock
             ->method('getAllBookings')
             ->willReturn($fakeBookings);
        
        $result = $this->bookingService->getAllBookings();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('A1', $result[0]['seat_number']);
    }

    public function testdeletesuccess()
    {
        $seatNumber = 'B3';
        $encodedSeat = base64_encode($seatNumber);

        $this->bookingRepositoryMock
             ->expects($this->once())
             ->method('deleteSeatByNumber')
             ->with($seatNumber)
             ->willReturn(true);
        
        $result = $this->bookingService->deleteSeat($encodedSeat);

        $this->assertTrue($result);
    }

    public function testupdatestatus()
    {
        $seatId = 5;
        $status = 2;

        $this->bookingRepositoryMock
             ->expects($this->once())
             ->method('updateSeatStatus')
             ->with($seatId, $status)
             ->willReturn(True);

        $result = $this->bookingService->updateBookingStatus($seatId, $status);
        
        $this->assertTrue($result);
    }
}