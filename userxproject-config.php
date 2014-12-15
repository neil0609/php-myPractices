<?php

date_default_timezone_set('UTC');

// Required Files
require 'library/jesseCrud.php';
//require 'library/other.php'

// Always have a session going
session_start();

// Always have a connection available
$crud = new CRUD('mysql', 'userx', 'localhost', 'root');


// Constants
define('DATETIME', date('Y-m-d H:i:s'));
define('SALT', '1209387412093857492013857409123847');


// Functions
function hash256($string) {
    return hash('sha256', $string . SALT);
}

function redirect($url) {
    header("location: $url");
}


function protected_area($admin_only = false) {
    // Protects the dashboard from unauthorized users
    if (!isset($_SESSION['user_id'])) {
        header('location: index.php');
        exit;
    }
    
    if ($admin_only == true) {
        if ($_SESSION['type'] != 'admin') {
            $_SESSION['message'] = 'You are not authorized to be here.';
            header('location: dashboard.php');
            exit;
        }
    }
    
}
