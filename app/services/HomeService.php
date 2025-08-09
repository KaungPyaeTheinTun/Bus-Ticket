<?php

require_once APPROOT . '/repositories/HomeRepository.php';

class HomeService
{
    private $homeRepository;

    // Dependency Injection, fallback to default repository
    public function __construct(HomeRepositoryInterface $homeRepository = null)
    {
        $this->homeRepository = $homeRepository ?: new HomeRepository();
    }

    public function getUserRecords($userId)
    {
        $allRecords = $this->homeRepository->getAllRecords();
        $userRecords = array_filter($allRecords, function($rec) use ($userId) {
            return $rec['user_id'] == $userId;
        });
        $user = $this->homeRepository->getUserById($userId);

        return [
            'record' => $userRecords,
            'user' => $user
        ];
    }

    public function getTripDetails($routeId)
    {
        $route = $this->homeRepository->getRouteById($routeId);
        if (!$route) {
            return null;
        }

        $operator = $this->homeRepository->getOperatorById($route['operator_id']);
        if (!$operator) {
            return null;
        }

        $busTypeRow = $this->homeRepository->getBusTypeById($operator['bus_type_id']);
        $busType = $busTypeRow ? $busTypeRow['type_name'] : 'Normal';

        $allSeats = $this->homeRepository->getAllSeats();
        $bookedSeatNumbers = [];

        foreach ($allSeats as $seat) {
            if (((int)$seat['is_booked'] === 2 || (int)$seat['is_booked'] === 1) && (int)$seat['route_id'] === $routeId) {
                $seatNumbersArray = json_decode($seat['seat_number'], true);
                if (is_array($seatNumbersArray)) {
                    foreach ($seatNumbersArray as $number) {
                        $bookedSeatNumbers[] = (int)$number;
                    }
                }
            }
        }

        // Default passengers to 1 if not found in session or GET
        $passengers = 1;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['search_params']['passengers'])) {
            $passengers = (int) $_SESSION['search_params']['passengers'];
        }

        return [
            'trip' => [
                'route_id' => $routeId,
                'from' => $route['from'],
                'to' => $route['to'],
                'departure_time' => $route['departure_time'],
                'arrival_time' => $route['arrival_time'],
                'operator_name' => $route['operator_name'],
                'operator_id' => $route['operator_id'],
                'price' => $route['price'],
                'seat_capacity' => (int)$operator['seat_capacity'],
                'bus_type' => $busType,
                'passengers' => $passengers,
            ],
            'bookedSeatNumbers' => $bookedSeatNumbers
        ];
    }

    public function getPayments($selectedId = null)
    {
        $payments = $this->homeRepository->getAllPayments();

        $selectedPayment = null;
        if ($selectedId) {
            foreach ($payments as $p) {
                if ($p['id'] == $selectedId) {
                    $selectedPayment = $p;
                    break;
                }
            }
        }

        if (!$selectedPayment && !empty($payments)) {
            $selectedPayment = $payments[0];
        }

        return [
            'payments' => $payments,
            'selectedPayment' => $selectedPayment,
        ];
    }

    public function searchRoutes($from, $to, $date)
    {
        $allRoutes = $this->homeRepository->getAllRoutes();

        $filteredRoutes = array_filter($allRoutes, function($route) use ($from, $to, $date) {
            return (stripos($route['from'], $from) !== false) &&
                (stripos($route['to'], $to) !== false) &&
                (date('Y-m-d', strtotime($route['departure_time'])) == $date);
        });

        return array_values($filteredRoutes);
    }
}
