<?php

require_once APPROOT . '/middleware/authmiddleware.php';

require_once APPROOT . '/services/DashboardService.php';

require_once APPROOT . '/helpers/SessionManager.php';

require_once APPROOT . '/services/FromToService.php';

class Pages extends Controller
{
    private $dashboardService;
    private $routeService;

    public function __construct(DashboardService $dashboardService, FromToService $routeService)
    {
        $this->dashboardService = $dashboardService;
        $this->routeService = $routeService;
    }

    public function index() 
    { 
        $fromLocations = $this->routeService->getUniqueFromCities();
        $toLocations   = $this->routeService->getUniqueToCities();

        $data = [
            'fromLocations' => $fromLocations,
            'toLocations'   => $toLocations
        ];

        $this->view('pages/index', $data); 
    }
    public function login() { $this->view('pages/login'); }
    public function register() { $this->view('pages/register'); }
    public function forgetpassword() { $this->view('pages/forgetpassword'); }
    public function changepassword() { $this->view('pages/changepassword'); }
    public function otp() { $this->view('pages/otp'); }
    public function term() { $this->view('pages/term&condition'); }
    public function policy() { $this->view('pages/policy'); }

    public function dashboard()
    {
        AuthMiddleware::requireRole(1);
        $session = new SessionManager(); 

        $data = $this->dashboardService->getDashboardData();
        $this->view('pages/dashboard', $data);
    }
}
