<?php
require_once APPROOT . '/middleware/authmiddleware.php';

require_once APPROOT . '/services/UserService.php';

class User extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        AuthMiddleware::requireRole(1);
        
        $this->userService = $userService;
    }

    public function index()
    {
        $this->view('backend/adminprofile');
    }

    public function profile()
    {
        $user = $this->userService->getAdmins();
        $login_user = $this->userService->getUserById($_SESSION['session_loginuserid']);
        $data = [
            'user' => $user,
            'login_user' => $login_user
        ];
        $this->view('backend/adminprofile', $data);
    }

    public function addadmin()
    {
        $this->view('backend/addadmin');
    }

    public function delete($id)
    {
        $id = base64_decode($id);
        $loginUserId = $_SESSION['session_loginuserid'];
        $loginUserEmail = $_SESSION['session_loginemail'];
        $result = $this->userService->deleteAdmin($id, $loginUserId, $loginUserEmail);
        $_SESSION[$result['success'] ? 'success' : 'error'] = $result['message'];
        redirect('user/profile');
    }

    public function deletecustomer($id)
    {
        $id = base64_decode($id);
        $deleted = $this->userService->deleteCustomer($id);
        $_SESSION[$deleted ? 'success' : 'error'] = $deleted ? "✅ Customer deleted successfully." : "❌ Failed to delete customer.";
        redirect('user/customer');
    }

    public function customer()
    {   
        $data = $this->userService->getCustomersWithTickets();
        
        // Add all operators for the dropdown
        $data['operators'] = $this->userService->getAllOperators();

        $this->view('backend/customerlist', $data);
    }
}
