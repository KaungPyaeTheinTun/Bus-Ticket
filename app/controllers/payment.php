<?php

require_once APPROOT . '/middleware/authmiddleware.php';

require_once APPROOT . '/services/PaymentService.php';

class Payment extends Controller
{
    private $paymentService;

    public function __construct()
    {
        AuthMiddleware::adminOnly();
        $this->paymentService = new PaymentService();
    }

    public function index()
    {
        $methods = $this->paymentService->getAllPayments();
        $this->view('payment/index', ['payments' => $methods]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/payment');
            return;
        }

        try {
            $data = [
                'method' => trim($_POST['name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? '')
            ];
            $this->paymentService->createPayment($data, $_FILES);
            $_SESSION['success'] = "✅ Payment Method added successfully.";
        } catch (\Exception $e) {
            $_SESSION['error'] = "❌ " . $e->getMessage();
        }
        redirect('/payment');
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/payment');
            return;
        }

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
