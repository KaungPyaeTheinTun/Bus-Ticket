<?php
session_start();
class User extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('UserModel');
    
        $this->db = new Database();
    }

    public function index(){
        // var_dump('index');die();
        $this->view('backend/adminprofile');

    }
    public function logout()
    {
        session_start();
        session_unset();     // Remove all session variables
        session_destroy();   // Destroy the session

        // Redirect to home page
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

    public function delete($id)
    {
        // $id = base64_decode($id);

        // $data = new UserModel();
        // $data->setRoleId($id);

        // $isdestroy = $this->db->delete('users', $data->getRoleId());
        // redirect('/user/profile');
        // session_start();

        $id = base64_decode($id); // ID to delete
        $loginUserId = $_SESSION['session_loginuserid']; // Logged-in admin ID

        if ($id == $loginUserId) {
            $_SESSION['error'] = "❌ You cannot delete yourself !";
            redirect('/user/profile');
            return;
        }

        $data = new UserModel();
        $data->setRoleId($id);

        $this->db->delete('users', $data->getRoleId());
        $_SESSION['success'] = "✅ Admin deleted successfully.";
        redirect('/user/profile');
    }

    public function deletecustomer($id)
    {
        $id = base64_decode($id); 

        $data = new UserModel();
        $data->setRoleId($id);

        $deletedUser =  $this->db->delete('users', $data->getRoleId());
        if(!$deletedUser){
            redirect('/user/customer');
        }
        $_SESSION['success'] = "✅ Customer deleted successfully.";
        redirect('user/customer');
    }


    public function customer()
    {
        $user = $this->db->getByRole('users' , 2);
        $data = [
            'user' => $user
        ];
        $this->view('backend/customerlist',$data);
    }

}