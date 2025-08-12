<?php

require_once APPROOT . '/middleware/authmiddleware.php';

class Pages extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function index()
    {
        $this->view('pages/index');
    }

    public function login()
    {
        $this->view('pages/login');
    }

    public function register()
    {
        $this->view('pages/register');
    }

    public function forgetpassword()
    {
        $this->view('pages/forgetpassword');
    }

    public function changepassword()
    {
        $this->view('pages/changepassword');
    }

    public function otp()
    {
        $this->view('pages/otp');
    }
    public function dashboard()
    {
        AuthMiddleware::requireRole(1);
        
        $this->view('pages/dashboard');
    }
   
}
