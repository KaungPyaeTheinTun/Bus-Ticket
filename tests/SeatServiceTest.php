<?php

require_once __DIR__ . '/bootstrap.php';
require_once APPROOT . '/../app/interfaces/SeatRepositoryInterface.php';
require_once APPROOT . '/../app/repositories/SeatRepository.php';
require_once APPROOT . '/../app/services/SeatService.php';
require_once APPROOT . '/../app/libraries/Database.php';
require_once APPROOT . '/../app/config/config.php';

class SeatServiceTest extends PHPUnit\Framework\TestCase
// provides lifecycle methods (setUp, tearDown) and
//  assertion methods (assertTrue, assertSame, etc.).
{
    protected function setUp(): void
    {
        //when the controller depends directly on a database
        
        // $this->dbMock = $this->createMock(Database::class);
        // $this->controller = $this->getMockBuilder(Operator::class)
        //     ->onlyMethods(['model', 'view', 'redirect']) 
        //     ->getMock();

        if (session_status() == PHP_SESSION_NONE) 
        {
            session_start();
            $_SESSION = []; 
        }
    }

    public function testStoreBookingSessionSetsSessionData()
    {
        $service = new SeatService(); // uses default repo but storeBookingSession doesn't call repo
        $service->storeBookingSession(123, ['A1','A2'], 42, 2);//places booking info into $_SESSION:
        // route_id = 123
        // selected_seats = ['A1','A2']
        // user_id = 42
        // passengers = 2
        $this->assertArrayHasKey('booking_data', $_SESSION);//checks the key exists.
        //Asserts that route_id equals 123 and is the same type (strict equality). 
        // assertSame is stricter than assertEquals 
        $this->assertSame(123, $_SESSION['booking_data']['route_id']);
        $this->assertSame(['A1','A2'], $_SESSION['booking_data']['selected_seats']);
        $this->assertSame(42, $_SESSION['booking_data']['user_id']);
        $this->assertSame(2, $_SESSION['booking_data']['passengers']);
    }

    public function testFinalizeBookingCallsRepoAndReturnsTrue()
    {
        $bookingData = [
            'route_id' => 10,
            'selected_seats' => ['1','2'],
            'user_id' => 5,
            'passengers' => 2
        ];
        $payment_id = 7;
        $imageName = 'payment_a.jpg';

        //Creates a PHPUnit mock object that implements SeatRepositoryInterface
        $repo = $this->createMock(SeatRepositoryInterface::class);
        $repo->expects($this->once())
             ->method('createSeat')//repository method
             ->with($this->callback(function ($data) use ($bookingData, $payment_id, $imageName) {
                // checks that equals the expected
                 return $data['route_id'] === $bookingData['route_id']
                     && json_decode($data['seat_number'], true) === $bookingData['selected_seats']
                     && $data['user_id'] === $bookingData['user_id']
                     && $data['payment_id'] === $payment_id
                     && $data['payment_slip'] === $imageName;
             }))
             //Configures the mock to return true when createSeat() is called with the matching argument. 
             // That simulates repository success.
             ->willReturn(true);

        $service = new SeatService($repo);//Instantiates the SeatService and injects the mock repository
        $result = $service->finalizeBooking($bookingData, $payment_id, $imageName);
        $this->assertTrue($result);//Asserts the method returned true
    }

    public function testFinalizeBookingReturnsFalseWhenRepoFails()
    {
        $bookingData = [
            'route_id' => 10,
            'selected_seats' => ['1','2'],
            'user_id' => 5,
            'passengers' => 2
        ];
        $payment_id = 7;
        $imageName = 'payment_a.jpg';

        $repo = $this->createMock(SeatRepositoryInterface::class);
        $repo->expects($this->once())
             ->method('createSeat')
             ->willReturn(false);

        $service = new SeatService($repo);
        $result = $service->finalizeBooking($bookingData, $payment_id, $imageName);
        $this->assertFalse($result);
    }

    public function testFinalizeBookingFailsWhenSeatAlreadyTaken()
    {
        $repo = $this->createMock(SeatRepositoryInterface::class);

        // Simulate "seat already booked" exception from DB
        $repo->expects($this->once())
            ->method('createSeat')
            ->willThrowException(new \RuntimeException('Seat already booked'));

        $service = new SeatService($repo);

        $this->expectException(\RuntimeException::class);

        $service->finalizeBooking([
            'route_id' => 10,
            'selected_seats' => ['A1'],
            'user_id' => 5,
            'passengers' => 1
        ], 7, 'payment_a.jpg');
    }

}
