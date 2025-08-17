<?php

require_once APPROOT . '/middleware/authmiddleware.php';

require_once APPROOT . '/services/DashboardService.php';

class Pages extends Controller
{
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index() { $this->view('pages/index'); }
    public function login() { $this->view('pages/login'); }
    public function register() { $this->view('pages/register'); }
    public function forgetpassword() { $this->view('pages/forgetpassword'); }
    public function changepassword() { $this->view('pages/changepassword'); }
    public function otp() { $this->view('pages/otp'); }

    public function dashboard()
    {
        AuthMiddleware::requireRole(1);

        $data = $this->dashboardService->getDashboardData();
        $this->view('pages/dashboard', $data);
    }
}
