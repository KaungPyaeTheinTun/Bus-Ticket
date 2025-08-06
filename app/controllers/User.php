<?php
// session_start();

require_once APPROOT . '/middleware/authmiddleware.php';

class User extends Controller
{
    private $db;
    public function __construct()
    {
        AuthMiddleware::adminOnly();
        $this->model('UserModel');
        $this->db = new Database();
    }

    public function index(){
        // var_dump('index');die();
        $this->view('backend/adminprofile');

    }
    public function logout()
    {
        // session_start();
        session_unset();    
        session_destroy(); 
        redirect('pages/login');
    }
    public function profile()
    {
        $user = $this->db->getByRole('users' , 1);
        // session_start();
        $login_user = $this->db->getById('view_user_with_role' , $_SESSION['session_loginuserid']);
        $data = [
            'user' => $user,
            'login_user' => $login_user
        ];
        $this->view('backend/adminprofile' , $data);
    }

    public function addadmin()
    {
        $this->view('backend/addadmin');
    }

    // public function delete($id)
    // {
    //     // $id = base64_decode($id);

    //     // $data = new UserModel();
    //     // $data->setRoleId($id);

    //     // $isdestroy = $this->db->delete('users', $data->getRoleId());
    //     // redirect('/user/profile');
    //     // session_start();

    //     $id = base64_decode($id); // ID to delete
    //     $loginUserId = $_SESSION['session_loginuserid']; // Logged-in admin ID

    //     if ($id == $loginUserId) {
    //         $_SESSION['error'] = "❌ You cannot delete yourself !";
    //         redirect('/user/profile');
    //         return;
    //     }

    //     $data = new UserModel();
    //     $data->setRoleId($id);

    //     $this->db->delete('users', $data->getRoleId());
    //     $_SESSION['success'] = "✅ Admin deleted successfully.";
    //     redirect('/user/profile');
    // }

    public function delete($id)
    {
        // session_start();
        $id = base64_decode($id); 
        $loginUserId = $_SESSION['session_loginuserid']; 
        $loginUserEmail = $_SESSION['session_loginemail'];
        // var_dump($loginUserEmail);
        // exit;

        if ($loginUserEmail !== 'admin@gmail.com') {
            $_SESSION['error'] = "❌ Only default admin can delete admins.";
            redirect('user/profile');
            return;
        }
        else{
            // Prevent deleting yourself
            if ($id == $loginUserId) {
                $_SESSION['error'] = "❌ You cannot delete yourself!";
                redirect('user/profile');
                return;
            }

            // Proceed to delete
            // $data = new UserModel();
            // $data->setRoleId($id);

            $this->db->delete('users', $id);
            $_SESSION['success'] = "✅ Admin deleted successfully.";
            redirect('user/profile');
            }
    }

    public function deletecustomer($id)
    {
        $id = base64_decode($id); 

        // $data = new UserModel();
        // $data->setRoleId($id);

        $deletedUser =  $this->db->delete('users', $id);
        if(!$deletedUser){
            redirect('/user/customer');
        }
        $_SESSION['success'] = "✅ Customer deleted successfully.";
        redirect('user/customer');
    }


    // public function customer()
    // {
    //     $user = $this->db->getByRole('users' , 2);
    //     $ticket = $this->db->readAll('seats');
    //     $data = [
    //         'user' => $user,
    //         'ticket' => $ticket,
    //     ];
    //     $this->view('backend/customerlist',$data);
    // }

public function customer()
{
    $users = $this->db->getByRole('users', 2);   
    $tickets = $this->db->readAll('seats');      
    $routes = $this->db->readAll('route');      
    $operators = $this->db->readAll('operator'); 

    // Build maps
    $routeMap = [];
    foreach ($routes as $route) {
        $routeMap[$route['id']] = $route;
    }

    $operatorMap = [];
    foreach ($operators as $op) {
        $operatorMap[$op['id']] = $op;
    }

    $ticketStats = [];      // For table summary
    $userTickets = [];      // For modal details

    foreach ($tickets as $ticket) {
        $userId = $ticket['user_id'];
        if (!$userId) continue;

        // Attach route info
        $route = $routeMap[$ticket['route_id']] ?? null;
        if ($route) {
            $ticket['route_from'] = $route['from'];
            $ticket['route_to'] = $route['to'];
            $ticket['price'] = $route['price'];
            $ticket['departure_time'] = $route['departure_time'];

            // Attach operator name
            $opId = $route['operator_id'] ?? null;
            $ticket['operator_name'] = $operatorMap[$opId]['name'] ?? '';
        } else {
            // fallback empty
            $ticket['route_from'] = '';
            $ticket['route_to'] = '';
            $ticket['price'] = '';
            $ticket['departure_time'] = '';
            $ticket['operator_name'] = '';
        }

        // Format date
        $dt = new DateTime($ticket['created_at']);
        $ticket['created_at_formatted'] = $dt->format('M j - g:i'); // Aug 4 - 1:30

        // Collect per user
        if (!isset($userTickets[$userId])) {
            $userTickets[$userId] = [];
        }
        $userTickets[$userId][] = $ticket;

        // Table summary
        if (!isset($ticketStats[$userId])) {
            $ticketStats[$userId] = [
                'total_tickets' => 0,
                'last_booking' => null,
                'last_booking_raw' => null,
            ];
        }
        $ticketStats[$userId]['total_tickets']++;

        if (
            !$ticketStats[$userId]['last_booking_raw'] ||
            strtotime($ticket['created_at']) > strtotime($ticketStats[$userId]['last_booking_raw'])
        ) {
            $ticketStats[$userId]['last_booking_raw'] = $ticket['created_at'];
            $ticketStats[$userId]['last_booking'] = $ticket['created_at_formatted'];
        }
    }

    $data = [
        'user' => $users,
        'ticketStats' => $ticketStats,
        'userTickets' => $userTickets,
    ];

    $this->view('backend/customerlist', $data);
}





}