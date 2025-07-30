<?php
class Seat extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('SeatModel');

        $this->db = new Database();
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $route_id = $_POST['route_id'] ?? null;
            $selectedSeatsStr = $_POST['selected_seats'] ?? '';
            $selectedSeats = array_filter(explode(',', $selectedSeatsStr));

            var_dump($route_id);
            var_dump($selectedSeats);
            if (!$route_id) {
                die('route_id missing or invalid');
            }
            if (empty($selectedSeats)) {
                die('No seats selected');
            }

            foreach ($selectedSeats as $seatNumber) {
                $this->db->create('seats', [
                    'route_id' => (int)$route_id,
                    'seat_number' => $seatNumber,
                    'is_booked' => 0
                ]);
            }
            echo "Store seat successful";
            exit;
        }
        die('Invalid request method');
    }


}
