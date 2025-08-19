<?php

require_once APPROOT . '/middleware/authmiddleware.php';

require_once APPROOT . '/services/PaymentService.php';

require_once APPROOT . '/helpers/SessionHelper.php';

class Payment extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        AuthMiddleware::requireRole(1);
        
        $this->paymentService = $paymentService;
    }

    private function startSessionAndValidateCsrf()
    {
        SessionHelper::startSecureSession();
        if (!SessionHelper::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            setMessage('error', '⚠️ Invalid request (CSRF).');
            redirect($_SERVER['HTTP_REFERER'] ?? 'pages/login');
            exit;
        }
    }

    private function ensurePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('payment'); 
            exit;
        }
    }

    public function index()
    {
        $methods = $this->paymentService->getAllPayments();
        $this->view('payment/index', ['payments' => $methods]);
    }

    public function store()
    {
        $this->ensurePost();
        $this->startSessionAndValidateCsrf();
        try {
            $data = [
                'method' => trim($_POST['name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? '')
            ];

            if (!empty($data['phone']) && !ctype_digit($data['phone'])) 
            {
                throw new \Exception("Phone must contain digits only.");
            }

            $this->paymentService->createPayment($data, $_FILES);

            $_SESSION['success'] = "✅ Payment Method added successfully.";

        } catch (\Exception $e) 
        {
            $_SESSION['error'] = "❌ " . $e->getMessage();
        }
        redirect('/payment');
    }

    public function update()
    {
        $this->ensurePost();
        $this->startSessionAndValidateCsrf();

        try {
            $id = intval($_POST['id'] ?? 0);
            if (!$id) {
                throw new \Exception("Invalid payment ID.");
            }
            $data = [
                'method' => trim($_POST['name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? '')
            ];
            $this->paymentService->updatePayment($id, $data, $_FILES);
            $_SESSION['success'] = "✅ Payment method updated successfully.";
        } catch (\Exception $e) {
            $_SESSION['error'] = "❌ " . $e->getMessage();
        }
        redirect('/payment');
    }

    public function delete($encodedId)
    {
        $id = base64_decode($encodedId);

        $deleted = $this->paymentService->deletePayment($id);
        $_SESSION[$deleted ? 'success' : 'error'] = $deleted? '✅ Payment deleted successfully.': '⚠️ Failed to delete payment.';

        redirect('/payment');
    }
}
