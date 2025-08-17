<?php

require_once APPROOT . '/repositories/DashboardRepository.php';

class DashboardService
{
    private $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getDashboardData(): array
    {
        $operators = $this->repo->getAllOperators();
        $routes = $this->repo->getAllRoutes();
        $bookings = $this->repo->getAllBookings();

        $totalOperators = count($operators);
        $totalRoutes = count($routes);
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
                if ($booking['is_booked'] == 2) {
                    $totalApprovedBookings++;
                    $routeId = $booking['route_id'] ?? null;
                    if ($routeId && isset($routePriceMap[$routeId])) {
                        $revenue += $routePriceMap[$routeId];
                    }
                } elseif ($booking['is_booked'] == 1) {
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
            'totalBookings' => $totalApprovedBookings,
            'pendingBookings' => $pendingBookings,
            'revenue' => $revenue,
            'ongoingBusCount' => count($ongoingBuses),
            'ongoingBuses' => $ongoingBuses
        ];
    }
}
