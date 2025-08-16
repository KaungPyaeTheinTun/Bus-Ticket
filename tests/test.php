<?php
use PHPUnit\Framework\TestCase;

require_once APPROOT . '/services/HomeService.php';
require_once APPROOT . '/repositories/HomeRepository.php';

class HomeServiceTest extends TestCase
{
    private $homeRepoMock;
    private $homeService;

    protected function setUp(): void
    {
        $this->homeRepoMock = $this->createMock(HomeRepository::class);
        $this->homeService = new HomeService($this->homeRepoMock);
    }

    public function testGetAllRecord()
    {
        //arrange
        $userId = 1;
        $record = [
            ['user_id' => 1, 'data' => 'record1'],
            ['user_id' => 2, 'data' => 'record2'],
        ];
        $user= ['id'=>1, 'name'=>"Kaung"];

        //action
        $this->homeRepoMock
             ->method('getAllRecords')
             ->willReturn($record);
        
        $this->homeRepoMock
             ->method('getUserById')
             ->with($userId)
             ->willReturn($user);
            
        $result = $this->homeService->getUserRecords($userId);
        
        //assertion
        $this->assertIsArray($result);
        $this->assertCount(1, $result['record']);
        $this->assertEquals($user, $result['user']);
    }

    public function testGetTrip()
    {
        $routeId = 10;
        $route = [
            'from' => 'Yangon',
            'to' => 'Mandalay',
            'departure_time' => '2025-08-20 10:00:00',
            'arrival_time' => '2025-08-20 14:00:00',
            'operator_name' => 'Elite',
            'operator_id' => 5,
            'price' => 30000
        ];
        $operator = [
            'seat_capacity' => 40,
            'bus_type_id' => 2
        ];
        $busType = ['type_name' => 'Normal'];
        $seats = [
            ['is_booked' => 1, 'route_id' => $routeId, 'seat_number' => json_encode([1, 2])],
            ['is_booked' => 0, 'route_id' => $routeId, 'seat_number' => json_encode([3])]
        ];

        $this->homeRepoMock
             ->method('getRouteById')
             ->willReturn($route);
        $this->homeRepoMock
             ->method('getOperatorById')
             ->willReturn($operator);
        $this->homeRepoMock
             ->method('getBusTypeById')
             ->willReturn($busType);
        $this->homeRepoMock
             ->method('getAllSeats')
             ->willReturn($seats);

        $result = $this->homeService->getTripDetails($routeId);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('trip', $result);
        $this->assertEquals([1, 2], $result['bookedSeatNumbers']);
    }

    public function testGetPayments()
    {
        $payments = [
            ['id' => 1, 'name' => 'KBZPay'],
            ['id' => 2, 'name' => 'WavePay'],
        ];

        $this->homeRepoMock
             ->method('getAllPayments')
             ->willReturn($payments);
        
        $result = $this->homeService->getPayments(2);

        $this->assertEquals($payments, $result['payments']);
        $this->assertEquals($payments[1], $result['selectedPayment']);

    }

}
