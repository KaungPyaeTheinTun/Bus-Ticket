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

    public function testGetUserRecords()
    {
        $userId = 1;
        $records = [
            ['user_id' => 1, 'data' => 'record1'],
            ['user_id' => 2, 'data' => 'record2'],
        ];
        $user = ['id' => 1, 'name' => 'Kaung'];

        $this->homeRepoMock
            ->method('getAllRecords')
            ->willReturn($records);

        $this->homeRepoMock
            ->method('getUserById')
            ->with($userId)
            ->willReturn($user);

        $result = $this->homeService->getUserRecords($userId);

        $this->assertIsArray($result);
        $this->assertCount(1, $result['record']); // only records with user_id=1
        $this->assertEquals($user, $result['user']);
    }

    public function testGetTripDetailsReturnsNullIfRouteNotFound()
    {
        $this->homeRepoMock
            ->method('getRouteById')
            ->willReturn(null);

        $result = $this->homeService->getTripDetails(123);
        $this->assertNull($result);
    }

    public function testGetTripDetails()
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

        $_SESSION['search_params']['passengers'] = 2;

        $result = $this->homeService->getTripDetails($routeId);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('trip', $result);
        $this->assertEquals([1, 2], $result['bookedSeatNumbers']);
    }

    public function testGetPaymentsSelectsCorrectPayment()
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

    public function testSearchRoutesFiltersCorrectly()
    {
        $routes = [
            ['from' => 'Yangon', 'to' => 'Mandalay', 'departure_time' => '2025-08-20 08:00:00'],
            ['from' => 'Yangon', 'to' => 'Bago', 'departure_time' => '2025-08-20 09:00:00'],
        ];

        $this->homeRepoMock->method('getAllRoutes')->willReturn($routes);

        $result = $this->homeService->searchRoutes('Yangon', 'Mandalay', '2025-08-20');

        $this->assertCount(1, $result);
        $this->assertEquals('Mandalay', $result[0]['to']);
    }
}
