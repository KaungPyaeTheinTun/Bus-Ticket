<?php

// class SeatModel
// {
//     private $id;
//     private $route_id;
//     private $seat_number;
//     private $is_booked;
//     private $user_id;
//     private $payment_id;
//     private $payment_slip;

//     public function setId($id)
//     {
//         $this->id = $id;
//     }

//     public function getId()
//     {
//         return $this->id;
//     }

//     public function setRoute_id($route_id)
//     {
//         $this->route_id = $route_id;
//     }

//     public function getRoute_id()
//     {
//         return $this->route_id;
//     }

//     public function setSeat_number($seat_number)
//     {
//         $this->seat_number = $seat_number;
//     }

//     public function getSeat_number()
//     {
//         return $this->seat_number;
//     }

//     public function setIs_booked($is_booked)
//     {
//         $this->is_booked = $is_booked;
//     }

//     public function getIs_booked()
//     {
//         return $this->is_booked;
//     }

//     public function setUser_id($user_id)
//     {
//         $this->user_id = $user_id;
//     }

//     public function getUser_id()
//     {
//         return $this->user_id;
//     }

//     public function setPaymentId($payment_id)
//     {
//         $this->payment_id = $payment_id;
//     }

//     public function getPaymentId()
//     {
//         return $this->payment_id;
//     }

//     public function setPaymentSilp($payment_silp)
//     {
//         $this->payment_silp = $payment_silp;
//     }

//     public function getPaymentSilp()
//     {
//         return $this->payment_silp;
//     }

//     public function toArray()
//     {
//         return [
//             'route_id'     => $this->getRoute_id(),
//             'seat_number'  => $this->getSeat_number(),
//             'is_booked'    => $this->getIs_booked(),
//             'user_id'    => $this->getUser_id(),
//             'payment_id'  => $this->getPaymentId(),
//             'payment_slip' => $this->getPaymentSilp(),
//         ];
//     }
// }

namespace App\Models;

use App\Interfaces\BaseModel; 

require_once APPROOT . '/interfaces/BaseModel.php';

class SeatModel extends BaseModel
{
    protected $id;
    protected $route_id;
    protected $seat_number;
    protected $is_booked;
    protected $user_id;
    protected $payment_id;
    protected $payment_slip;

}