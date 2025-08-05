<?php 

// class OperatorModel 
// {
//     private $id;
//     private $name;
//     private $phone;
//     private $seat_capacity;

//     public function setId($id)
//     {
//         $this->id = $id;
//     }

//     public function getId()
//     {
//         return $this->id;
//     }

//     public function setName($name)
//     {
//         $this->name = $name;
//     }

//     public function getName()
//     {
//         return $this->name;
//     }

//     public function setPhone($phone)
//     {
//         $this->phone = $phone;
//     }

//     public function getPhone()
//     {
//         return $this->phone;
//     }

//     public function setSeatCapacity($seat_capacity)
//     {
//         $this->seat_capacity = $seat_capacity;
//     }

//     public function getSeatCapacity()
//     {
//         return $this->seat_capacity;
//     }

//     public function toArray()
//     {
//         return [
//             'id'    => $this->getId(),
//             'name'   => $this->getName(),
//             'phone'   => $this->getPhone(),
//             'seat_capacity'   => $this->getSeatCapacity(),
//         ];
//     }
// }

namespace App\Models;

use App\Interfaces\BaseModel; 

require_once APPROOT . '/interfaces/BaseModel.php';

class OperatorModel extends BaseModel
{
    protected $id;
    protected $name;
    protected $phone;
    protected $seat_capacity;
}


