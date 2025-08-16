<?php

require_once APPROOT . '/repositories/PaymentRepository.php';

class PaymentService
{
    private $paymentRepo;

    public function __construct(PaymentRepository $paymentRepo)
    {
        // Dependency Injection 
        $this->paymentRepo = $paymentRepo;
    }

    public function getAllPayments()
    {
        return $this->paymentRepo->getAll();
    }

    public function createPayment(array $data, array $file = null)
    {
        $scan_image = null;

        if ($file && isset($file['scan_image']) && $file['scan_image']['error'] === 0) {
            $scan_image = $this->uploadImage($file['scan_image']);
        }

        $data['scan_image'] = $scan_image;
        return $this->paymentRepo->create($data);
    }

    public function updatePayment(int $id, array $data, array $file = null)
    {
        $oldPayment = $this->paymentRepo->getById($id);
        if (!$oldPayment) {
            throw new \Exception("Payment method not found.");
        }

        $phoneChanged = ($data['phone'] ?? '') !== $oldPayment['phone'];
        $imageChanged = $file && isset($file['scan_image']) && $file['scan_image']['error'] === 0;

        if ($phoneChanged && !$imageChanged) {
            throw new \Exception("You must also upload a new scan image when changing phone.");
        }

        if ($imageChanged && !$phoneChanged) {
            throw new \Exception("Changing only the scan image is invalid.");
        }

        if ($imageChanged) {
            $scan_image = $this->uploadImage($file['scan_image']);
            if (!empty($oldPayment['scan_image'])) {
                $this->deleteImage($oldPayment['scan_image']);
            }
            $data['scan_image'] = $scan_image;
        } else {
            $data['scan_image'] = $oldPayment['scan_image'];
        }

        return $this->paymentRepo->update($id, $data);
    }

    public function deletePayment(int $id)
    {
        return $this->paymentRepo->delete($id);
    }

    private function uploadImage(array $image)
    {
        $targetDir = dirname(APPROOT) . '/public/uploads/scan_image/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowedExt)) {
            throw new \Exception("Invalid image format. Allowed: jpg, jpeg, png, gif.");
        }

        $uniqueName = uniqid('scan_image_', true) . '.' . $ext;
        $targetFile = $targetDir . $uniqueName;

        if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
            throw new \Exception("Failed to upload image.");
        }

        return $uniqueName;
    }

    private function deleteImage(string $filename)
    {
        $targetDir = dirname(APPROOT) . '/public/uploads/scan_image/';
        $filePath = $targetDir . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
