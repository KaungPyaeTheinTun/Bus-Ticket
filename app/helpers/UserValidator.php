<?php

class UserValidator
{
    private $data;
    private $errors        = [];
    private static $fields = ['name','phone', 'email', 'password'];

    public function __construct($post_data)
    {
        $this->data = $post_data;
    }

    public function validateForm()
    {
        foreach (self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                trigger_error($field . " is not present in data");
                return;
            }
        }
        $this->validateUserName();
        $this->validateUserPhone();
        $this->validateEmail();
        $this->validatePassword();
        return $this->errors;
    }

    private function validateUserName()
    {
        $val = trim($this->data['name']);

        $uppercase    = preg_match('@[A-Z]@', $val);//check whether the first argument include in second argument
        $lowercase    = preg_match('@[a-z]@', $val);//if include , it will return 1, if not will return 0
        if (empty($val)) {
            $this->addError('name-err', 'User name can not be empty !');
        } else {
            if (!$uppercase || !$lowercase) {
                $this->addError('name-err', 'Name at least 6 chars & alphabatic !');
            }
        }
    }

    private function validateUserPhone()
    {
        $val = trim($this->data['phone']);

        if (empty($val)) {
            $this->addError('phone-err', 'User phone can not be empty !');
        } else {
            if (!preg_match('/^[0-9]{11}$/', $val)) {
                $this->addError('phone-err', 'Phone number format is wrong !');
            }
        }
    }

    private function validateEmail()
    {
        $val = trim($this->data['email']);

        if (empty($val)) {
            $this->addError('email-err', 'Email cannot be empty!');
            return;
        }

        // Normalize email
        $val = strtolower($val);

        // Basic PHP validation
        if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email-err', 'Email must be a valid format.');
            return;
        }

        // Extra regex to enforce stricter rule (e.g. domain.tld must be at least 2 chars)
        if (!preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/', $val)) {
            $this->addError('email-err', 'Email format is invalid or domain not allowed.');
        }
    }


    private function validatePassword()
    {
        // Validate password strength
        $password     = trim($this->data['password']);
        $uppercase    = preg_match('@[A-Z]@', $password);
        $lowercase    = preg_match('@[a-z]@', $password);
        $number       = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if (empty($password)) {
            $this->addError('password-err', 'Password can not be empty.');
        } else {
            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $this->addError('password-err', 'Password does not meet the required format!');
            }
        }
    }

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }

    public function validatePasswordOnly()
    {
        $this->errors = []; // clear previous errors
        $this->validatePassword(); // call the private method internally
        return $this->errors;
    }

}