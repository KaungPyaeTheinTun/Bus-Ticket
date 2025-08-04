<?php
session_start();
class Payment extends Controller
{
    public function __construct()
    {
        $this->model('PaymentModel');
        $this->db = new Database();
    }

    public function index()
    {
        $methods = $this->db->readAll('payments');
        $data = ['payments' => $methods];
        $this->view('payment/index', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $method = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $scan_image = null;

            if (isset($_FILES['scan_image']) && $_FILES['scan_image']['error'] === 0) {
                $targetDir = dirname(APPROOT) . '/public/uploads/scan_image/';
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $ext = strtolower(pathinfo($_FILES['scan_image']['name'], PATHINFO_EXTENSION));
                $uniqueName = uniqid('scan_image_', true) . '.' . $ext;
                $targetFile = $targetDir . $uniqueName;

                if (move_uploaded_file($_FILES['scan_image']['tmp_name'], $targetFile)) {
                    $scan_image = $uniqueName;
                }
            }

            $payment = new PaymentModel();
            $payment->method = $method;
            $payment->phone = $phone;
            $payment->scan_image = $scan_image;

            $this->db->create('payments', $payment->toArray());

            $_SESSION['success'] = "✅ Payment Method added successfully.";
            redirect('/payment');
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $method = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            if (!$id || !$method || !$phone) {
                $_SESSION['error'] = "❌ Please fill all fields";
                redirect('/payment');
                return;
            }

            $oldPayment = $this->db->getById('payments', $id);
            if (!$oldPayment) {
                $_SESSION['error'] = "❌ Payment method not found!";
                redirect('/payment');
                return;
            }

            $phoneChanged = ($phone !== $oldPayment['phone']);
            $imageChanged = (isset($_FILES['scan_image']) && $_FILES['scan_image']['error'] === 0);

            if ($phoneChanged && !$imageChanged) {
                $_SESSION['error'] = "⚠️ You must also upload a new scan image.";
                redirect('/payment');
                return;
            }

            if ($imageChanged && !$phoneChanged) {
                $_SESSION['error'] = "⚠️ Changing only the scan image is invalid!";
                redirect('/payment');
                return;
            }

            $payment = new PaymentModel();
            $payment->id = $id;
            $payment->method = $method;
            $payment->phone = $phone;

            if ($imageChanged) {
                $targetDir = dirname(APPROOT) . '/public/uploads/scan_image/';
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $ext = strtolower(pathinfo($_FILES['scan_image']['name'], PATHINFO_EXTENSION));
                $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($ext, $allowedExt)) {
                    $_SESSION['error'] = "⚠️ Invalid image format. Allowed: jpg, jpeg, png, gif.";
                    redirect('/payment');
                    return;
                }

                $uniqueName = uniqid('scan_image_', true) . '.' . $ext;
                $targetFile = $targetDir . $uniqueName;

                if (move_uploaded_file($_FILES['scan_image']['tmp_name'], $targetFile)) {
                    if (!empty($oldPayment['scan_image'])) {
                        $oldImagePath = $targetDir . $oldPayment['scan_image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $payment->scan_image = $uniqueName;
                } else {
                    $_SESSION['error'] = "⚠️ Failed to upload image!";
                    redirect('/payment');
                    return;
                }
            } else {
                $payment->scan_image = $oldPayment['scan_image'];
            }

            $isUpdated = $this->db->update('payments', [
                'method' => $payment->method,
                'phone' => $payment->phone,
                'scan_image' => $payment->scan_image
            ], ['id' => $payment->id]);

            if ($isUpdated) {
                $_SESSION['success'] = "✅ Payment method updated successfully.";
            } else {
                $_SESSION['error'] = "⚠️ Failed to update payment method!";
            }

            // redirect('/payment');
        }
    }

    public function delete($id)
    {
        $id = base64_decode($id);

        // Hard delete:
        $deleted = $this->db->delete('payments', $id);

        // Optional: Soft delete (preferable)
        // $deleted = $this->db->update('payments', ['is_active' => 0], ['id' => $id]);

        if ($deleted) {
            $_SESSION['success'] = '✅ Payment deleted successfully.';
        } else {
            $_SESSION['error'] = '⚠️ Failed to delete payment.';
        }
        redirect('/payment');
    }
}
?>
