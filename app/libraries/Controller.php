<?php
// load model and views

// class Controller
// {
//     // Load Model
//     public function model($model) // Product
//     {
//         require_once '../app/models/' . $model . '.php';
//         return new $model();
//     }
//     // Load views
//     public function view($view, $data = [])
//     {
//         if (file_exists('../app/views/' . $view . '.php')) {
//             require_once('../app/views/' . $view . '.php');
//         } else {
//             die('View does not exist');
//         }
//     }
// }

// define('APPROOT', dirname(__DIR__));

class Controller
{
    // Load Model (FIXED)
    public function model($model)
    {
        $modelFile = APPROOT . '/models/' . $model . '.php';

        if (!file_exists($modelFile)) {
            die("Model file '$modelFile' not found.");
        }

        require_once $modelFile;

        // Use namespace
        $fullClass = 'App\\Models\\' . $model;

        if (!class_exists($fullClass)) {
            die("Class '$fullClass' not found in '$modelFile'.");
        }

        return new $fullClass();
    }

    // Load Views
    public function view($view, $data = [])
    {
        $viewFile = APPROOT . '/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View '$viewFile' does not exist.");
        }

        require_once $viewFile;
    }
}
