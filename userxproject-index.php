<?php

require 'config.php';

// Attempt to login
if (isset($_GET['login'])){
    $login    = $_POST['login'];
    $password = $_POST['password'];
    
    $crud->table = 'user';
    $result = $crud->select('*', [
        'login' => $login,
        'password'=> hash256($password)
    ]);
    
    // There is a match
    if (!empty($result)){
        $user_id = $result[0]['user_id'];
        $type = $result[0]['type'];
        
        $_SESSION['user_id'] = $user_id; 
        $_SESSION['type'] = $type; 
                
        $crud->update(['date_logged' => DATETIME], ['user_id' => $user_id]);
        redirect('dashboard.php');
        exit;
    }
   
    echo 'Invalid User';
    
}

if (isset($_GET['logout'])) {
    session_destroy();
}

 
// Construct
?>

<!doctype html>
<html>
    <body>
        <form method="post" action="index.php?login">
            Login:    <input type="text"      name="login" /> <br />
            Password: <input type="password"  name="password" /> <br />
            <input type="submit" />
            
            
            
        </form>
    </body>
</html>
