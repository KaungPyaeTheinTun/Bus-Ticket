<?php 

// class RouteModel
// {
//     private $id;
//     private $operator_id;
//     private $price;
//     private $departure_time;
//     private $arrival_time;
//     private $image;
//     private $from;
//     private $to;

//     // ID
//     public function setId($id)
//     {
//         $this->id = $id;
//     }

//     public function getId()
//     {
//         return $this->id;
//     }

//     // Operator ID
//     public function setOperatorId($operator_id)
//     {
//         $this->operator_id = $operator_id;
//     }

//     public function getOperatorId()
//     {
//         return $this->operator_id;
//     }

//     // Price
//     public function setPrice($price)
//     {
//         $this->price = $price;
//     }

//     public function getPrice()
//     {
//         return $this->price;
//     }
//     public function setFrom($from)
//     {
//         $this->from = $from;
//     }

//     public function getFrom()
//     {
//         return $this->from;
//     }

//     public function setTo($to)
//     {
//         $this->to = $to;
//     }

//     public function getTo()
//     {
//         return $this->to;
//     }

//     // Departure time
//     public function setDepartureTime($departure_time)
//     {
//         $this->departure_time = $departure_time;
//     }

//     public function getDepartureTime()
//     {
//         return $this->departure_time;
//     }

//     // Arrival time
//     public function setArrivalTime($arrival_time)
//     {
//         $this->arrival_time = $arrival_time;
//     }

//     public function getArrivalTime()
//     {
//         return $this->arrival_time;
//     }

//     // Image name
//     public function setImage($image)
//     {
//         $this->image = $image;
//     }

//     public function getImage()
//     {
//         return $this->image;
//     }

//     // Convert to array for DB
//     public function toArray()
//     {
//         return [
//             'operator_id'     => $this->getOperatorId(),
//             'price'           => $this->getPrice(),
//             'from'           => $this->getFrom(),
//             'to'           => $this->getTo(),
//             'departure_time'  => $this->getDepartureTime(),
//             'arrival_time'    => $this->getArrivalTime(),
//             'image'      => $this->getImage()
//         ];
//     }
// }

namespace App\Models;

use App\Interfaces\BaseModel; 

require_once APPROOT . '/interfaces/BaseModel.php';

class RouteModel extends BaseModel
{
    protected $id;
    protected $operator_id;
    protected $price;
    protected $from;
    protected $to;
    protected $departure_time;
    protected $arrival_time;
    protected $image;

}