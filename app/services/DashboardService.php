<?php

require_once APPROOT . '/repositories/DashboardRepository.php';

class DashboardService
{
    private $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getFromTo()
    {
        return $this->repo->getFromTo();
    }

    public function getDashboardData(): array
    {
        $operators = $this->repo->getAllOperators();
        $routes = $this->repo->getAllRoutes();
        $bookings = $this->repo->getAllBookings();
        $payment = $this->repo->getAllPayment();

        $totalOperators = count($operators);
        $totalRoutes = count($routes);
        $totalPayment = count($payment);
        $totalApprovedBookings = 0;
        $pendingBookings = 0;
        $revenue = 0;

        // Build route price map
        $routePriceMap = [];
        foreach ($routes as $route) {
            $routePriceMap[$route['id']] = isset($route['price']) ? (int)$route['price'] : 0;
        }

        // Calculate booking stats
        foreach ($bookings as $booking) {
            if (isset($booking['is_booked'])) {
                if ($booking['is_booked'] == 2) { // approved booking
                    $totalApprovedBookings++;

                    // ✅ If booking comes from view_booking, use total_price
                    if (isset($booking['total_price'])) {
                        $revenue += (float)$booking['total_price'];
                    } else {
                        // fallback to route price if total_price not available
                        $routeId = $booking['route_id'] ?? null;
                        if ($routeId && isset($routePriceMap[$routeId])) {
                            $revenue += $routePriceMap[$routeId];
                        }
                    }
                } elseif ($booking['is_booked'] == 1) { // pending booking
                    $pendingBookings++;
                }
            }
        }


        // Find ongoing buses
        date_default_timezone_set('Asia/Yangon');
        $currentTime = time();
        $ongoingBuses = [];

        foreach ($routes as $route) {
            if (!empty($route['departure_time']) && !empty($route['arrival_time'])) {
                $departureTimestamp = strtotime($route['departure_time']);
                $arrivalTimestamp = strtotime($route['arrival_time']);

                if ($currentTime >= $departureTimestamp && $currentTime < $arrivalTimestamp) {
                    $ongoingBuses[] = [
                        'operator_name' => $route['operator_name'] ?? 'Unknown',
                        'from_location' => $route['from'],
                        'to_location' => $route['to'],
                        'departure_time' => $route['departure_time'],
                        'arrival_time' => $route['arrival_time']
                    ];
                }
            }
        }

        return [
            'totalOperators' => $totalOperators,
            'totalRoutes' => $totalRoutes,
            'totalPayment' => $totalPayment,
            'totalBookings' => $totalApprovedBookings,
            'pendingBookings' => $pendingBookings,
            'revenue' => $revenue,
            'ongoingBusCount' => count($ongoingBuses),
            'ongoingBuses' => $ongoingBuses
        ];
    }
}
