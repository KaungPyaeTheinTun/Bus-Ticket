<?php

// class UserModel
// {
//     // Access Modifier = public, private, protected
//     private $name;
//     private $email;
//     private $phone;
//     private $password;
//     private $profile_image;
//     private $is_confirmed;
//     private $is_active;
//     private $is_login;
//     private $token;
//     private $date;
//     private $role_id;

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
//     public function setEmail($email)
//     {
//         $this->email = $email;
//     }
//     public function getEmail()
//     {
//         return $this->email;
//     }

//     public function setPassword($password)
//     {
//         $this->password = $password;
//     }
//     public function getPassword()
//     {
//         return $this->password;
//     }

//     public function setProfileImage($profile_image)
//     {
//         $this->profile_image = $profile_image;
//     }
//     public function getProfileImage()
//     {
//         return $this->profile_image;
//     }

//     public function setIsConfirmed($is_confirmed)
//     {
//         $this->is_confirmed = $is_confirmed;
//     }
//     public function getIsConfirmed()
//     {
//         return $this->is_confirmed;
//     }

//     public function setIsActive($is_active)
//     {
//         $this->is_active = $is_active;
//     }
//     public function getIsActive()
//     {
//         return $this->is_active;
//     }

//     public function setIsLogin($is_login)
//     {
//         $this->is_login = $is_login;
//     }
//     public function getIsLogin()
//     {
//         return $this->is_login;
//     }

//     public function setToken($token)
//     {
//         $this->token = $token;
//     }
//     public function getToken()
//     {
//         return $this->token;
//     }

//     public function setDate($date)
//     {
//         $this->date = $date;
//     }
//     public function getDate()
//     {
//         return $this->date;
//     }

//     public function setRoleId($role_id) {
//         $this->role_id = $role_id;
//     }
//     public function getRoleId() {
//         return $this->role_id;
//     }

//     public function toArray() {
//         return [
//             "name" => $this->getName(),
//             "email" => $this->getEmail(),
//             "phone" => $this->getPhone(),
//             "password" => $this->getPassword(),
//             "profile_image" => $this->getProfileImage(),
//             "is_confirmed" => $this->getIsConfirmed(),
//             "is_active" => $this->getIsActive(),
//             "is_login" => $this->getIsLogin(),
//             "token" => $this->getToken(),
//             "date" => $this->getDate(),
//             "role_id" => $this->getRoleId()
//         ];
//     }
// }
namespace App\Models;

use App\Interfaces\BaseModel; 

require_once APPROOT . '/interfaces/BaseModel.php';

class UserModel extends BaseModel
{
    protected $name;
    protected $email;
    protected $phone;
    protected $password;
    protected $profile_image;
    protected $is_confirmed;
    protected $is_active;
    protected $is_login;
    protected $token;
    protected $date;
    protected $role_id;

}

?>


