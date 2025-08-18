<?php

require_once APPROOT . '/repositories/SeatRepository.php';

class SeatService
{
    private $seatRepo;

    public function __construct(SeatRepository $seatRepo)//dependency injection
    {
        $this->seatRepo = $seatRepo;
    }

    public function storeBookingSession($route_id, array $selectedSeats, $user_id, $passengers)
    {
        $_SESSION['booking_data'] = [
            'route_id'       => (int)$route_id,
            'selected_seats' => $selectedSeats,
            'user_id'        => (int)$user_id,
            'passengers'     => $passengers
        ];
    }

    public function finalizeBooking(array $bookingData, $payment_id, $imageName): bool
    {
        $seatData = [
            'route_id'      => $bookingData['route_id'],
            'seat_number'   => json_encode($bookingData['selected_seats']),
            'is_booked'     => 1,
            'user_id'       => $bookingData['user_id'],
            'payment_id'    => $payment_id,
            'payment_slip'  => $imageName
        ];

        return $this->seatRepo->createSeat($seatData);
    }

    public function handlePaymentSlipUpload(?array $file): string
    {
        if (!$file || $file['error'] !== 0) {
            throw new Exception('Payment slip is required.');
        }

        //  File size check (2MB max)
        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new Exception('File size exceeds 2MB.');
        }

        //  Allowed extensions and MIME types
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];

        $ext = strtolower(pathinfo(basename($file['name']), PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            throw new Exception('Invalid file type (extension). Only JPG, PNG, GIF, PDF allowed.');
        }

        //  MIME type check
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMime)) {
            throw new Exception('Invalid file type (MIME).');
        }

        // Optional: check if real image for image files
        if (in_array($ext, ['jpg','jpeg','png','gif'])) {
            $imgCheck = getimagesize($file['tmp_name']);
            if ($imgCheck === false) {
                throw new Exception('File is not a valid image.');
            }
        }

        //  Target directory
        $targetDir = dirname(APPROOT) . '/public/uploads/payment_slip/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // safer permissions than 0777
        }

        //  Unique filename
        $uniqueName = uniqid('payment_', true) . '.' . $ext;
        $targetFile = $targetDir . $uniqueName;

        //  Move uploaded file securely
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new Exception('Failed to upload payment slip.');
        }

        return $uniqueName;
    }

}
