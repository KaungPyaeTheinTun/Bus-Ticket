<?php

require_once APPROOT . '/middleware/authmiddleware.php';

class Booking extends Controller
{
    private $db;
    public function __construct()
    {
        AuthMiddleware::adminOnly();
        $this->db = new Database();
    }

    public function index()
    {
        $bookings = $this->db->readAll('view_booking');
        // var_dump($bookings);
        // exit;
        $data =[
            'booking' => $bookings,
        ];
        $this->view('booking/index',$data);
    }

    public function deleteseat($encodedSeatNumber)
    {
        $seat_number = base64_decode($encodedSeatNumber);

        $deleted = $this->db->deleteByColumn('seats', 'seat_number', $seat_number);

        if ($deleted) {
            $_SESSION['success'] = 'Seat deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete seat';
        }

        redirect('/booking'); 
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? null;
            if (!in_array($status, ['0', '1', '2'])) {
                $_SESSION['error'] = 'Invalid status value.';
                redirect('/booking'); 
            }

            $id = (int)$id;
            $this->db->update('seats', $id, ['is_booked' => $status]);

            $_SESSION['success'] = 'Booking status updated successfully.';
            redirect('/booking');  
        } else {
            redirect('/booking');
        }
    }



}