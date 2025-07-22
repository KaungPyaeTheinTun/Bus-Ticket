<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Auth extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('UserModel');
        $this->db = new Database();
    }

    public function formRegister()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['email_check']) &&
            $_POST['email_check'] == 1
        ) {
            $email = $_POST['email'];
            // call columnFilter Method from Database.php
            $isUserExist = $this->db->columnFilter('users', 'email', $email);
            if ($isUserExist) {
                echo 'Sorry! email has already taken. Please try another.';
            }
        }
    }

       public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
        
            // Check if user exists
            $isUserExist = $this->db->columnFilter('users', 'email', $email);
    
            if ($isUserExist) {
                // var_dump('already registered');
                // die();
                setMessage('error', 'This email is already registered !');
                redirect('pages/register');
            } else {
                // Validate entries 
                $validation = new UserValidator($_POST);
                $data = $validation->validateForm();
    
                if (count($data) > 0) {
                    $this->view('pages/register', $data);
                } else {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    $password = $_POST['password'];
    
                    $profile_image = 'default_profile.jpg';
                    $token = bin2hex(random_bytes(50));
    
                    $password = base64_encode($password); // (❗You should use password_hash instead for real apps)
    
                    $user = new UserModel();
                    $user->setName($name);
                    $user->setEmail($email);
                    $user->setPhone($phone);
                    $user->setPassword($password);
                    $user->setToken($token);
                    $user->setProfileImage($profile_image);
                    $user->setIsLogin(0);
                    $user->setIsActive(0);
                    $user->setIsConfirmed(0);
                    $user->setDate(time());
                    $user->setRoleId(2);

                    $userCreated = $this->db->create('users', $user->toArray());

                    if ($userCreated) {
                        // $mail = new Mail();
                        // $verify_token = URLROOT . '/auth/verify/' . $token;
                        // $mail->verifyMail($email, $name, $verify_token);
    
                        // setMessage('success', 'Please check your Mail box !');
                        redirect('pages/login');
                    }
                    redirect('pages/register');
                }
            }
        }
    }
    //admin register function
   public function adminRegister()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];

        // Check if user exists
        $isUserExist = $this->db->columnFilter('users', 'email', $email);

        if ($isUserExist) {
            setMessage('error', 'This email is already registered!');
            redirect('user/addadmin');
        } else {
            // Validate entries
            $validation = new UserValidator($_POST);
            $data = $validation->validateForm();

            if (count($data) > 0) {
                $this->view('backend/addadmin', $data);
            } else {
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $password = $_POST['password'];

                $profile_image = 'default_profile.jpg';
                $token = bin2hex(random_bytes(50));
                $password = base64_encode($password); // should use password_hash ideally

                $user = new UserModel();
                $user->setName($name);
                $user->setEmail($email);
                $user->setPhone($phone);
                $user->setPassword($password);
                $user->setToken($token);
                $user->setProfileImage($profile_image);
                $user->setIsLogin(0);
                $user->setIsActive(0);
                $user->setIsConfirmed(0);
                $user->setDate(time());
                $user->setRoleId(1);

                $userCreated = $this->db->create('users', $user->toArray());

                if ($userCreated) {
                    redirect('user/profile');
                }
                redirect('user/addadmin');
            }
        }
    } else {
        // if request is not POST, just show the addadmin form
        $this->view('user/addadmin');
    }
}

    public function verify($token)
        {
            $user = $this->db->columnFilter('users', 'token', $token);

            if ($user) {
                $success = $this->db->verifyMail($user[0]['id']);

                if ($success) {
                    setMessage(
                        'success',
                        'Successfully Verified . Please log in !'
                    );
                } else {
                    setMessage('error', 'Fail to Verify . Please try again!');
                }
            } else {
                setMessage('error', 'Incrorrect Token . Please try again!');
            }

            redirect('');
        }

        // public function login()
        // {
        //     //  echo "Hello Bo Kaw";
        //     //  exit;
        //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //         if (isset($_POST['email']) && isset($_POST['password'])) {
        //             $email = $_POST['email'];
        //             $password = base64_encode($_POST['password']);

        //             $isLogin = $this->db->loginCheck($email, $password);

        //             if ($isLogin) {
        //                 setMessage('id', base64_encode($isLogin['id']));
        //                 $id = $isLogin['id'];
        //                 $setLogin = $this->db->setLogin($id);
        //                 redirect('pages/dashboard');
        //             } else {
        //                 setMessage('error', 'Login Fail!');
        //                 redirect('pages/login');
        //             }

        //             // $isEmailExist = $this->db->columnFilter('users', 'email', $email);
        //             // print_r($isEmailExist);
        //             // exit;
        //             // $isPasswordExist = $this->db->columnFilter('users', 'password', $password);

        //             // if ($isEmailExist && $isPasswordExist) {
        //             //     echo "Login success";
        //             // } else {
        //             //     echo "login fail";
        //             // }
        //             // print_r($email);
        //             // print_r($password);
        //         }
        //     }
        // }
        // public function login()
        // {
        //     // echo"I'm here";
        //     // exit;
        //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //         if (isset($_POST['email']) && isset($_POST['password'])) {
        //             $email = $_POST['email'];
        //             $password = base64_encode($_POST['password']);
        //             // var_dump($email,$password);
        //             // exit;
        //             $isLogin = $this->db->loginCheck($email, $password);
        //             if ($isLogin) {
        //                 // optional: mark user as logged in DB
        //                  $this->db->setLogin($isLogin['id']);
        //                 session_start();
        //                 $_SESSION['session_loginuserid'] = $isLogin['id'];

        //                 // redirect by role
        //                 if ($isLogin['role_id'] == 1) {
        //                     redirect('pages/dashboard');  // admin dashboard
        //                 } else if($isLogin['role_id'] == 2){
        //                     // setMessage('error', 'Login Fail!');
        //                     // exit;
        //                     redirect('pages/index');  // user dashboard
        //                 }
        //             } else {
        //                 // echo"here";
        //                 // exit;
        //                 setMessage('error', 'Login Fail!');
        //                 redirect('pages/login');
        //             }
        //         }
        //     }
        // }
        public function login()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['email']) && isset($_POST['password'])) {
                    $email = $_POST['email'];
                    $password = base64_encode($_POST['password']);

                    $isLogin = $this->db->loginCheck($email, $password);
                    // var_dump($isLogin);
                    // exit;
                    if ($isLogin) {
                        // Mark user as logged in
                        $this->db->setLogin($isLogin['id']);
                        // $this->db->setLogin($isLogin['email']);

                        session_start();
                        $_SESSION['session_loginuserid'] = $isLogin['id'];
                        $_SESSION['session_loginemail'] = $isLogin['email'];

                        // Redirect based on role
                        // if ($isLogin) {
                        //     switch ($isLogin['role_id']) {
                        //         case ROLE_ADMIN:
                        //             redirect('/pages/dashboard'); 
                        //             break;

                        //         case ROLE_USER:
                        //             redirect('/pages/index');
                        //             break;

                        //         default:
                        //             setMessage('error', 'Invalid role!');
                        //             redirect('/pages/login');
                        //             break;
                        //     }
                        if ($isLogin['role_id'] == ROLE_ADMIN) {
                            redirect('pages/dashboard');  // admin dashboard
                        } else if($isLogin['role_id'] == ROLE_USER){
                            // setMessage('error', 'Login Fail!');
                            // exit;
                            redirect('pages/index');  // user dashboard
                        }
                        } else { 
                            // var_dump('fail');exit;
                            setMessage('error', 'Login Fail!');
                            redirect('pages/login');
                        }
                }
            }
        }
    
  
    function logout($id)
    {
        // session_start();
        // $this->db->unsetLogin(base64_decode($_SESSION['id']));

        //$this->db->unsetLogin($this->auth->getAuthId());
        $this->db->unsetLogin($id);
        redirect('pages/login');
    }


    public function forgetpassword(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' ){
            $email = $_POST['email'];

            $checkmail = $this->db->columnFilter('users','email',$email);

            if($checkmail){
               $otp = str_pad(rand(0,999999),6,'0',STR_PAD_LEFT);
               $expiry =date('Y-m-d H:i:s', strtotime('+5 minutes')); 
               
               $iscreate = $this->db->updateotp($otp,$expiry,$email);
            //    var_dump($iscreate);
            //    die();
               if($iscreate){
                $new = new Mail();
                $user = $new->sendOTP($email,$otp);
                session_start();
                     $_SESSION['post_mail'] =$email;
                    redirect('pages/otp');
               }else{
                setMessage('error','Error');
                redirect('pages/forgetpassword');
               }

            }else{
                setMessage('error','Mail is not have');
                redirect('pages/forgetpassword');
            }

        }
    }

    public function otp(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' ){
            $otp = implode('',$_POST['otp']);
            session_start();
            $email = $_SESSION['post_mail'];

            $otpcheck = $this->db->otpcheck($email,$otp);

            if($otpcheck){
                $_SESSION['otp'] = $email;
                redirect('pages/changepassword');
            }else{
                setMessage('error','Code is Incorrect');
                redirect('pages/otp');
            }
        }
    }

    public function changepassword(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' ){
           session_start();

        //    $id = $_POST['id'];
           $email = $_SESSION['otp'];
           $password = $_POST['password'];
           $confirmpassword = $_POST['confirm_password'];
           if($password !== $confirmpassword){
            // echo "Password does not match";
            // die();
            setMessage('error','⚠️ Password does not match !');
            redirect('pages/changepassword');
           }else{
                $uppercase    = preg_match('@[A-Z]@', $password);
                $lowercase    = preg_match('@[a-z]@', $password);
                $number       = preg_match('@[0-9]@', $password);
                $specialChars = preg_match('@[^\w]@', $password);
                if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                    setMessage('error','⚠️ Password does not meet the required format!');
                    redirect('pages/changepassword');
                }
                $password = base64_encode($password);
                $ischange = $this->db->changepassword($email,$password);
                if($ischange){
                    redirect('pages/login');
                }else{
                    setMessage('error','Error');
                    redirect('pages/changepassword');
                }
           }
        }
    }

    public function changepasswordadmin()
        {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();
            $adminId = $_SESSION['session_loginuserid'] ?? null;
            // var_dump($adminId);exit;
            if (!$adminId) {
                setMessage('error', 'User not logged in.');
                redirect('/user/profile');
            }

            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];

            if (empty($password) || empty($confirmPassword)) {
                setMessage('error', 'All fields are required.');
                redirect('/user/profile');
            }

            if ($password !== $confirmPassword) {
                setMessage('error', '⚠️ Passwords do not match.');
                redirect('/user/profile');
            }
            else{
                $password = base64_encode($password);
                if ($this->db->changepasswordadmin($adminId, $password)) {
                    setMessage('success', 'Password changed successfully.');
                } else {
                    setMessage('error', 'Failed to change password.');
                }
            redirect('/user/profile');
            }
        } 
            else 
            {
                redirect('/user/profile');
            }
        }

}
