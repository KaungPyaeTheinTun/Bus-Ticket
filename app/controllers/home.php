<?php

class Home extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function trip()
    {
        $this->view('frontend/trip');
    }

    public function record()
    {
        $this->view('pages/record');
    }

    public function selectseat()
{
    $routeId = isset($_GET['route_id']) ? intval($_GET['route_id']) : 0;

    // Instead of reading from 'route' table, read from view 'view_route_operator'
    $route = $this->db->getById('view_route_operator', $routeId);
    if (!$route) {
        die('Route not found');
    }

    // Store in session
    $_SESSION['selected_trip'] = [
        'route_id'       => $routeId,
        'from'           => $route['from'],
        'to'             => $route['to'],
        'departure_time' => $route['departure_time'],
        'arrival_time'   => $route['arrival_time'],
        'operator_name'  => $route['operator_name'],
        'price'          => $route['price'],
        // if you also have image or other fields you want, add here
    ];

    // Passengers from GET or default to 1
    if (isset($_GET['passengers'])) {
        $_SESSION['selected_trip']['passengers'] = intval($_GET['passengers']);
    } else {
        $_SESSION['selected_trip']['passengers'] = 1;
    }

    // Pass data to view
    $data = [
        'trip' => $_SESSION['selected_trip'],
    ];

    $this->view('frontend/selectseat', $data);
}






}
