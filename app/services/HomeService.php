<?php

require_once APPROOT . '/repositories/HomeRepository.php';

class HomeService
{
    private $homeRepository;

    // Dependency Injection, fallback to default repository
    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function getUserRecords($userId)
    {
        // 1) All history records
        $allRecords = $this->homeRepository->getAllRecords();

        // 2) Only this user's records (reindex to 0..n)
        $userRecords = array_values(array_filter($allRecords, function ($rec) use ($userId) {
            return (int)$rec['user_id'] === (int)$userId;
        }));

        // 3) Prefetch all operators once
        $allOperators = $this->homeRepository->getAllOperator();

        // Build lookup maps (adjust column names if yours differ)
        $opById = [];
        $opByName = [];
        foreach ($allOperators as $op) {
            // assuming columns: id, name, phone
            if (isset($op['id'])) {
                $opById[(string)$op['id']] = $op;
            }
            if (isset($op['name'])) {
                $opByName[strtolower(trim($op['name']))] = $op;
            }
        }

        // 4) Enrich each record with operator_phone
        foreach ($userRecords as &$rec) {
            $phone = 'N/A';

            // Prefer exact id match when available
            if (!empty($rec['operator_id'])) {
                $key = (string)$rec['operator_id'];
                if (isset($opById[$key])) {
                    $phone = $opById[$key]['phone'] ?? 'N/A';
                }
            }
            // Fallback to name match if id is missing in your view_history
            elseif (!empty($rec['operator_name'])) {
                $nameKey = strtolower(trim($rec['operator_name']));
                if (isset($opByName[$nameKey])) {
                    $phone = $opByName[$nameKey]['phone'] ?? 'N/A';
                }
            }

            $rec['operator_phone'] = $phone;
        }
        unset($rec);

        // 5) User
        $user = $this->homeRepository->getUserById($userId);

        return [
            'record' => $userRecords,
            'user'   => $user,
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
