<?php

// class PaymentModel
// {
//     private $id;
//     private $method;
//     private $phone;
//     private $scan_image;

//     public function setId($id)
//     {
//         $this->id = $id;
//     }

//     public function getId()
//     {
//         return $this->id;
//     }
    
//     public function setMethod($method)
//     {
//         $this->method = $method;
//     }

//     public function getMethod()
//     {
//         return $this->method;
//     }

//     public function setPhone($phone)
//     {
//         $this->phone = $phone;
//     }

//     public function getPhone()
//     {
//         return $this->phone;
//     }

//     public function setScan_image($scan_image)
//     {
//         $this->scan_image = $scan_image;
//     }

//     public function getScan_image()
//     {
//         return $this->scan_image;
//     }
//     public function toArray()
//     {
//         return [
//             'id'     => $this->getId(),
//             'phone'  => $this->getPhone(),
//             'method'    => $this->getMethod(),
//             'scan_image'    => $this->getScan_image(),
//         ];
//     }
// }

namespace App\Models;

use App\Interfaces\BaseModel; 

require_once APPROOT . '/interfaces/BaseModel.php';

class PaymentModel extends BaseModel
{
    protected $id;
    protected $method;
    protected $phone;
    protected $scan_image;

}
?>